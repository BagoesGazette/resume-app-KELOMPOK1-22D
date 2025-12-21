<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (Auth::user()->hasRole('kandidat')) {
            return $this->kandidatDashboard();
        }

        return $this->adminDashboard();
    }

    /**
     * Dashboard untuk Admin/HR
     */
    private function adminDashboard()
    {
        // === STATISTIK UTAMA ===
        $pengguna = User::role('kandidat')->count();
        $penggunaBulanLalu = User::role('kandidat')
            ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->count();
        $penggunaTrendPercent = $this->calculateTrend($pengguna, $penggunaBulanLalu);

        $lamaranTerkirim = JobApplication::count();
        $lamaranBulanLalu = JobApplication::where('created_at', '<', Carbon::now()->startOfMonth())
            ->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->count();
        $lamaranTrendPercent = $this->calculateTrend($lamaranTerkirim, $lamaranBulanLalu);

        $lamaranDiterima = JobApplication::where('status', 'accepted')->count();
        $diterimaBulanLalu = JobApplication::where('status', 'accepted')
            ->where('updated_at', '<', Carbon::now()->startOfMonth())
            ->where('updated_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->count();
        $diterimaTrendPercent = $this->calculateTrend($lamaranDiterima, $diterimaBulanLalu);

        $lamaranDitolak = JobApplication::where('status', 'rejected')->count();
        $ditolakBulanLalu = JobApplication::where('status', 'rejected')
            ->where('updated_at', '<', Carbon::now()->startOfMonth())
            ->where('updated_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->count();
        $ditolakTrendPercent = $this->calculateTrend($lamaranDitolak, $ditolakBulanLalu);

        // === WELCOME SECTION STATS ===
        $totalLowongan = JobOpening::where('status', 'open')->count();
        $interviewToday = JobApplication::whereDate('interview_date', Carbon::today())->count();
        $pendingReview = JobApplication::where('status', 'submitted')->count();
        $lamaranBaru = JobApplication::where('created_at', '>=', Carbon::today())->count();

        // === STATUS LAMARAN (untuk Donut Chart) ===
        $statusLamaran = [
            'pending' => JobApplication::where('status', 'submitted')->count(),
            'review' => JobApplication::where('status', 'reviewed')->count(),
            'interview' => JobApplication::where('status', 'interview')->count(),
            'diterima' => JobApplication::where('status', 'accepted')->count(),
            'ditolak' => JobApplication::where('status', 'rejected')->count(),
        ];

        // === CHART DATA (Statistik Bulanan) ===
        $chartData = $this->getMonthlyChartData();

        // === LOWONGAN TERPOPULER ===
        $popularJobs = JobOpening::withCount('apply')
            ->where('status', 'open')
            ->orderByDesc('apply_count')
            ->take(5)
            ->get()
            ->map(function ($job, $index) {
                $colors = ['#667eea', '#11998e', '#f7971e', '#eb3349', '#00c6fb'];
                return [
                    'id' => $job->id,
                    'title' => $job->judul,
                    'company' => $job->perusahaan,
                    'applicants' => $job->apply_count,
                    'color' => $colors[$index % count($colors)],
                ];
            });

        // === TOP KANDIDAT BULAN INI ===
        $topApplicants = JobApplication::with(['user', 'jobOpening'])
            ->whereNotNull('score')
            ->where('score', '>', 0)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderByDesc('score')
            ->take(5)
            ->get()
            ->map(function ($app, $index) {
                $colors = ['#667eea', '#11998e', '#f7971e', '#eb3349', '#00c6fb'];
                return [
                    'name' => $app->user->name ?? 'Unknown',
                    'position' => $app->jobOpening->judul ?? '-',
                    'score' => $app->score,
                    'color' => $colors[$index % count($colors)],
                ];
            });

        // === AKTIVITAS TERBARU ===
        $recentActivities = $this->getRecentActivities();

        // === TINGKAT KONVERSI ===
        $conversionRates = $this->getConversionRates();

        // === INTERVIEW HARI INI ===
        $todayInterviews = JobApplication::with(['user', 'jobOpening'])
            ->whereDate('interview_date', Carbon::today())
            ->orderBy('interview_date')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'pengguna',
            'penggunaTrendPercent',
            'lamaranTerkirim',
            'lamaranTrendPercent',
            'lamaranDiterima',
            'diterimaTrendPercent',
            'lamaranDitolak',
            'ditolakTrendPercent',
            'totalLowongan',
            'interviewToday',
            'pendingReview',
            'lamaranBaru',
            'statusLamaran',
            'chartData',
            'popularJobs',
            'topApplicants',
            'recentActivities',
            'conversionRates',
            'todayInterviews'
        ));
    }

    /**
     * Dashboard untuk Kandidat
     */
    private function kandidatDashboard()
    {
        $user = Auth::user();

        // Statistik lamaran user
        $myApplications = JobApplication::where('user_id', $user->id);
        
        $stats = [
            'total' => (clone $myApplications)->count(),
            'pending' => (clone $myApplications)->where('status', 'submitted')->count(),
            'reviewed' => (clone $myApplications)->where('status', 'reviewed')->count(),
            'interview' => (clone $myApplications)->where('status', 'interview')->count(),
            'accepted' => (clone $myApplications)->where('status', 'accepted')->count(),
            'rejected' => (clone $myApplications)->where('status', 'rejected')->count(),
        ];

        // Lamaran terbaru
        $recentApplications = JobApplication::with('jobOpening')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Interview mendatang
        $upcomingInterviews = JobApplication::with('jobOpening')
            ->where('user_id', $user->id)
            ->where('status', 'interview')
            ->whereNotNull('interview_date')
            ->where('interview_date', '>=', Carbon::now())
            ->orderBy('interview_date')
            ->take(3)
            ->get();

        // Lowongan yang direkomendasikan
        $appliedJobIds = (clone $myApplications)->pluck('jobopening_id')->toArray();
        $recommendedJobs = JobOpening::where('status', 'open')
            ->whereNotIn('id', $appliedJobIds)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Aktivitas terbaru user
        // $recentActivities = $this->getUserActivities($user->id);

        return view('dashboard.kandidat', compact(
            'stats',
            'recentApplications',
            'upcomingInterviews',
            'recommendedJobs',
        ));
    }

    /**
     * Hitung persentase trend
     */
    private function calculateTrend($current, $previous): array
    {
        if ($previous == 0) {
            return ['percent' => $current > 0 ? 100 : 0, 'direction' => 'up'];
        }

        $percent = round((($current - $previous) / $previous) * 100);
        
        return [
            'percent' => abs($percent),
            'direction' => $percent >= 0 ? 'up' : 'down'
        ];
    }

    /**
     * Data chart bulanan
     */
    private function getMonthlyChartData(): array
    {
        $year = Carbon::now()->year;
        $months = [];
        $lamaranMasuk = [];
        $diterima = [];
        $ditolak = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create($year, $i, 1)->locale('id')->isoFormat('MMM');
            
            $lamaranMasuk[] = JobApplication::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();
            
            $diterima[] = JobApplication::whereYear('updated_at', $year)
                ->whereMonth('updated_at', $i)
                ->where('status', 'accepted')
                ->count();
            
            $ditolak[] = JobApplication::whereYear('updated_at', $year)
                ->whereMonth('updated_at', $i)
                ->where('status', 'rejected')
                ->count();
        }

        return [
            'labels' => $months,
            'lamaranMasuk' => $lamaranMasuk,
            'diterima' => $diterima,
            'ditolak' => $ditolak,
        ];
    }

    /**
     * Aktivitas terbaru
     */
    private function getRecentActivities(): array
    {
        $activities = [];

        // Pelamar baru (hari ini)
        $newApplicants = JobApplication::with(['user', 'jobOpening'])
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        foreach ($newApplicants as $app) {
            $activities[] = [
                'icon' => 'user-plus',
                'type' => 'primary',
                'title' => 'Pelamar Baru',
                'desc' => ($app->user->name ?? 'Unknown') . ' melamar ' . ($app->jobOpening->judul ?? '-'),
                'time' => $app->created_at->locale('id')->diffForHumans(),
                'timestamp' => $app->created_at,
            ];
        }

        // Lamaran diterima
        $accepted = JobApplication::with(['user', 'jobOpening'])
            ->where('status', 'accepted')
            ->where('updated_at', '>=', Carbon::now()->subDays(3))
            ->orderByDesc('updated_at')
            ->take(2)
            ->get();

        foreach ($accepted as $app) {
            $activities[] = [
                'icon' => 'check-circle',
                'type' => 'success',
                'title' => 'Lamaran Diterima',
                'desc' => ($app->user->name ?? 'Unknown') . ' diterima sebagai ' . ($app->jobOpening->judul ?? '-'),
                'time' => $app->updated_at->locale('id')->diffForHumans(),
                'timestamp' => $app->updated_at,
            ];
        }

        // Interview dijadwalkan
        $interviews = JobApplication::with(['user', 'jobOpening'])
            ->where('status', 'interview')
            ->whereNotNull('interview_date')
            ->where('updated_at', '>=', Carbon::now()->subDays(3))
            ->orderByDesc('updated_at')
            ->take(2)
            ->get();

        foreach ($interviews as $app) {
            $activities[] = [
                'icon' => 'calendar-check',
                'type' => 'info',
                'title' => 'Interview Dijadwalkan',
                'desc' => ($app->user->name ?? 'Unknown') . ' - ' . ($app->interview_date ? Carbon::parse($app->interview_date)
                ->locale('id')
                ->translatedFormat('d F Y H:i'): '-'),
                'time' => $app->updated_at->locale('id')->diffForHumans(),
                'timestamp' => $app->updated_at,
            ];
        }

        // Lamaran ditolak
        $rejected = JobApplication::with(['user', 'jobOpening'])
            ->where('status', 'rejected')
            ->where('updated_at', '>=', Carbon::now()->subDays(3))
            ->orderByDesc('updated_at')
            ->take(2)
            ->get();

        foreach ($rejected as $app) {
            $activities[] = [
                'icon' => 'times-circle',
                'type' => 'danger',
                'title' => 'Lamaran Ditolak',
                'desc' => ($app->user->name ?? 'Unknown') . ' tidak memenuhi kualifikasi',
                'time' => $app->updated_at->locale('id')->diffForHumans(),
                'timestamp' => $app->updated_at,
            ];
        }

        // Lowongan baru
        $newJobs = JobOpening::where('created_at', '>=', Carbon::now()->subDays(3))
            ->orderByDesc('created_at')
            ->take(2)
            ->get();

        foreach ($newJobs as $job) {
            $activities[] = [
                'icon' => 'briefcase',
                'type' => 'warning',
                'title' => 'Lowongan Baru',
                'desc' => $job->judul . ' di ' . ($job->perusahaan ?? '-'),
                'time' => $job->created_at->locale('id')->diffForHumans(),
                'timestamp' => $job->created_at,
            ];
        }

        // Sort by timestamp descending
        usort($activities, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        // Return top 5
        return array_slice($activities, 0, 5);
    }

    /**
     * Tingkat konversi recruitment funnel
     */
    private function getConversionRates(): array
    {
        $total = JobApplication::count() ?: 1;
        
        $reviewed = JobApplication::whereIn('status', ['reviewed', 'interview', 'accepted', 'rejected'])->count();
        $interviewed = JobApplication::whereIn('status', ['interview', 'accepted'])->count();
        $offered = JobApplication::where('status', 'accepted')->count();
        $hired = JobApplication::where('status', 'accepted')->count(); // Bisa disesuaikan jika ada status 'hired'

        return [
            'screening' => round(($reviewed / $total) * 100),
            'interview' => round(($interviewed / $total) * 100),
            'offering' => round(($offered / $total) * 100),
            'hired' => round(($hired / $total) * 100),
        ];
    }

    /**
     * API endpoint untuk chart data (untuk AJAX)
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $year = Carbon::now()->year;

        switch ($period) {
            case 'weekly':
                return response()->json($this->getWeeklyChartData());
            case 'daily':
                return response()->json($this->getDailyChartData());
            default:
                return response()->json($this->getMonthlyChartData());
        }
    }

    /**
     * Data chart mingguan
     */
    private function getWeeklyChartData(): array
    {
        $labels = [];
        $lamaranMasuk = [];
        $diterima = [];
        $ditolak = [];

        for ($i = 11; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $labels[] = 'Minggu ' . (12 - $i);
            
            $lamaranMasuk[] = JobApplication::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            $diterima[] = JobApplication::whereBetween('updated_at', [$startOfWeek, $endOfWeek])->where('status', 'accepted')->count();
            $ditolak[] = JobApplication::whereBetween('updated_at', [$startOfWeek, $endOfWeek])->where('status', 'rejected')->count();
        }

        return [
            'labels' => $labels,
            'lamaranMasuk' => $lamaranMasuk,
            'diterima' => $diterima,
            'ditolak' => $ditolak,
        ];
    }

    /**
     * Data chart harian (30 hari terakhir)
     */
    private function getDailyChartData(): array
    {
        $labels = [];
        $lamaranMasuk = [];
        $diterima = [];
        $ditolak = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            $labels[] = $date->format('d/m');
            
            $lamaranMasuk[] = JobApplication::whereDate('created_at', $date)->count();
            $diterima[] = JobApplication::whereDate('updated_at', $date)->where('status', 'accepted')->count();
            $ditolak[] = JobApplication::whereDate('updated_at', $date)->where('status', 'rejected')->count();
        }

        return [
            'labels' => $labels,
            'lamaranMasuk' => $lamaranMasuk,
            'diterima' => $diterima,
            'ditolak' => $ditolak,
        ];
    }

    /**
     * Export laporan dashboard ke Excel (CSV)
     */
    public function exportExcel()
    {
        $filename = 'Laporan_Rekrutmen_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // BOM untuk UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Title
            fputcsv($file, ['LAPORAN REKRUTMEN - ' . date('F Y')]);
            fputcsv($file, ['Diekspor pada: ' . now()->format('d/m/Y H:i')]);
            fputcsv($file, []);

            // Statistik Umum
            fputcsv($file, ['=== STATISTIK UMUM ===']);
            fputcsv($file, ['Total Pengguna', User::role('kandidat')->count()]);
            fputcsv($file, ['Total Lowongan Aktif', JobOpening::where('status', 'open')->count()]);
            fputcsv($file, ['Total Lamaran', JobApplication::count()]);
            fputcsv($file, ['Lamaran Diterima', JobApplication::where('status', 'accepted')->count()]);
            fputcsv($file, ['Lamaran Ditolak', JobApplication::where('status', 'rejected')->count()]);
            fputcsv($file, ['Interview Hari Ini', JobApplication::whereDate('interview_date', Carbon::today())->count()]);
            fputcsv($file, []);

            // Status Lamaran
            fputcsv($file, ['=== STATUS LAMARAN ===']);
            fputcsv($file, ['Status', 'Jumlah']);
            fputcsv($file, ['Pending', JobApplication::where('status', 'submitted')->count()]);
            fputcsv($file, ['Reviewed', JobApplication::where('status', 'reviewed')->count()]);
            fputcsv($file, ['Interview', JobApplication::where('status', 'interview')->count()]);
            fputcsv($file, ['Diterima', JobApplication::where('status', 'accepted')->count()]);
            fputcsv($file, ['Ditolak', JobApplication::where('status', 'rejected')->count()]);
            fputcsv($file, []);

            // Lowongan Terpopuler
            fputcsv($file, ['=== LOWONGAN TERPOPULER ===']);
            fputcsv($file, ['No', 'Judul Lowongan', 'Perusahaan', 'Jumlah Pelamar']);
            
            $popularJobs = JobOpening::withCount('apply')
                ->orderByDesc('apply_count')
                ->take(10)
                ->get();

            $no = 1;
            foreach ($popularJobs as $job) {
                fputcsv($file, [
                    $no++,
                    $job->judul,
                    $job->perusahaan,
                    $job->apply_count
                ]);
            }
            fputcsv($file, []);

            // Statistik Bulanan
            fputcsv($file, ['=== STATISTIK BULANAN ' . date('Y') . ' ===']);
            fputcsv($file, ['Bulan', 'Lamaran Masuk', 'Diterima', 'Ditolak']);
            
            for ($i = 1; $i <= 12; $i++) {
                $bulan = Carbon::create(date('Y'), $i, 1)->locale('id')->isoFormat('MMMM');
                $masuk = JobApplication::whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->count();
                $diterima = JobApplication::whereYear('updated_at', date('Y'))->whereMonth('updated_at', $i)->where('status', 'accepted')->count();
                $ditolak = JobApplication::whereYear('updated_at', date('Y'))->whereMonth('updated_at', $i)->where('status', 'rejected')->count();
                
                fputcsv($file, [$bulan, $masuk, $diterima, $ditolak]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export laporan dashboard ke PDF
     */
    public function exportPdf()
    {
        $data = [
            'exportDate' => now()->locale('id')->translatedFormat('d F Y H:i'),
            'year' => date('Y'),
            'stats' => [
                'pengguna' => User::role('kandidat')->count(),
                'lowonganAktif' => JobOpening::where('status', 'open')->count(),
                'totalLamaran' => JobApplication::count(),
                'diterima' => JobApplication::where('status', 'accepted')->count(),
                'ditolak' => JobApplication::where('status', 'rejected')->count(),
                'interviewToday' => JobApplication::whereDate('interview_date', Carbon::today())->count(),
            ],
            'statusLamaran' => [
                'pending' => JobApplication::where('status', 'submitted')->count(),
                'reviewed' => JobApplication::where('status', 'reviewed')->count(),
                'interview' => JobApplication::where('status', 'interview')->count(),
                'diterima' => JobApplication::where('status', 'accepted')->count(),
                'ditolak' => JobApplication::where('status', 'rejected')->count(),
            ],
            'popularJobs' => JobOpening::withCount('apply')
                ->orderByDesc('apply_count')
                ->take(10)
                ->get(),
            'monthlyStats' => $this->getMonthlyChartData(),
            'topCandidates' => JobApplication::with(['user', 'jobOpening'])
                ->whereNotNull('score')
                ->where('score', '>', 0)
                ->orderByDesc('score')
                ->take(10)
                ->get(),
        ];

        // Return HTML view untuk di-print sebagai PDF
        return view('exports.dashboard-report', $data);
    }
}