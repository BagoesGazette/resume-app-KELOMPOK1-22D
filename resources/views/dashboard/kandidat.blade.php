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
}

.skill-badge {
    display: inline-block;
    padding: 6px 15px;
    margin: 4px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transition: transform 0.2s ease;
}

.skill-badge:hover {
    transform: scale(1.05);
}

/* Welcome Banner */
.welcome-banner {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 35px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
}

.welcome-banner::before {
    content: '';
    position: absolute;
    top: -80px;
    right: -80px;
    width: 250px;
    height: 250px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.welcome-banner::after {
    content: '';
    position: absolute;
    bottom: -100px;
    right: 100px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.welcome-content {
    position: relative;
    z-index: 1;
}

.welcome-banner h2 {
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 8px;
}

.welcome-banner p {
    opacity: 0.9;
    font-size: 1rem;
    margin-bottom: 20px;
}

.welcome-banner .btn-light {
    padding: 10px 25px;
    border-radius: 10px;
    font-weight: 600;
    color: #667eea;
    border: none;
    transition: all 0.3s ease;
}

.welcome-banner .btn-light:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

/* Profile Completion */
.profile-completion {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.profile-completion h6 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.profile-completion h6 span {
    font-size: 0.9rem;
    color: #667eea;
}

.completion-bar {
    height: 12px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 10px;
}

.completion-progress {
    height: 100%;
    background: var(--success-gradient);
    border-radius: 10px;
    transition: width 1s ease;
}

.completion-tasks {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
}

.completion-task {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 8px;
    background: #f8f9fa;
}

.completion-task.done {
    color: #28a745;
}

.completion-task.done i {
    color: #28a745;
}

.completion-task.pending {
    color: #6c757d;
}

.completion-task.pending i {
    color: #ffc107;
}

/* Stats Cards */
.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stat-card.primary::before { background: var(--primary-gradient); }
.stat-card.success::before { background: var(--success-gradient); }
.stat-card.warning::before { background: var(--warning-gradient); }
.stat-card.info::before { background: var(--info-gradient); }
.stat-card.danger::before { background: var(--danger-gradient); }

.stat-card .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 15px;
}

.stat-card .stat-icon.primary { background: var(--primary-gradient); }
.stat-card .stat-icon.success { background: var(--success-gradient); }
.stat-card .stat-icon.warning { background: var(--warning-gradient); }
.stat-card .stat-icon.info { background: var(--info-gradient); }
.stat-card .stat-icon.danger { background: var(--danger-gradient); }

.stat-card .stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #34395e;
    line-height: 1;
    margin-bottom: 5px;
}

.stat-card .stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Info Cards */
.info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 25px;
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

/* Application Status List */
.application-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 12px;
    background: #f8f9fa;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.application-item:hover {
    background: #f0f0f0;
    transform: translateX(5px);
}

.application-item:last-child {
    margin-bottom: 0;
}

.application-logo {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    color: white;
    margin-right: 15px;
    flex-shrink: 0;
}

.application-info {
    flex: 1;
}

.application-info h6 {
    margin: 0 0 3px 0;
    font-weight: 600;
    color: #34395e;
    font-size: 0.95rem;
}

.application-info small {
    color: #6c757d;
    font-size: 0.8rem;
}

