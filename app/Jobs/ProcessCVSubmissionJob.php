<?php

namespace App\Jobs;

use App\Models\CvSubmission;
use App\Services\OCRService;
use App\Services\GeminiService;
use App\Services\StorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessCVSubmissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 1;

    protected $submissionId;

    public function __construct(int $submissionId)
    {
        $this->submissionId = $submissionId;
    }

    public function handle(OCRService $ocrService, GeminiService $geminiService): void
    {
        Log::info('ProcessCVSubmissionJob: Starting job', ['submission_id' => $this->submissionId]);

        $submission = CvSubmission::find($this->submissionId);

        if (!$submission) {
            Log::error('ProcessCVSubmissionJob: Submission not found', ['submission_id' => $this->submissionId]);
            return;
        }

        $tempFilePath = null; 
        try {
            $submission->update(['status' => 'processing', 'processing_step' => 'downloading']);
            Log::info('ProcessCVSubmissionJob: Step 1 - Downloading file from S3');
            $tempFilePath = $this->downloadFileToTemp($submission);
            
            $submission->update(['processing_step' => 'ocr']);
            Log::info('ProcessCVSubmissionJob: Step 2 - Starting OCR extraction');
            $ocrText = $ocrService->extractText($tempFilePath, $submission->cv_file_type);
            
            if (empty(trim($ocrText))) {
                throw new \Exception('OCR extraction returned empty text');
            }
            $submission->update(['ocr_text' => $ocrText]);
            Log::info('ProcessCVSubmissionJob: OCR text saved', ['submission_id' => $this->submissionId, 'text_length' => strlen($ocrText)]);

            $submission->update(['processing_step' => 'analyzing']);
            Log::info('ProcessCVSubmissionJob: Step 3 - Starting Gemini classification');
            $classificationResult = $geminiService->classifyCV($ocrText, $this->submissionId);

            $submission->update(['processing_step' => 'saving']);
            Log::info('ProcessCVSubmissionJob: Step 4 - Saving classification results');
            $submission->update([
                'pendidikan_terakhir' => $classificationResult['pendidikan_terakhir'] ?? '',
                'rangkuman_pendidikan' => $classificationResult['rangkuman_pendidikan'] ?? '',
                'ipk_nilai_akhir' => $classificationResult['ipk_nilai_akhir'] ?? '',
                'pengalaman_kerja_terakhir' => $classificationResult['pengalaman_kerja_terakhir'] ?? '',
                'rangkuman_pengalaman_kerja' => $classificationResult['rangkuman_pengalaman_kerja'] ?? '',
                'rangkuman_sertifikasi_prestasi' => $classificationResult['rangkuman_sertifikasi_prestasi'] ?? '',
                'rangkuman_profil' => $classificationResult['rangkuman_profil'] ?? '',
                'hardskills' => $classificationResult['hardskills'] ?? [],
                'softskills' => $classificationResult['softskills'] ?? [],
                'status' => 'completed',
                'processing_step' => 'finished', 
                'processing_error' => null,
            ]);

            Log::info('ProcessCVSubmissionJob: Job completed successfully', ['submission_id' => $this->submissionId]);

        } catch (\Exception $e) {
            Log::error('ProcessCVSubmissionJob: Job failed', ['submission_id' => $this->submissionId, 'error' => $e->getMessage()]);

            $submission->update([
                'status' => 'failed',
                'processing_step' => 'failed', 
                'processing_error' => $e->getMessage(),
            ]);

            throw $e; 

        } finally {
            if ($tempFilePath) {
                $this->cleanupTempFile($tempFilePath);
            }
        }
    }

    protected function downloadFileToTemp(CvSubmission $submission): string
    {
        try {
            $tempDir = sys_get_temp_dir();
            $tempFileName = 'cv_' . $submission->id . '_' . time() . '.' . $this->getFileExtension($submission->cv_file_type);
            $tempFilePath = $tempDir . '/' . $tempFileName;

            Log::info('ProcessCVSubmissionJob: Downloading from Local Storage', [
                'submission_id' => $submission->id,
                'url' => $submission->cv_file_url,
            ]);

            // Convert public URL (/storage/cvs/...) to storage path (cvs/...)
            $urlPath = parse_url($submission->cv_file_url, PHP_URL_PATH);
            $relativePath = ltrim(str_replace('/storage/', '', $urlPath), '/');

            Log::info('ProcessCVSubmissionJob: Relative path extracted', [
                'submission_id' => $submission->id,
                'relative_path' => $relativePath,
            ]);

            if (!Storage::disk('public')->exists($relativePath)) {
                throw new \Exception("File not found on public disk at path: {$relativePath}");
            }

            $fileContent = Storage::disk('public')->get($relativePath);
            
            if (empty($fileContent)) {
                throw new \Exception('Downloaded file is empty');
            }

            $written = file_put_contents($tempFilePath, $fileContent);
            
            if ($written === false) {
                throw new \Exception('Failed to write file to temp directory');
            }

            $fileSize = filesize($tempFilePath);
            if ($fileSize === 0) {
                throw new \Exception('Temp file is empty after write');
            }

            Log::info('ProcessCVSubmissionJob: File downloaded to temp', [
                'submission_id' => $submission->id,
                'temp_path' => $tempFilePath,
                'file_size' => $fileSize,
            ]);

            return $tempFilePath;
            
        } catch (\Exception $e) {
            Log::error('ProcessCVSubmissionJob: Failed to download file', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    protected function getFileExtension(string $fileType): string
    {
        $extensions = [
            'pdf' => 'pdf',
            'image' => 'jpg',
            'docx' => 'docx',
        ];

        return $extensions[$fileType] ?? 'tmp';
    }

    protected function cleanupTempFile(string $filePath): void
    {
        try {
            if (file_exists($filePath)) {
                unlink($filePath);
                Log::info('ProcessCVSubmissionJob: Temp file cleaned up', [
                    'file_path' => $filePath,
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('ProcessCVSubmissionJob: Failed to cleanup temp file', [
                'file_path' => $filePath,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessCVSubmissionJob: Job failed permanently', ['submission_id' => $this->submissionId, 'error' => $exception->getMessage()]);

        $submission = CvSubmission::find($this->submissionId);
        if ($submission) {
            $submission->update([
                'status' => 'failed',
                'processing_step' => 'failed', 
                'processing_error' => $exception->getMessage(),
            ]);
        }
    }
}