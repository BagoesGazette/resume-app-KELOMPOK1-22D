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
    /**
     * Show job detail and apply button
     */
    public function show($id)
    {
        $jobOpening = JobOpening::findOrFail($id); // Update variable name
        
        // Check if already applied
        $existingApplication = JobApplication::where('jobopening_id', $id) // Update column name
            ->where('user_id', auth()->id())
            ->first();
        
        return view('kandidat.jobopening.show', compact('jobOpening', 'existingApplication'));
    }

    /**
     * Show apply form
     */
    public function apply($jobOpeningId) // Update parameter name
    {
        $jobOpening = JobOpening::findOrFail($jobOpeningId);
        
        // Check if already applied
        $existingApplication = JobApplication::where('jobopening_id', $jobOpeningId)
            ->where('user_id', auth()->id())
            ->first();
        
        if ($existingApplication && $existingApplication->status !== 'draft') {
            return redirect()->route('kandidat.jobopening.show', $jobOpeningId)
                ->with('error', 'Anda sudah melamar lowongan ini.');
        }
        
        // Get user's latest completed CV
        $latestCv = CvSubmission::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->first();
        
        return view('kandidat.jobopening.apply', compact('jobOpening', 'latestCv', 'existingApplication'));
    }

    /**
     * Process CV and create draft application
     */
    public function processCv(Request $request, $jobOpeningId)
    {
        $request->validate([
            'cv_submission_id' => 'required|exists:cv_submissions,id',
        ]);
        
        $jobOpening = JobOpening::findOrFail($jobOpeningId);
        $cvSubmission = CvSubmission::where('id', $request->cv_submission_id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();
        
        // Create or update draft application
        $application = JobApplication::updateOrCreate(
            [
                'jobopening_id' => $jobOpeningId, // Update
                'user_id' => auth()->id(),
                'status' => 'draft',
            ],
            [
                'cv_submission_id' => $cvSubmission->id,
                'pendidikan_terakhir' => $cvSubmission->pendidikan_terakhir,
                'rangkuman_pendidikan' => $cvSubmission->rangkuman_pendidikan,
                'ipk_nilai_akhir' => $cvSubmission->ipk_nilai_akhir,
                'pengalaman_kerja_terakhir' => $cvSubmission->pengalaman_kerja_terakhir,
                'rangkuman_pengalaman_kerja' => $cvSubmission->rangkuman_pengalaman_kerja,
                'rangkuman_sertifikasi_prestasi' => $cvSubmission->rangkuman_sertifikasi_prestasi,
                'rangkuman_profil' => $cvSubmission->rangkuman_profil,
                'hardskills' => $cvSubmission->hardskills,
                'softskills' => $cvSubmission->softskills,
            ]
        );
        
        return redirect()->route('kandidat.jobopening.validate', $jobOpeningId)
            ->with('success', 'CV berhasil diproses! Silakan review data Anda.');
    }

    /**
     * Show validation form
     */
    public function validate($jobOpeningId)
    {
        $jobOpening = JobOpening::findOrFail($jobOpeningId);
        
        $application = JobApplication::where('jobopening_id', $jobOpeningId)
            ->where('user_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();
        
        return view('kandidat.jobopening.validate', compact('jobOpening', 'application'));
    }

    /**
     * Submit application
     */
    public function submit(Request $request, $jobOpeningId)
    {
        $request->validate([
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'rangkuman_pendidikan' => 'nullable|string',
            'ipk_nilai_akhir' => 'nullable|string|max:10',
            'pengalaman_kerja_terakhir' => 'nullable|string|max:255',
            'rangkuman_pengalaman_kerja' => 'nullable|string',
            'rangkuman_sertifikasi_prestasi' => 'nullable|string',
            'rangkuman_profil' => 'nullable|string',
            'hardskills' => 'nullable|array',
            'softskills' => 'nullable|array',
            'cover_letter' => 'nullable|string',
            'expected_salary' => 'nullable|string|max:50',
        ]);
        
        $application = JobApplication::where('jobopening_id', $jobOpeningId)
            ->where('user_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();
        
        $application->update([
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'rangkuman_pendidikan' => $request->rangkuman_pendidikan,
            'ipk_nilai_akhir' => $request->ipk_nilai_akhir,
            'pengalaman_kerja_terakhir' => $request->pengalaman_kerja_terakhir,
            'rangkuman_pengalaman_kerja' => $request->rangkuman_pengalaman_kerja,
            'rangkuman_sertifikasi_prestasi' => $request->rangkuman_sertifikasi_prestasi,
            'rangkuman_profil' => $request->rangkuman_profil,
            'hardskills' => $request->hardskills,
            'softskills' => $request->softskills,
            'cover_letter' => $request->cover_letter,
            'expected_salary' => $request->expected_salary,
            'status' => 'submitted',
        ]);
        
        Log::info('JobApplication: Application submitted', [
            'application_id' => $application->id,
            'user_id' => auth()->id(),
            'jobopening_id' => $jobOpeningId,
        ]);
        
        return redirect()->route('kandidat.applications.index')
            ->with('success', 'Lamaran berhasil dikirim!');
    }
}