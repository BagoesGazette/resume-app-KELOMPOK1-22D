@extends('layouts.app')

@push('custom-css')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    --info-gradient: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
    --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    --purple-gradient: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
    --dark-gradient: linear-gradient(135deg, #434343 0%, #000000 100%);
    --teal-gradient: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
}

/* Welcome Section */
.welcome-section {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 35px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
}

.welcome-section::before {
    content: '';
    position: absolute;
    top: -80px;
    right: -80px;
    width: 250px;
    height: 250px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.welcome-section::after {
    content: '';
    position: absolute;
    bottom: -100px;
    right: 150px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.welcome-content {
    position: relative;
    z-index: 1;
}

.welcome-section h2 {
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 8px;
}

.welcome-section p {
    opacity: 0.9;
    margin-bottom: 20px;
    font-size: 1rem;
}

.welcome-section .btn-light {
    padding: 10px 25px;
    border-radius: 10px;
    font-weight: 600;
    color: #667eea;
}

.welcome-stats {
    display: flex;
    gap: 30px;
    margin-top: 25px;
}

.welcome-stat-item {
    text-align: center;
}

.welcome-stat-item .number {
    font-size: 2rem;
    font-weight: 800;
}

.welcome-stat-item .label {
    font-size: 0.85rem;
    opacity: 0.8;
}

.welcome-illustration {
    position: absolute;
    right: 50px;
    bottom: 0;
    height: 200px;
    z-index: 1;
    opacity: 0.9;
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
}

.stat-card.primary::before { background: var(--primary-gradient); }
.stat-card.success::before { background: var(--success-gradient); }
.stat-card.warning::before { background: var(--warning-gradient); }
.stat-card.danger::before { background: var(--danger-gradient); }
.stat-card.info::before { background: var(--info-gradient); }
.stat-card.purple::before { background: var(--purple-gradient); }

.stat-card .stat-icon {
    width: 65px;
    height: 65px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: white;
    margin-bottom: 20px;
    position: relative;
}

.stat-card .stat-icon::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 18px;
    background: inherit;
    filter: blur(15px);
    opacity: 0.4;
    z-index: -1;
}

.stat-card .stat-icon.primary { background: var(--primary-gradient); }
.stat-card .stat-icon.success { background: var(--success-gradient); }
.stat-card .stat-icon.warning { background: var(--warning-gradient); }
.stat-card .stat-icon.danger { background: var(--danger-gradient); }
.stat-card .stat-icon.info { background: var(--info-gradient); }
.stat-card .stat-icon.purple { background: var(--purple-gradient); }

.stat-card .stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 8px;
    font-weight: 500;
}

.stat-card .stat-value {
    font-size: 2.2rem;
    font-weight: 800;
    color: #34395e;
    line-height: 1;
    margin-bottom: 10px;
}

.stat-card .stat-trend {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}

.stat-card .stat-trend.up {
    background: #d4edda;
    color: #155724;
}

.stat-card .stat-trend.down {
    background: #f8d7da;
    color: #721c24;
}

.stat-card .stat-chart {
    position: absolute;
    bottom: 0;
    right: 0;
    left: 0;
    height: 60px;
    opacity: 0.1;
}

/* Info Cards */
.info-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

.info-card .card-header {
    background: transparent;
    border-bottom: 2px solid #f4f6f9;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-card .card-header h4 {
    margin: 0;
    font-weight: 700;
    color: #34395e;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.1rem;
}

.info-card .card-header h4 i {
    color: #667eea;
}

.info-card .card-body {
    padding: 25px;
}

/* Chart Card */
.chart-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

.chart-card .card-header {
    background: transparent;
    border-bottom: none;
    padding: 25px 25px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-card .card-header h4 {
    margin: 0;
    font-weight: 700;
    color: #34395e;
}

.chart-tabs {
    display: flex;
    gap: 5px;
    background: #f0f0f0;
    padding: 4px;
    border-radius: 10px;
}

.chart-tabs .tab {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    background: transparent;
}

.chart-tabs .tab.active {
    background: white;
    color: #667eea;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chart-card .card-body {
    padding: 25px;
}

/* Recent Activity */
.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px dashed #e9ecef;
}

.activity-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.activity-item:first-child {
    padding-top: 0;
}

