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

.welcome-illustration {
    position: absolute;
    right: 40px;
    bottom: 0;
    height: 180px;
    z-index: 1;
    opacity: 0.95;
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

.stat-card .stat-trend {
    position: absolute;
    top: 20px;
    right: 20px;
    font-size: 0.8rem;
    padding: 4px 10px;
    border-radius: 50px;
}

.stat-card .stat-trend.up {
    background: #d4edda;
    color: #155724;
}

.stat-card .stat-trend.down {
    background: #f8d7da;
    color: #721c24;
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

.application-status.pending {
    background: #fff3cd;
    color: #856404;
}

.application-status.review {
    background: #cce5ff;
    color: #004085;
}

.application-status.interview {
    background: #e2d5f1;
    color: #6f42c1;
}

.application-status.accepted {
    background: #d4edda;
    color: #155724;
}

.application-status.rejected {
    background: #f8d7da;
    color: #721c24;
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

.job-recommendation .match-badge {
    padding: 4px 10px;
    background: var(--success-gradient);
    color: white;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Activity Timeline */
.activity-timeline {
    position: relative;
    padding-left: 30px;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 5px;
    bottom: 5px;
    width: 2px;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
}

.activity-item {
    position: relative;
    padding-bottom: 20px;
}

.activity-item:last-child {
    padding-bottom: 0;
}

.activity-item::before {
    content: '';
    position: absolute;
    left: -26px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: white;
    border: 3px solid #667eea;
}

.activity-item.success::before { border-color: #28a745; }
.activity-item.warning::before { border-color: #ffc107; }
.activity-item.info::before { border-color: #17a2b8; }
.activity-item.danger::before { border-color: #dc3545; }

.activity-time {
    font-size: 0.75rem;
    color: #999;
    margin-bottom: 3px;
}

.activity-text {
    font-size: 0.9rem;
    color: #34395e;
}

.activity-text strong {
    color: #667eea;
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
    background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
    border: 2px solid #ffcccc;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 15px;
}

.interview-card.upcoming {
    background: linear-gradient(135deg, #f0fff4 0%, #dcffe4 100%);
    border-color: #9ae6b4;
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
    color: #dc3545;
    line-height: 1;
}

.interview-card.upcoming .date-box .day {
    color: #28a745;
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

.interview-card .interview-time {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #ddd;
}

.interview-card .interview-time i {
    color: #667eea;
}

/* Skills Chart */
.skill-item {
    margin-bottom: 15px;
}

.skill-item:last-child {
    margin-bottom: 0;
}

.skill-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}

.skill-name {
    font-weight: 600;
    color: #34395e;
    font-size: 0.9rem;
}

.skill-percentage {
    color: #667eea;
    font-weight: 700;
    font-size: 0.85rem;
}

.skill-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.skill-progress {
    height: 100%;
    border-radius: 10px;
    background: var(--primary-gradient);
}

/* Notification Badge */
.notification-dot {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 20px;
    height: 20px;
    background: #dc3545;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    color: white;
    font-weight: 700;
    border: 2px solid white;
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

    .welcome-illustration {
        display: none;
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
                        <h2>Selamat Datang, {{ Auth::user()->name ?? 'Kandidat' }}! 👋</h2>
                        <p>Pantau perkembangan lamaran kerja Anda dan temukan peluang karir terbaik.</p>
                        <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-light">
                            <i class="fas fa-search mr-2"></i> Cari Lowongan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Completion -->
        <div class="profile-completion">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6><i class="fas fa-user-circle mr-2 text-primary"></i> Kelengkapan Profil</h6>
                    <div class="completion-bar">
                        <div class="completion-progress" style="width: {{ $profileCompletion ?? 75 }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">{{ $profileCompletion ?? 75 }}% Lengkap</small>
                        <a href="#" class="text-primary" style="font-size: 0.85rem;"><i class="fas fa-edit mr-1"></i> Lengkapi Profil</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="completion-tasks">
                        <span class="completion-task done"><i class="fas fa-check-circle"></i> Foto Profil</span>
                        <span class="completion-task done"><i class="fas fa-check-circle"></i> Data Diri</span>
                        <span class="completion-task pending"><i class="fas fa-exclamation-circle"></i> Upload CV</span>
                        <span class="completion-task done"><i class="fas fa-check-circle"></i> Pendidikan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card primary">
                    <span class="stat-trend up"><i class="fas fa-arrow-up mr-1"></i> 12%</span>
                    <div class="stat-icon primary">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="stat-value">{{ $totalLamaran ?? 8 }}</div>
                    <div class="stat-label">Total Lamaran</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card warning">
                    <div class="stat-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ $pendingLamaran ?? 3 }}</div>
                    <div class="stat-label">Menunggu Review</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card info">
                    <div class="stat-icon info">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-value">{{ $interviewCount ?? 2 }}</div>
                    <div class="stat-label">Jadwal Interview</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ $acceptedCount ?? 1 }}</div>
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
                        <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @php
                            $applications = [
                                ['company' => 'PT. Teknologi Nusantara', 'position' => 'Frontend Developer', 'status' => 'interview', 'date' => '2 jam lalu', 'color' => '#667eea'],
                                ['company' => 'PT. Digital Indonesia', 'position' => 'UI/UX Designer', 'status' => 'review', 'date' => '1 hari lalu', 'color' => '#11998e'],
                                ['company' => 'PT. Startup Maju', 'position' => 'Full Stack Developer', 'status' => 'pending', 'date' => '3 hari lalu', 'color' => '#f7971e'],
                                ['company' => 'PT. Solusi Digital', 'position' => 'Backend Developer', 'status' => 'accepted', 'date' => '1 minggu lalu', 'color' => '#eb3349'],
                            ];
                        @endphp

                        @foreach($applications as $app)
                        <div class="application-item">
                            <div class="application-logo" style="background: {{ $app['color'] }};">
                                {{ strtoupper(substr($app['company'], 4, 2)) }}
                            </div>
                            <div class="application-info">
                                <h6>{{ $app['position'] }}</h6>
                                <small><i class="fas fa-building mr-1"></i> {{ $app['company'] }} • {{ $app['date'] }}</small>
                            </div>
                            <span class="application-status {{ $app['status'] }}">
                                @if($app['status'] == 'pending')
                                    <i class="fas fa-clock mr-1"></i> Pending
                                @elseif($app['status'] == 'review')
                                    <i class="fas fa-search mr-1"></i> Review
                                @elseif($app['status'] == 'interview')
                                    <i class="fas fa-calendar mr-1"></i> Interview
                                @elseif($app['status'] == 'accepted')
                                    <i class="fas fa-check mr-1"></i> Diterima
                                @elseif($app['status'] == 'rejected')
                                    <i class="fas fa-times mr-1"></i> Ditolak
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Rekomendasi Lowongan -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-magic"></i> Rekomendasi Untuk Anda</h4>
                        <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @php
                            $recommendations = [
                                ['title' => 'Senior React Developer', 'company' => 'PT. Tech Solutions', 'location' => 'Jakarta', 'type' => 'Full-time', 'match' => 95],
                                ['title' => 'Frontend Engineer', 'company' => 'PT. Startup Indonesia', 'location' => 'Remote', 'type' => 'Full-time', 'match' => 88],
                                ['title' => 'Web Developer', 'company' => 'PT. Digital Agency', 'location' => 'Yogyakarta', 'type' => 'Contract', 'match' => 82],
                            ];
                        @endphp

                        @foreach($recommendations as $job)
                        <div class="job-recommendation">
                            <div class="job-header">
                                <div>
                                    <h6>{{ $job['title'] }}</h6>
                                    <div class="company"><i class="fas fa-building mr-1"></i> {{ $job['company'] }}</div>
                                </div>
                                <span class="match-badge"><i class="fas fa-star mr-1"></i> {{ $job['match'] }}% Match</span>
                            </div>
                            <div class="job-meta">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $job['location'] }}</span>
                                <span><i class="fas fa-briefcase"></i> {{ $job['type'] }}</span>
                                <span><i class="fas fa-clock"></i> Baru diposting</span>
                            </div>
                        </div>
                        @endforeach
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
                        <div class="interview-card upcoming">
                            <div class="interview-date">
                                <div class="date-box">
                                    <span class="day">15</span>
                                    <span class="month">Des</span>
                                </div>
                                <div class="interview-info">
                                    <h6>Frontend Developer</h6>
                                    <small>PT. Teknologi Nusantara</small>
                                </div>
                            </div>
                            <div class="interview-time">
                                <i class="fas fa-clock"></i> 10:00 - 11:00 WIB
                                <span class="ml-auto badge badge-success">Besok</span>
                            </div>
                        </div>

                        <div class="interview-card">
                            <div class="interview-date">
                                <div class="date-box">
                                    <span class="day">20</span>
                                    <span class="month">Des</span>
                                </div>
                                <div class="interview-info">
                                    <h6>UI/UX Designer</h6>
                                    <small>PT. Digital Indonesia</small>
                                </div>
                            </div>
                            <div class="interview-time">
                                <i class="fas fa-clock"></i> 14:00 - 15:00 WIB
                                <span class="ml-auto badge badge-secondary">5 hari lagi</span>
                            </div>
                        </div>
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
                        <a href="#" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <div class="action-text">
                                <h6>Upload CV</h6>
                                <small>Perbarui CV Anda</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                        <a href="#" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="action-text">
                                <h6>Edit Profil</h6>
                                <small>Lengkapi data diri</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                        <a href="#" class="quick-action">
                            <div class="action-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="action-text">
                                <h6>Riwayat Lamaran</h6>
                                <small>Lihat semua lamaran</small>
                            </div>
                            <i class="fas fa-chevron-right action-arrow"></i>
                        </a>
                    </div>
                </div>

                <!-- Skills -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-code"></i> Skill Match</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $skills = [
                                ['name' => 'JavaScript', 'percentage' => 90],
                                ['name' => 'React.js', 'percentage' => 85],
                                ['name' => 'HTML/CSS', 'percentage' => 95],
                                ['name' => 'Node.js', 'percentage' => 70],
                                ['name' => 'Git', 'percentage' => 80],
                            ];
                        @endphp

                        @foreach($skills as $skill)
                        <div class="skill-item">
                            <div class="skill-info">
                                <span class="skill-name">{{ $skill['name'] }}</span>
                                <span class="skill-percentage">{{ $skill['percentage'] }}%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: {{ $skill['percentage'] }}%;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-stream"></i> Aktivitas Terbaru</h4>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            <div class="activity-item success">
                                <div class="activity-time">Hari ini, 10:30</div>
                                <div class="activity-text">Lamaran Anda di <strong>PT. Tech Solutions</strong> diterima untuk tahap interview</div>
                            </div>
                            <div class="activity-item info">
                                <div class="activity-time">Kemarin, 14:00</div>
                                <div class="activity-text">Anda melamar posisi <strong>Frontend Developer</strong></div>
                            </div>
                            <div class="activity-item warning">
                                <div class="activity-time">2 hari lalu</div>
                                <div class="activity-text">Profil Anda dilihat oleh <strong>3 perusahaan</strong></div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-time">1 minggu lalu</div>
                                <div class="activity-text">Anda memperbarui <strong>CV</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom-js')
<script>
$(document).ready(function() {
    // Animate progress bars on load
    setTimeout(function() {
        $('.completion-progress').css('width', '{{ $profileCompletion ?? 75 }}%');
        $('.skill-progress').each(function() {
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