<?php

namespace App\Services;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Image;
use Illuminate\Support\Facades\Log;
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
                return $this->extractFromImage($filePath);
            
            case 'pdf':
                return $this->extractFromPDF($filePath);
            
            case 'docx':
                return $this->extractFromDocx($filePath);
            
            default:
                throw new \Exception("Unsupported file type for OCR: {$fileType}");
        }
    }

    /**
     * Extract text from image using Google Vision API
     */
    protected function extractFromImage(string $filePath): string
    {
        try {
            $imageAnnotator = new ImageAnnotatorClient([
                'credentials' => config('services.google_vision.credentials'),
            ]);

            $imageContent = file_get_contents($filePath);
            $image = (new Image())->setContent($imageContent);

            $response = $imageAnnotator->textDetection($image);
            $texts = $response->getTextAnnotations();

            if (count($texts) > 0) {
                $extractedText = $texts[0]->getDescription();
                $imageAnnotator->close();
                return $extractedText;
            }

            $imageAnnotator->close();
            throw new \Exception('No text found in image');
        } catch (\Exception $e) {
            Log::error('OCRService: Image OCR failed', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
            ]);
            throw $e;
        }
    }

    /**
     * Extract text from PDF using PDFParser (no OCR, direct text extraction)
     */
    protected function extractFromPDF(string $filePath): string
    {
        try {
            Log::info('OCRService: Starting PDF text extraction with PDFParser');
            
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            
            $text = $pdf->getText();
            
            if (empty(trim($text))) {
                Log::warning('OCRService: PDFParser returned empty text, trying OCR method');
                // Fallback ke Google Vision jika PDF adalah scanned document
                return $this->extractFromPDFWithOCR($filePath);
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
            
            // Fallback ke OCR method
            Log::info('OCRService: Attempting OCR fallback for PDF');
            try {
                return $this->extractFromPDFWithOCR($filePath);
            } catch (\Exception $ocrException) {
                throw new \Exception('PDF extraction failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Extract text from PDF using Google Vision OCR (for scanned PDFs)
     */
    protected function extractFromPDFWithOCR(string $filePath): string
    {
        try {
            Log::info('OCRService: Using Google Vision OCR for PDF');
            
            // Convert PDF first page to image
            $imagePath = $this->convertPDFToImage($filePath);
            
            if (!$imagePath) {
                throw new \Exception('Could not convert PDF to image');
            }
            
            // Extract text using Vision API
            $text = $this->extractFromImage($imagePath);
            
            // Cleanup temp image
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            return $text;
            
        } catch (\Exception $e) {
            Log::error('OCRService: PDF OCR fallback failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Convert PDF first page to image (simple method without Imagick)
     */
    protected function convertPDFToImage(string $pdfPath): ?string
    {
        // For now, skip this if Imagick not available
        // You can implement using Ghostscript or other tools later
        Log::warning('OCRService: PDF to image conversion not available without Imagick');
        return null;
    }

    /**
     * Extract text from DOCX (direct text extraction, no OCR needed)
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

                // Remove XML tags and extract text
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