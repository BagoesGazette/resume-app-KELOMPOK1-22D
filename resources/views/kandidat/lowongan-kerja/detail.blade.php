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
    --teal-gradient: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
}

/* Header Card */
.applicant-header-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

.applicant-header {
    background: var(--primary-gradient);
    padding: 40px;
    color: white;
    position: relative;
    overflow: hidden;
}

.applicant-header::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 350px;
    height: 350px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.applicant-header::after {
    content: '';
    position: absolute;
    bottom: -150px;
    left: -50px;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.applicant-profile {
    display: flex;
    align-items: center;
    gap: 25px;
    position: relative;
    z-index: 1;
}

.applicant-avatar {
    width: 120px;
    height: 120px;
    border-radius: 20px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 800;
    color: #667eea;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    flex-shrink: 0;
    overflow: hidden;
}

.applicant-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.applicant-info h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.applicant-info .email {
    opacity: 0.9;
    margin-bottom: 5px;
    font-size: 1rem;
}

.applicant-info .phone {
    opacity: 0.9;
    font-size: 0.95rem;
}

.applicant-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 15px;
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(255,255,255,0.2);
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
}

.header-actions {
    position: absolute;
    top: 30px;
    right: 30px;
    z-index: 2;
    display: flex;
    gap: 10px;
}

