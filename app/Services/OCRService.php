<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser as PdfParser;

class OCRService
{
    protected $maxRetries;
    protected $retryDelay;

    public function __construct()
    {
        $this->maxRetries = config('services.google_vision.max_retries', 3);
        $this->retryDelay = [5, 6, 7, 8];
    }

    /**
     * Extract text from file with retry mechanism
     */
    public function extractText(string $filePath, string $fileType): string
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->maxRetries) {
            try {
                $attempt++;
                
                Log::info('OCRService: Starting text extraction', [
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'attempt' => $attempt,
                ]);

                $text = $this->performOCR($filePath, $fileType);

                Log::info('OCRService: Text extraction successful', [
                    'file_path' => $filePath,
                    'text_length' => strlen($text),
                    'attempt' => $attempt,
                ]);

                return $text;
            } catch (\Exception $e) {
                $lastException = $e;
                
                Log::warning('OCRService: Text extraction failed', [
                    'file_path' => $filePath,
                    'attempt' => $attempt,
                    'max_retries' => $this->maxRetries,
                    'error' => $e->getMessage(),
                ]);

                if ($attempt < $this->maxRetries) {
                    $delay = $this->retryDelay[array_rand($this->retryDelay)];
                    Log::info('OCRService: Retrying after delay', ['delay' => $delay]);
                    sleep($delay);
                }
            }
        }

        Log::error('OCRService: All retry attempts failed', [
            'file_path' => $filePath,
            'attempts' => $attempt,
            'error' => $lastException->getMessage(),
        ]);

        throw new \Exception("OCR extraction failed after {$attempt} attempts: " . $lastException->getMessage());
    }

    /**
     * Perform OCR based on file type
     */
    protected function performOCR(string $filePath, string $fileType): string
    {
        switch ($fileType) {
            case 'image':
                return $this->extractFromImageWithGemini($filePath);
            
            case 'pdf':
                return $this->extractFromPDF($filePath);
            
            case 'docx':
                return $this->extractFromDocx($filePath);
            
            default:
                throw new \Exception("Unsupported file type for OCR: {$fileType}");
        }
    }

    /**
     * Extract text from image using Gemini Vision
     */
    protected function extractFromImageWithGemini(string $filePath): string
    {
        try {
            Log::info('OCRService: Using Gemini Vision for image OCR');
            
            // Read image and convert to base64
            $imageData = file_get_contents($filePath);
            $base64Image = base64_encode($imageData);
            
            // Detect mime type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);
            
            $apiKey = config('services.gemini.api_key');
            $apiUrl = config('services.gemini.api_url');
            
            // Use Gemini Vision model
            $response = Http::timeout(60)
                ->post("{$apiUrl}/models/gemini-2.5-pro:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => 'Extract all text from this image. Return ONLY the extracted text without any explanation or formatting. If this is a CV/resume, extract all information including name, education, experience, skills, etc.'
                                ],
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data' => $base64Image
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'maxOutputTokens' => 8192,
                    ]
                ]);

            if (!$response->successful()) {
                throw new \Exception("Gemini API request failed: " . $response->body());
            }

            $data = $response->json();
            
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                throw new \Exception("Invalid response format from Gemini API");
            }

            $extractedText = $data['candidates'][0]['content']['parts'][0]['text'];
            
            if (empty(trim($extractedText))) {
                throw new \Exception('No text found in image');
            }

            Log::info('OCRService: Image OCR successful with Gemini', [
                'text_length' => strlen($extractedText),
            ]);

            return trim($extractedText);
            
        } catch (\Exception $e) {
            Log::error('OCRService: Image OCR with Gemini failed', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
            ]);
            throw $e;
        }
    }

    /**
     * Extract text from PDF using PDFParser
     */
    protected function extractFromPDF(string $filePath): string
    {
        try {
            Log::info('OCRService: Starting PDF text extraction with PDFParser');
            
            // Normalize path
            $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);
            
            if (!file_exists($filePath)) {
                throw new \Exception('File does not exist: ' . $filePath);
            }
            
            if (!is_readable($filePath)) {
                throw new \Exception('File is not readable: ' . $filePath);
            }
            
            $fileSize = filesize($filePath);
            Log::info('OCRService: PDF file info', [
                'path' => $filePath,
                'size' => $fileSize,
                'readable' => is_readable($filePath),
            ]);
            
            if ($fileSize === 0) {
                throw new \Exception('File is empty');
            }
            
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            
            $text = $pdf->getText();
            
            if (empty(trim($text))) {
                Log::warning('OCRService: PDFParser returned empty text, trying Gemini OCR for scanned PDF');
                // Fallback: Convert PDF to image then use Gemini
                return $this->extractFromScannedPDFWithGemini($filePath);
            }

            Log::info('OCRService: PDF text extracted successfully', [
                'text_length' => strlen($text),
            ]);

            return trim($text);
            
        } catch (\Exception $e) {
            Log::error('OCRService: PDF text extraction failed', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
            ]);
            
            // Try Gemini as fallback
            try {
                Log::info('OCRService: Trying Gemini as fallback for PDF');
                return $this->extractFromScannedPDFWithGemini($filePath);
            } catch (\Exception $geminiError) {
                throw new \Exception('PDF extraction failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Extract text from scanned PDF using Gemini (requires Imagick)
     */
    protected function extractFromScannedPDFWithGemini(string $filePath): string
    {
        try {
            if (!extension_loaded('imagick')) {
                throw new \Exception('Imagick extension required for scanned PDF. Please install or convert PDF to image first.');
            }

            Log::info('OCRService: Converting PDF to image for Gemini OCR');
            
            $pdf = new \Imagick();
            $pdf->setResolution(200, 200);
            $pdf->readImage($filePath . '[0]'); // Read first page only for now
            $pdf->setImageFormat('jpg');
            
            // Save to temp file
            $tempImagePath = sys_get_temp_dir() . '/pdf_page_' . time() . '.jpg';
            $pdf->writeImage($tempImagePath);
            $pdf->clear();

            // Extract text from image
            $text = $this->extractFromImageWithGemini($tempImagePath);
            
            // Cleanup
            if (file_exists($tempImagePath)) {
                unlink($tempImagePath);
            }

            return $text;
            
        } catch (\Exception $e) {
            Log::error('OCRService: Scanned PDF OCR failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Extract text from DOCX
     */
    protected function extractFromDocx(string $filePath): string
    {
        try {
            $zip = new \ZipArchive();
            
            if ($zip->open($filePath) === true) {
                $content = $zip->getFromName('word/document.xml');
                $zip->close();

                if ($content === false) {
                    throw new \Exception('Could not read document.xml from DOCX');
                }

                $content = str_replace('</w:p>', "\n", $content);
                $content = strip_tags($content);
                $content = trim($content);

                if (empty($content)) {
                    throw new \Exception('No text found in DOCX');
                }

                return $content;
            }

            throw new \Exception('Could not open DOCX file');
        } catch (\Exception $e) {
            Log::error('OCRService: DOCX text extraction failed', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
            ]);
            throw $e;
        }
    }
}