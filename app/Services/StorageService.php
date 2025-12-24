<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class StorageService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = 's3'; 
    }

    /**
     * Upload file to Vultr S3
     */
    public function uploadCV(UploadedFile $file, int $userId): array
    {
        try {
            Log::info('StorageService: Starting CV upload', [
                'user_id' => $userId,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            $fileName = $this->generateFileName($file, $userId);
            $path = "cvs/{$userId}/{$fileName}";

            // Upload to S3
            $uploaded = Storage::disk($this->disk)->put($path, file_get_contents($file), 'public');

            if (!$uploaded) {
                throw new \Exception('Failed to upload file to S3');
            }

            $url = Storage::disk($this->disk)->url($path);

            Log::info('StorageService: CV uploaded successfully', [
                'user_id' => $userId,
                'path' => $path,
                'url' => $url,
            ]);

            return [
                'url' => $url,
                'path' => $path,
                'name' => $fileName,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $this->detectFileType($file),
            ];
        } catch (\Exception $e) {
            Log::error('StorageService: Failed to upload CV', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Generate unique file name
     */
    protected function generateFileName(UploadedFile $file, int $userId): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = substr(md5(uniqid()), 0, 8);
        
        return "cv_{$userId}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Detect file type for processing
     */
    public function detectFileType(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        // PDF
        if ($mimeType === 'application/pdf' || $extension === 'pdf') {
            return 'pdf';
        }

        // Images
        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg']) || 
            in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return 'image';
        }

        // Word Documents
        if (in_array($mimeType, [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword'
        ]) || in_array($extension, ['doc', 'docx'])) {
            return 'docx';
        }

        throw new \Exception("Unsupported file type: {$mimeType}");
    }

    /**
     * Download file from S3
     */
    public function downloadFile(string $path): string
    {
        try {
            Log::info('StorageService: Downloading file from S3', ['path' => $path]);
            
            return Storage::disk($this->disk)->get($path);
        } catch (\Exception $e) {
            Log::error('StorageService: Failed to download file', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete file from S3
     */
    public function deleteFile(string $path): bool
    {
        try {
            Log::info('StorageService: Deleting file from S3', ['path' => $path]);
            
            return Storage::disk($this->disk)->delete($path);
        } catch (\Exception $e) {
            Log::error('StorageService: Failed to delete file', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}