.activity-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: white;
    flex-shrink: 0;
}

.activity-icon.primary { background: var(--primary-gradient); }
.activity-icon.success { background: var(--success-gradient); }
.activity-icon.warning { background: var(--warning-gradient); }
.activity-icon.danger { background: var(--danger-gradient); }
.activity-icon.info { background: var(--info-gradient); }

.activity-content {
    flex: 1;
}

.activity-content h6 {
    margin: 0 0 3px 0;
    font-weight: 600;
    color: #34395e;
    font-size: 0.95rem;
}

.activity-content p {
    margin: 0;
    font-size: 0.85rem;
    color: #6c757d;
}

.activity-time {
    font-size: 0.75rem;
    color: #999;
    white-space: nowrap;
}

/* Top Applicants */
.applicant-rank-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-radius: 12px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.applicant-rank-item:hover {
    background: #f0f0ff;
    transform: translateX(5px);
}

.rank-badge {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.85rem;
    margin-right: 12px;
}

.rank-badge.gold { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: white; }
.rank-badge.silver { background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%); color: white; }
.rank-badge.bronze { background: linear-gradient(135deg, #d35400 0%, #e67e22 100%); color: white; }
.rank-badge.normal { background: #e9ecef; color: #666; }

.applicant-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    margin-right: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
    font-size: 0.9rem;
}

.applicant-info {
    flex: 1;
}

.applicant-info h6 {
    margin: 0;
    font-weight: 600;
    color: #34395e;
    font-size: 0.9rem;
}

.applicant-info small {
    color: #6c757d;
}

.applicant-score {
    font-weight: 800;
    color: #667eea;
    font-size: 1rem;
}

/* Job Stats */
.job-stat-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    transition: all 0.3s ease;
}

.job-stat-item:hover {
    transform: scale(1.02);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
}

.job-stat-item .job-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.job-stat-item .job-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
}

.job-stat-item .job-details h6 {
    margin: 0;
    font-weight: 600;
    color: #34395e;
    font-size: 0.95rem;
}

.job-stat-item .job-details small {
    color: #6c757d;
}

.job-stat-item .job-count {
    text-align: right;
}

.job-stat-item .job-count .number {
    font-size: 1.3rem;
    font-weight: 800;
    color: #34395e;
}

.job-stat-item .job-count .label {
    font-size: 0.75rem;
    color: #6c757d;
}

/* Progress Stats */
.progress-stat {
    margin-bottom: 20px;
}

.progress-stat:last-child {
    margin-bottom: 0;
}

.progress-stat .progress-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.progress-stat .progress-label {
    font-weight: 600;
    color: #34395e;
    font-size: 0.9rem;
}

.progress-stat .progress-value {
    font-weight: 700;
    color: #667eea;
}

.progress-stat .progress {
    height: 10px;
    border-radius: 10px;
    background: #e9ecef;
    overflow: hidden;
}

.progress-stat .progress-bar {
    border-radius: 10px;
    transition: width 1s ease;
}

.progress-stat .progress-bar.primary { background: var(--primary-gradient); }
.progress-stat .progress-bar.success { background: var(--success-gradient); }
.progress-stat .progress-bar.warning { background: var(--warning-gradient); }
.progress-stat .progress-bar.danger { background: var(--danger-gradient); }

/* Quick Actions */
.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 18px 20px;
    border-radius: 15px;
    background: white;
    border: 2px solid #f0f0f0;
    width: 100%;
    text-align: left;
    transition: all 0.3s ease;
    margin-bottom: 12px;
    text-decoration: none;
    color: inherit;
}

.quick-action-btn:hover {
    border-color: #667eea;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    transform: translateX(5px);
    text-decoration: none;
    color: inherit;
}

.quick-action-btn .action-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.quick-action-btn .action-icon.primary { background: var(--primary-gradient); }
.quick-action-btn .action-icon.success { background: var(--success-gradient); }
.quick-action-btn .action-icon.warning { background: var(--warning-gradient); }
.quick-action-btn .action-icon.danger { background: var(--danger-gradient); }

.quick-action-btn .action-text h6 {
    margin: 0;
    font-weight: 600;
    color: #34395e;
}

.quick-action-btn .action-text small {
    color: #6c757d;
}

