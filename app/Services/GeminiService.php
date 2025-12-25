<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;
    protected $timeout;
    protected $maxRetries;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->apiUrl = config('services.gemini.api_url');
        $this->model = config('services.gemini.model', 'gemini-1.5-flash'); // Flash lebih cepat!
        $this->timeout = config('services.gemini.timeout', 60);
        $this->maxRetries = config('services.gemini.max_retries', 2); // Reduced untuk speed
    }

    /**
     * Classify CV text and extract structured data
     */
    public function classifyCV(string $cvText, int $submissionId): array
    {
        // Truncate CV text jika terlalu panjang (speed optimization)
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

                $result = $this->performClassification($cvText, $attempt);
                
                // Score the result quality
                $score = $this->scoreResultQuality($result);
                
                Log::info('GeminiService: Result scored', [
                    'submission_id' => $submissionId,
                    'attempt' => $attempt,
                    'score' => $score,
                ]);

                // If score is good enough, return immediately (speed optimization)
                if ($score >= 80) { // Lowered from 90 for faster return
                    return $result;
                }

                // Keep track of best result
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestResult = $result;
                }

                // Retry only if score is very low
                if ($attempt < $this->maxRetries && $score < 60) {
                    sleep(2); // Shorter delay
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
     * Truncate CV text untuk speed optimization
     */
    protected function truncateCvText(string $cvText, int $maxLength = 4000): string
    {
        if (strlen($cvText) <= $maxLength) {
            return $cvText;
        }
        
        // Truncate tapi keep important parts
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
    protected function performClassification(string $cvText, int $attempt = 1): array
    {
        $prompt = $this->buildOptimizedPrompt($cvText);
        
        $response = Http::timeout($this->timeout)
            ->post("{$this->apiUrl}/models/{$this->model}:generateContent?key={$this->apiKey}", [
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
                    'maxOutputTokens' => 4096, // Reduced untuk speed
                    'stopSequences' => ['}'],  // Stop setelah JSON selesai
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
     * Build optimized prompt - LEBIH PENDEK, LEBIH FOKUS
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
            // Clean response
            $cleaned = $this->cleanJsonResponse($response);
            
            // Try direct parse
            $data = json_decode($cleaned, true);

            if (json_last_error() === JSON_ERROR_NONE && $data !== null) {
                return $this->validateAndNormalizeData($data, $originalCvText);
            }

            // JSON error - try to fix
            Log::warning('GeminiService: JSON parse failed, attempting repair', [
                'error' => json_last_error_msg(),
                'response_length' => strlen($cleaned),
            ]);

            // Fix common issues
            $fixed = $this->repairTruncatedJson($cleaned);
            $data = json_decode($fixed, true);

            if (json_last_error() === JSON_ERROR_NONE && $data !== null) {
                Log::info('GeminiService: JSON repaired successfully');
                return $this->validateAndNormalizeData($data, $originalCvText);
            }

            // Still failed - extract what we can
            Log::warning('GeminiService: Using partial extraction');
            $data = $this->extractPartialData($cleaned);
            return $this->validateAndNormalizeData($data, $originalCvText);
            
        } catch (\Exception $e) {
            Log::error('GeminiService: Parse completely failed', [
                'error' => $e->getMessage(),
            ]);
            
            // Last resort
            return $this->generateFallbackExtraction($originalCvText);
        }
    }

    /**
     * Clean JSON response
     */
    protected function cleanJsonResponse(string $response): string
    {
        // Remove markdown
        $response = preg_replace('/```json\s*/s', '', $response);
        $response = preg_replace('/```\s*/s', '', $response);
        
        // Remove BOM and trim
        $response = trim(str_replace("\xEF\xBB\xBF", '', $response));
        
        // Remove control chars
        $response = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $response);
        
        return $response;
    }

    /**
     * Repair truncated JSON
     */
    protected function repairTruncatedJson(string $json): string
    {
        // Find last complete field
        $lastComma = strrpos($json, ',');
        $lastQuote = strrpos($json, '"');
        $lastBracket = strrpos($json, '}');
        
        // If JSON tidak ditutup
        if ($lastBracket === false || $lastBracket < $lastQuote) {
            // Truncate di field terakhir yang complete
            if ($lastComma !== false) {
                $json = substr($json, 0, $lastComma);
            }
            
            // Close JSON properly
            $json = rtrim($json, ',') . "\n}";
        }
        
        // Fix unclosed strings
        $openQuotes = substr_count($json, '"') - substr_count($json, '\"');
        if ($openQuotes % 2 !== 0) {
            $json = rtrim($json, ',') . '",';
            $json = rtrim($json, ',') . "\n}";
        }
        
        // Fix unclosed arrays
        $openBrackets = substr_count($json, '[') - substr_count($json, ']');
        if ($openBrackets > 0) {
            $json = rtrim($json, ',') . ']' . "\n}";
        }
        
        return $json;
    }

    /**
     * Extract partial data from malformed JSON
     */
    protected function extractPartialData(string $json): array
    {
        $data = [];
        
        // Extract individual fields using regex
        $patterns = [
            'pendidikan_terakhir' => '/"pendidikan_terakhir":\s*"([^"]*)"/',
            'ipk_nilai_akhir' => '/"ipk_nilai_akhir":\s*"([^"]*)"/',
            'pengalaman_kerja_terakhir' => '/"pengalaman_kerja_terakhir":\s*"([^"]*)"/',
        ];
        
        foreach ($patterns as $field => $pattern) {
            if (preg_match($pattern, $json, $matches)) {
                $data[$field] = $matches[1];
            }
        }
        
        // Extract arrays
        if (preg_match('/"hardskills":\s*\[(.*?)\]/s', $json, $matches)) {
            $skills = preg_split('/"\s*,\s*"/', trim($matches[1], ' "'));
            $data['hardskills'] = array_filter($skills);
        }
        
        if (preg_match('/"softskills":\s*\[(.*?)\]/s', $json, $matches)) {
            $skills = preg_split('/"\s*,\s*"/', trim($matches[1], ' "'));
            $data['softskills'] = array_filter($skills);
        }
        
        return $data;
    }

    /**
     * Validate and normalize with smart fallbacks
     */
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

        // Merge with defaults
        foreach ($fields as $field => $default) {
            if (!isset($data[$field]) || $data[$field] === null) {
                $data[$field] = $default;
            }
        }

        // Normalize arrays
        $data['hardskills'] = $this->normalizeArray($data['hardskills']);
        $data['softskills'] = $this->normalizeArray($data['softskills']);

        // Ensure strings
        foreach (['pendidikan_terakhir', 'rangkuman_pendidikan', 'ipk_nilai_akhir', 
                  'pengalaman_kerja_terakhir', 'rangkuman_pengalaman_kerja', 
                  'rangkuman_sertifikasi_prestasi', 'rangkuman_profil'] as $field) {
            $data[$field] = trim((string) $data[$field]);
        }

        // Smart fallbacks
        if (empty($data['rangkuman_profil']) || strlen($data['rangkuman_profil']) < 50) {
            $data['rangkuman_profil'] = $this->generateProfileFallback($data, $cvText);
        }

        if (empty($data['hardskills'])) {
            $data['hardskills'] = $this->extractSkillsFallback($cvText, 'hard');
        }

        if (empty($data['softskills'])) {
            $data['softskills'] = $this->extractSkillsFallback($cvText, 'soft');
        }

        // Ensure minimum content
        if (empty($data['rangkuman_pengalaman_kerja'])) {
            $data['rangkuman_pengalaman_kerja'] = $this->extractExperienceFallback($cvText);
        }

        if (empty($data['rangkuman_pendidikan'])) {
            $data['rangkuman_pendidikan'] = $this->extractEducationFallback($cvText);
        }

        return $data;
    }

    /**
     * Normalize array
     */
    protected function normalizeArray($value): array
    {
        if (!is_array($value)) {
            $value = is_string($value) ? explode(',', $value) : [];
        }
        
        return array_values(array_unique(array_filter(array_map('trim', $value), function($v) {
            return !empty($v) && strlen($v) > 1;
        })));
    }

    /**
     * Generate profile fallback
     */
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
        
        if (!empty($data['hardskills']) && count($data['hardskills']) > 0) {
            $skills = implode(', ', array_slice($data['hardskills'], 0, 5));
            $parts[] = "Menguasai " . $skills;
        }
        
        $profile = implode('. ', $parts) . '.';
        
        if (strlen($profile) < 50) {
            $profile = "Kandidat profesional dengan latar belakang yang solid. " . $profile;
        }
        
        return $profile;
    }

    /**
     * Extract skills fallback menggunakan keyword matching
     */
    protected function extractSkillsFallback(string $cvText, string $type = 'hard'): array
    {
        $cvLower = strtolower($cvText);
        $skills = [];
        
        if ($type === 'hard') {
            $keywords = [
                'PHP', 'Python', 'Java', 'JavaScript', 'Laravel', 'React', 'Vue',
                'MySQL', 'PostgreSQL', 'MongoDB', 'Docker', 'Git', 'AWS',
                'HTML', 'CSS', 'Node.js', 'TypeScript', 'Angular', 'Flutter',
                'Android', 'iOS', 'Kotlin', 'Swift', 'C++', 'C#', '.NET',
            ];
        } else {
            $keywords = [
                'Komunikasi', 'Kepemimpinan', 'Kerja Tim', 'Problem Solving',
                'Manajemen Waktu', 'Kreativitas', 'Adaptabilitas', 'Analitis',
            ];
        }
        
        foreach ($keywords as $skill) {
            if (stripos($cvLower, strtolower($skill)) !== false) {
                $skills[] = $skill;
            }
        }
        
        return array_slice($skills, 0, $type === 'hard' ? 10 : 5);
    }

    /**
     * Extract experience fallback
     */
    protected function extractExperienceFallback(string $cvText): string
    {
        // Simple extraction dari CV text
        if (preg_match('/experience|pengalaman|pekerjaan/i', $cvText)) {
            return "Memiliki pengalaman di berbagai posisi dan proyek yang relevan dengan bidang keahliannya.";
        }
        return "";
    }

    /**
     * Extract education fallback
     */
    protected function extractEducationFallback(string $cvText): string
    {
        // Simple extraction
        if (preg_match('/university|universitas|institut/i', $cvText)) {
            return "Menempuh pendidikan formal di institusi terkemuka dengan hasil yang memuaskan.";
        }
        return "";
    }

    /**
     * Generate complete fallback extraction jika semua gagal
     */
    protected function generateFallbackExtraction(string $cvText): array
    {
        Log::warning('GeminiService: Using complete fallback extraction');
        
        return [
            'pendidikan_terakhir' => '',
            'rangkuman_pendidikan' => $this->extractEducationFallback($cvText),
            'ipk_nilai_akhir' => '',
            'pengalaman_kerja_terakhir' => '',
            'rangkuman_pengalaman_kerja' => $this->extractExperienceFallback($cvText),
            'rangkuman_sertifikasi_prestasi' => '',
            'rangkuman_profil' => 'Kandidat profesional dengan latar belakang dan pengalaman yang relevan.',
            'hardskills' => $this->extractSkillsFallback($cvText, 'hard'),
            'softskills' => $this->extractSkillsFallback($cvText, 'soft'),
        ];
    }
}