<?php

namespace App\Http\Controllers\Kandidat;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua lamaran user dengan relasi
        $applications = JobApplication::with(['jobOpening'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // Statistik
        $totalLamaran = $applications->count();
        
        // Hitung rata-rata skor (hanya yang sudah ada skor)
        $scoredApplications = $applications->whereNotNull('score')->where('score', '>', 0);
        $avgScore = $scoredApplications->count() > 0 
            ? round($scoredApplications->avg('score')) 
            : 0;

        // Ranking terbaik
        $bestRank = $applications->whereNotNull('ranking')->min('ranking') ?? '-';

        // Hitung status
        $pendingCount = $applications->whereIn('status', ['submitted', 'reviewed'])->count();
        $interviewCount = $applications->where('status', 'interview')->count();
        $acceptedCount = $applications->where('status', 'accepted')->count();
        $rejectedCount = $applications->where('status', 'rejected')->count();

        // Ambil total pelamar per lowongan untuk setiap aplikasi
        $results = $applications->map(function ($app) {
            // Hitung total pelamar untuk lowongan yang sama
            $totalApplicants = JobApplication::where('jobopening_id', $app->jobopening_id)->count();
            
            return [
                'id' => $app->id,
                'position' => $app->jobOpening->judul ?? 'Posisi tidak tersedia',
                'company' => $app->jobOpening->perusahaan ?? '-',
                'category' => $app->jobOpening->category ?? 'Umum',
                'color' => $this->getRandomColor($app->id),
                'date' => $app->created_at,
                'score' => $app->score ? round($app->score) : null,
                'rank' => $app->ranking,
                'total_applicants' => $totalApplicants,
                'status' => $this->mapStatus($app->status),
                'status_raw' => $app->status,
                'job_id' => $app->jobopening_id,
            ];
        });

        // Ambil daftar kategori unik untuk filter
        $categories = $applications->pluck('jobOpening.category')
            ->filter()
            ->unique()
            ->values();

        return view('kandidat.hasil.index', compact(
            'results',
            'totalLamaran',
            'avgScore',
            'bestRank',
            'pendingCount',
            'interviewCount',
            'acceptedCount',
            'rejectedCount',
            'categories'
        ));
    }

    /**
     * Map status ke format yang konsisten
     */
    private function mapStatus($status): string
    {
        return match($status) {
            'submitted' => 'pending',
            'reviewed' => 'review',
            'interview' => 'interview',
            'accepted' => 'accepted',
            'rejected' => 'rejected',
            default => 'pending'
        };
    }

    /**
     * Generate warna berdasarkan ID
     */
    private function getRandomColor($id): string
    {
        $colors = ['#667eea', '#11998e', '#f7971e', '#eb3349', '#00c6fb', '#a855f7'];
        return $colors[$id % count($colors)];
    }

    /**
     * Batalkan lamaran
     */
    public function cancel($id)
    {
        $application = JobApplication::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'submitted')
            ->firstOrFail();

        $application->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lamaran berhasil dibatalkan'
        ]);
    }

    /**
     * Export data ke Excel (CSV)
     */
    public function exportExcel()
    {
        $user = Auth::user();
        $applications = JobApplication::with('jobOpening')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $filename = 'Hasil_Lamaran_' . $user->name . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, [
                'No',
                'Posisi',
                'Perusahaan',
                'Kategori',
                'Tanggal Melamar',
                'Skor CV (%)',
                'Ranking',
                'Total Pelamar',
                'Status'
            ]);

            // Data
            $no = 1;
            foreach ($applications as $app) {
                $totalApplicants = JobApplication::where('jobopening_id', $app->jobopening_id)->count();
                
                fputcsv($file, [
                    $no++,
                    $app->jobOpening->judul ?? '-',
                    $app->jobOpening->perusahaan ?? '-',
                    $app->jobOpening->category ?? '-',
                    $app->created_at->format('d/m/Y'),
                    $app->score ? round($app->score * 100, 1) : '-',
                    $app->ranking ?? '-',
                    $totalApplicants,
                    $this->getStatusLabel($app->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export data ke PDF
     */
    public function exportPdf()
    {
        $user = Auth::user();
        $applications = JobApplication::with('jobOpening')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $results = $applications->map(function ($app) {
            $totalApplicants = JobApplication::where('jobopening_id', $app->jobopening_id)->count();
            
            return [
                'position' => $app->jobOpening->judul ?? '-',
                'company' => $app->jobOpening->perusahaan ?? '-',
                'category' => $app->jobOpening->category ?? '-',
                'date' => $app->created_at,
                'score' => $app->score ? round($app->score * 100, 1) : null,
                'rank' => $app->ranking,
                'total_applicants' => $totalApplicants,
                'status' => $app->status,
            ];
        });

        $data = [
            'user' => $user,
            'results' => $results,
            'exportDate' => now()->locale('id')->translatedFormat('d F Y H:i'),
            'totalLamaran' => $applications->count(),
            'avgScore' => $applications->whereNotNull('score')->avg('score') 
                ? round($applications->whereNotNull('score')->avg('score') * 100, 1) 
                : 0,
        ];

        return view('kandidat.hasil.export-pdf', $data);
    }

    /**
     * Get status label
     */
    private function getStatusLabel($status): string
    {
        return match($status) {
            'submitted' => 'Pending',
            'reviewed' => 'Review',
            'interview' => 'Interview',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            default => ucfirst($status)
        };
    }
}