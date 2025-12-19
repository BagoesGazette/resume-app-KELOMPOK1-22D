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

/* Job Header Card */
.job-header-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.job-header {
    background: var(--primary-gradient);
    padding: 35px;
    color: white;
    position: relative;
    overflow: hidden;
}

.job-header::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.job-header::after {
    content: '';
    position: absolute;
    bottom: -150px;
    left: -50px;
    width: 250px;
    height: 250px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.company-logo {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #667eea;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    position: relative;
    z-index: 1;
}

.job-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 5px;
    position: relative;
    z-index: 1;
}

.company-name {
    font-size: 1rem;
    opacity: 0.95;
    position: relative;
    z-index: 1;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 15px;
    position: relative;
    z-index: 1;
}

.job-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9rem;
    opacity: 0.9;
}

.type-badge {
    padding: 6px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.8rem;
    position: relative;
    z-index: 1;
}

.type-badge.full-time { background: var(--success-gradient); color: white; }
.type-badge.part-time { background: var(--info-gradient); color: white; }
.type-badge.contract { background: var(--warning-gradient); color: #333; }

.status-badge {
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    position: relative;
    z-index: 1;
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.5);
}

.status-badge.open::before {
    content: '';
    width: 8px;
    height: 8px;
    background: #4ade80;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
}

/* Stats Bar */
.stats-bar {
    background: #fff;
    padding: 25px 35px;
}

.stat-box {
    text-align: center;
    padding: 15px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    transition: all 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
}

.stat-box .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 1.3rem;
    color: white;
}

.stat-box .stat-icon.primary { background: var(--primary-gradient); }
.stat-box .stat-icon.success { background: var(--success-gradient); }
.stat-box .stat-icon.warning { background: var(--warning-gradient); }
.stat-box .stat-icon.info { background: var(--info-gradient); }
.stat-box .stat-icon.danger { background: var(--danger-gradient); }

.stat-box .stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #34395e;
}

.stat-box .stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 25px;
    border: none;
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

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.filter-tab {
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
    border: 2px solid #e0e0e0;
    background: white;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-tab:hover {
    border-color: #667eea;
    color: #667eea;
}

.filter-tab.active {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

.filter-tab .count {
    padding: 2px 8px;
    border-radius: 50px;
    font-size: 0.75rem;
    background: rgba(0,0,0,0.1);
}

.filter-tab.active .count {
    background: rgba(255,255,255,0.3);
}

/* Search & Filter Bar */
.search-filter-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    min-width: 250px;
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 12px 20px 12px 45px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.search-box input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.filter-select {
    padding: 12px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 0.9rem;
    min-width: 150px;
    cursor: pointer;
}

.filter-select:focus {
    border-color: #667eea;
    outline: none;
}

/* Applicant Table */
.applicant-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.applicant-table thead th {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    padding: 15px 20px;
    font-weight: 700;
    color: #34395e;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

.applicant-table thead th:first-child {
    border-radius: 10px 0 0 10px;
}

.applicant-table thead th:last-child {
    border-radius: 0 10px 10px 0;
}

.applicant-table tbody tr {
    transition: all 0.3s ease;
}

.applicant-table tbody tr:hover {
    background: #f8f9ff;
    transform: scale(1.01);
}

.applicant-table tbody td {
    padding: 18px 20px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

/* Applicant Info */
.applicant-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.applicant-avatar {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.applicant-avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    color: white;
    flex-shrink: 0;
}

.applicant-details h6 {
    margin: 0 0 3px 0;
    font-weight: 600;
    color: #34395e;
}

.applicant-details small {
    color: #6c757d;
    font-size: 0.8rem;
}

/* Score Badge */
.score-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.85rem;
}

.score-badge.excellent {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.score-badge.good {
    background: linear-gradient(135deg, #cce5ff 0%, #b8daff 100%);
    color: #004085;
}

.score-badge.average {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
    color: #856404;
}

.score-badge.poor {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

/* Status Badge */
.status-pill {
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pill.pending {
    background: #fff3cd;
    color: #856404;
}

.status-pill.review {
    background: #cce5ff;
    color: #004085;
}

.status-pill.interview {
    background: #e2d5f1;
    color: #6f42c1;
}

.status-pill.accepted {
    background: #d4edda;
    color: #155724;
}

.status-pill.rejected {
    background: #f8d7da;
    color: #721c24;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-action.view {
    background: linear-gradient(135deg, #e8f4fd 0%, #d1e9ff 100%);
    color: #0066cc;
}

.btn-action.view:hover {
    background: var(--info-gradient);
    color: white;
    transform: translateY(-2px);
}

.btn-action.accept {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #28a745;
}

.btn-action.accept:hover {
    background: var(--success-gradient);
    color: white;
    transform: translateY(-2px);
}

.btn-action.reject {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #dc3545;
}

.btn-action.reject:hover {
    background: var(--danger-gradient);
    color: white;
    transform: translateY(-2px);
}

.btn-action.download {
    background: linear-gradient(135deg, #e2d5f1 0%, #d4c5e8 100%);
    color: #6f42c1;
}

.btn-action.download:hover {
    background: var(--purple-gradient);
    color: white;
    transform: translateY(-2px);
}

/* Applicant Card (Mobile/Grid View) */
.applicant-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
    margin-bottom: 20px;
}

.applicant-card:hover {
    border-color: #667eea;
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.15);
}

.applicant-card .card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.applicant-card .applicant-main {
    display: flex;
    gap: 15px;
}

.applicant-card .avatar-large {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
    color: white;
}

.applicant-card .applicant-name {
    font-weight: 700;
    color: #34395e;
    font-size: 1.1rem;
    margin-bottom: 3px;
}

.applicant-card .applicant-email {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 5px;
}

.applicant-card .applicant-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin: 15px 0;
    padding: 15px 0;
    border-top: 1px dashed #e0e0e0;
    border-bottom: 1px dashed #e0e0e0;
}

.applicant-card .meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: #6c757d;
}

.applicant-card .meta-item i {
    color: #667eea;
}

.applicant-card .card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
}

.applicant-card .score-display {
    display: flex;
    align-items: center;
    gap: 10px;
}

.applicant-card .score-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1rem;
    color: white;
}

.applicant-card .score-circle.high { background: var(--success-gradient); }
.applicant-card .score-circle.medium { background: var(--warning-gradient); color: #333; }
.applicant-card .score-circle.low { background: var(--danger-gradient); }

/* Quick Info Sidebar */
.quick-info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    padding: 25px;
    margin-bottom: 25px;
}

.quick-info-card h5 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.quick-info-card h5 i {
    color: #667eea;
}

.quick-info-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px dashed #e9ecef;
}

.quick-info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.quick-info-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%);
    color: #667eea;
    font-size: 0.9rem;
}

