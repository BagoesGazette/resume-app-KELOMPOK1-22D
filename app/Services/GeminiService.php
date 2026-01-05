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
        $this->timeout = config('services.gemini.timeout', 90);
        $this->maxRetries = config('services.gemini.max_retries', 3);
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

        // Log usage
        $this->logApiKeyUsage();

        // Preprocessing CV text
        $cvText = $this->preprocessCvText($cvText);
        
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

                // Multi-pass extraction strategy
                $result = $this->performMultiPassExtraction($cvText, $apiKey, $attempt);
                
                // Score the result quality
                $score = $this->scoreResultQuality($result);
                
                Log::info('GeminiService: Result scored', [
                    'submission_id' => $submissionId,
                    'attempt' => $attempt,
                    'score' => $score,
                    'fields_filled' => $this->countFilledFields($result),
                ]);

                // If score is excellent, return immediately
                if ($score >= 85) {
                    return $result;
                }

                // Keep track of best result
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestResult = $result;
                }

                // Retry with different strategy if score is low
                if ($attempt < $this->maxRetries && $score < 70) {
                    sleep(2);
                    continue;
                }

                // Return current result if acceptable
                if ($score >= 70) {
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
                    sleep(3);
                }
            }
        }

        // Return best result if available
        if ($bestResult !== null && $bestScore >= 50) {
            Log::warning('GeminiService: Returning best available result', [
                'score' => $bestScore,
            ]);
            return $this->enrichResult($bestResult, $cvText);
        }

        // Last resort: enhanced fallback extraction
        Log::error('GeminiService: All attempts failed, using enhanced fallback');
        return $this->generateEnhancedFallback($cvText);
    }

    /**
     * Preprocess CV text untuk meningkatkan quality extraction
     */
    protected function preprocessCvText(string $cvText): string
    {
        // Remove excessive whitespace
        $cvText = preg_replace('/\s+/', ' ', $cvText);
        
        // Normalize common separators
        $cvText = str_replace(['|', '•', '–', '—'], '-', $cvText);
        
        // Limit length but keep important sections
        if (strlen($cvText) > 8000) {
            // Try to keep key sections
            $sections = $this->identifyKeySections($cvText);
            $cvText = implode("\n\n", $sections);
            
            // Final truncation if still too long
            if (strlen($cvText) > 8000) {
                $cvText = substr($cvText, 0, 8000);
            }
        }
        
        return trim($cvText);
    }

    /**
     * Identify key sections in CV
     */
    protected function identifyKeySections(string $cvText): array
    {
        $sections = [];
        $lines = explode("\n", $cvText);
        $currentSection = '';
        $sectionKeywords = ['pendidikan', 'education', 'pengalaman', 'experience', 'skill', 'keahlian', 'profil', 'profile', 'sertifikat', 'certificate', 'prestasi', 'achievement'];
        
        foreach ($lines as $line) {
            $lineLower = strtolower($line);
            $isKeySection = false;
            
            foreach ($sectionKeywords as $keyword) {
                if (stripos($lineLower, $keyword) !== false) {
                    if (!empty($currentSection)) {
                        $sections[] = $currentSection;
                    }
                    $currentSection = $line . "\n";
                    $isKeySection = true;
                    break;
                }
            }
            
            if (!$isKeySection && !empty($currentSection)) {
                $currentSection .= $line . "\n";
            } elseif (!$isKeySection && empty($currentSection)) {
                $currentSection .= $line . "\n";
            }
        }
        
        if (!empty($currentSection)) {
            $sections[] = $currentSection;
        }
        
        return array_slice($sections, 0, 10); // Max 10 sections
    }

    /**
     * Multi-pass extraction strategy
     */
    protected function performMultiPassExtraction(string $cvText, string $apiKey, int $attempt): array
    {
        // First pass: Main extraction
        $mainResult = $this->performClassification($cvText, $apiKey, $attempt);
        
        // Check quality
        $score = $this->scoreResultQuality($mainResult);
        
        // If quality is already high, return
        if ($score >= 85) {
            return $mainResult;
        }
        
        // Second pass: Fill missing critical fields
        $enrichedResult = $this->fillMissingFields($mainResult, $cvText, $apiKey);
        
        return $enrichedResult;
    }

    /**
     * Fill missing critical fields with targeted extraction
     */
    protected function fillMissingFields(array $result, string $cvText, string $apiKey): array
    {
        $missingFields = [];
        
        // Check which critical fields are missing
        if (empty($result['pendidikan_terakhir'])) $missingFields[] = 'pendidikan_terakhir';
        if (empty($result['rangkuman_profil']) || strlen($result['rangkuman_profil']) < 50) $missingFields[] = 'rangkuman_profil';
        if (empty($result['hardskills']) || count($result['hardskills']) < 3) $missingFields[] = 'hardskills';
        if (empty($result['softskills']) || count($result['softskills']) < 2) $missingFields[] = 'softskills';
        
        // If no missing fields, return original result
        if (empty($missingFields)) {
            return $result;
        }
        
        try {
            // Create targeted prompt for missing fields
            $targetedPrompt = $this->buildTargetedPrompt($cvText, $missingFields);
            
            $response = Http::timeout($this->timeout)
                ->post("{$this->apiUrl}/models/{$this->model}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $targetedPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 2048,
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('GeminiService: Targeted extraction failed');
                return $result;
            }

            $responseData = $response->json();
            $content = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            $targetedData = $this->parseGeminiResponse($content, $cvText);
            
            // Merge targeted data with original result
            foreach ($missingFields as $field) {
                if (!empty($targetedData[$field])) {
                    $result[$field] = $targetedData[$field];
                }
            }
            
        } catch (\Exception $e) {
            Log::warning('GeminiService: Targeted extraction exception', [
                'error' => $e->getMessage()
            ]);
        }
        
        return $result;
    }

    /**
     * Build targeted prompt for missing fields
     */
    protected function buildTargetedPrompt(string $cvText, array $missingFields): string
    {
        $fieldInstructions = [
            'pendidikan_terakhir' => 'Ekstrak pendidikan terakhir dengan format: "Jenjang - Jurusan - Institusi (Tahun)"',
            'rangkuman_profil' => 'Buat ringkasan profil profesional 2-3 kalimat yang menonjolkan keahlian dan pengalaman utama',
            'hardskills' => 'Ekstrak minimal 5-10 hard skills (keahlian teknis) dalam bentuk array',
            'softskills' => 'Ekstrak minimal 3-5 soft skills (keahlian non-teknis) dalam bentuk array',
        ];
        
        $instructions = "Analisis CV berikut dan ekstrak field yang diminta:\n\n";
        
        foreach ($missingFields as $field) {
            $instructions .= "- {$field}: {$fieldInstructions[$field]}\n";
        }
        
        $instructions .= "\nCV TEXT:\n{$cvText}\n\n";
        $instructions .= "OUTPUT FORMAT (JSON):\n{\n";
        
        foreach ($missingFields as $field) {
            if (in_array($field, ['hardskills', 'softskills'])) {
                $instructions .= "  \"{$field}\": [],\n";
            } else {
                $instructions .= "  \"{$field}\": \"\",\n";
            }
        }
        
        $instructions .= "}\n\nPastikan output adalah JSON yang valid dan lengkap!";
        
        return $instructions;
    }

    /**
     * Count filled fields
     */
    protected function countFilledFields(array $result): int
    {
        $count = 0;
        
        foreach ($result as $key => $value) {
            if (is_array($value)) {
                if (!empty($value)) $count++;
            } elseif (is_string($value)) {
                if (!empty(trim($value))) $count++;
            }
        }
        
        return $count;
    }

    /**
     * Log API key usage
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

            \DB::table('api_key_audits')->insert([
                'service' => 'gemini',
                'action' => 'accessed',
                'performed_by' => auth()->user()->email ?? 'system',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'performed_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::debug('GeminiService: Failed to log API usage', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Enhanced score result quality
     */
    protected function scoreResultQuality(array $result): int
    {
        $score = 0;

        // Profile (25 points)
        if (!empty($result['rangkuman_profil'])) {
            $len = strlen($result['rangkuman_profil']);
            if ($len >= 150) $score += 25;
            elseif ($len >= 100) $score += 20;
            elseif ($len >= 50) $score += 12;
            else $score += 5;
        }

        // Hardskills (25 points)
        if (!empty($result['hardskills']) && is_array($result['hardskills'])) {
            $count = count($result['hardskills']);
            if ($count >= 7) $score += 25;
            elseif ($count >= 5) $score += 20;
            elseif ($count >= 3) $score += 12;
            else $score += 5;
        }

        // Softskills (15 points)
        if (!empty($result['softskills']) && is_array($result['softskills'])) {
            $count = count($result['softskills']);
            if ($count >= 5) $score += 15;
            elseif ($count >= 3) $score += 12;
            elseif ($count >= 2) $score += 8;
            else $score += 3;
        }

        // Education (15 points)
        $score += !empty($result['pendidikan_terakhir']) && strlen($result['pendidikan_terakhir']) > 10 ? 10 : 0;
        $score += !empty($result['rangkuman_pendidikan']) && strlen($result['rangkuman_pendidikan']) > 30 ? 5 : 0;

        // Experience (15 points)
        $score += !empty($result['pengalaman_kerja_terakhir']) && strlen($result['pengalaman_kerja_terakhir']) > 10 ? 10 : 0;
        $score += !empty($result['rangkuman_pengalaman_kerja']) && strlen($result['rangkuman_pengalaman_kerja']) > 30 ? 5 : 0;

        // Bonus points for other fields (5 points)
        if (!empty($result['rangkuman_sertifikasi_prestasi']) && strlen($result['rangkuman_sertifikasi_prestasi']) > 20) {
            $score += 5;
        }

        return min($score, 100);
    }

    /**
     * Perform classification with enhanced prompt
     */
    protected function performClassification(string $cvText, string $apiKey, int $attempt = 1): array
    {
        $prompt = $this->buildEnhancedPrompt($cvText, $attempt);
        
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
            throw new \Exception('Gemini API request failed: ' . $response->status());
        }

        $responseData = $response->json();
        
        if (!isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception('Invalid Gemini API response structure');
        }

        $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'];
        
        return $this->parseGeminiResponse($generatedText, $cvText);
    }

    /**
     * Build enhanced prompt dengan instruksi yang lebih detail
     */
    protected function buildEnhancedPrompt(string $cvText, int $attempt = 1): string
    {
        $emphasis = $attempt > 1 ? "\n⚠️ ATTEMPT #{$attempt}: Fokus pada kelengkapan data! Jangan biarkan field kosong!\n" : "";
        
        return <<<PROMPT
Anda adalah AI expert dalam analisis CV/Resume. Tugas Anda adalah mengekstrak informasi dengan sangat detail dan akurat.

{$emphasis}

CV TEXT:
{$cvText}

INSTRUKSI EKSTRAKSI (WAJIB LENGKAP):

1. **pendidikan_terakhir**: 
   - Format: "Jenjang - Jurusan - Institusi (Tahun Lulus)"
   - Contoh: "S1 - Teknik Informatika - Universitas Indonesia (2020)"
   - Cari keyword: pendidikan, education, kuliah, universitas, sekolah
   - Jika tidak ada info tahun, tulis "tahun tidak disebutkan"

2. **rangkuman_pendidikan**: 
   - Ringkasan 2-4 kalimat tentang riwayat pendidikan
   - Sebutkan jenjang, institusi, tahun, IPK (jika ada)
   - Tambahkan organisasi kampus atau achievement jika ada

3. **ipk_nilai_akhir**: 
   - Format: "X.XX" atau "X.XX/4.00"
   - Cari keyword: IPK, GPA, nilai, grade
   - Jika tidak ditemukan, tulis "tidak disebutkan"

4. **pengalaman_kerja_terakhir**: 
   - Format: "Posisi - Perusahaan (Periode)"
   - Contoh: "Software Engineer - PT ABC (2020-2023)"
   - Ambil posisi paling recent atau paling relevan

5. **rangkuman_pengalaman_kerja**: 
   - Ringkasan 3-5 kalimat tentang pengalaman kerja
   - Sebutkan jumlah tahun pengalaman
   - Highlight tanggung jawab utama dan pencapaian
   - Jika fresh graduate, tulis pengalaman magang/project

6. **rangkuman_sertifikasi_prestasi**: 
   - List sertifikasi profesional dan prestasi
   - Format: "- Sertifikat A (Tahun)\n- Prestasi B (Tahun)"
   - Include: sertifikasi, penghargaan, kompetisi, publikasi
   - Jika tidak ada, tulis "Tidak ada sertifikasi atau prestasi yang disebutkan"

7. **rangkuman_profil**: 
   - WAJIB 2-4 kalimat professional summary
   - Highlight: latar belakang pendidikan + pengalaman + keahlian utama
   - Tulis dalam bahasa Indonesia yang profesional
   - Jika CV tidak punya profil eksplisit, BUAT dari seluruh data CV
   - Contoh: "Lulusan S1 Teknik Informatika dengan pengalaman 3 tahun sebagai Software Engineer. Menguasai PHP, Laravel, dan MySQL dengan track record pengembangan aplikasi web skala enterprise. Memiliki kemampuan problem solving yang kuat dan terbiasa bekerja dalam tim agile."

8. **hardskills**: 
   - WAJIB minimal 5-10 items (array of strings)
   - Ekstrak SEMUA keahlian teknis yang disebutkan
   - Include: bahasa pemrograman, framework, tools, software, metodologi
   - Contoh: ["PHP", "Laravel", "MySQL", "Git", "Docker", "REST API", "JavaScript", "React", "PostgreSQL", "CI/CD"]
   - Cari di seluruh CV, termasuk di deskripsi pengalaman kerja

9. **softskills**: 
   - WAJIB minimal 3-5 items (array of strings)
   - Ekstrak kemampuan non-teknis
   - Include: komunikasi, leadership, teamwork, problem solving, dll
   - Contoh: ["Komunikasi", "Kepemimpinan", "Kerja Tim", "Problem Solving", "Manajemen Waktu"]
   - Jika tidak disebutkan eksplisit, inferensi dari deskripsi kerja

ATURAN PENTING:
✅ SEMUA field WAJIB diisi, jangan ada yang kosong
✅ Jika data tidak ditemukan, berikan nilai default yang masuk akal
✅ hardskills minimal 5 items, softskills minimal 3 items
✅ rangkuman_profil minimal 100 karakter
✅ Gunakan bahasa Indonesia yang profesional
✅ Output HARUS JSON valid dan complete

FORMAT OUTPUT (JSON):
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

CRITICAL: 
- Pastikan JSON COMPLETE dan VALID
- Tutup semua brackets dan quotes
- Jangan truncate di tengah jalan
- SEMUA 9 field harus ada valuenya

OUTPUT (langsung JSON, tanpa penjelasan tambahan):
PROMPT;
    }

    /**
     * Parse Gemini response dengan enhanced error handling
     */
    protected function parseGeminiResponse(string $response, string $originalCvText): array
    {
        try {
            $cleaned = $this->cleanJsonResponse($response);
            
            // Try direct parse
            $data = json_decode($cleaned, true);

            if (json_last_error() === JSON_ERROR_NONE && $data !== null) {
                return $this->validateAndNormalizeData($data, $originalCvText);
            }

            Log::warning('GeminiService: JSON parse failed, attempting repair', [
                'error' => json_last_error_msg(),
                'response_preview' => substr($cleaned, 0, 200)
            ]);

            // Try to repair truncated JSON
            $fixed = $this->repairTruncatedJson($cleaned);
            $data = json_decode($fixed, true);

            if (json_last_error() === JSON_ERROR_NONE && $data !== null) {
                Log::info('GeminiService: JSON repaired successfully');
                return $this->validateAndNormalizeData($data, $originalCvText);
            }

            // Partial extraction as fallback
            Log::warning('GeminiService: Using partial extraction');
            $data = $this->extractPartialData($cleaned);
            return $this->validateAndNormalizeData($data, $originalCvText);
            
        } catch (\Exception $e) {
            Log::error('GeminiService: Parse completely failed', [
                'error' => $e->getMessage(),
            ]);
            
            return $this->generateEnhancedFallback($originalCvText);
        }
    }

    /**
     * Clean JSON response
     */
    protected function cleanJsonResponse(string $response): string
    {
        // Remove markdown code blocks
        $response = preg_replace('/```json\s*/s', '', $response);
        $response = preg_replace('/```\s*/s', '', $response);
        
        // Remove BOM
        $response = trim(str_replace("\xEF\xBB\xBF", '', $response));
        
        // Remove control characters
        $response = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $response);
        
        // Find JSON object
        $start = strpos($response, '{');
        $end = strrpos($response, '}');
        
        if ($start !== false && $end !== false && $end > $start) {
            $response = substr($response, $start, $end - $start + 1);
        }
        
        return trim($response);
    }

    /**
     * Repair truncated JSON
     */
    protected function repairTruncatedJson(string $json): string
    {
        // Count braces
        $openBraces = substr_count($json, '{');
        $closeBraces = substr_count($json, '}');
        $openBrackets = substr_count($json, '[');
        $closeBrackets = substr_count($json, ']');
        
        // Find last complete position
        $lastComma = strrpos($json, ',');
        $lastQuote = strrpos($json, '"');
        $lastBracket = strrpos($json, '}');
        
        // If JSON is incomplete
        if ($openBraces > $closeBraces || $openBrackets > $closeBrackets) {
            // Remove incomplete last field
            if ($lastComma !== false && $lastComma > $lastBracket) {
                $json = substr($json, 0, $lastComma);
            }
            
            // Close arrays if needed
            while ($openBrackets > $closeBrackets) {
                $json .= ']';
                $closeBrackets++;
            }
            
            // Close objects if needed
            while ($openBraces > $closeBraces) {
                $json .= '}';
                $closeBraces++;
            }
        }
        
        return $json;
    }

    /**
     * Extract partial data using regex
     */
    protected function extractPartialData(string $json): array
    {
        $data = [];
        
        // Pattern untuk string fields
        $patterns = [
            'pendidikan_terakhir' => '/"pendidikan_terakhir":\s*"([^"]*)"/',
            'rangkuman_pendidikan' => '/"rangkuman_pendidikan":\s*"([^"]*)"/',
            'ipk_nilai_akhir' => '/"ipk_nilai_akhir":\s*"([^"]*)"/',
            'pengalaman_kerja_terakhir' => '/"pengalaman_kerja_terakhir":\s*"([^"]*)"/',
            'rangkuman_pengalaman_kerja' => '/"rangkuman_pengalaman_kerja":\s*"([^"]*)"/',
            'rangkuman_sertifikasi_prestasi' => '/"rangkuman_sertifikasi_prestasi":\s*"([^"]*)"/',
            'rangkuman_profil' => '/"rangkuman_profil":\s*"([^"]*)"/',
        ];
        
        foreach ($patterns as $field => $pattern) {
            if (preg_match($pattern, $json, $matches)) {
                $data[$field] = $matches[1];
            }
        }
        
        // Pattern untuk array fields
        if (preg_match('/"hardskills":\s*\[(.*?)\]/s', $json, $matches)) {
            $items = preg_split('/,\s*/', $matches[1]);
            $data['hardskills'] = array_map(function($item) {
                return trim($item, ' "\'');
            }, array_filter($items));
        }
        
        if (preg_match('/"softskills":\s*\[(.*?)\]/s', $json, $matches)) {
            $items = preg_split('/,\s*/', $matches[1]);
            $data['softskills'] = array_map(function($item) {
                return trim($item, ' "\'');
            }, array_filter($items));
        }
        
        return $data;
    }

    /**
     * Validate and normalize extracted data
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
        $data['hardskills'] = $this->normalizeSkillsArray($data['hardskills']);
        $data['softskills'] = $this->normalizeSkillsArray($data['softskills']);

        // Trim string fields
        foreach (['pendidikan_terakhir', 'rangkuman_pendidikan', 'ipk_nilai_akhir', 
                  'pengalaman_kerja_terakhir', 'rangkuman_pengalaman_kerja', 
                  'rangkuman_sertifikasi_prestasi', 'rangkuman_profil'] as $field) {
            $data[$field] = trim((string) $data[$field]);
        }

        // Fill critical missing fields
        $data = $this->fillCriticalMissingFields($data, $cvText);

        return $data;
    }

    /**
     * Normalize skills array
     */
    protected function normalizeSkillsArray($value): array
    {
        if (!is_array($value)) {
            if (is_string($value)) {
                // Try to parse if it's a JSON string
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    $value = $decoded;
                } else {
                    // Split by comma
                    $value = array_map('trim', explode(',', $value));
                }
            } else {
                $value = [];
            }
        }
        
        // Clean and filter
        $cleaned = array_values(array_unique(array_filter(array_map(function($v) {
            $v = trim($v);
            // Remove quotes if present
            $v = trim($v, '"\'');
            return $v;
        }, $value), function($v) {
            return !empty($v) && strlen($v) > 1 && strlen($v) < 50;
        })));
        
        return $cleaned;
    }

    /**
     * Fill critical missing fields dengan berbagai strategi
     */
    protected function fillCriticalMissingFields(array $data, string $cvText): array
    {
        // Fill missing profile
        if (empty($data['rangkuman_profil']) || strlen($data['rangkuman_profil']) < 50) {
            $data['rangkuman_profil'] = $this->generateEnhancedProfile($data, $cvText);
        }

        // Fill missing hardskills
        if (empty($data['hardskills']) || count($data['hardskills']) < 3) {
            $extracted = $this->extractSkillsFromText($cvText, 'hard');
            $data['hardskills'] = array_unique(array_merge($data['hardskills'], $extracted));
        }

        // Fill missing softskills
        if (empty($data['softskills']) || count($data['softskills']) < 2) {
            $extracted = $this->extractSkillsFromText($cvText, 'soft');
            $data['softskills'] = array_unique(array_merge($data['softskills'], $extracted));
        }

        // Fill missing education summary
        if (empty($data['rangkuman_pendidikan']) && !empty($data['pendidikan_terakhir'])) {
            $data['rangkuman_pendidikan'] = $this->generateEducationSummary($data, $cvText);
        }

        // Fill missing experience summary
        if (empty($data['rangkuman_pengalaman_kerja']) && !empty($data['pengalaman_kerja_terakhir'])) {
            $data['rangkuman_pengalaman_kerja'] = $this->generateExperienceSummary($data, $cvText);
        }

        // Extract pendidikan if missing
        if (empty($data['pendidikan_terakhir'])) {
            $data['pendidikan_terakhir'] = $this->extractEducationFromText($cvText);
        }

        // Extract pengalaman if missing
        if (empty($data['pengalaman_kerja_terakhir'])) {
            $data['pengalaman_kerja_terakhir'] = $this->extractExperienceFromText($cvText);
        }

        // Extract IPK if missing
        if (empty($data['ipk_nilai_akhir'])) {
            $data['ipk_nilai_akhir'] = $this->extractIPKFromText($cvText);
        }

        return $data;
    }

    /**
     * Generate enhanced professional profile
     */
    protected function generateEnhancedProfile(array $data, string $cvText): string
    {
        $parts = [];
        
        // Education
        if (!empty($data['pendidikan_terakhir'])) {
            $edu = $data['pendidikan_terakhir'];
            if (!empty($data['ipk_nilai_akhir']) && $data['ipk_nilai_akhir'] !== 'tidak disebutkan') {
                $parts[] = "Lulusan {$edu} dengan IPK {$data['ipk_nilai_akhir']}";
            } else {
                $parts[] = "Lulusan {$edu}";
            }
        }
        
        // Experience
        if (!empty($data['pengalaman_kerja_terakhir'])) {
            $exp = $data['pengalaman_kerja_terakhir'];
            
            // Extract years of experience from CV text
            $years = $this->extractYearsOfExperience($cvText);
            if ($years > 0) {
                $parts[] = "memiliki pengalaman {$years} tahun sebagai {$exp}";
            } else {
                $parts[] = "berpengalaman sebagai {$exp}";
            }
        } else {
            // Fresh graduate case
            if (preg_match('/(fresh graduate|baru lulus|freshgraduate)/i', $cvText)) {
                $parts[] = "fresh graduate yang antusias untuk memulai karir profesional";
            }
        }
        
        // Skills
        if (!empty($data['hardskills']) && count($data['hardskills']) >= 3) {
            $topSkills = array_slice($data['hardskills'], 0, 5);
            $skillsText = implode(', ', array_slice($topSkills, 0, -1)) . ' dan ' . end($topSkills);
            $parts[] = "menguasai {$skillsText}";
        }
        
        // Soft skills
        if (!empty($data['softskills']) && count($data['softskills']) >= 2) {
            $topSoft = array_slice($data['softskills'], 0, 3);
            $parts[] = "dengan kemampuan " . implode(', ', $topSoft);
        }
        
        // Combine
        if (empty($parts)) {
            return "Kandidat profesional dengan latar belakang yang solid dan siap berkontribusi dalam tim.";
        }
        
        $profile = ucfirst(implode(', ', $parts)) . '.';
        
        // Add closing statement
        if (strlen($profile) < 100) {
            $profile .= " Siap untuk mengambil tantangan baru dan berkontribusi dalam mencapai tujuan perusahaan.";
        }
        
        return $profile;
    }

    /**
     * Generate education summary
     */
    protected function generateEducationSummary(array $data, string $cvText): string
    {
        $summary = $data['pendidikan_terakhir'];
        
        if (!empty($data['ipk_nilai_akhir']) && $data['ipk_nilai_akhir'] !== 'tidak disebutkan') {
            $summary .= " dengan IPK {$data['ipk_nilai_akhir']}";
        }
        
        // Look for additional education info in CV
        if (preg_match('/(organisasi|himpunan|ukm|bem|senat)/i', $cvText)) {
            $summary .= ". Aktif dalam organisasi kampus";
        }
        
        return $summary . ".";
    }

    /**
     * Generate experience summary
     */
    protected function generateExperienceSummary(array $data, string $cvText): string
    {
        $summary = "Berpengalaman sebagai " . $data['pengalaman_kerja_terakhir'];
        
        $years = $this->extractYearsOfExperience($cvText);
        if ($years > 0) {
            $summary .= " selama {$years} tahun";
        }
        
        // Look for achievements
        if (preg_match('/(berhasil|meningkatkan|mengembangkan|memimpin)/i', $cvText)) {
            $summary .= " dengan track record yang baik dalam pengembangan dan implementasi project";
        }
        
        return $summary . ".";
    }

    /**
     * Extract skills from text using keyword matching
     */
    protected function extractSkillsFromText(string $cvText, string $type = 'hard'): array
    {
        $cvLower = strtolower($cvText);
        $skills = [];
        
        if ($type === 'hard') {
            $keywords = [
                // Programming Languages
                'PHP', 'Python', 'Java', 'JavaScript', 'TypeScript', 'C++', 'C#', 'Go', 'Ruby', 'Swift', 'Kotlin', 'Rust',
                // Web Frameworks
                'Laravel', 'React', 'Vue', 'Angular', 'Django', 'Flask', 'Express', 'Next.js', 'Nuxt',
                // Databases
                'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Oracle', 'SQL Server', 'SQLite', 'MariaDB',
                // DevOps & Tools
                'Docker', 'Kubernetes', 'Git', 'Jenkins', 'CI/CD', 'AWS', 'Azure', 'GCP', 'Linux', 'Nginx',
                // Others
                'REST API', 'GraphQL', 'Microservices', 'Agile', 'Scrum', 'Machine Learning', 'Data Analysis',
                'Node.js', 'jQuery', 'Bootstrap', 'Tailwind', 'SASS', 'Webpack',
            ];
        } else {
            $keywords = [
                'Komunikasi', 'Kepemimpinan', 'Kerja Tim', 'Problem Solving', 'Manajemen Waktu',
                'Analytical Thinking', 'Kreativitas', 'Adaptasi', 'Kolaborasi', 'Public Speaking',
                'Negosiasi', 'Critical Thinking', 'Decision Making', 'Teamwork', 'Leadership',
                'Time Management', 'Communication', 'Presentation', 'Organization',
            ];
        }
        
        foreach ($keywords as $skill) {
            if (stripos($cvLower, strtolower($skill)) !== false) {
                $skills[] = $skill;
            }
        }
        
        return array_slice(array_unique($skills), 0, $type === 'hard' ? 15 : 7);
    }

    /**
     * Extract education from text
     */
    protected function extractEducationFromText(string $cvText): string
    {
        // Pattern untuk pendidikan
        $patterns = [
            '/(?:S[123]|D[34]|SMA|SMK)\s+[-–]\s+([^,\n]+?)(?:\s+[-–]\s+([^,\n(]+?))?(?:\s*\((\d{4})\))?/i',
            '/(Universitas|Institut|Politeknik|Sekolah Tinggi)\s+([^\n,]+)/i',
            '/(Sarjana|Magister|Doktor)\s+([^\n,]+)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cvText, $matches)) {
                return trim($matches[0]);
            }
        }
        
        return 'Tidak disebutkan';
    }

    /**
     * Extract experience from text
     */
    protected function extractExperienceFromText(string $cvText): string
    {
        // Pattern untuk pengalaman kerja
        $patterns = [
            '/([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)\s+[-–]\s+([^,\n]+?)(?:\s+\((\d{4})\s*[-–]\s*(?:\d{4}|Sekarang)\))?/i',
            '/(Staff|Engineer|Developer|Manager|Analyst|Designer|Programmer|Administrator)\s+(?:di\s+)?([^\n,]+)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cvText, $matches)) {
                return trim($matches[0]);
            }
        }
        
        return 'Tidak disebutkan';
    }

    /**
     * Extract IPK from text
     */
    protected function extractIPKFromText(string $cvText): string
    {
        $patterns = [
            '/(IPK|GPA)[\s:]+([0-9]\.[0-9]{1,2})/i',
            '/([0-9]\.[0-9]{1,2})[\s\/]+4[\.,]00/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cvText, $matches)) {
                return isset($matches[2]) ? $matches[2] : $matches[1];
            }
        }
        
        return 'tidak disebutkan';
    }

    /**
     * Extract years of experience
     */
    protected function extractYearsOfExperience(string $cvText): int
    {
        // Look for year ranges
        if (preg_match_all('/\b(20\d{2})\s*[-–]\s*(20\d{2}|Sekarang|Present|Now)\b/i', $cvText, $matches)) {
            $totalYears = 0;
            $currentYear = date('Y');
            
            foreach ($matches[1] as $i => $startYear) {
                $endYear = $matches[2][$i];
                if (preg_match('/sekarang|present|now/i', $endYear)) {
                    $endYear = $currentYear;
                }
                $totalYears += intval($endYear) - intval($startYear);
            }
            
            return max(0, $totalYears);
        }
        
        // Look for explicit mention
        if (preg_match('/(\d+)\s+tahun\s+(pengalaman|experience)/i', $cvText, $matches)) {
            return intval($matches[1]);
        }
        
        return 0;
    }

    /**
     * Enrich result dengan data tambahan
     */
    protected function enrichResult(array $result, string $cvText): array
    {
        return $this->fillCriticalMissingFields($result, $cvText);
    }

    /**
     * Generate enhanced fallback extraction
     */
    protected function generateEnhancedFallback(string $cvText): array
    {
        Log::info('GeminiService: Using enhanced fallback extraction');
        
        return [
            'pendidikan_terakhir' => $this->extractEducationFromText($cvText),
            'rangkuman_pendidikan' => 'Informasi pendidikan akan dilengkapi setelah verifikasi.',
            'ipk_nilai_akhir' => $this->extractIPKFromText($cvText),
            'pengalaman_kerja_terakhir' => $this->extractExperienceFromText($cvText),
            'rangkuman_pengalaman_kerja' => 'Informasi pengalaman kerja akan dilengkapi setelah verifikasi.',
            'rangkuman_sertifikasi_prestasi' => 'Tidak ada sertifikasi atau prestasi yang disebutkan.',
            'rangkuman_profil' => $this->generateFallbackProfile($cvText),
            'hardskills' => $this->extractSkillsFromText($cvText, 'hard'),
            'softskills' => $this->extractSkillsFromText($cvText, 'soft'),
        ];
    }

    /**
     * Generate fallback profile
     */
    protected function generateFallbackProfile(string $cvText): string
    {
        $education = $this->extractEducationFromText($cvText);
        $experience = $this->extractExperienceFromText($cvText);
        $years = $this->extractYearsOfExperience($cvText);
        
        $parts = [];
        
        if ($education !== 'Tidak disebutkan') {
            $parts[] = "Lulusan {$education}";
        } else {
            $parts[] = "Kandidat profesional";
        }
        
        if ($experience !== 'Tidak disebutkan') {
            if ($years > 0) {
                $parts[] = "dengan pengalaman {$years} tahun sebagai {$experience}";
            } else {
                $parts[] = "berpengalaman sebagai {$experience}";
            }
        }
        
        $skills = $this->extractSkillsFromText($cvText, 'hard');
        if (!empty($skills)) {
            $topSkills = array_slice($skills, 0, 3);
            $parts[] = "menguasai " . implode(', ', $topSkills);
        }
        
        if (empty($parts)) {
            return "Kandidat profesional dengan latar belakang yang solid dan siap berkontribusi dalam tim.";
        }
        
        return ucfirst(implode(', ', $parts)) . ". Siap untuk mengambil tantangan baru dan berkontribusi dalam mencapai tujuan perusahaan.";
    }
}