.quick-action-btn .action-arrow {
    margin-left: auto;
    color: #667eea;
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.quick-action-btn:hover .action-arrow {
    transform: translateX(5px);
}

/* Calendar Widget */
.calendar-widget {
    background: var(--primary-gradient);
    border-radius: 15px;
    padding: 20px;
    color: white;
    text-align: center;
    margin-bottom: 20px;
}

.calendar-widget .current-date {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1;
}

.calendar-widget .current-month {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-top: 5px;
}

.calendar-widget .current-day {
    font-size: 0.9rem;
    opacity: 0.8;
    margin-top: 3px;
}

/* Notification Badge */
.notif-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 20px;
    height: 20px;
    background: #dc3545;
    border-radius: 50%;
    font-size: 0.7rem;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

/* Donut Chart Placeholder */
.donut-chart-wrapper {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto;
}

.donut-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.donut-center .value {
    font-size: 2rem;
    font-weight: 800;
    color: #34395e;
}

.donut-center .label {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Legend */
.chart-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-top: 20px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: #666;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 4px;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-section {
        padding: 25px;
        text-align: center;
    }

    .welcome-section h2 {
        font-size: 1.4rem;
    }

    .welcome-stats {
        justify-content: center;
        gap: 20px;
    }

    .welcome-illustration {
        display: none;
    }

    .stat-card .stat-value {
        font-size: 1.8rem;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card, .info-card, .chart-card {
    animation: fadeInUp 0.5s ease forwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Dashboard</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">Dashboard</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="welcome-content">
                        <h2>Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h2>
                        <p>
                            Berikut adalah ringkasan aktivitas rekrutmen hari ini. 
                            @if($lamaranBaru > 0)
                                Anda memiliki <strong>{{ $lamaranBaru }} lamaran baru</strong> yang perlu direview.
                            @else
                                Semua lamaran sudah ditangani hari ini.
                            @endif
                        </p>
                        <a href="{{ route('job.index') }}" class="btn btn-light">
                            <i class="fas fa-eye mr-2"></i> Lihat Lamaran Baru
                        </a>
                        <div class="welcome-stats">
                            <div class="welcome-stat-item">
                                <div class="number">{{ $totalLowongan }}</div>
                                <div class="label">Lowongan Aktif</div>
                            </div>
                            <div class="welcome-stat-item">
                                <div class="number">{{ $interviewToday }}</div>
                                <div class="label">Interview Hari Ini</div>
                            </div>
                            <div class="welcome-stat-item">
                                <div class="number">{{ $pendingReview }}</div>
                                <div class="label">Pending Review</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card primary">
                    <div class="stat-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-label">Total Pengguna</div>
                    <div class="stat-value">{{ number_format($pengguna) }}</div>
                    <span class="stat-trend {{ $penggunaTrendPercent['direction'] }}">
                        <i class="fas fa-arrow-{{ $penggunaTrendPercent['direction'] }}"></i> 
                        {{ $penggunaTrendPercent['percent'] }}% dari bulan lalu
                    </span>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card warning">
                    <div class="stat-icon warning">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="stat-label">Lamaran Terkirim</div>
                    <div class="stat-value">{{ number_format($lamaranTerkirim) }}</div>
                    <span class="stat-trend {{ $lamaranTrendPercent['direction'] }}">
                        <i class="fas fa-arrow-{{ $lamaranTrendPercent['direction'] }}"></i> 
                        {{ $lamaranTrendPercent['percent'] }}% dari bulan lalu
                    </span>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-label">Lamaran Diterima</div>
                    <div class="stat-value">{{ number_format($lamaranDiterima) }}</div>
                    <span class="stat-trend {{ $diterimaTrendPercent['direction'] }}">
                        <i class="fas fa-arrow-{{ $diterimaTrendPercent['direction'] }}"></i> 
                        {{ $diterimaTrendPercent['percent'] }}% dari bulan lalu
                    </span>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card danger">
                    <div class="stat-icon danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-label">Lamaran Ditolak</div>
                    <div class="stat-value">{{ number_format($lamaranDitolak) }}</div>
                    <span class="stat-trend {{ $ditolakTrendPercent['direction'] }}">
                        <i class="fas fa-arrow-{{ $ditolakTrendPercent['direction'] }}"></i> 
                        {{ $ditolakTrendPercent['percent'] }}% dari bulan lalu
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Chart -->
            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-line mr-2 text-primary"></i> Statistik Pelamar Kerja {{ date('Y') }}</h4>
                        <div class="chart-tabs">
                            <button class="tab active" data-period="monthly">Bulanan</button>
                            <button class="tab" data-period="weekly">Mingguan</button>
                            <button class="tab" data-period="daily">Harian</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="mainChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Job Statistics -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-briefcase"></i> Lowongan Terpopuler</h4>
                        <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @forelse($popularJobs as $job)
                        <div class="job-stat-item">
                            <div class="job-info">
                                <div class="job-icon" style="background: {{ $job['color'] }};">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="job-details">
                                    <h6>{{ Str::limit($job['title'], 30) }}</h6>
                                    <small>{{ $job['company'] }}</small>
                                </div>
                            </div>
                            <div class="job-count">
                                <div class="number">{{ $job['applicants'] }}</div>
                                <div class="label">Pelamar</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-briefcase fa-2x mb-2"></i>
                            <p>Belum ada lowongan</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Calendar Widget -->
                @php
                    use Carbon\Carbon;
                    $now = Carbon::now()->locale('id');
                @endphp

                <div class="calendar-widget">
                    <div class="current-date">{{ $now->isoFormat('DD') }}</div>
                    <div class="current-month">{{ $now->isoFormat('MMMM YYYY') }}</div>
                    <div class="current-day">{{ $now->isoFormat('dddd') }}</div>
                </div>

                <!-- Application Status Donut -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-pie"></i> Status Lamaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="donut-chart-wrapper">
                            <canvas id="donutChart"></canvas>
                            <div class="donut-center">
                                <div class="value">{{ number_format($lamaranTerkirim) }}</div>
                                <div class="label">Total</div>
                            </div>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <span class="legend-dot" style="background: #f7971e;"></span>
                                Pending ({{ $statusLamaran['pending'] }})
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot" style="background: #00c6fb;"></span>
                                Review ({{ $statusLamaran['review'] }})
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot" style="background: #a855f7;"></span>
                                Interview ({{ $statusLamaran['interview'] }})
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot" style="background: #11998e;"></span>
                                Diterima ({{ $statusLamaran['diterima'] }})
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot" style="background: #eb3349;"></span>
                                Ditolak ({{ $statusLamaran['ditolak'] }})
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conversion Rate -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-percentage"></i> Tingkat Konversi</h4>
                    </div>
                    <div class="card-body">
                        <div class="progress-stat">
                            <div class="progress-header">
                                <span class="progress-label">CV Screening</span>
                                <span class="progress-value">{{ $conversionRates['screening'] }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar primary" style="width: {{ $conversionRates['screening'] }}%;"></div>
                            </div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-header">
                                <span class="progress-label">Interview</span>
                                <span class="progress-value">{{ $conversionRates['interview'] }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar success" style="width: {{ $conversionRates['interview'] }}%;"></div>
                            </div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-header">
                                <span class="progress-label">Offering</span>
                                <span class="progress-value">{{ $conversionRates['offering'] }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar warning" style="width: {{ $conversionRates['offering'] }}%;"></div>
                            </div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-header">
                                <span class="progress-label">Hired</span>
                                <span class="progress-value">{{ $conversionRates['hired'] }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar danger" style="width: {{ $conversionRates['hired'] }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Activity -->
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-history"></i> Aktivitas Terbaru</h4>
                    </div>
                    <div class="card-body">
                        @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon {{ $activity['type'] }}">
                                <i class="fas fa-{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="activity-content">
                                <h6>{{ $activity['title'] }}</h6>
                                <p>{{ Str::limit($activity['desc'], 40) }}</p>
                            </div>
                            <div class="activity-time">{{ $activity['time'] }}</div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p>Belum ada aktivitas</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Applicants -->
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-trophy"></i> Top Kandidat Bulan Ini</h4>
                    </div>
                    <div class="card-body">
                        @forelse($topApplicants as $index => $applicant)
                        <div class="applicant-rank-item">
                            <div class="rank-badge {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'normal')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="applicant-avatar" style="background: {{ $applicant['color'] }};">
                                {{ strtoupper(substr($applicant['name'], 0, 2)) }}
                            </div>
                            <div class="applicant-info">
                                <h6>{{ Str::limit($applicant['name'], 15) }}</h6>
                                <small>{{ Str::limit($applicant['position'], 20) }}</small>
                            </div>
                            <div class="applicant-score">{{ $applicant['score'] }}%</div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-trophy fa-2x mb-2"></i>
                            <p>Belum ada kandidat dinilai</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-bolt"></i> Aksi Cepat</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('job.create') }}" class="quick-action-btn">
                            <div class="action-icon primary">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="action-text">
                                <h6>Tambah Lowongan</h6>
                                <small>Buat lowongan kerja baru</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                        <a href="{{ route('job.index') }}" class="quick-action-btn">
                            <div class="action-icon success">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="action-text">
                                <h6>Review Lamaran</h6>
                                <small>{{ $pendingReview }} lamaran menunggu</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                        <a href="#" class="quick-action-btn">
                            <div class="action-icon warning">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="action-text">
                                <h6>Jadwal Interview</h6>
                                <small>{{ $interviewToday }} interview hari ini</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                        <a href="#" class="quick-action-btn" id="btnExportReport">
                            <div class="action-icon danger">
                                <i class="fas fa-file-export"></i>
                            </div>
                            <div class="action-text">
                                <h6>Export Laporan</h6>
                                <small>Download laporan bulanan</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initial chart data from PHP
    let chartData = @json($chartData);

    // Main Chart
    const mainCtx = document.getElementById('mainChart').getContext('2d');
    let mainChart = new Chart(mainCtx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Lamaran Masuk',
                    data: chartData.lamaranMasuk,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                },
                {
                    label: 'Diterima',
                    data: chartData.diterima,
                    borderColor: '#11998e',
                    backgroundColor: 'rgba(17, 153, 142, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#11998e',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                },
                {
                    label: 'Ditolak',
                    data: chartData.ditolak,
                    borderColor: '#eb3349',
                    backgroundColor: 'rgba(235, 51, 73, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#eb3349',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, weight: '600' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 15,
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 },
                    cornerRadius: 10
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 12, weight: '500' } }
                },
                y: {
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { font: { size: 12, weight: '500' } }
                }
            },
            interaction: { intersect: false, mode: 'index' }
        }
    });

    // Donut Chart
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    const statusData = @json($statusLamaran);
    
    const donutChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Review', 'Interview', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    statusData.pending,
                    statusData.review,
                    statusData.interview,
                    statusData.diterima,
                    statusData.ditolak
                ],
                backgroundColor: ['#f7971e', '#00c6fb', '#a855f7', '#11998e', '#eb3349'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8
                }
            }
        }
    });

    // Chart tabs - Load data via AJAX
    $('.chart-tabs .tab').on('click', function() {
        const period = $(this).data('period');
        
        $('.chart-tabs .tab').removeClass('active');
        $(this).addClass('active');

        // Show loading
        $('#mainChart').css('opacity', '0.5');

        $.ajax({
            url: '{{ route("dashboard.chart-data") }}',
            data: { period: period },
            success: function(data) {
                mainChart.data.labels = data.labels;
                mainChart.data.datasets[0].data = data.lamaranMasuk;
                mainChart.data.datasets[1].data = data.diterima;
                mainChart.data.datasets[2].data = data.ditolak;
                mainChart.update();
                $('#mainChart').css('opacity', '1');
            },
            error: function() {
                $('#mainChart').css('opacity', '1');
                alert('Gagal memuat data chart');
            }
        });
    });

    // Animate progress bars
    setTimeout(function() {
        $('.progress-bar').each(function() {
            const width = $(this).css('width');
            $(this).css('width', '0').animate({width: width}, 1000);
        });
    }, 500);

    // Export report button
    $('#btnExportReport').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Export Laporan',
            text: 'Pilih format laporan yang ingin diexport',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: '<i class="fas fa-file-excel"></i> Excel',
            denyButtonText: '<i class="fas fa-file-pdf"></i> PDF',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            denyButtonColor: '#dc3545',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("dashboard.export.excel") ?? "#" }}';
            } else if (result.isDenied) {
                window.location.href = '{{ route("dashboard.export.pdf") ?? "#" }}';
            }
        });
    });
});
</script>
@endpush