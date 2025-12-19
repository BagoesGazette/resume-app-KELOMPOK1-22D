<?php

namespace App\Http\Controllers\Kandidat;

use App\Http\Controllers\Controller;
use App\Models\jobopening;
use App\Models\JobApplication;
use App\Models\CvSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobApplicationController extends Controller
{
    public function show($id)
    {
        $jobOpening = JobOpening::findOrFail($id); 
        
        $existingApplication = JobApplication::where('jobopening_id', $id) 
            ->where('user_id', auth()->id())
            ->first();
        
        return view('kandidat.jobopening.show', compact('jobOpening', 'existingApplication'));
    }

    public function apply($jobOpeningId) 
    {
        $jobOpening = JobOpening::findOrFail($jobOpeningId);
        
        $existingApplication = JobApplication::where('jobopening_id', $jobOpeningId)
            ->where('user_id', auth()->id())
            ->first();
        
        if ($existingApplication && $existingApplication->status !== 'draft') {
            return redirect()->route('kandidat.jobopening.show', $jobOpeningId)
                ->with('error', 'Anda sudah melamar lowongan ini.');
        }
        
        $latestCv = CvSubmission::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->first();
        
        return view('kandidat.jobopening.apply', compact('jobOpening', 'latestCv', 'existingApplication'));
    }

    public function processCv(Request $request, $jobOpeningId)
    {
        $request->validate(['cv_submission_id' => 'required|exists:cv_submissions,id']);
        
        $cvSubmission = CvSubmission::where('id', $request->cv_submission_id)
            ->where('user_id', auth()->id())->where('status', 'completed')->firstOrFail();
        
        $application = JobApplication::updateOrCreate(
            ['jobopening_id' => $jobOpeningId, 'user_id' => auth()->id()],
            [
                'cv_submission_id' => $cvSubmission->id,
                'status' => 'draft',
            ]
        );
        
        return redirect()->route('lamaran.validate', $application->id)
            ->with('success', 'CV berhasil diproses! Silakan review data Anda.');
    }

    public function validate($applicationId) 
    {
        $application = JobApplication::where('id', $applicationId)
            ->where('user_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();
        
        $jobOpening = $application->jobopening; 
        
        return view('kandidat.lowongan-kerja.validate', compact('jobOpening', 'application'));
    }

    public function submit(Request $request, $applicationId) 
    {
        $validatedData = $request->validate([
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'rangkuman_pendidikan' => 'nullable|string',
            'ipk_nilai_akhir' => 'required|string|max:10',
            'pengalaman_kerja_terakhir' => 'nullable|string|max:255',
            'rangkuman_pengalaman_kerja' => 'nullable|string',
            'rangkuman_sertifikasi_prestasi' => 'nullable|string',
            'rangkuman_profil' => 'nullable|string',
            'hardskills' => 'nullable|string', 
            'softskills' => 'nullable|string', 
            'cover_letter' => 'nullable|string',
            'expected_salary' => 'nullable|string|max:50',
            'total_pengalaman_kerja' => 'required',
            'tipe_pendidikan' => 'required'
        ]);
        
        $application = JobApplication::where('id', $applicationId)
            ->where('user_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();
        $validatedData['status'] = 'submitted';
        $validatedData['hardskills'] = $request->hardskills ? array_map('trim', explode(',', $request->hardskills)) : [];
        $validatedData['softskills'] = $request->softskills ? array_map('trim', explode(',', $request->softskills)) : [];

        $application->update($validatedData);
        
        $cv = CvSubmission::find($application->cv_submission_id);
        $cv->update([
            'ipk_nilai_akhir' => $validatedData['ipk_nilai_akhir'],
            'total_pengalaman' => $validatedData['total_pengalaman_kerja'],
            'tipe_pendidikan' => $validatedData['tipe_pendidikan']
        ]);

        Log::info('JobApplication: Application submitted', [
            'application_id' => $application->id,
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->route('lowongan-kerja.index')
            ->with('success', 'Lamaran berhasil dikirim!');
    }
}