.quick-info-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.quick-info-value {
    font-weight: 600;
    color: #34395e;
    font-size: 0.95rem;
}

/* Deadline Card */
.deadline-card {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
    border: 2px solid #ffcccc;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    margin-bottom: 25px;
}

.deadline-card .deadline-icon {
    font-size: 2.5rem;
    color: #dc3545;
    margin-bottom: 10px;
}

.deadline-card .deadline-label {
    font-size: 0.85rem;
    color: #666;
}

.deadline-card .deadline-date {
    font-size: 1.2rem;
    font-weight: 800;
    color: #dc3545;
}

.deadline-card .deadline-remaining {
    display: inline-block;
    margin-top: 10px;
    padding: 5px 15px;
    background: #fff3cd;
    color: #856404;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.pagination-info {
    color: #6c757d;
    font-size: 0.9rem;
}

.pagination-buttons {
    display: flex;
    gap: 5px;
}

.pagination-buttons .btn {
    padding: 8px 15px;
    border-radius: 8px;
    font-weight: 500;
}

/* Export Buttons */
.export-buttons {
    display: flex;
    gap: 10px;
}

.btn-export {
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}

.btn-export:hover {
    transform: translateY(-2px);
}

.btn-export.perhitungan {
    background: linear-gradient(135deg, #e7ecff 0%, #d6ddff 100%);
    color: #3f4ad8;
    border: none;
}



.btn-export.perhitungan:hover {
    background: var(--primary-gradient);
    color: white;
}

.btn-export.excel {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border: none;
}

.btn-export.excel:hover {
    background: var(--success-gradient);
    color: white;
}

.btn-export.pdf {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border: none;
}

.btn-export.pdf:hover {
    background: var(--danger-gradient);
    color: white;
}

/* Responsive */
@media (max-width: 992px) {
    .applicant-table-wrapper {
        overflow-x: auto;
    }

    .applicant-table {
        min-width: 800px;
    }
}

@media (max-width: 768px) {
    .job-header {
        padding: 25px;
        text-align: center;
    }

    .job-header .row {
        flex-direction: column;
        gap: 15px;
    }

    .company-logo {
        margin: 0 auto;
    }

    .search-filter-bar {
        flex-direction: column;
    }

    .search-box {
        min-width: 100%;
    }

    .filter-tabs {
        justify-content: center;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.applicant-card, .applicant-table tbody tr {
    animation: fadeIn 0.4s ease forwards;
}
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Lowongan & Pelamar</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('lowongan-kerja.index') }}">Lowongan Kerja</a></div>
            <div class="breadcrumb-item">Detail & Pelamar</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Job Header Card -->
        <div class="card job-header-card">
            <div class="job-header">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="company-logo">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center flex-wrap mb-2" style="gap: 10px;">
                            <h1 class="job-title mb-0">{{ $detail->judul }}</h1>
                            <span class="type-badge full-time">{{ ucfirst($detail->tipe) }}</span>
                        </div>
                        <p class="company-name mb-0"><i class="fas fa-building mr-2"></i>{{ $detail->perusahaan }}</p>
                        <div class="job-meta">
                            <div class="job-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $detail->lokasi }}</span>
                            </div>
                            <div class="job-meta-item">
                                <i class="fas fa-tags"></i>
                                <span>{{ $detail->category }}</span>
                            </div>
                            <div class="job-meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>Diposting: {{ $detail->tanggal_tutup_indo  }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="status-badge open">Open</span>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="row">
                    <div class="col-6 col-md-2 mb-3 mb-md-0">
                        <div class="stat-box">
                            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
                            <div class="stat-value">{{ $detail->apply->count()  }}</div>
                            <div class="stat-label">Total Pelamar</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3 mb-md-0">
                        <div class="stat-box">
                            <div class="stat-icon warning"><i class="fas fa-clock"></i></div>
                            <div class="stat-value">{{ $detail->apply->where('status', 'submitted')->count()  }}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3 mb-md-0">
                        <div class="stat-box">
                            <div class="stat-icon info"><i class="fas fa-search"></i></div>
                            <div class="stat-value">{{ $detail->apply->where('status', 'interview')->count() }}</div>
                            <div class="stat-label">Review</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3 mb-md-0">
                        <div class="stat-box">
                            <div class="stat-icon" style="background: var(--purple-gradient);"><i class="fas fa-calendar-check"></i></div>
                            <div class="stat-value">{{ $detail->apply->where('status', 'interview')->count() }}</div>
                            <div class="stat-label">Interview</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="stat-box">
                            <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
                            <div class="stat-value">{{ $detail->apply->where('status', 'accepted')->count() }}</div>
                            <div class="stat-label">Diterima</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="stat-box">
                            <div class="stat-icon danger"><i class="fas fa-times-circle"></i></div>
                            <div class="stat-value">{{ $detail->apply->where('status', 'rejected')->count() }}</div>
                            <div class="stat-label">Ditolak</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Applicants Card -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-user-friends"></i> Daftar Pelamar</h4>
                        <div class="export-buttons">
                            <button class="btn-export perhitungan">
                                <i class="fas fa-chart-line"></i> Perhitungan
                            </button>
                            <button class="btn-export excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button class="btn-export pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Tabs -->
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="all">
                                Semua <span class="count">{{ $detail->apply->count() }}</span>
                            </button>
                            <button class="filter-tab" data-filter="pending">
                                <i class="fas fa-clock"></i> Pending <span class="count">{{ $detail->apply->where('status', 'submitted')->count() }}</span>
                            </button>
                            <button class="filter-tab" data-filter="review">
                                <i class="fas fa-search"></i> Review <span class="count">{{ $detail->apply->where('status', 'review')->count()  }}</span>
                            </button>
                            <button class="filter-tab" data-filter="interview">
                                <i class="fas fa-calendar-check"></i> Interview <span class="count">{{ $detail->apply->where('status', 'interview')->count()  }}</span>
                            </button>
                            <button class="filter-tab" data-filter="accepted">
                                <i class="fas fa-check"></i> Diterima <span class="count">{{ $detail->apply->where('status', 'accepted')->count() }}</span>
                            </button>
                            <button class="filter-tab" data-filter="rejected">
                                <i class="fas fa-times"></i> Ditolak <span class="count">{{ $detail->apply->where('status', 'rejected')->count()  }}</span>
                            </button>
                        </div>

                        <!-- Search & Filter -->
                        <div class="search-filter-bar">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Cari nama pelamar..." id="searchInput">
                            </div>
                            <select class="filter-select" id="sortBy">
                                <option value="">Urutkan</option>
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="score_high">Skor Tertinggi</option>
                                <option value="score_low">Skor Terendah</option>
                                <option value="name_asc">Nama A-Z</option>
                                <option value="name_desc">Nama Z-A</option>
                            </select>
                        </div>

                        @php
                            $perPage = 10;
                            $page    = request()->get('page', 1);
                            $total   = $detail->apply->count();
                            $offset  = ($page - 1) * $perPage;

                            // Ambil data sesuai halaman
                            $pelamars = $detail->apply->slice($offset, $perPage);
                            $lastPage = (int) ceil($total / $perPage);
                        @endphp

                        <!-- Applicant Table -->
                        <div class="applicant-table-wrapper">
                            <table class="applicant-table">
                                <thead>
                                    <tr>
                                        <th>Pelamar</th>
                                        <th>Tanggal Melamar</th>
                                        <th>Pengalaman</th>
                                        <th>Skor CV</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($pelamars as $index => $pelamar)
                                    <tr data-status="{{ $pelamar->status }}" style="animation-delay: {{ $index * 0.05 }}s">
                                        <td>
                                            <div class="applicant-info">
                                                <div class="applicant-avatar-placeholder" style="background: #667eea">
                                                    {{ strtoupper(substr($pelamar->user->name, 0, 2)) }}
                                                </div>
                                                <div class="applicant-details">
                                                    <h6>{{ $pelamar->user->name }}</h6>
                                                    <small><i class="fas fa-envelope mr-1"></i> {{ $pelamar->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $pelamar->created_at->timezone('Asia/Jakarta')->locale('id')->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span>
                                                <i class="fas fa-briefcase mr-1 text-primary"></i>
                                                {{ $pelamar->cvSubmission->total_pengalaman }} Tahun
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $scoreClass = $pelamar->score >= 90 ? 'excellent' :
                                                            ($pelamar->score >= 80 ? 'good' :
                                                            ($pelamar->score >= 70 ? 'average' : 'poor'));
                                            @endphp
                                            <span class="score-badge {{ $scoreClass }}">
                                                <i class="fas fa-star"></i> {{ $pelamar->score }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-pill text-white" style="background-color: {{ $pelamar->status_color }}">
                                                <i class="{{ $pelamar->status_icon }} mr-1"></i>
                                                {{ $pelamar->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('pelamar.show', $pelamar->id) }}" class="btn-action view" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn-action download" title="Download CV">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                @if(!in_array($pelamar->status, ['accepted', 'rejected']))
                                                    <button class="btn-action accept" title="Terima">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn-action reject" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper">
                            <div class="pagination-info">
                                Menampilkan {{ $total ? $offset + 1 : 0 }}–{{ min($offset + $perPage, $total) }}
                                dari {{ $total }} pelamar
                            </div>

                            <div class="pagination-buttons">
                                {{-- Previous --}}
                                <a class="btn btn-outline-primary {{ $page <= 1 ? 'disabled' : '' }}"
                                href="{{ $page > 1 ? request()->fullUrlWithQuery(['page' => $page - 1]) : '#' }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>

                                {{-- Page Number --}}
                                @for ($i = 1; $i <= $lastPage; $i++)
                                    <a class="btn {{ $i == $page ? 'btn-primary' : 'btn-outline-primary' }}"
                                    href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">
                                        {{ $i }}
                                    </a>
                                @endfor

                                {{-- Next --}}
                                <a class="btn btn-outline-primary {{ $page >= $lastPage ? 'disabled' : '' }}"
                                href="{{ $page < $lastPage ? request()->fullUrlWithQuery(['page' => $page + 1]) : '#' }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Deadline Card -->
                <div class="deadline-card">
                    <div class="deadline-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <div class="deadline-label">Batas Akhir Lamaran</div>
                    <div class="deadline-date">
                        {{ isset($lowongan->tanggal_tutup) ? \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d F Y') : '25 Desember 2025' }}
                    </div>
                    <div class="deadline-remaining">
                        <i class="fas fa-exclamation-triangle mr-1"></i> 12 hari lagi
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="quick-info-card">
                    <h5><i class="fas fa-info-circle"></i> Info Lowongan</h5>
                    <div class="quick-info-item">
                        <div class="quick-info-icon"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <div class="quick-info-label">Tipe</div>
                            <div class="quick-info-value">{{ ucfirst($detail->tipe) }}</div>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <div class="quick-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="quick-info-label">Lokasi</div>
                            <div class="quick-info-value">{{ $detail->lokasi ?? 'Jakarta Selatan' }}</div>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <div class="quick-info-icon"><i class="fas fa-tags"></i></div>
                        <div>
                            <div class="quick-info-label">Kategori</div>
                            <div class="quick-info-value">{{ $detail->category ?? 'IT & Software' }}</div>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <div class="quick-info-icon"><i class="fas fa-eye"></i></div>
                        <div>
                            <div class="quick-info-label">Dilihat</div>
                            <div class="quick-info-value">{{ number_format($totalViews ?? 1234) }}x</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-info-card">
                    <h5><i class="fas fa-bolt"></i> Aksi Cepat</h5>
                    <a href="{{ route('job.edit', $detail->id) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit mr-2"></i> Edit Lowongan
                    </a>
                    @if ($detail->status === 'open')
                        <a href="{{ route('job.close', $detail->id) }}" class="btn btn-outline-danger btn-block">
                            <i class="fas fa-times-circle mr-2"></i> Tutup Lowongan
                        </a>
                    @endif
                    
                </div>

                <!-- Top Candidates -->
                <div class="quick-info-card">
                    <h5><i class="fas fa-trophy"></i> Kandidat Terbaik</h5>
                    @php
                        $topCandidates = [
                            ['name' => 'Budi Santoso', 'score' => 95, 'color' => '#f7971e'],
                            ['name' => 'Ahmad Rizky', 'score' => 92, 'color' => '#667eea'],
                            ['name' => 'Siti Nurhaliza', 'score' => 88, 'color' => '#11998e'],
                        ];
                    @endphp
                    @foreach($topCandidates as $index => $candidate)
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3" style="width: 30px; height: 30px; border-radius: 50%; background: {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : '#cd7f32') }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.8rem;">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight: 600; color: #34395e; font-size: 0.9rem;">{{ $candidate['name'] }}</div>
                            <small class="text-muted">Skor: {{ $candidate['score'] }}%</small>
                        </div>
                        <div class="score-badge excellent" style="padding: 4px 10px; font-size: 0.75rem;">
                            {{ $candidate['score'] }}%
                        </div>
                    </div>
                    @endforeach
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

    // Filter tabs
    $('.filter-tab').on('click', function() {
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        
        const filter = $(this).data('filter');
        
        if (filter === 'all') {
            $('.applicant-table tbody tr').show();
        } else {
            $('.applicant-table tbody tr').hide();
            $('.applicant-table tbody tr[data-status="' + filter + '"]').show();
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        const searchValue = $(this).val().toLowerCase();
        
        $('.applicant-table tbody tr').each(function() {
            const name = $(this).find('.applicant-details h6').text().toLowerCase();
            const email = $(this).find('.applicant-details small').text().toLowerCase();
            
            if (name.includes(searchValue) || email.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Action buttons
    $('.btn-action.view').on('click', function() {
        const name = $(this).closest('tr').find('.applicant-details h6').text();
        window.location.href = '/pelamar/' + encodeURIComponent(name);
    });

    $('.btn-action.accept').on('click', function() {
        const name = $(this).closest('tr').find('.applicant-details h6').text();
        Swal.fire({
            title: 'Terima Pelamar?',
            text: 'Apakah Anda yakin ingin menerima ' + name + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Terima!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Berhasil!', 'Pelamar telah diterima.', 'success');
            }
        });
    });

    $('.btn-action.reject').on('click', function() {
        const name = $(this).closest('tr').find('.applicant-details h6').text();
        Swal.fire({
            title: 'Tolak Pelamar?',
            text: 'Apakah Anda yakin ingin menolak ' + name + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Berhasil!', 'Pelamar telah ditolak.', 'success');
            }
        });
    });

    // Export buttons
    $('.btn-export.excel').on('click', function() {
        Swal.fire({
            title: 'Export Excel',
            text: 'Data pelamar akan diexport ke file Excel',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Export',
            cancelButtonText: 'Batal'
        });
    });

    $('.btn-export.pdf').on('click', function() {
        Swal.fire({
            title: 'Export PDF',
            text: 'Data pelamar akan diexport ke file PDF',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Export',
            cancelButtonText: 'Batal'
        });
    });
});
</script>
@endpush