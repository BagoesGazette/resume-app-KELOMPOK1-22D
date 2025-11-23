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
    protected $retryDelay;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->apiUrl = config('services.gemini.api_url');
        $this->model = config('services.gemini.model');
        $this->timeout = config('services.gemini.timeout', 60);
        $this->maxRetries = config('services.gemini.max_retries', 3);
        $this->retryDelay = [5, 6, 7, 8];
    }

    /**
     * Classify CV text and extract structured data
     */
    public function classifyCV(string $cvText, int $submissionId): array
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->maxRetries) {
            try {
                $attempt++;
                
                Log::info('GeminiService: Starting CV classification', [
                    'submission_id' => $submissionId,
                    'text_length' => strlen($cvText),
                    'attempt' => $attempt,
                ]);

                $result = $this->performClassification($cvText, $attempt);

                Log::info('GeminiService: CV classification successful', [
                    'submission_id' => $submissionId,
                    'attempt' => $attempt,
                ]);

                return $result;
                
            } catch (\Exception $e) {
                $lastException = $e;
                
                Log::warning('GeminiService: CV classification failed', [
                    'submission_id' => $submissionId,
                    'attempt' => $attempt,
                    'max_retries' => $this->maxRetries,
                    'error' => $e->getMessage(),
                ]);

                if ($attempt < $this->maxRetries) {
                    $delay = $this->retryDelay[array_rand($this->retryDelay)];
                    Log::info('GeminiService: Retrying after delay', ['delay' => $delay]);
                    sleep($delay);
                }
            }
        }

        Log::error('GeminiService: All retry attempts failed', [
            'submission_id' => $submissionId,
            'attempts' => $attempt,
            'error' => $lastException->getMessage(),
        ]);

        throw new \Exception("Gemini classification failed after {$attempt} attempts: " . $lastException->getMessage());
    }

    /**
     * Perform classification with Gemini API
     */
    protected function performClassification(string $cvText, int $attempt = 1): array
    {
        $prompt = $this->buildPrompt($cvText);
        
        // Adjust temperature based on attempt
        $temperature = match($attempt) {
            1 => 0.2,
            2 => 0.1,
            3 => 0.3,
            default => 0.2
        };
        
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
                    'temperature' => $temperature,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 4096,
                ]
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gemini API request failed: " . $response->body());
        }

        $data = $response->json();
        
        if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception("Invalid response format from Gemini API");
        }

        $textResponse = $data['candidates'][0]['content']['parts'][0]['text'];
        
        return $this->parseGeminiResponse($textResponse);
    }

    /**
     * Build prompt for Gemini
     */
    protected function buildPrompt(string $cvText): string
    {
        return <<<PROMPT
Analisis CV/Resume berikut dan ekstrak informasi dalam format JSON yang valid dan konsisten.

PENTING:
1. Gunakan BAHASA INDONESIA untuk semua output
2. Jika informasi tidak ditemukan, gunakan string kosong "" atau array kosong []
3. JANGAN menambahkan komentar, penjelasan, atau text selain JSON
4. JANGAN gunakan markdown code blocks (```json)
5. Pastikan JSON valid dan dapat di-parse

CV TEXT:
---
{$cvText}
---

INSTRUKSI EKSTRAKSI:

1. pendidikan_terakhir (string):
   - Format: "Jenjang Program Studi" (contoh: "S1 Teknik Informatika", "D3 Manajemen")
   - Jika tidak ada: ""

2. rangkuman_pendidikan (string):
   - Ringkasan LENGKAP semua riwayat pendidikan
   - Include: institusi, tahun lulus, IPK/nilai jika ada
   - Format paragraf, bahasa Indonesia
   - Jika tidak ada: ""

3. ipk_nilai_akhir (string):
   - Format: "X.XX" atau "X.XX/4.00" (contoh: "3.75", "3.67/4.00")
   - Ambil IPK/GPA/nilai akhir tertinggi
   - Jika tidak ada: ""

4. pengalaman_kerja_terakhir (string):
   - Format: "Posisi di Perusahaan" (contoh: "Senior Developer di PT Tech")
   - Ambil posisi/jabatan terakhir atau saat ini
   - Jika tidak ada: ""

5. rangkuman_pengalaman_kerja (string):
   - Ringkasan LENGKAP semua pengalaman kerja
   - Include: posisi, perusahaan, periode, tanggung jawab utama
   - Format paragraf, bahasa Indonesia
   - Urutkan dari terbaru ke terlama
   - Jika tidak ada: ""

6. rangkuman_sertifikasi_prestasi (string):
   - Ringkasan semua sertifikasi, penghargaan, prestasi
   - Format paragraf, bahasa Indonesia
   - Jika tidak ada: ""

7. rangkuman_profil (string):
   - Ringkasan profil/tentang kandidat
   - Summary dari keseluruhan CV
   - Highlight keahlian utama dan pengalaman
   - Format paragraf, bahasa Indonesia, maksimal 200 kata
   - Jika tidak ada profil eksplisit, buat ringkasan dari keseluruhan CV
   - WAJIB diisi, minimal 50 kata

8. hardskills (array of strings):
   - List keahlian TEKNIS saja
   - Contoh: programming languages, tools, frameworks, software
   - ["PHP", "Laravel", "JavaScript", "MySQL", "Docker"]
   - Minimal 3, maksimal 20 skills
   - Jika tidak ada: []

9. softskills (array of strings):
   - List keahlian NON-TEKNIS saja
   - Contoh: komunikasi, kepemimpinan, kerja tim, problem solving
   - ["Komunikasi", "Kepemimpinan", "Kerja Tim", "Problem Solving"]
   - Minimal 2, maksimal 10 skills
   - Jika tidak ada: []

FORMAT OUTPUT (JSON ONLY):
{
  "pendidikan_terakhir": "string",
  "rangkuman_pendidikan": "string",
  "ipk_nilai_akhir": "string",
  "pengalaman_kerja_terakhir": "string",
  "rangkuman_pengalaman_kerja": "string",
  "rangkuman_sertifikasi_prestasi": "string",
  "rangkuman_profil": "string",
  "hardskills": ["skill1", "skill2", "skill3"],
  "softskills": ["skill1", "skill2"]
}

RESPONSE HARUS:
✓ JSON valid (bisa di-parse)
✓ Semua field wajib ada
✓ Bahasa Indonesia konsisten
✓ Tidak ada markdown atau penjelasan tambahan
✓ Tidak ada control characters atau escape yang salah

Mulai ekstraksi sekarang:
PROMPT;
    }

    /**
     * Parse Gemini response and extract JSON
     */
    protected function parseGeminiResponse(string $response): array
    {
        try {
            // Remove markdown code blocks if present
            $response = preg_replace('/```json\n?/', '', $response);
            $response = preg_replace('/```\n?/', '', $response);
            $response = trim($response);
            
            // Remove BOM if present
            $response = str_replace("\xEF\xBB\xBF", '', $response);
            
            // Fix common JSON issues
            $response = $this->fixCommonJsonIssues($response);

            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('GeminiService: JSON parse error', [
                    'error' => json_last_error_msg(),
                    'response_preview' => substr($response, 0, 500),
                ]);
                throw new \Exception("Failed to parse JSON: " . json_last_error_msg());
            }

            // Validate and normalize data
            $data = $this->validateAndNormalizeData($data);

            return $data;
            
        } catch (\Exception $e) {
            Log::error('GeminiService: Failed to parse response', [
                'error' => $e->getMessage(),
                'response_preview' => substr($response, 0, 500),
            ]);
            throw $e;
        }
    }

    /**
     * Fix common JSON issues
     */
    protected function fixCommonJsonIssues(string $json): string
    {
        // Remove control characters
        $json = preg_replace('/[\x00-\x1F\x7F]/u', '', $json);
        
        return $json;
    }

    /**
     * Validate and normalize extracted data
     */
    protected function validateAndNormalizeData(array $data): array
    {
        $requiredFields = [
            'pendidikan_terakhir',
            'rangkuman_pendidikan',
            'ipk_nilai_akhir',
            'pengalaman_kerja_terakhir',
            'rangkuman_pengalaman_kerja',
            'rangkuman_sertifikasi_prestasi',
            'rangkuman_profil',
            'hardskills',
            'softskills',
        ];

        // Ensure all required fields exist
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $data[$field] = in_array($field, ['hardskills', 'softskills']) ? [] : '';
            }
        }

        // Normalize arrays
        if (!is_array($data['hardskills'])) {
            $data['hardskills'] = [];
        }
        if (!is_array($data['softskills'])) {
            $data['softskills'] = [];
        }
        
        // Remove empty strings from arrays
        $data['hardskills'] = array_values(array_filter($data['hardskills'], function($skill) {
            return !empty(trim($skill));
        }));
        
        $data['softskills'] = array_values(array_filter($data['softskills'], function($skill) {
            return !empty(trim($skill));
        }));
        
        // Ensure strings are strings
        foreach ($requiredFields as $field) {
            if (!in_array($field, ['hardskills', 'softskills'])) {
                $data[$field] = (string) $data[$field];
            }
        }
        
        // Validate minimum data quality
        if (empty($data['rangkuman_profil']) || strlen($data['rangkuman_profil']) < 30) {
            Log::warning('GeminiService: Poor quality extraction - missing profile summary');
        }
        
        if (empty($data['hardskills']) && empty($data['softskills'])) {
            Log::warning('GeminiService: Poor quality extraction - no skills extracted');
        }

        return $data;
    }
}