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

.status-badge-large.pending {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
    color: #856404;
}

.status-badge-large.review {
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

/* Info Items */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #f0f0ff;
    transform: translateX(5px);
}

.info-item .item-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
    background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%);
    color: #667eea;
}

.info-item .item-content {
    flex: 1;
}

.info-item .item-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 3px;
}

.info-item .item-value {
    font-weight: 600;
    color: #34395e;
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
    font-size: 1.8rem;
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

.score-circle::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: conic-gradient(#667eea 0% 85%, #e9ecef 85% 100%);
}

.score-circle::after {
    content: '';
    position: absolute;
    width: 110px;
    height: 110px;
    background: white;
    border-radius: 50%;
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
    color: #28a745;
    font-size: 1.1rem;
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
    border: 4px solid #667eea;
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

/* Action Buttons */
.action-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.action-card h5 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.action-card h5 i {
    color: #667eea;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    border: 2px solid transparent;
}

.action-btn:last-child {
    margin-bottom: 0;
}

.action-btn:hover {
    text-decoration: none;
    color: inherit;
}

.action-btn .action-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: white;
}

.action-btn .action-text h6 {
    margin: 0;
    font-weight: 600;
    font-size: 0.95rem;
}

.action-btn .action-text small {
    color: #6c757d;
}

.action-btn.accept {
    background: linear-gradient(135deg, #f0fff4 0%, #dcffe4 100%);
    border-color: #9ae6b4;
}

.action-btn.accept:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(40, 167, 69, 0.2);
}

.action-btn.accept .action-icon {
    background: var(--success-gradient);
}

.action-btn.reject {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
    border-color: #feb2b2;
}

.action-btn.reject:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(220, 53, 69, 0.2);
}

.action-btn.reject .action-icon {
    background: var(--danger-gradient);
}

.action-btn.interview {
    background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%);
    border-color: #c4b5fd;
}

.action-btn.interview:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(139, 92, 246, 0.2);
}

.action-btn.interview .action-icon {
    background: var(--purple-gradient);
}

.action-btn.download {
    background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%);
    border-color: #67e8f9;
}

.action-btn.download:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(6, 182, 212, 0.2);
}

.action-btn.download .action-icon {
    background: var(--info-gradient);
}

/* Documents */
.document-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.document-item:hover {
    background: #f0f0ff;
    transform: translateX(5px);
}

.document-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.document-icon.pdf {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
}

.document-info {
    flex: 1;
}

.document-info h6 {
    margin: 0 0 3px 0;
    font-weight: 600;
    color: #34395e;
}

.document-info small {
    color: #6c757d;
}

.document-actions a {
    color: #667eea;
    font-size: 1.1rem;
    margin-left: 10px;
    transition: all 0.2s ease;
}

.document-actions a:hover {
    color: #764ba2;
}

/* Notes Section */
.notes-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
}

.notes-section textarea {
    width: 100%;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    font-size: 0.9rem;
    resize: vertical;
    min-height: 100px;
    transition: all 0.3s ease;
}

