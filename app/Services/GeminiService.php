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
     * Classify CV text with retry mechanism
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

                $result = $this->performClassification($cvText);

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
     * Perform actual classification using Gemini API
     */
    protected function performClassification(string $cvText): array
    {
        $prompt = $this->buildPrompt($cvText);
        
        $response = Http::timeout($this->timeout)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post("{$this->apiUrl}/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 2048,
                ]
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gemini API request failed: " . $response->body());
        }

        $data = $response->json();
        
        if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception("Invalid response format from Gemini API");
        }

        $resultText = $data['candidates'][0]['content']['parts'][0]['text'];
        
        return $this->parseGeminiResponse($resultText);
    }

    /**
     * Build prompt for Gemini
     */
    protected function buildPrompt(string $cvText): string
    {
        return <<<PROMPT
Analisis CV berikut dan ekstrak informasi dalam format JSON yang valid. Berikan response HANYA dalam format JSON tanpa markdown atau penjelasan tambahan.

CV Text:
{$cvText}

Ekstrak informasi berikut:
1. pendidikan_terakhir: Pendidikan terakhir (contoh: "S1 Teknik Informatika")
2. rangkuman_pendidikan: Ringkasan lengkap riwayat pendidikan
3. ipk_nilai_akhir: IPK atau nilai akhir (contoh: "3.75")
4. pengalaman_kerja_terakhir: Posisi dan perusahaan terakhir
5. rangkuman_pengalaman_kerja: Ringkasan lengkap pengalaman kerja
6. rangkuman_sertifikasi_prestasi: Ringkasan sertifikasi dan prestasi
7. rangkuman_profil: Ringkasan profil/tentang kandidat
8. hardskills: Array keahlian teknis (programming languages, tools, technologies)
9. softskills: Array keahlian non-teknis (leadership, communication, problem solving, dll)

Format response JSON:
{
  "pendidikan_terakhir": "string",
  "rangkuman_pendidikan": "string",
  "ipk_nilai_akhir": "string",
  "pengalaman_kerja_terakhir": "string",
  "rangkuman_pengalaman_kerja": "string",
  "rangkuman_sertifikasi_prestasi": "string",
  "rangkuman_profil": "string",
  "hardskills": ["skill1", "skill2"],
  "softskills": ["skill1", "skill2"]
}

Jika informasi tidak ditemukan, gunakan string kosong "" atau array kosong [].
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

            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Failed to parse JSON: " . json_last_error_msg());
            }

            // Validate required fields
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

            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    $data[$field] = is_array($field) ? [] : '';
                }
            }

            // Ensure arrays for skills
            if (!is_array($data['hardskills'])) {
                $data['hardskills'] = [];
            }
            if (!is_array($data['softskills'])) {
                $data['softskills'] = [];
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('GeminiService: Failed to parse response', [
                'error' => $e->getMessage(),
                'response' => $response,
            ]);
            throw $e;
        }
    }
}