<?php

namespace App\Http\Controllers\Kandidat;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use App\Models\CvSubmission;
use App\Services\StorageService;
use App\Jobs\ProcessCVSubmissionJob;
use App\Models\JobApplication;
use Carbon\Carbon;
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
            ->where('status', '!=', 'draft')
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
                    'original_file_name' => $uploadResult['original_name'],
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
        $application = JobApplication::with([
            'user',
            'jobOpening',
            'cvSubmission'
        ])->where('user_id', Auth::id())
          ->findOrFail($id);

        // Hitung total pelamar untuk lowongan yang sama
        $totalApplicants = JobApplication::where('jobopening_id', $application->jobopening_id)->count();

        // Hitung total skills
        $hardskills = $application->cvSubmission->hardskills ?? [];
        $softskills = $application->cvSubmission->softskills ?? [];
        $totalSkills = (is_array($hardskills) ? count($hardskills) : 0) + 
                       (is_array($softskills) ? count($softskills) : 0);

        // Mapping tipe pendidikan
        $pendidikanLabels = [
            1 => 'SMA/SMK',
            2 => 'D3 (Diploma)',
            3 => 'S1 (Sarjana)',
            4 => 'S2/S3 (Magister/Doktor)',
        ];

        // Build timeline
        $timeline = $this->buildTimeline($application);

        return view('kandidat.lowongan-kerja.detail', compact(
            'application',
            'totalApplicants',
            'totalSkills',
            'pendidikanLabels',
            'timeline'
        ));
    }

    /**
     * Build application timeline
     */
    private function buildTimeline($application): array
    {
        $timeline = [];

        // 1. Lamaran Dikirim
        $timeline[] = [
            'date' => $application->created_at->format('d M Y, H:i'),
            'title' => 'Lamaran Dikirim',
            'desc' => 'CV berhasil diupload dan lamaran terkirim',
            'status' => 'completed',
        ];

        // 2. CV Diproses (setelah beberapa jam dari submit)
        if (in_array($application->status, ['reviewed', 'interview', 'accepted', 'rejected'])) {
            $timeline[] = [
                'date' => $application->created_at->addHours(2)->format('d M Y, H:i'),
                'title' => 'CV Diproses',
                'desc' => 'CV telah dianalisis oleh sistem',
                'status' => 'completed',
            ];
        } else {
            $timeline[] = [
                'date' => '-',
                'title' => 'CV Diproses',
                'desc' => 'CV sedang dianalisis oleh sistem',
                'status' => $application->status == 'submitted' ? 'active' : 'pending',
            ];
        }

        // 3. Dalam Review
        if (in_array($application->status, ['reviewed', 'interview', 'accepted', 'rejected'])) {
            $timeline[] = [
                'date' => $application->updated_at->format('d M Y, H:i'),
                'title' => 'Dalam Review',
                'desc' => 'Tim HR telah mereview lamaran',
                'status' => 'completed',
            ];
        } else {
            $timeline[] = [
                'date' => '-',
                'title' => 'Dalam Review',
                'desc' => 'Menunggu review dari tim HR',
                'status' => 'pending',
            ];
        }

        // 4. Interview
        if (in_array($application->status, ['interview', 'accepted', 'rejected']) && $application->interview_date) {
            $timeline[] = [
                'date' => Carbon::parse($application->interview_date)
                ->locale('id')
                ->translatedFormat('d F Y H:i'),
                'title' => 'Interview',
                'desc' => ucfirst($application->interview_type ?? 'Online') . ' Interview',
                'status' => $application->status == 'interview' ? 'active' : 'completed',
            ];
        } else {
            $timeline[] = [
                'date' => '-',
                'title' => 'Interview',
                'desc' => 'Menunggu jadwal interview',
                'status' => $application->status == 'interview' ? 'active' : 'pending',
            ];
        }

        // 5. Keputusan
        if (in_array($application->status, ['accepted', 'rejected'])) {
            $timeline[] = [
                'date' => $application->updated_at->format('d M Y, H:i'),
                'title' => 'Keputusan',
                'desc' => $application->status == 'accepted' ? 'Selamat! Anda diterima' : 'Maaf, lamaran ditolak',
                'status' => 'completed',
            ];
        } else {
            $timeline[] = [
                'date' => '-',
                'title' => 'Keputusan',
                'desc' => 'Menunggu hasil akhir',
                'status' => 'pending',
            ];
        }

        return $timeline;
    }
}