.application-status {
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

/* Job Recommendations */
.job-recommendation {
    padding: 18px;
    border-radius: 12px;
    border: 2px solid #f0f0f0;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.job-recommendation:hover {
    border-color: #667eea;
    background: #fafbff;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.1);
}

.job-recommendation:last-child {
    margin-bottom: 0;
}

.job-recommendation .job-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.job-recommendation h6 {
    margin: 0;
    font-weight: 700;
    color: #34395e;
    font-size: 1rem;
}

.job-recommendation .company {
    color: #667eea;
    font-weight: 500;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.job-recommendation .job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 0.8rem;
    color: #6c757d;
}

.job-recommendation .job-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.job-recommendation .job-meta i {
    color: #667eea;
}

.job-recommendation .type-badge {
    padding: 4px 10px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

/* Quick Actions */
.quick-action {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 12px;
    background: #f8f9fa;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    text-decoration: none;
    color: #34395e;
}

.quick-action:hover {
    background: var(--primary-gradient);
    color: white;
    transform: translateX(5px);
    text-decoration: none;
}

.quick-action:hover .action-icon {
    background: rgba(255,255,255,0.2);
    color: white;
}

.quick-action:hover .action-arrow {
    color: white;
}

.action-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%);
    color: #667eea;
    margin-right: 15px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.action-text {
    flex: 1;
}

.action-text h6 {
    margin: 0;
    font-weight: 600;
    font-size: 0.95rem;
}

.action-text small {
    opacity: 0.8;
    font-size: 0.8rem;
}

.action-arrow {
    color: #667eea;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

/* Interview Schedule */
.interview-card {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    border: 2px solid #e0e0ff;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.interview-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
}

.interview-card.upcoming {
    background: linear-gradient(135deg, #f0fff4 0%, #dcffe4 100%);
    border-color: #9ae6b4;
}

.interview-card.today {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
    border-color: #feb2b2;
}

.interview-card .interview-date {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 12px;
}

.interview-card .date-box {
    width: 55px;
    height: 55px;
    background: white;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.interview-card .date-box .day {
    font-size: 1.3rem;
    font-weight: 800;
    color: #667eea;
    line-height: 1;
}

.interview-card.upcoming .date-box .day {
    color: #28a745;
}

.interview-card.today .date-box .day {
    color: #dc3545;
}

.interview-card .date-box .month {
    font-size: 0.7rem;
    color: #6c757d;
    text-transform: uppercase;
}

.interview-card .interview-info h6 {
    margin: 0 0 3px 0;
    font-weight: 700;
    color: #34395e;
}

.interview-card .interview-info small {
    color: #6c757d;
}

.interview-card .interview-details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px dashed #ddd;
}

.interview-card .interview-details span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.interview-card .interview-details i {
    color: #667eea;
}

.interview-type-badge {
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.interview-type-badge.online {
    background: #cce5ff;
    color: #004085;
}

.interview-type-badge.onsite {
    background: #d4edda;
    color: #155724;
}

.interview-type-badge.phone {
    background: #fff3cd;
    color: #856404;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-state h6 {
    color: #34395e;
    margin-bottom: 5px;
}

.empty-state p {
    font-size: 0.9rem;
    margin-bottom: 15px;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-banner {
        padding: 25px;
        text-align: center;
    }

    .welcome-banner h2 {
        font-size: 1.4rem;
    }

    .stat-card .stat-value {
        font-size: 1.5rem;
    }

    .application-item {
        flex-wrap: wrap;
    }

    .application-status {
        margin-top: 10px;
        width: 100%;
        text-align: center;
    }
}

/* Animation */
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

.stat-card, .info-card {
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
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Kandidat</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="welcome-content">
                        <h2>Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
                        <p>
                            @if($stats['pending'] > 0)
                                Anda memiliki <strong>{{ $stats['pending'] }} lamaran</strong> yang sedang menunggu review.
                            @elseif($stats['interview'] > 0)
                                Anda memiliki <strong>{{ $stats['interview'] }} jadwal interview</strong> yang akan datang.
                            @else
                                Pantau perkembangan lamaran kerja Anda dan temukan peluang karir terbaik.
                            @endif
                        </p>
                        <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-light">
                            <i class="fas fa-search mr-2"></i> Cari Lowongan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card primary">
                    <div class="stat-icon primary">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Lamaran</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card warning">
                    <div class="stat-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ $stats['pending'] + $stats['reviewed'] }}</div>
                    <div class="stat-label">Menunggu Review</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card info">
                    <div class="stat-icon info">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-value">{{ $stats['interview'] }}</div>
                    <div class="stat-label">Jadwal Interview</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ $stats['accepted'] }}</div>
                    <div class="stat-label">Diterima</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Status Lamaran -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-file-alt"></i> Status Lamaran Terbaru</h4>
                        @if($stats['total'] > 5)
                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        @endif
                    </div>
                    <div class="card-body">
                        @forelse($recentApplications as $application)
                        <div class="application-item">
                            @php
                                $colors = ['#667eea', '#11998e', '#f7971e', '#eb3349', '#00c6fb', '#a855f7'];
                                $color = $colors[array_rand($colors)];
                            @endphp
                            <div class="application-logo" style="background: {{ $application->status_color ?? $color }}">
                                {{ strtoupper(substr($application->jobOpening->perusahaan ?? 'XX', 0, 2)) }}
                            </div>
                            <div class="application-info">
                                <h6>{{ $application->jobOpening->judul ?? 'Posisi tidak tersedia' }}</h6>
                                <small>
                                    <i class="fas fa-building mr-1"></i>
                                    {{ $application->jobOpening->perusahaan ?? '-' }}
                                    • Dilamar {{ $application->created_at->locale('id')->diffForHumans() }}
                                </small>
                            </div>
                            <span class="application-status text-white" style="background-color: {{ $application->status_color ?? '#6c757d' }}">
                                <i class="{{ $application->status_icon ?? 'fas fa-circle' }} mr-1"></i>
                                {{ $application->status_label ?? ucfirst($application->status) }}
                            </span>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-file-alt"></i>
                            <h6>Belum Ada Lamaran</h6>
                            <p>Anda belum melamar pekerjaan apapun.</p>
                            <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-search mr-1"></i> Cari Lowongan
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Rekomendasi Lowongan -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-briefcase"></i> Rekomendasi Lowongan</h4>
                        <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @forelse($recommendedJobs as $job)
                        <div class="job-recommendation">
                            <div class="job-header">
                                <div>
                                    <h6>{{ $job->judul }}</h6>
                                    <div class="company">
                                        <i class="fas fa-building mr-1"></i> {{ $job->perusahaan }}
                                    </div>
                                </div>
                                <span class="type-badge">{{ ucfirst($job->tipe ?? 'Full Time') }}</span>
                            </div>
                            <div class="job-meta">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $job->lokasi ?? 'Indonesia' }}</span>
                                <span><i class="fas fa-tags"></i> {{ $job->category ?? 'Umum' }}</span>
                                <span><i class="fas fa-clock"></i> {{ $job->created_at->locale('id')->diffForHumans() }}</span>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('lowongan-kerja.show', $job->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye mr-1"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-briefcase"></i>
                            <h6>Tidak Ada Rekomendasi</h6>
                            <p>Belum ada lowongan yang cocok untuk Anda saat ini.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-bolt"></i> Aksi Cepat</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('lowongan-kerja.index') }}" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="action-text">
                                <h6>Cari Lowongan</h6>
                                <small>Temukan pekerjaan impian</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                        <a href="" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <div class="action-text">
                                <h6>Lengkapi CV</h6>
                                <small>Perbarui data CV Anda</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                        <a href="" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="action-text">
                                <h6>Edit Profil</h6>
                                <small>Lengkapi data diri</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Jadwal Interview -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-calendar-alt"></i> Jadwal Interview</h4>
                    </div>
                    <div class="card-body">
                        @forelse($upcomingInterviews as $interview)
                        @php
                            $interviewDate = \Carbon\Carbon::parse($interview->interview_date);
                            $isToday = $interviewDate->isToday();
                            $isTomorrow = $interviewDate->isTomorrow();
                            $daysUntil = now()->diffInDays($interviewDate, false);
                            
                            $cardClass = $isToday ? 'today' : ($isTomorrow ? 'upcoming' : '');
                            $badgeClass = $isToday ? 'badge-danger' : ($isTomorrow ? 'badge-success' : 'badge-secondary');
                            $badgeText = $isToday ? 'Hari Ini' : ($isTomorrow ? 'Besok' : ceil($daysUntil ). ' hari lagi');
                        @endphp
                        <div class="interview-card {{ $cardClass }}">
                            <div class="interview-date">
                                <div class="date-box">
                                    <span class="day">{{ $interviewDate->format('d') }}</span>
                                    <span class="month">{{ $interviewDate->locale('id')->isoFormat('MMM') }}</span>
                                </div>
                                <div class="interview-info">
                                    <h6>{{ $interview->jobOpening->judul ?? 'Posisi tidak tersedia' }}</h6>
                                    <small>{{ $interview->jobOpening->perusahaan ?? '-' }}</small>
                                </div>
                            </div>
                            <div class="interview-details">
                                <span>
                                    <i class="fas fa-clock"></i> 
                                    {{ $interviewDate->format('H:i') }} WIB
                                </span>
                                @if($interview->interview_type)
                                <span class="interview-type-badge {{ $interview->interview_type }}">
                                    {{ ucfirst($interview->interview_type) }}
                                </span>
                                @endif
                                <span class="ml-auto badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            </div>
                            @if($interview->interview_location)
                            <div class="mt-2" style="font-size: 0.8rem; color: #6c757d;">
                                <i class="fas fa-{{ $interview->interview_type == 'online' ? 'link' : 'map-marker-alt' }} mr-1"></i>
                                @if($interview->interview_type == 'online')
                                    <a href="{{ $interview->interview_location }}" target="_blank">{{ Str::limit($interview->interview_location, 30) }}</a>
                                @else
                                    {{ Str::limit($interview->interview_location, 40) }}
                                @endif
                            </div>
                            @endif
                            @if($interview->interview_notes)
                            <div class="mt-2" style="font-size: 0.8rem; color: #6c757d; font-style: italic;">
                                <i class="fas fa-sticky-note mr-1"></i>
                                {{ Str::limit($interview->interview_notes, 50) }}
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt"></i>
                            <h6>Tidak Ada Jadwal</h6>
                            <p>Belum ada jadwal interview untuk Anda.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Skills -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-code"></i> Keahlian Anda</h4>
                        <a href="" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->cv && Auth::user()->cv->hardskills && count(Auth::user()->cv->hardskills) > 0)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">Hard Skills</small>
                                @foreach(Auth::user()->cv->hardskills as $skill)
                                    <span class="skill-badge">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if(Auth::user()->cv && Auth::user()->cv->softskills && count(Auth::user()->cv->softskills) > 0)
                            <div>
                                <small class="text-muted d-block mb-2">Soft Skills</small>
                                @foreach(Auth::user()->cv->softskills as $skill)
                                    <span class="skill-badge" style="background: var(--success-gradient);">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if(!Auth::user()->cv || (empty(Auth::user()->cv->hardskills) && empty(Auth::user()->cv->softskills)))
                            <div class="empty-state">
                                <i class="fas fa-code"></i>
                                <h6>Belum Ada Keahlian</h6>
                                <p>Tambahkan keahlian Anda untuk meningkatkan peluang diterima.</p>
                                <a href="{{ route('cv.edit') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i> Tambah Keahlian
                                </a> 
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistik Lamaran -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-pie"></i> Statistik Lamaran</h4>
                    </div>
                    <div class="card-body">
                        @if($stats['total'] > 0)
                        <div class="d-flex justify-content-center mb-3">
                            <canvas id="applicationChart" width="180" height="180"></canvas>
                        </div>
                        <div class="row text-center" style="font-size: 0.85rem;">
                            <div class="col-4">
                                <div style="color: #f7971e; font-weight: 700;">{{ $stats['pending'] + $stats['reviewed'] }}</div>
                                <small class="text-muted">Proses</small>
                            </div>
                            <div class="col-4">
                                <div style="color: #28a745; font-weight: 700;">{{ $stats['accepted'] }}</div>
                                <small class="text-muted">Diterima</small>
                            </div>
                            <div class="col-4">
                                <div style="color: #dc3545; font-weight: 700;">{{ $stats['rejected'] }}</div>
                                <small class="text-muted">Ditolak</small>
                            </div>
                        </div>
                        @else
                        <div class="empty-state">
                            <i class="fas fa-chart-pie"></i>
                            <h6>Belum Ada Data</h6>
                            <p>Mulai melamar untuk melihat statistik.</p>
                        </div>
                        @endif
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
    // Application Chart
    @if($stats['total'] > 0)
    const ctx = document.getElementById('applicationChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Proses', 'Interview', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    {{ $stats['pending'] + $stats['reviewed'] }},
                    {{ $stats['interview'] }},
                    {{ $stats['accepted'] }},
                    {{ $stats['rejected'] }}
                ],
                backgroundColor: ['#f7971e', '#667eea', '#28a745', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    @endif

    // Animate progress bars
    setTimeout(function() {
        $('.completion-progress').each(function() {
            var width = $(this).css('width');
            $(this).css('width', '0').animate({width: width}, 1000);
        });
    }, 300);

    // Hover effects
    $('.quick-action').hover(
        function() {
            $(this).find('.action-arrow').css('transform', 'translateX(5px)');
        },
        function() {
            $(this).find('.action-arrow').css('transform', 'translateX(0)');
        }
    );
});
</script>
@endpush