<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvSubmission;
use App\Services\StorageService;
use App\Jobs\ProcessCVSubmissionJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CvSubmissionController extends Controller
{
    protected $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Upload CV and start processing
     * POST /api/cv-submissions
     */
    public function store(Request $request)
    {
        Log::info('CvSubmissionController: Received upload request', [
            'user_id' => auth()->id(),
        ]);

        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'cv_file' => [
                    'required',
                    'file',
                    'max:10240', // 10MB
                    'mimes:pdf,jpg,jpeg,png,doc,docx',
                ],
            ]);

            if ($validator->fails()) {
                Log::warning('CvSubmissionController: Validation failed', [
                    'errors' => $validator->errors()->toArray(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $file = $request->file('cv_file');
            $userId = auth()->id();

            // Check file type
            try {
                $fileType = $this->storageService->detectFileType($file);
            } catch (\Exception $e) {
                Log::error('CvSubmissionController: Unsupported file type', [
                    'user_id' => $userId,
                    'mime_type' => $file->getMimeType(),
                    'error' => $e->getMessage(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unsupported file type. Please upload PDF, Image (JPG/PNG), or Word document.',
                ], 422);
            }

            // Upload to S3
            Log::info('CvSubmissionController: Uploading file to S3');
            $uploadResult = $this->storageService->uploadCV($file, $userId);

            // Create submission record
            $submission = CvSubmission::create([
                'user_id' => $userId,
                'cv_file_url' => $uploadResult['url'],
                'cv_file_name' => $uploadResult['name'],
                'cv_file_type' => $uploadResult['type'],
                'cv_file_size' => $uploadResult['size'],
                'status' => 'pending',
            ]);

            Log::info('CvSubmissionController: Submission created', [
                'submission_id' => $submission->id,
                'user_id' => $userId,
            ]);

            // Dispatch job for processing
            ProcessCVSubmissionJob::dispatch($submission->id);

            Log::info('CvSubmissionController: Processing job dispatched', [
                'submission_id' => $submission->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'CV uploaded successfully and is being processed',
                'data' => [
                    'submission_id' => $submission->id,
                    'status' => $submission->status,
                    'file_name' => $submission->cv_file_name,
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('CvSubmissionController: Upload failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload CV: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get submission status
     * GET /api/cv-submissions/{id}
     */
    public function show($id)
    {
        Log::info('CvSubmissionController: Fetching submission', [
            'submission_id' => $id,
            'user_id' => auth()->id(),
        ]);

        try {
            $submission = CvSubmission::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $submission->id,
                    'status' => $submission->status,
                    'file_name' => $submission->cv_file_name,
                    'file_type' => $submission->cv_file_type,
                    'file_size' => $submission->cv_file_size,
                    'cv_file_url' => $submission->cv_file_url,
                    'processing_error' => $submission->processing_error,
                    'is_validated' => $submission->is_validated,
                    'created_at' => $submission->created_at,
                    'updated_at' => $submission->updated_at,
                    
                    // Classification results (only if completed)
                    'classification' => $submission->status === 'completed' ? [
                        'pendidikan_terakhir' => $submission->pendidikan_terakhir,
                        'rangkuman_pendidikan' => $submission->rangkuman_pendidikan,
                        'ipk_nilai_akhir' => $submission->ipk_nilai_akhir,
                        'pengalaman_kerja_terakhir' => $submission->pengalaman_kerja_terakhir,
                        'rangkuman_pengalaman_kerja' => $submission->rangkuman_pengalaman_kerja,
                        'rangkuman_sertifikasi_prestasi' => $submission->rangkuman_sertifikasi_prestasi,
                        'rangkuman_profil' => $submission->rangkuman_profil,
                        'hardskills' => $submission->hardskills,
                        'softskills' => $submission->softskills,
                    ] : null,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('CvSubmissionController: Failed to fetch submission', [
                'submission_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Submission not found',
            ], 404);
        }
    }

    /**
     * Update/validate submission data
     * PUT /api/cv-submissions/{id}
     */
    public function update(Request $request, $id)
    {
        Log::info('CvSubmissionController: Updating submission', [
            'submission_id' => $id,
            'user_id' => auth()->id(),
        ]);

        try {
            $submission = CvSubmission::where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', 'completed')
                ->firstOrFail();

            $validator = Validator::make($request->all(), [
                'pendidikan_terakhir' => 'nullable|string|max:255',
                'rangkuman_pendidikan' => 'nullable|string',
                'ipk_nilai_akhir' => 'nullable|string|max:10',
                'pengalaman_kerja_terakhir' => 'nullable|string|max:255',
                'rangkuman_pengalaman_kerja' => 'nullable|string',
                'rangkuman_sertifikasi_prestasi' => 'nullable|string',
                'rangkuman_profil' => 'nullable|string',
                'hardskills' => 'nullable|array',
                'hardskills.*' => 'string|max:100',
                'softskills' => 'nullable|array',
                'softskills.*' => 'string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $submission->update([
                'pendidikan_terakhir' => $request->input('pendidikan_terakhir', $submission->pendidikan_terakhir),
                'rangkuman_pendidikan' => $request->input('rangkuman_pendidikan', $submission->rangkuman_pendidikan),
                'ipk_nilai_akhir' => $request->input('ipk_nilai_akhir', $submission->ipk_nilai_akhir),
                'pengalaman_kerja_terakhir' => $request->input('pengalaman_kerja_terakhir', $submission->pengalaman_kerja_terakhir),
                'rangkuman_pengalaman_kerja' => $request->input('rangkuman_pengalaman_kerja', $submission->rangkuman_pengalaman_kerja),
                'rangkuman_sertifikasi_prestasi' => $request->input('rangkuman_sertifikasi_prestasi', $submission->rangkuman_sertifikasi_prestasi),
                'rangkuman_profil' => $request->input('rangkuman_profil', $submission->rangkuman_profil),
                'hardskills' => $request->input('hardskills', $submission->hardskills),
                'softskills' => $request->input('softskills', $submission->softskills),
                'is_validated' => true,
                'validated_at' => now(),
            ]);

            Log::info('CvSubmissionController: Submission updated and validated', [
                'submission_id' => $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'CV data updated successfully',
                'data' => [
                    'id' => $submission->id,
                    'is_validated' => $submission->is_validated,
                    'validated_at' => $submission->validated_at,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('CvSubmissionController: Update failed', [
                'submission_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update submission: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's submissions list
     * GET /api/cv-submissions
     */
    public function index(Request $request)
    {
        Log::info('CvSubmissionController: Fetching user submissions', [
            'user_id' => auth()->id(),
        ]);

        try {
            $submissions = CvSubmission::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $submissions->map(function ($submission) {
                    return [
                        'id' => $submission->id,
                        'status' => $submission->status,
                        'file_name' => $submission->cv_file_name,
                        'file_type' => $submission->cv_file_type,
                        'is_validated' => $submission->is_validated,
                        'created_at' => $submission->created_at,
                        'updated_at' => $submission->updated_at,
                    ];
                }),
                'pagination' => [
                    'total' => $submissions->total(),
                    'per_page' => $submissions->perPage(),
                    'current_page' => $submissions->currentPage(),
                    'last_page' => $submissions->lastPage(),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('CvSubmissionController: Failed to fetch submissions', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch submissions',
            ], 500);
        }
    }

    /**
     * Delete submission
     * DELETE /api/cv-submissions/{id}
     */
    public function destroy($id)
    {
        Log::info('CvSubmissionController: Deleting submission', [
            'submission_id' => $id,
            'user_id' => auth()->id(),
        ]);

        try {
            $submission = CvSubmission::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Delete file from S3
            $path = $this->storageService->extractPathFromUrl($submission->cv_file_url);
            $this->storageService->deleteFile($path);

            // Delete record
            $submission->delete();

            Log::info('CvSubmissionController: Submission deleted', [
                'submission_id' => $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Submission deleted successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('CvSubmissionController: Delete failed', [
                'submission_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete submission',
            ], 500);
        }
    }
}