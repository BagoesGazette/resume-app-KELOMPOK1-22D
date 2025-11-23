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

    public $timeout = 300; // 5 minutes
    public $tries = 1; // Don't retry the job itself, we handle retries internally

    protected $submissionId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $submissionId)
    {
        $this->submissionId = $submissionId;
    }

    /**
     * Execute the job.
     */
    public function handle(
        OCRService $ocrService,
        GeminiService $geminiService,
        StorageService $storageService
    ): void {
        Log::info('ProcessCVSubmissionJob: Starting job', [
            'submission_id' => $this->submissionId,
        ]);

        $submission = CvSubmission::find($this->submissionId);

        if (!$submission) {
            Log::error('ProcessCVSubmissionJob: Submission not found', [
                'submission_id' => $this->submissionId,
            ]);
            return;
        }

        try {
            // Update status to processing
            $this->updateStatus($submission, 'processing');
            Log::info('ProcessCVSubmissionJob: Status updated to processing', [
                'submission_id' => $this->submissionId,
            ]);

            // Step 1: Download file from S3
            Log::info('ProcessCVSubmissionJob: Step 1 - Downloading file from S3');
            $tempFilePath = $this->downloadFileToTemp($submission);
            
            // Step 2: Perform OCR
            Log::info('ProcessCVSubmissionJob: Step 2 - Starting OCR extraction');
            $ocrText = $ocrService->extractText($tempFilePath, $submission->cv_file_type);
            
            if (empty(trim($ocrText))) {
                throw new \Exception('OCR extraction returned empty text');
            }

            // Save OCR text
            $submission->ocr_text = $ocrText;
            $submission->save();
            Log::info('ProcessCVSubmissionJob: OCR text saved', [
                'submission_id' => $this->submissionId,
                'text_length' => strlen($ocrText),
            ]);

            // Step 3: Classify with Gemini
            Log::info('ProcessCVSubmissionJob: Step 3 - Starting Gemini classification');
            $classificationResult = $geminiService->classifyCV($ocrText, $this->submissionId);

            // Step 4: Save classification results
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
                'processing_error' => null,
            ]);

            Log::info('ProcessCVSubmissionJob: Job completed successfully', [
                'submission_id' => $this->submissionId,
            ]);

            // Clean up temp file
            $this->cleanupTempFile($tempFilePath);

        } catch (\Exception $e) {
            Log::error('ProcessCVSubmissionJob: Job failed', [
                'submission_id' => $this->submissionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $submission->update([
                'status' => 'failed',
                'processing_error' => $e->getMessage(),
            ]);

            // Clean up temp file if exists
            if (isset($tempFilePath) && file_exists($tempFilePath)) {
                $this->cleanupTempFile($tempFilePath);
            }

            throw $e;
        }
    }

    /**
     * Update submission status
     */
    protected function updateStatus(CvSubmission $submission, string $status): void
    {
        $submission->update(['status' => $status]);
        
        Log::info('ProcessCVSubmissionJob: Status updated', [
            'submission_id' => $this->submissionId,
            'status' => $status,
        ]);
    }
                
    /**
     * Download file from S3 to temporary location
     */
    protected function downloadFileToTemp(CvSubmission $submission): string
    {
        try {
            $tempDir = sys_get_temp_dir();
            $tempFileName = 'cv_' . $submission->id . '_' . time() . '.' . $this->getFileExtension($submission->cv_file_type);
            $tempFilePath = $tempDir . '/' . $tempFileName;

            Log::info('ProcessCVSubmissionJob: Downloading from S3', [
                'submission_id' => $submission->id,
                'url' => $submission->cv_file_url,
            ]);

            // Extract S3 key from URL
            $urlPath = parse_url($submission->cv_file_url, PHP_URL_PATH);
            $s3Key = ltrim($urlPath, '/');
            
            // Remove bucket name from path if present
            $bucketName = config('filesystems.disks.s3.bucket');
            $s3Key = str_replace($bucketName . '/', '', $s3Key);

            Log::info('ProcessCVSubmissionJob: S3 key extracted', [
                'submission_id' => $submission->id,
                's3_key' => $s3Key,
            ]);

            // Get file content from S3
            $fileContent = Storage::disk('s3')->get($s3Key);
            
            if (empty($fileContent)) {
                throw new \Exception('Downloaded file is empty');
            }

            // Save to temp file
            $written = file_put_contents($tempFilePath, $fileContent);
            
            if ($written === false) {
                throw new \Exception('Failed to write file to temp directory');
            }

            // Verify file was written correctly
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

    /**
     * Extract path from S3 URL
     */
    protected function extractPathFromUrl(string $url): string
    {
        $parsed = parse_url($url);
        return ltrim($parsed['path'] ?? '', '/');
    }

    /**
     * Get file extension based on type
     */
    protected function getFileExtension(string $fileType): string
    {
        $extensions = [
            'pdf' => 'pdf',
            'image' => 'jpg',
            'docx' => 'docx',
        ];

        return $extensions[$fileType] ?? 'tmp';
    }

    /**
     * Clean up temporary file
     */
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

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessCVSubmissionJob: Job failed permanently', [
            'submission_id' => $this->submissionId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        $submission = CvSubmission::find($this->submissionId);
        if ($submission) {
            $submission->update([
                'status' => 'failed',
                'processing_error' => $exception->getMessage(),
            ]);
        }
    }
}