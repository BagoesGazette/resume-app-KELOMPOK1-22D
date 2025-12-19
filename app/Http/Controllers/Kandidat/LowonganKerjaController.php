<?php

namespace App\Http\Controllers\Kandidat;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use App\Models\CvSubmission;
use App\Services\StorageService;
use App\Jobs\ProcessCVSubmissionJob;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LowonganKerjaController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->tipe;

        $job = JobOpening::where('status', 'open')
        ->searchTipe($tipe)
        ->get();

        return view('kandidat.lowongan-kerja.index', compact('job', 'tipe'));
    }

    public function create($id)
    {
        $lowongan = JobOpening::find($id);
        $currentCv = CvSubmission::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->first();

        return view('kandidat.lowongan-kerja.create', compact('lowongan', 'currentCv'));
    }

    public function show($id)
    {
        $lowongan = JobOpening::find($id);
        $cv       = CvSubmission::where('user_id', Auth::id())->first();
        if ($cv) {
            $exists = JobApplication::where('user_id', Auth::id())
            ->where('cv_submission_id', $cv->id)
            ->where('jobopening_id', $id)
            ->where('status', 'submitted')
            ->first();
        }else{
            $exists = false;
        }
        

        return view('kandidat.lowongan-kerja.show', compact('lowongan', 'exists'));
    }

    public function pelamar()
    {
        return view('kandidat.lowongan-kerja.detail-loker');
    }

    public function store(Request $request, StorageService $storageService)
    {
        $validator = Validator::make($request->all(), [
            'cv' => ['required', 'file', 'max:5120', 'mimes:pdf,doc,docx'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $file = $request->file('cv');
            $userId = auth()->id();

            $uploadResult = $storageService->uploadCV($file, $userId);

             $submission = CvSubmission::updateOrCreate(
                ['user_id' => $userId], 
                [ 
                    'cv_file_url' => $uploadResult['url'],
                    'cv_file_name' => $uploadResult['name'],
                    'cv_file_type' => $uploadResult['type'],
                    'cv_file_size' => $uploadResult['size'],
                    'status' => 'pending', 
                    'processing_step' => 'queued',
                    'processing_error' => null,
                ]
            );

            ProcessCVSubmissionJob::dispatch($submission->id);

            return response()->json([
                'success' => true,
                'message' => 'CV berhasil diupload dan sedang diproses.',
                'submission_id' => $submission->id,
            ]);

        } catch (\Exception $e) {
            Log::error('CV Upload Failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal mengupload CV: ' . $e->getMessage()], 500);
        }
    }

    public function status(int $id)
    {
        try {
            $submission = CvSubmission::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'status' => $submission->status,
                'processing_step' => $submission->processing_step, 
                'error' => $submission->processing_error,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Submission not found'], 404);
        }
    }

    public function detail($id)
    {
        $application = JobApplication::find($id);

        return view('kandidat.lowongan-kerja.detail', compact('application'));
    }
}