.notes-section textarea:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            <a href="{{ route('job.show', $application->jobopening_id) }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Pelamar</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="#">Lowongan Kerja</a></div>
            <div class="breadcrumb-item"><a href="#">Pelamar</a></div>
            <div class="breadcrumb-item">Detail</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Applicant Header Card -->
        <div class="applicant-header-card">
            <div class="applicant-header">
                <div class="header-actions">
                    <button class="btn btn-light" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Cetak
                    </button>
                </div>
                
                <div class="applicant-profile">
                    <div class="applicant-avatar">
                        {{ strtoupper(substr($application->user->name ?? '', 0, 2)) }}
                    </div>
                    <div class="applicant-info">
                        <h2>{{ $application->user->name ?? '' }}</h2>
                        <p class="email"><i class="fas fa-envelope mr-2"></i> {{ $application->user->email ?? '' }}</p>
                        <div class="applicant-meta">
                            <span class="meta-badge">
                                <i class="fas fa-briefcase"></i>
                                {{ $application->jobOpening->judul ?? '-' }}
                            </span>
                            <span class="meta-badge">
                                <i class="fas fa-building"></i>
                                {{ $application->jobOpening->perusahaan ?? '-' }}
                            </span>
                            <span class="meta-badge">
                                <i class="fas fa-calendar"></i>
                                Melamar: {{ $application->created_at
                                    ->timezone('Asia/Jakarta')
                                    ->locale('id')
                                    ->translatedFormat('d M Y')
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Bar -->
            <div class="quick-stats-bar">
                <div class="quick-stat">
                    <div class="stat-value">{{ $application->cvSubmission->total_pengalaman ?? '0' }}</div>
                    <div class="stat-label">Tahun Pengalaman</div>
                </div>
                <div class="quick-stat">
                    <div class="stat-value">{{ $application->cvSubmission->ipk_nilai_akhir ?? '0' }}</div>
                    <div class="stat-label">IPK</div>
                </div>
                <div class="quick-stat">
                    @php
                        $hardskills = $application->cvSubmission->hardskills ?? ['PHP', 'Laravel', 'JavaScript', 'React', 'MySQL'];
                        $softskills = $application->cvSubmission->softskills ?? ['Komunikasi', 'Teamwork', 'Problem Solving'];
                        $totalSkills = (is_array($hardskills) ? count($hardskills) : count(explode(',', $hardskills))) + (is_array($softskills) ? count($softskills) : count(explode(',', $softskills)));
                    @endphp
                    <div class="stat-value">{{ $totalSkills }}</div>
                    <div class="stat-label">Total Skills</div>
                </div>
                <div class="quick-stat">
                    <div class="stat-value status-badge-large {{ $application->status ?? 'review' }}">
                        @php $status = $application->status ?? 'review'; @endphp
                        @if($status == 'pending')
                            <i class="fas fa-clock"></i> Pending
                        @elseif($status == 'review')
                            <i class="fas fa-search"></i> Review
                        @elseif($status == 'interview')
                            <i class="fas fa-calendar-check"></i> Interview
                        @elseif($status == 'accepted')
                            <i class="fas fa-check-circle"></i> Diterima
                        @elseif($status == 'rejected')
                            <i class="fas fa-times-circle"></i> Ditolak
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Profile Summary -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-user-tie bg-primary"></i> Rangkuman Profil</h4>
                    </div>
                    <div class="card-body">
                        <div class="profile-summary">
                            {{ $application->cvSubmission->rangkuman_profil ?? 'Saya adalah profesional IT dengan pengalaman lebih dari 4 tahun di bidang web development. Memiliki keahlian dalam pengembangan aplikasi web menggunakan Laravel dan React.js. Berpengalaman memimpin tim developer dan mengelola proyek dari awal hingga deployment. Passionate dalam teknologi baru dan selalu berusaha meningkatkan kemampuan teknis serta soft skills.' }}
                        </div>
                    </div>
                </div>

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
                                    <h5>{{ $application->cvSubmission->pendidikan_terakhir ?? 'S1 Teknik Informatika - Universitas Indonesia' }}</h5>
                                    <p>
                                        @php
                                            $tipePendidikan = [1 => 'Diploma (D3)', 2 => 'Sarjana (S1)', 3 => 'Magister (S2)', 4 => 'Doktor (S3)'];
                                        @endphp
                                        {{ $tipePendidikan[$application->cvSubmission->tipe_pendidikan ?? 2] }}
                                    </p>
                                </div>
                            </div>
                            <div class="education-details">
                                <div class="detail-item">
                                    <i class="fas fa-star"></i>
                                    <span>IPK: <strong>{{ $application->cvSubmission->ipk_nilai_akhir ?? '3.75' }}</strong></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-award"></i>
                                    <span>Lulus dengan Predikat Cumlaude</span>
                                </div>
                            </div>
                            @if($application->cvSubmission->rangkuman_pendidikan ?? true)
                            <div class="education-summary">
                                <strong><i class="fas fa-info-circle mr-1"></i> Rangkuman:</strong><br>
                                {{ $application->cvSubmission->rangkuman_pendidikan ?? 'Lulus dengan predikat cum laude. Aktif dalam organisasi kampus sebagai Ketua Himpunan Mahasiswa Informatika. Mengikuti berbagai kompetisi programming tingkat nasional dan meraih juara 2 pada lomba hackathon tahun 2020.' }}
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
                                    <h5>{{ $application->cvSubmission->pengalaman_kerja_terakhir ?? 'Senior Web Developer di PT. Tech Indonesia (2020-2024)' }}</h5>
                                    <p>Pengalaman Kerja Terakhir</p>
                                </div>
                            </div>
                            <div class="experience-stats">
                                <div class="stat-box">
                                    <div class="number">{{ $application->cvSubmission->total_pengalaman_kerja ?? '4' }}</div>
                                    <div class="label">Tahun Pengalaman</div>
                                </div>
                            </div>
                            @if($application->cvSubmission->rangkuman_pengalaman_kerja ?? true)
                            <div class="experience-summary">
                                <strong><i class="fas fa-info-circle mr-1"></i> Rangkuman:</strong><br>
                                {{ $application->cvSubmission->rangkuman_pengalaman_kerja ?? 'Bertanggung jawab dalam pengembangan aplikasi web menggunakan Laravel dan React.js. Mengelola tim developer yang terdiri dari 5 orang. Berhasil meningkatkan performa aplikasi hingga 40%. Mengimplementasikan CI/CD pipeline dan best practices dalam pengembangan software.' }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Skills -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-cogs bg-purple"></i> Keahlian (Skills)</h4>
                    </div>
                    <div class="card-body">
                        <div class="skills-container">
                            <!-- Hard Skills -->
                            <div class="skill-category">
                                <h6><i class="fas fa-code"></i> Hard Skills (Technical)</h6>
                                <div class="skill-badges">
                                    @php
                                        $hardskillsList = is_array($application->cvSubmission->hardskills ?? null) 
                                            ? ($application->cvSubmission->hardskills ?? []) 
                                            : explode(',', $application->cvSubmission->hardskills ?? 'PHP, Laravel, MySQL, JavaScript, React.js, Vue.js, Node.js, Git, Docker, AWS');
                                    @endphp
                                    @foreach($hardskillsList as $skill)
                                        <span class="skill-badge hardskill">{{ trim($skill) }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Soft Skills -->
                            <div class="skill-category">
                                <h6><i class="fas fa-users"></i> Soft Skills (Interpersonal)</h6>
                                <div class="skill-badges">
                                    @php
                                        $softskillsList = is_array($application->cvSubmission->softskills ?? null) 
                                            ? ($application->cvSubmission->softskills ?? []) 
                                            : explode(',', $application->cvSubmission->softskills ?? 'Komunikasi, Teamwork, Problem Solving, Leadership, Time Management, Critical Thinking');
                                    @endphp
                                    @foreach($softskillsList as $skill)
                                        <span class="skill-badge softskill">{{ trim($skill) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cover Letter -->
                @if($application->cover_letter ?? true)
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-envelope-open-text bg-warning"></i> Cover Letter</h4>
                    </div>
                    <div class="card-body">
                        <div class="cover-letter-content">
                            <p>{{ $application->cover_letter ?? '' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- CV Score -->
                <div class="cv-score-card mb-4">
                    <h5 class="mb-3"><i class="fas fa-chart-pie mr-2 text-primary"></i> Skor CV</h5>
                    <div class="score-circle">
                        <span class="score-value">{{ $application->cv_score ?? '40' }}%</span>
                    </div>
                    <p class="score-label">Kesesuaian dengan Posisi</p>
                    <p class="score-status">
                        @php $score = $application->cv_score ?? 40; @endphp
                        @if($score >= 80)
                            <i class="fas fa-check-circle"></i> Sangat Baik
                        @elseif($score >= 60)
                            <i class="fas fa-thumbs-up"></i> Baik
                        @else
                            <i class="fas fa-exclamation-circle"></i> Cukup
                        @endif
                    </p>
                </div>

                <!-- Salary Expectation -->
                <div class="salary-card mb-4">
                    <div class="salary-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <p class="salary-label">Ekspektasi Gaji</p>
                    <p class="salary-amount">Rp {{ $application->expected_salary }}</p>
                </div>

                <!-- Documents -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-folder-open bg-info"></i> Dokumen</h4>
                    </div>
                    <div class="card-body">
                        <div class="document-item">
                            <div class="document-icon pdf">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-info">
                                <h6>CV_{{ str_replace(' ', '_', $application->user->name ?? 'Ahmad_Rizky') }}.pdf</h6>
                                <small>2.3 MB • Diupload {{ $application->created_at->format('d M Y') ?? '05 Des 2025' }}</small>
                            </div>
                            <div class="document-actions">
                                <a href="#" title="Download"><i class="fas fa-download"></i></a>
                                <a href="#" title="Lihat"><i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Timeline -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-history bg-purple"></i> Timeline Lamaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item completed">
                                <div class="timeline-date">05 Des 2025, 10:30</div>
                                <div class="timeline-title">Lamaran Dikirim</div>
                                <div class="timeline-desc">CV berhasil diupload dan lamaran terkirim</div>
                            </div>
                            <div class="timeline-item completed">
                                <div class="timeline-date">05 Des 2025, 14:00</div>
                                <div class="timeline-title">CV Diproses</div>
                                <div class="timeline-desc">CV sedang dianalisis oleh sistem</div>
                            </div>
                            <div class="timeline-item active">
                                <div class="timeline-date">06 Des 2025, 09:00</div>
                                <div class="timeline-title">Dalam Review</div>
                                <div class="timeline-desc">Tim HR sedang mereview lamaran</div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-date">-</div>
                                <div class="timeline-title">Interview</div>
                                <div class="timeline-desc">Menunggu jadwal interview</div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-date">-</div>
                                <div class="timeline-title">Keputusan</div>
                                <div class="timeline-desc">Hasil akhir lamaran</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HR Notes -->
                <div class="info-card mt-4">
                    <div class="card-header">
                        <h4><i class="fas fa-sticky-note bg-warning"></i> Catatan HR</h4>
                    </div>
                    <div class="card-body">
                        <div class="notes-section">
                            <textarea placeholder="Tambahkan catatan untuk pelamar ini...">{{ $application->hr_notes ?? 'Kandidat memiliki pengalaman yang sangat relevan. Skill teknis baik, terutama di Laravel dan React. Perlu dijadwalkan interview teknis dengan tim engineering.' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Schedule Interview Modal -->
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-calendar-plus mr-2"></i> Jadwalkan Interview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="interviewForm">
                    <div class="form-group">
                        <label>Tanggal Interview</label>
                        <input type="date" class="form-control" name="interview_date" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu</label>
                        <input type="time" class="form-control" name="interview_time" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe Interview</label>
                        <select class="form-control" name="interview_type">
                            <option value="online">Online (Video Call)</option>
                            <option value="onsite">Onsite (Tatap Muka)</option>
                            <option value="phone">Phone Interview</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="scheduleInterview()">
                    <i class="fas fa-check mr-1"></i> Jadwalkan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-js')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});

function acceptApplicant() {
    Swal.fire({
        title: 'Terima Pelamar?',
        text: 'Apakah Anda yakin ingin menerima pelamar ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Terima!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form or AJAX call here
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pelamar telah diterima.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
}

function rejectApplicant() {
    Swal.fire({
        title: 'Tolak Pelamar?',
        text: 'Apakah Anda yakin ingin menolak pelamar ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-times mr-1"></i> Ya, Tolak!',
        cancelButtonText: 'Batal',
        input: 'textarea',
        inputLabel: 'Alasan penolakan (opsional)',
        inputPlaceholder: 'Masukkan alasan penolakan...'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form or AJAX call here
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pelamar telah ditolak.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
}

function scheduleInterview() {
    const form = $('#interviewForm');
    const date = form.find('[name="interview_date"]').val();
    const time = form.find('[name="interview_time"]').val();
    
    if (!date || !time) {
        Swal.fire({
            title: 'Error!',
            text: 'Silakan isi tanggal dan waktu interview.',
            icon: 'error'
        });
        return;
    }

    // Submit form or AJAX call here
    $('#scheduleInterviewModal').modal('hide');
    
    Swal.fire({
        title: 'Berhasil!',
        text: 'Interview telah dijadwalkan.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        location.reload();
    });
}
</script>
@endpush
