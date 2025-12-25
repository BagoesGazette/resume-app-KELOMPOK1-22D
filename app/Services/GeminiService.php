<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $secureKeyService;
    protected $apiUrl;
    protected $model;
    protected $timeout;
    protected $maxRetries;

    public function __construct(SecureKeyService $secureKeyService)
    {
        $this->secureKeyService = $secureKeyService;
        $this->apiUrl = config('services.gemini.api_url');
        $this->model = config('services.gemini.model', 'gemini-1.5-flash');
        $this->timeout = config('services.gemini.timeout', 60);
        $this->maxRetries = config('services.gemini.max_retries', 2);
    }

    /**
     * Classify CV text and extract structured data
     */
    public function classifyCV(string $cvText, int $submissionId): array
    {
        // Get API key securely
        $apiKey = $this->secureKeyService->getGeminiApiKey();
        
        if (!$apiKey) {
            Log::error('GeminiService: Failed to retrieve API key', [
                'submission_id' => $submissionId
            ]);
            throw new \Exception('Failed to retrieve API key securely');
        }

        // Log usage (untuk audit)
        $this->logApiKeyUsage();

        // Truncate CV text untuk speed optimization
        $cvText = $this->truncateCvText($cvText);
        
        $attempt = 0;
        $lastException = null;
        $bestResult = null;
        $bestScore = 0;

        while ($attempt < $this->maxRetries) {
            try {
                $attempt++;
                
                Log::info('GeminiService: Starting CV classification', [
                    'submission_id' => $submissionId,
                    'text_length' => strlen($cvText),
                    'attempt' => $attempt,
                ]);

                $result = $this->performClassification($cvText, $apiKey, $attempt);
                
                // Score the result quality
                $score = $this->scoreResultQuality($result);
                
                Log::info('GeminiService: Result scored', [
                    'submission_id' => $submissionId,
                    'attempt' => $attempt,
                    'score' => $score,
                ]);

                // If score is good enough, return immediately
                if ($score >= 80) {
                    return $result;
                }

                // Keep track of best result
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestResult = $result;
                }

                // Retry only if score is very low
                if ($attempt < $this->maxRetries && $score < 60) {
                    sleep(2);
                    continue;
                }

                // Return current result if acceptable
                if ($score >= 60) {
                    return $result;
                }
                
            } catch (\Exception $e) {
                $lastException = $e;
                
                Log::warning('GeminiService: Classification failed', [
                    'submission_id' => $submissionId,
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                ]);

                if ($attempt < $this->maxRetries) {
                    sleep(2);
                }
            }
        }

        // Return best result if available
        if ($bestResult !== null && $bestScore >= 50) {
            Log::warning('GeminiService: Returning best available result', [
                'score' => $bestScore,
            ]);
            return $bestResult;
        }

        // Last resort: return basic extraction
        Log::error('GeminiService: All attempts failed, using fallback');
        return $this->generateFallbackExtraction($cvText);
    }

    /**
     * Log API key usage untuk audit trail
     */
    protected function logApiKeyUsage(): void
    {
        try {
            \DB::table('api_keys')
                ->where('service', 'gemini')
                ->update([
                    'last_used_at' => now(),
                    'usage_count' => \DB::raw('usage_count + 1')
                ]);

            // Log ke audit table
            \DB::table('api_key_audits')->insert([
                'service' => 'gemini',
                'action' => 'accessed',
                'performed_by' => auth()->user()->email ?? 'system',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'performed_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silent fail - don't break flow
            Log::debug('GeminiService: Failed to log API usage', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Truncate CV text untuk speed optimization
     */
    protected function truncateCvText(string $cvText, int $maxLength = 4000): string
    {
        if (strlen($cvText) <= $maxLength) {
            return $cvText;
        }
        
        $truncated = substr($cvText, 0, $maxLength);
        
        Log::info('GeminiService: CV text truncated', [
            'original_length' => strlen($cvText),
            'truncated_length' => strlen($truncated),
        ]);
        
        return $truncated . "\n\n[... text truncated for processing efficiency ...]";
    }

    /**
     * Score result quality
     */
    protected function scoreResultQuality(array $result): int
    {
        $score = 0;

        // Profile (30 points)
        if (!empty($result['rangkuman_profil'])) {
            $len = strlen($result['rangkuman_profil']);
            $score += $len >= 100 ? 30 : ($len >= 50 ? 20 : 10);
        }

        // Hardskills (25 points)
        if (!empty($result['hardskills']) && is_array($result['hardskills'])) {
            $count = count($result['hardskills']);
            $score += $count >= 5 ? 25 : ($count >= 3 ? 15 : 5);
        }

        // Softskills (15 points)
        if (!empty($result['softskills']) && is_array($result['softskills'])) {
            $count = count($result['softskills']);
            $score += $count >= 3 ? 15 : ($count >= 2 ? 10 : 5);
        }

        // Education (15 points)
        $score += !empty($result['pendidikan_terakhir']) ? 10 : 0;
        $score += strlen($result['rangkuman_pendidikan']) > 30 ? 5 : 0;

        // Experience (15 points)
        $score += !empty($result['pengalaman_kerja_terakhir']) ? 10 : 0;
        $score += strlen($result['rangkuman_pengalaman_kerja']) > 30 ? 5 : 0;

        return min($score, 100);
    }

    /**
     * Perform classification with optimized settings
     */
    protected function performClassification(string $cvText, string $apiKey, int $attempt = 1): array
    {
        $prompt = $this->buildOptimizedPrompt($cvText);
        
        $response = Http::timeout($this->timeout)
            ->post("{$this->apiUrl}/models/{$this->model}:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 4096,
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                ]
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gemini API failed: HTTP {$response->status()}");
        }

        $data = $response->json();
        
        if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception("Invalid API response format");
        }

        $textResponse = $data['candidates'][0]['content']['parts'][0]['text'];
        
        return $this->parseGeminiResponse($textResponse, $cvText);
    }

    /**
     * Build optimized prompt
     */
    protected function buildOptimizedPrompt(string $cvText): string
    {
        return <<<PROMPT
Ekstrak informasi dari CV berikut dalam format JSON yang VALID dan LENGKAP.

ATURAN PENTING:
1. Output HANYA JSON (tanpa markdown, tanpa penjelasan)
2. Bahasa Indonesia untuk semua text
3. Jika tidak ada data: gunakan "" atau []
4. PASTIKAN JSON VALID - tutup semua kurung dan quotes

CV:
---
{$cvText}
---

EKSTRAK 9 FIELD BERIKUT:

1. pendidikan_terakhir: "Jenjang Program Studi" (ex: "S1 Teknik Informatika")

2. rangkuman_pendidikan: Ringkasan semua pendidikan. Include institusi, tahun, IPK. Min 50 kata.

3. ipk_nilai_akhir: "X.XX" format (ex: "3.75")

4. pengalaman_kerja_terakhir: "Posisi di Perusahaan" (ex: "Senior Dev di PT Tech")

5. rangkuman_pengalaman_kerja: Ringkasan semua pekerjaan. Include posisi, perusahaan, periode, tanggung jawab. Min 80 kata.

6. rangkuman_sertifikasi_prestasi: Ringkasan sertifikasi & prestasi. Jika ada.

7. rangkuman_profil: EXECUTIVE SUMMARY. WAJIB min 100 kata. Include:
   - Latar belakang pendidikan
   - Pengalaman kerja (tahun + posisi)
   - 5-7 skill utama
   - Prestasi/pencapaian
   Jika tidak ada profil eksplisit, BUAT dari keseluruhan CV.

8. hardskills: Array keahlian TEKNIS. Min 5 items.
   ["PHP", "Laravel", "MySQL", "Docker", "Git"]

9. softskills: Array keahlian NON-TEKNIS. Min 3 items.
   ["Komunikasi", "Kepemimpinan", "Kerja Tim"]

FORMAT OUTPUT:
{
  "pendidikan_terakhir": "",
  "rangkuman_pendidikan": "",
  "ipk_nilai_akhir": "",
  "pengalaman_kerja_terakhir": "",
  "rangkuman_pengalaman_kerja": "",
  "rangkuman_sertifikasi_prestasi": "",
  "rangkuman_profil": "",
  "hardskills": [],
  "softskills": []
}

CRITICAL: Pastikan JSON COMPLETE dan VALID. Tutup semua brackets dan quotes!

OUTPUT:
PROMPT;
    }

    /**
     * Parse response dengan handling untuk truncated JSON
     */
    protected function parseGeminiResponse(string $response, string $originalCvText): array
    {
        try {
            $cleaned = $this->cleanJsonResponse($response);
            
            $data = json_decode($cleaned, true);

            if (json_last_error() === JSON_ERROR_NONE && $data !== null) {
                return $this->validateAndNormalizeData($data, $originalCvText);
            }

            Log::warning('GeminiService: JSON parse failed, attempting repair', [
                'error' => json_last_error_msg(),
            ]);

            $fixed = $this->repairTruncatedJson($cleaned);
            $data = json_decode($fixed, true);

            if (json_last_error() === JSON_ERROR_NONE && $data !== null) {
                Log::info('GeminiService: JSON repaired successfully');
                return $this->validateAndNormalizeData($data, $originalCvText);
            }

            Log::warning('GeminiService: Using partial extraction');
            $data = $this->extractPartialData($cleaned);
            return $this->validateAndNormalizeData($data, $originalCvText);
            
        } catch (\Exception $e) {
            Log::error('GeminiService: Parse completely failed', [
                'error' => $e->getMessage(),
            ]);
            
            return $this->generateFallbackExtraction($originalCvText);
        }
    }

    // ... (copy semua method helper dari GeminiService_FINAL.php yang lain)
    // cleanJsonResponse, repairTruncatedJson, extractPartialData, dll
    // (untuk brevity, saya skip duplicate methods - gunakan dari file sebelumnya)

    protected function cleanJsonResponse(string $response): string
    {
        $response = preg_replace('/```json\s*/s', '', $response);
        $response = preg_replace('/```\s*/s', '', $response);
        $response = trim(str_replace("\xEF\xBB\xBF", '', $response));
        $response = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $response);
        return $response;
    }

    protected function repairTruncatedJson(string $json): string
    {
        $lastComma = strrpos($json, ',');
        $lastQuote = strrpos($json, '"');
        $lastBracket = strrpos($json, '}');
        
        if ($lastBracket === false || $lastBracket < $lastQuote) {
            if ($lastComma !== false) {
                $json = substr($json, 0, $lastComma);
            }
            $json = rtrim($json, ',') . "\n}";
        }
        
        return $json;
    }

    protected function extractPartialData(string $json): array
    {
        $data = [];
        $patterns = [
            'pendidikan_terakhir' => '/"pendidikan_terakhir":\s*"([^"]*)"/',
            'ipk_nilai_akhir' => '/"ipk_nilai_akhir":\s*"([^"]*)"/',
        ];
        
        foreach ($patterns as $field => $pattern) {
            if (preg_match($pattern, $json, $matches)) {
                $data[$field] = $matches[1];
            }
        }
        
        return $data;
    }

    protected function validateAndNormalizeData(array $data, string $cvText): array
    {
        $fields = [
            'pendidikan_terakhir' => '',
            'rangkuman_pendidikan' => '',
            'ipk_nilai_akhir' => '',
            'pengalaman_kerja_terakhir' => '',
            'rangkuman_pengalaman_kerja' => '',
            'rangkuman_sertifikasi_prestasi' => '',
            'rangkuman_profil' => '',
            'hardskills' => [],
            'softskills' => [],
        ];

        foreach ($fields as $field => $default) {
            if (!isset($data[$field]) || $data[$field] === null) {
                $data[$field] = $default;
            }
        }

        $data['hardskills'] = $this->normalizeArray($data['hardskills']);
        $data['softskills'] = $this->normalizeArray($data['softskills']);

        foreach (['pendidikan_terakhir', 'rangkuman_pendidikan', 'ipk_nilai_akhir', 
                  'pengalaman_kerja_terakhir', 'rangkuman_pengalaman_kerja', 
                  'rangkuman_sertifikasi_prestasi', 'rangkuman_profil'] as $field) {
            $data[$field] = trim((string) $data[$field]);
        }

        if (empty($data['rangkuman_profil']) || strlen($data['rangkuman_profil']) < 50) {
            $data['rangkuman_profil'] = $this->generateProfileFallback($data, $cvText);
        }

        if (empty($data['hardskills'])) {
            $data['hardskills'] = $this->extractSkillsFallback($cvText, 'hard');
        }

        if (empty($data['softskills'])) {
            $data['softskills'] = $this->extractSkillsFallback($cvText, 'soft');
        }

        return $data;
    }

    protected function normalizeArray($value): array
    {
        if (!is_array($value)) {
            $value = is_string($value) ? explode(',', $value) : [];
        }
        
        return array_values(array_unique(array_filter(array_map('trim', $value), function($v) {
            return !empty($v) && strlen($v) > 1;
        })));
    }

    protected function generateProfileFallback(array $data, string $cvText): string
    {
        $parts = [];
        
        if (!empty($data['pendidikan_terakhir'])) {
            $parts[] = "Lulusan " . $data['pendidikan_terakhir'];
            if (!empty($data['ipk_nilai_akhir'])) {
                $parts[] = "dengan IPK " . $data['ipk_nilai_akhir'];
            }
        }
        
        if (!empty($data['pengalaman_kerja_terakhir'])) {
            $parts[] = "Berpengalaman sebagai " . $data['pengalaman_kerja_terakhir'];
        }
        
        if (!empty($data['hardskills'])) {
            $skills = implode(', ', array_slice($data['hardskills'], 0, 5));
            $parts[] = "Menguasai " . $skills;
        }
        
        $profile = implode('. ', $parts) . '.';
        
        if (strlen($profile) < 50) {
            $profile = "Kandidat profesional dengan latar belakang yang solid. " . $profile;
        }
        
        return $profile;
    }

    protected function extractSkillsFallback(string $cvText, string $type = 'hard'): array
    {
        $cvLower = strtolower($cvText);
        $skills = [];
        
        if ($type === 'hard') {
            $keywords = [
                'PHP', 'Python', 'Java', 'JavaScript', 'Laravel', 'React',
                'MySQL', 'PostgreSQL', 'Docker', 'Git', 'AWS',
            ];
        } else {
            $keywords = [
                'Komunikasi', 'Kepemimpinan', 'Kerja Tim', 'Problem Solving',
            ];
        }
        
        foreach ($keywords as $skill) {
            if (stripos($cvLower, strtolower($skill)) !== false) {
                $skills[] = $skill;
            }
        }
        
        return array_slice($skills, 0, $type === 'hard' ? 10 : 5);
    }

    protected function generateFallbackExtraction(string $cvText): array
    {
        return [
            'pendidikan_terakhir' => '',
            'rangkuman_pendidikan' => '',
            'ipk_nilai_akhir' => '',
            'pengalaman_kerja_terakhir' => '',
            'rangkuman_pengalaman_kerja' => '',
            'rangkuman_sertifikasi_prestasi' => '',
            'rangkuman_profil' => 'Kandidat profesional dengan latar belakang yang relevan.',
            'hardskills' => $this->extractSkillsFallback($cvText, 'hard'),
            'softskills' => $this->extractSkillsFallback($cvText, 'soft'),
        ];
    }
}