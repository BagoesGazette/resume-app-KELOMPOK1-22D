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
        
        return redirect()->route('lowongan-kerja.index', $application->jobopening_id)
            ->with('success', 'Lamaran berhasil dikirim!');
    }

    public function scheduleInterview(Request $request, string $id)
    {
        $request->validate([
            'interview_date' => 'required|date|after:now',
            'interview_type' => 'required|in:online,onsite,phone',
            'interview_location' => 'nullable|string|max:255',
            'interview_notes' => 'nullable|string|max:1000',
        ]);

        $application = JobApplication::findOrFail($id);
        
        $application->update([
            'interview_date' => $request->interview_date,
            'interview_type' => $request->interview_type,
            'interview_location' => $request->interview_location,
            'interview_notes' => $request->interview_notes,
            'status' => 'interview',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal interview berhasil disimpan'
        ]);
    }

     /**
     * Accept applicant
     */
    public function acceptApplicant(Request $request, $id)
    {
        $request->validate([
            'acceptance_notes' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'offered_salary' => 'nullable|numeric',
        ]);

        $application = JobApplication::with(['user', 'jobOpening'])->findOrFail($id);
        
        $application->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'acceptance_notes' => $request->acceptance_notes,
            'start_date' => $request->start_date,
            'offered_salary' => $request->offered_salary,
        ]);

        // Kirim email notifikasi (opsional)
        // Mail::to($application->user->email)->send(new ApplicationAccepted($application));

        return response()->json([
            'success' => true,
            'message' => 'Pelamar berhasil diterima! Notifikasi telah dikirim ke ' . $application->user->email
        ]);
    }

    /**
     * Reject applicant
     */
    public function rejectApplicant(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'rejection_notes' => 'nullable|string|max:1000',
            'send_notification' => 'nullable|boolean',
        ]);

        $application = JobApplication::with(['user', 'jobOpening'])->findOrFail($id);
        
        $application->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'rejection_notes' => $request->rejection_notes,
        ]);

        // Kirim email notifikasi jika diminta
        if ($request->send_notification) {
            // Mail::to($application->user->email)->send(new ApplicationRejected($application));
        }

        return response()->json([
            'success' => true,
            'message' => 'Pelamar telah ditolak.' . ($request->send_notification ? ' Notifikasi telah dikirim.' : '')
        ]);
    }
}