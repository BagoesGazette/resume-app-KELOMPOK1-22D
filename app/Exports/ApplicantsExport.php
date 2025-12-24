<?php

namespace App\Exports;

use App\Models\JobOpening;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class ApplicantsExport
{
    protected $jobId;

    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }

    public function download()
    {
        $job = JobOpening::with(['apply.user', 'apply.cvSubmission'])->find($this->jobId);
        
        $pendidikanLabels = [
            1 => 'SMA/SMK',
            2 => 'D3',
            3 => 'S1',
            4 => 'S2/S3',
        ];

        $statusLabels = [
            'submitted' => 'Pending',
            'reviewed' => 'Reviewed',
            'interview' => 'Interview',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
        ];

        $data = $job->apply->sortBy('ranking')->values()->map(function ($applicant, $index) use ($pendidikanLabels, $statusLabels) {
            return [
                'No' => $index + 1,
                'Ranking' => $applicant->ranking ?? '-',
                'Nama Pelamar' => $applicant->user->name ?? '-',
                'Email' => $applicant->user->email ?? '-',
                'No. Telepon' => $applicant->user->phone ?? '-',
                'Pendidikan' => $pendidikanLabels[$applicant->cvSubmission->tipe_pendidikan ?? 1] ?? '-',
                'IPK/Nilai' => $applicant->cvSubmission->ipk_nilai_akhir ?? '-',
                'Pengalaman (Tahun)' => $applicant->cvSubmission->total_pengalaman ?? 0,
                'Skor' => $applicant->score ? round($applicant->score * 100, 2) . '%' : '-',
                'Status' => $statusLabels[$applicant->status] ?? $applicant->status,
                'Tanggal Melamar' => $applicant->created_at?->format('d/m/Y H:i') ?? '-',
                'Jadwal Interview' => Carbon::parse($applicant->interview_date)
                ->locale('id')
                ->translatedFormat('d F Y H:i') ?? '-',
                'Catatan Interview' => $applicant->interview_notes ?? '-',
            ];
        });

        $filename = 'Pelamar_' . str_replace(' ', '_', $job->judul) . '_' . date('Y-m-d') . '.xlsx';

        return (new FastExcel($data))->download($filename);
    }
}