.header-actions .btn {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.header-actions .btn:hover {
    transform: translateY(-2px);
}

/* Status Badge */
.status-badge-large {
    padding: 12px 25px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.status-badge-large.submitted {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
    color: #856404;
}

.status-badge-large.reviewed {
    background: linear-gradient(135deg, #cce5ff 0%, #9ec5fe 100%);
    color: #004085;
}

.status-badge-large.interview {
    background: linear-gradient(135deg, #e2d5f1 0%, #c9b3e6 100%);
    color: #6f42c1;
}

.status-badge-large.accepted {
    background: linear-gradient(135deg, #d4edda 0%, #a3cfbb 100%);
    color: #155724;
}

.status-badge-large.rejected {
    background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%);
    color: #721c24;
}

/* Quick Stats Bar */
.quick-stats-bar {
    background: #f8f9fa;
    padding: 25px 40px;
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 20px;
}

.quick-stat {
    text-align: center;
}

.quick-stat .stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #34395e;
}

.quick-stat .stat-label {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Info Cards */
.info-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
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
    gap: 12px;
    font-size: 1.1rem;
}

.info-card .card-header h4 i {
    width: 35px;
    height: 35px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.95rem;
}

.info-card .card-header h4 i.bg-primary { background: var(--primary-gradient); }
.info-card .card-header h4 i.bg-success { background: var(--success-gradient); }
.info-card .card-header h4 i.bg-warning { background: var(--warning-gradient); }
.info-card .card-header h4 i.bg-info { background: var(--info-gradient); }
.info-card .card-header h4 i.bg-danger { background: var(--danger-gradient); }
.info-card .card-header h4 i.bg-purple { background: var(--purple-gradient); }

.info-card .card-body {
    padding: 25px;
}

/* Profile Summary */
.profile-summary {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    border-radius: 15px;
    padding: 25px;
    border-left: 5px solid #667eea;
    line-height: 1.8;
    color: #555;
    font-size: 0.95rem;
}

/* Education Card */
.education-card {
    background: linear-gradient(135deg, #f0fff4 0%, #dcffe4 100%);
    border-radius: 15px;
    padding: 25px;
    border: 2px solid #9ae6b4;
}

.education-card .education-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.education-card .degree-icon {
    width: 55px;
    height: 55px;
    border-radius: 15px;
    background: var(--success-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.education-card .degree-info h5 {
    margin: 0 0 5px 0;
    font-weight: 700;
    color: #155724;
}

.education-card .degree-info p {
    margin: 0;
    color: #28a745;
    font-size: 0.9rem;
}

.education-card .education-details {
    display: flex;
    gap: 25px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px dashed #9ae6b4;
    flex-wrap: wrap;
}

.education-card .detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: #155724;
}

.education-card .detail-item i {
    color: #28a745;
}

.education-summary {
    margin-top: 15px;
    padding: 15px;
    background: rgba(255,255,255,0.7);
    border-radius: 10px;
    font-size: 0.9rem;
    color: #555;
    line-height: 1.7;
}

/* Experience Card */
.experience-card {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
    border-radius: 15px;
    padding: 25px;
    border: 2px solid #feb2b2;
}

.experience-card .experience-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.experience-card .exp-icon {
    width: 55px;
    height: 55px;
    border-radius: 15px;
    background: var(--danger-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.experience-card .exp-info h5 {
    margin: 0 0 5px 0;
    font-weight: 700;
    color: #c53030;
}

.experience-card .exp-info p {
    margin: 0;
    color: #e53e3e;
    font-size: 0.9rem;
}

.experience-card .experience-stats {
    display: flex;
    gap: 25px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px dashed #feb2b2;
    flex-wrap: wrap;
}

.experience-card .stat-box {
    text-align: center;
    padding: 15px 25px;
    background: rgba(255,255,255,0.7);
    border-radius: 12px;
}

.experience-card .stat-box .number {
    font-size: 1.8rem;
    font-weight: 800;
    color: #c53030;
}

.experience-card .stat-box .label {
    font-size: 0.8rem;
    color: #6c757d;
}

.experience-summary {
    margin-top: 15px;
    padding: 15px;
    background: rgba(255,255,255,0.7);
    border-radius: 10px;
    font-size: 0.9rem;
    color: #555;
    line-height: 1.7;
}

/* Skills Section */
.skills-container {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.skill-category {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 20px;
}

.skill-category h6 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.skill-category h6 i {
    color: #667eea;
}

.skill-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.skill-badge {
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.skill-badge:hover {
    transform: translateY(-2px);
}

.skill-badge.hardskill {
    background: var(--primary-gradient);
    color: white;
}

.skill-badge.softskill {
    background: var(--teal-gradient);
    color: white;
}

/* Cover Letter */
.cover-letter-content {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border-radius: 15px;
    padding: 30px;
    border: 2px solid #fcd34d;
    position: relative;
}

.cover-letter-content::before {
    content: '"';
    position: absolute;
    top: 10px;
    left: 20px;
    font-size: 4rem;
    color: #f59e0b;
    opacity: 0.3;
    font-family: Georgia, serif;
}

.cover-letter-content p {
    font-size: 0.95rem;
    line-height: 1.9;
    color: #555;
    margin: 0;
    position: relative;
    z-index: 1;
    padding-left: 30px;
}

/* Salary Expectation */
.salary-card {
    background: var(--primary-gradient);
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    color: white;
}

.salary-card .salary-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 1.5rem;
}

.salary-card .salary-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 5px;
}

.salary-card .salary-amount {
    font-size: 1.5rem;
    font-weight: 800;
}

/* CV Score */
.cv-score-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.score-circle {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    margin: 0 auto 20px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.score-value {
    position: relative;
    z-index: 1;
    font-size: 2rem;
    font-weight: 800;
    color: #667eea;
}

.score-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.score-status {
    font-weight: 700;
    font-size: 1.1rem;
}

.score-status.excellent { color: #28a745; }
.score-status.good { color: #17a2b8; }
.score-status.average { color: #ffc107; }
.score-status.poor { color: #dc3545; }

/* Ranking Badge */
.ranking-card {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    border: 2px solid #fcd34d;
}

.ranking-card .ranking-icon {
    width: 60px;
    height: 60px;
    background: var(--warning-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 1.5rem;
    color: white;
}

.ranking-card .ranking-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: #92400e;
}

.ranking-card .ranking-label {
    font-size: 0.9rem;
    color: #78350f;
}

.ranking-card .ranking-total {
    font-size: 0.85rem;
    color: #a16207;
    margin-top: 5px;
}

/* Application Timeline */
.timeline {
    position: relative;
    padding-left: 35px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 5px;
    bottom: 5px;
    width: 3px;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    border-radius: 3px;
}

.timeline-item {
    position: relative;
    padding-bottom: 25px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 5px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: white;
    border: 4px solid #e0e0e0;
}

.timeline-item.completed::before {
    background: #28a745;
    border-color: #28a745;
}

.timeline-item.active::before {
    background: #667eea;
    border-color: #667eea;
    box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.2);
}

.timeline-item .timeline-date {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 3px;
}

.timeline-item .timeline-title {
    font-weight: 600;
    color: #34395e;
    margin-bottom: 3px;
}

.timeline-item .timeline-desc {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Interview Info Card */
.interview-info-card {
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    border-radius: 15px;
    padding: 20px;
    border: 2px solid #a5b4fc;
    margin-bottom: 20px;
}

.interview-info-card h6 {
    color: #3730a3;
    font-weight: 700;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.interview-info-card .info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    font-size: 0.9rem;
    color: #4338ca;
}

.interview-info-card .info-row i {
    width: 20px;
    text-align: center;
}

.interview-info-card .info-row:last-child {
    margin-bottom: 0;
}

/* Empty State */
.empty-state-mini {
    text-align: center;
    padding: 20px;
    color: #6c757d;
}

.empty-state-mini i {
    font-size: 2rem;
    margin-bottom: 10px;
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 992px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .applicant-header {
        padding: 25px;
        text-align: center;
    }

    .applicant-profile {
        flex-direction: column;
        text-align: center;
    }

    .applicant-info h2 {
        font-size: 1.4rem;
    }

    .header-actions {
        position: relative;
        top: auto;
        right: auto;
        justify-content: center;
        margin-top: 20px;
    }

    .quick-stats-bar {
        padding: 20px;
    }

    .education-card .education-details,
    .experience-card .experience-stats {
        flex-direction: column;
        gap: 10px;
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

.info-card {
    animation: fadeInUp 0.5s ease forwards;
}
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('hasil.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Lamaran</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('hasil.index') }}">Hasil Penilaian</a></div>
            <div class="breadcrumb-item">Detail</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Applicant Header Card -->
        <div class="applicant-header-card">
            <div class="applicant-header">
                <div class="header-actions">
                    <a href="{{ route('lowongan-kerja.show', $application->jobopening_id) }}" class="btn btn-light">
                        <i class="fas fa-briefcase mr-1"></i> Lihat Lowongan
                    </a>
                </div>
                
                <div class="applicant-profile">
                    <div class="applicant-avatar">
                        @if($application->user->photo)
                            <img src="{{ asset('storage/' . $application->user->photo) }}" alt="Avatar">
                        @else
                            {{ strtoupper(substr($application->user->name ?? 'U', 0, 2)) }}
                        @endif
                    </div>
                    <div class="applicant-info">
                        <h2>{{ $application->user->name ?? 'Nama tidak tersedia' }}</h2>
                        <p class="email"><i class="fas fa-envelope mr-2"></i> {{ $application->user->email ?? '-' }}</p>
                        @if($application->user->phone)
                        <p class="phone"><i class="fas fa-phone mr-2"></i> {{ $application->user->phone }}</p>
                        @endif
                        <div class="applicant-meta">
                            <span class="meta-badge">
                                <i class="fas fa-briefcase"></i>
                                {{ $application->jobOpening->judul ?? 'Posisi tidak tersedia' }}
                            </span>
                            <span class="meta-badge">
                                <i class="fas fa-building"></i>
                                {{ $application->jobOpening->perusahaan ?? '-' }}
                            </span>
                            <span class="meta-badge">
                                <i class="fas fa-calendar"></i>
                                Dilamar: {{ $application->created_at->locale('id')->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Bar -->
            <div class="quick-stats-bar">
                <div class="quick-stat">
                    <div class="stat-value">{{ $application->cvSubmission->total_pengalaman ?? 0 }}</div>
                    <div class="stat-label">Tahun Pengalaman</div>
                </div>
                <div class="quick-stat">
                    <div class="stat-value">{{ $application->cvSubmission->ipk_nilai_akhir ?? '-' }}</div>
                    <div class="stat-label">IPK/Nilai</div>
                </div>
                <div class="quick-stat">
                    <div class="stat-value">{{ $totalSkills }}</div>
                    <div class="stat-label">Total Skills</div>
                </div>
                <div class="quick-stat">
                    <div class="stat-value status-badge-large {{ $application->status }}">
                        @switch($application->status)
                            @case('submitted')
                                <i class="fas fa-clock"></i> Pending
                                @break
                            @case('reviewed')
                                <i class="fas fa-search"></i> Review
                                @break
                            @case('interview')
                                <i class="fas fa-calendar-check"></i> Interview
                                @break
                            @case('accepted')
                                <i class="fas fa-check-circle"></i> Diterima
                                @break
                            @case('rejected')
                                <i class="fas fa-times-circle"></i> Ditolak
                                @break
                            @default
                                <i class="fas fa-clock"></i> {{ ucfirst($application->status) }}
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Profile Summary -->
                @if($application->cvSubmission->rangkuman_profil)
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-user-tie bg-primary"></i> Rangkuman Profil</h4>
                    </div>
                    <div class="card-body">
                        <div class="profile-summary">
                            {{ $application->cvSubmission->rangkuman_profil }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Education -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-graduation-cap bg-success"></i> Pendidikan</h4>
                    </div>
                    <div class="card-body">
                        <div class="education-card">
                            <div class="education-header">
                                <div class="degree-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="degree-info">
                                    <h5>{{ $application->cvSubmission->pendidikan_terakhir ?? 'Pendidikan tidak tersedia' }}</h5>
                                    <p>{{ $pendidikanLabels[$application->cvSubmission->tipe_pendidikan ?? 3] ?? 'S1 (Sarjana)' }}</p>
                                </div>
                            </div>
                            <div class="education-details">
                                <div class="detail-item">
                                    <i class="fas fa-star"></i>
                                    <span>IPK/Nilai: <strong>{{ $application->cvSubmission->ipk_nilai_akhir ?? '-' }}</strong></span>
                                </div>
                                @if($application->cvSubmission->tahun_lulus)
                                <div class="detail-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Tahun Lulus: <strong>{{ $application->cvSubmission->tahun_lulus }}</strong></span>
                                </div>
                                @endif
                            </div>
                            @if($application->cvSubmission->rangkuman_pendidikan)
                            <div class="education-summary">
                                <strong><i class="fas fa-info-circle mr-1"></i> Rangkuman:</strong><br>
                                {{ $application->cvSubmission->rangkuman_pendidikan }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Work Experience -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-briefcase bg-danger"></i> Pengalaman Kerja</h4>
                    </div>
                    <div class="card-body">
                        <div class="experience-card">
                            <div class="experience-header">
                                <div class="exp-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="exp-info">
                                    <h5>{{ $application->cvSubmission->pengalaman_kerja_terakhir ?? 'Belum ada pengalaman kerja' }}</h5>
                                    <p>Pengalaman Kerja Terakhir</p>
                                </div>
                            </div>
                            <div class="experience-stats">
                                <div class="stat-box">
                                    <div class="number">{{ $application->cvSubmission->total_pengalaman ?? 0 }}</div>
                                    <div class="label">Tahun Pengalaman</div>
                                </div>
                            </div>
                            @if($application->cvSubmission->rangkuman_pengalaman_kerja)
                            <div class="experience-summary">
                                <strong><i class="fas fa-info-circle mr-1"></i> Rangkuman:</strong><br>
                                {{ $application->cvSubmission->rangkuman_pengalaman_kerja }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Skills -->
                @php
                    $hardskills = $application->cvSubmission->hardskills ?? [];
                    $softskills = $application->cvSubmission->softskills ?? [];
                    $hasSkills = (is_array($hardskills) && count($hardskills) > 0) || (is_array($softskills) && count($softskills) > 0);
                @endphp
                @if($hasSkills)
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-cogs bg-purple"></i> Keahlian (Skills)</h4>
                    </div>
                    <div class="card-body">
                        <div class="skills-container">
                            <!-- Hard Skills -->
                            @if(is_array($hardskills) && count($hardskills) > 0)
                            <div class="skill-category">
                                <h6><i class="fas fa-code"></i> Hard Skills (Technical)</h6>
                                <div class="skill-badges">
                                    @foreach($hardskills as $skill)
                                        <span class="skill-badge hardskill">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Soft Skills -->
                            @if(is_array($softskills) && count($softskills) > 0)
                            <div class="skill-category">
                                <h6><i class="fas fa-users"></i> Soft Skills (Interpersonal)</h6>
                                <div class="skill-badges">
                                    @foreach($softskills as $skill)
                                        <span class="skill-badge softskill">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Cover Letter -->
                @if($application->cover_letter)
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-envelope-open-text bg-warning"></i> Cover Letter</h4>
                    </div>
                    <div class="card-body">
                        <div class="cover-letter-content">
                            <p>{{ $application->cover_letter }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- CV Score -->
                @if($application->score)
                <div class="cv-score-card mb-4">
                    <h5 class="mb-3"><i class="fas fa-chart-pie mr-2 text-primary"></i> Skor CV</h5>
                    @php
                        $scorePercent = $application->score;
                        $scoreClass = $scorePercent >= 90 ? 'excellent' : ($scorePercent >= 80 ? 'good' : ($scorePercent >= 70 ? 'average' : 'poor'));
                        $scoreLabel = $scorePercent >= 90 ? 'Sangat Baik' : ($scorePercent >= 80 ? 'Baik' : ($scorePercent >= 70 ? 'Cukup' : 'Kurang'));
                    @endphp
                    <div class="score-circle" style="background: conic-gradient(#667eea 0% {{ $scorePercent }}%, #e9ecef {{ $scorePercent }}% 100%);">
                        <span class="score-value" style="background: white; width: 110px; height: 110px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">{{ $scorePercent }}%</span>
                    </div>
                    <p class="score-label">Kesesuaian dengan Posisi</p>
                    <p class="score-status {{ $scoreClass }}">
                        @if($scorePercent >= 90)
                            <i class="fas fa-check-circle"></i>
                        @elseif($scorePercent >= 80)
                            <i class="fas fa-thumbs-up"></i>
                        @elseif($scorePercent >= 70)
                            <i class="fas fa-minus-circle"></i>
                        @else
                            <i class="fas fa-exclamation-circle"></i>
                        @endif
                        {{ $scoreLabel }}
                    </p>
                </div>
                @endif

                <!-- Ranking -->
                @if($application->ranking)
                <div class="ranking-card mb-4">
                    <div class="ranking-icon">
                        @if($application->ranking == 1)
                            🥇
                        @elseif($application->ranking == 2)
                            🥈
                        @elseif($application->ranking == 3)
                            🥉
                        @else
                            <i class="fas fa-trophy"></i>
                        @endif
                    </div>
                    <div class="ranking-value">#{{ $application->ranking }}</div>
                    <div class="ranking-label">Ranking Anda</div>
                    <div class="ranking-total">dari {{ $totalApplicants }} pelamar</div>
                </div>
                @endif

                <!-- Salary Expectation -->
                @if($application->expected_salary)
                <div class="salary-card mb-4">
                    <div class="salary-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <p class="salary-label">Ekspektasi Gaji</p>
                    <p class="salary-amount">Rp {{ $application->expected_salary }}</p>
                </div>
                @endif

                <!-- Interview Info (if scheduled) -->
                @if($application->status == 'interview' && $application->interview_date)
                <div class="interview-info-card">
                    <h6><i class="fas fa-calendar-check"></i> Jadwal Interview</h6>
                    <div class="info-row">
                        <i class="fas fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($application->interview_date)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <i class="fas fa-clock"></i>
                        <span>{{ \Carbon\Carbon::parse($application->interview_date)
                        ->locale('id')
                        ->translatedFormat('H:i') }} WIB</span>
                    </div>
                    @if($application->interview_type)
                    <div class="info-row">
                        <i class="fas fa-video"></i>
                        <span>{{ ucfirst($application->interview_type) }}</span>
                    </div>
                    @endif
                    @if($application->interview_location)
                    <div class="info-row">
                        <i class="fas fa-{{ $application->interview_type == 'online' ? 'link' : 'map-marker-alt' }}"></i>
                        @if($application->interview_type == 'online')
                            <a href="{{ $application->interview_location }}" target="_blank" style="color: #4338ca;">
                                {{ Str::limit($application->interview_location, 30) }}
                            </a>
                        @else
                            <span>{{ Str::limit($application->interview_location, 30) }}</span>
                        @endif
                    </div>
                    @endif
                    @if($application->interview_notes)
                    <div class="info-row">
                        <i class="fas fa-sticky-note"></i>
                        <span>{{ $application->interview_notes }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Application Timeline -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-history bg-purple"></i> Timeline Lamaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach($timeline as $item)
                            <div class="timeline-item {{ $item['status'] }}">
                                <div class="timeline-date">{{ $item['date'] }}</div>
                                <div class="timeline-title">{{ $item['title'] }}</div>
                                <div class="timeline-desc">{{ $item['desc'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Job Info -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-briefcase bg-info"></i> Info Lowongan</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Posisi</small>
                            <strong>{{ $application->jobOpening->judul ?? '-' }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Perusahaan</small>
                            <strong>{{ $application->jobOpening->perusahaan ?? '-' }}</strong>
                        </div>
                        @if($application->jobOpening->lokasi)
                        <div class="mb-3">
                            <small class="text-muted d-block">Lokasi</small>
                            <strong>{{ $application->jobOpening->lokasi }}</strong>
                        </div>
                        @endif
                        @if($application->jobOpening->tipe)
                        <div class="mb-3">
                            <small class="text-muted d-block">Tipe</small>
                            <strong>{{ ucfirst($application->jobOpening->tipe) }}</strong>
                        </div>
                        @endif
                        <a href="{{ route('lowongan-kerja.show', $application->jobopening_id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-eye mr-1"></i> Lihat Detail Lowongan
                        </a>
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
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush