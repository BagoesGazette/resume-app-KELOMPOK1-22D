@extends('layouts.app')

@push('custom-css')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    --info-gradient: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
    --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    --purple-gradient: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
}

/* Page Header */
.page-header-banner {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 35px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
}

.page-header-banner::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 350px;
    height: 350px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.page-header-banner::after {
    content: '';
    position: absolute;
    bottom: -120px;
    left: 50px;
    width: 250px;
    height: 250px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.page-header-content {
    position: relative;
    z-index: 1;
}

.page-header-banner h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.page-header-banner p {
    opacity: 0.9;
    margin-bottom: 20px;
    font-size: 1rem;
}

.header-stats {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.header-stat {
    text-align: center;
    padding: 15px 25px;
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.header-stat .stat-number {
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1;
}

.header-stat .stat-label {
    font-size: 0.85rem;
    opacity: 0.9;
    margin-top: 5px;
}

/* Stats Cards */
.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.stat-card .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-card .stat-icon.primary { background: var(--primary-gradient); }
.stat-card .stat-icon.success { background: var(--success-gradient); }
.stat-card .stat-icon.warning { background: var(--warning-gradient); }
.stat-card .stat-icon.danger { background: var(--danger-gradient); }
.stat-card .stat-icon.info { background: var(--info-gradient); }

.stat-card .stat-content .stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #34395e;
    line-height: 1;
}

.stat-card .stat-content .stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-top: 5px;
}

/* Table Card */
.table-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

.table-card .card-header {
    background: transparent;
    border-bottom: 2px solid #f4f6f9;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.table-card .card-header h4 {
    margin: 0;
    font-weight: 700;
    color: #34395e;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.1rem;
}

.table-card .card-header h4 i {
    color: #667eea;
}

.table-card .card-body {
    padding: 25px;
}

/* Filter Section */
.filter-section {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 20px;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    border-radius: 15px;
}

.filter-group {
    flex: 1;
    min-width: 180px;
}

.filter-group label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-group select,
.filter-group input {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: white;
}

.filter-group select:focus,
.filter-group input:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Custom DataTable Styling */
.dataTables_wrapper {
    padding: 0;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 20px;
}

.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 8px 15px;
    transition: all 0.3s ease;
}

.dataTables_wrapper .dataTables_length select:focus,
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    margin: 0 3px;
    border: none !important;
    background: #f0f0f0 !important;
    color: #666 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--primary-gradient) !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--primary-gradient) !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_info {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Table Styling */
#resultsTable {
    width: 100% !important;
    border-collapse: separate;
    border-spacing: 0 8px;
}

#resultsTable thead th {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    padding: 15px 20px;
    font-weight: 700;
    color: #34395e;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    white-space: nowrap;
}

#resultsTable thead th:first-child {
    border-radius: 12px 0 0 12px;
}

#resultsTable thead th:last-child {
    border-radius: 0 12px 12px 0;
}

#resultsTable tbody tr {
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

#resultsTable tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

#resultsTable tbody td {
    padding: 18px 20px;
    vertical-align: middle;
    border: none;
    background: white;
}

#resultsTable tbody td:first-child {
    border-radius: 12px 0 0 12px;
}

#resultsTable tbody td:last-child {
    border-radius: 0 12px 12px 0;
}

/* Job Info Cell */
.job-info-cell {
    display: flex;
    align-items: center;
    gap: 15px;
}

.job-logo {
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

.job-details h6 {
    margin: 0 0 3px 0;
    font-weight: 600;
    color: #34395e;
    font-size: 0.95rem;
}

.job-details small {
    color: #6c757d;
    font-size: 0.8rem;
}

/* Rank Badge */
.rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    font-weight: 800;
    font-size: 1rem;
    color: white;
}

.rank-badge.rank-1 {
    background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    box-shadow: 0 5px 15px rgba(247, 151, 30, 0.3);
}

.rank-badge.rank-2 {
    background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
    box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
}

.rank-badge.rank-3 {
    background: linear-gradient(135deg, #d35400 0%, #e67e22 100%);
    box-shadow: 0 5px 15px rgba(211, 84, 0, 0.3);
}

.rank-badge.rank-normal {
    background: #e9ecef;
    color: #666;
}

/* Score Badge */
.score-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
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
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.pending {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
    color: #856404;
}

.status-badge.review {
    background: linear-gradient(135deg, #cce5ff 0%, #b8daff 100%);
    color: #004085;
}

.status-badge.interview {
    background: linear-gradient(135deg, #e2d5f1 0%, #c9b3e6 100%);
    color: #6f42c1;
}

.status-badge.accepted {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.status-badge.rejected {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 10px;
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

.btn-action.ranking {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
    color: #856404;
}

.btn-action.ranking:hover {
    background: var(--warning-gradient);
    color: white;
    transform: translateY(-2px);
}

.btn-action.delete {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #dc3545;
}

.btn-action.delete:hover {
    background: var(--danger-gradient);
    color: white;
    transform: translateY(-2px);
}

/* Score Progress Mini */
.score-progress-mini {
    width: 100%;
    max-width: 120px;
}

.score-progress-mini .progress-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 0.85rem;
}

.score-progress-mini .progress-header .score-value {
    font-weight: 700;
    color: #667eea;
}

.score-progress-mini .progress-bar-wrapper {
    height: 8px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.score-progress-mini .progress-fill {
    height: 100%;
    border-radius: 10px;
}

.score-progress-mini .progress-fill.excellent { background: var(--success-gradient); }
.score-progress-mini .progress-fill.good { background: var(--info-gradient); }
.score-progress-mini .progress-fill.average { background: var(--warning-gradient); }
.score-progress-mini .progress-fill.poor { background: var(--danger-gradient); }

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 5rem;
    color: #e0e0e0;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #34395e;
    font-weight: 700;
    margin-bottom: 10px;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 20px;
}

/* Export Buttons */
.export-buttons {
    display: flex;
    gap: 10px;
}

.btn-export {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
}

.btn-export:hover {
    transform: translateY(-2px);
}

.btn-export.excel {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.btn-export.excel:hover {
    background: var(--success-gradient);
    color: white;
}

.btn-export.pdf {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.btn-export.pdf:hover {
    background: var(--danger-gradient);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header-banner {
        padding: 25px;
        text-align: center;
    }

    .page-header-banner h2 {
        font-size: 1.4rem;
    }

    .header-stats {
        justify-content: center;
    }

    .filter-section {
        flex-direction: column;
    }

    .filter-group {
        min-width: 100%;
    }

    .table-card .card-header {
        flex-direction: column;
        text-align: center;
    }

    .job-info-cell {
        flex-direction: column;
        text-align: center;
    }

    .action-buttons {
        justify-content: center;
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

.stat-card, .table-card {
    animation: fadeInUp 0.5s ease forwards;
}
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Hasil Penilaian Lamaran</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Hasil Penilaian</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Page Header Banner -->
        <div class="page-header-banner">
            <div class="page-header-content">
                <h2><i class="fas fa-chart-line mr-2"></i> Riwayat Hasil Penilaian Lamaran</h2>
                <p>Lihat semua hasil penilaian dari lamaran kerja yang pernah Anda kirimkan beserta ranking dan skor CV Anda.</p>
                <div class="header-stats">
                    <div class="header-stat">
                        <div class="stat-number">{{ $totalLamaran ?? 12 }}</div>
                        <div class="stat-label">Total Lamaran</div>
                    </div>
                    <div class="header-stat">
                        <div class="stat-number">{{ $avgScore ?? 82 }}%</div>
                        <div class="stat-label">Rata-rata Skor</div>
                    </div>
                    <div class="header-stat">
                        <div class="stat-number">{{ $bestRank ?? 2 }}</div>
                        <div class="stat-label">Ranking Terbaik</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $totalLamaran ?? 12 }}</div>
                        <div class="stat-label">Total Lamaran</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $pendingCount ?? 4 }}</div>
                        <div class="stat-label">Dalam Proses</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $acceptedCount ?? 2 }}</div>
                        <div class="stat-label">Diterima</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card">
                    <div class="stat-icon danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $rejectedCount ?? 3 }}</div>
                        <div class="stat-label">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="card-header">
                <h4><i class="fas fa-list-alt"></i> Daftar Hasil Penilaian</h4>
                <div class="export-buttons">
                    <button class="btn-export excel" id="exportExcel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button class="btn-export pdf" id="exportPdf">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-group">
                        <label>Status</label>
                        <select id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="review">Review</option>
                            <option value="interview">Interview</option>
                            <option value="accepted">Diterima</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Kategori</label>
                        <select id="filterCategory">
                            <option value="">Semua Kategori</option>
                            <option value="IT & Software">IT & Software</option>
                            <option value="Design">Design</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Finance">Finance</option>
                            <option value="HR">Human Resources</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Periode</label>
                        <select id="filterPeriod">
                            <option value="">Semua Waktu</option>
                            <option value="7">7 Hari Terakhir</option>
                            <option value="30">30 Hari Terakhir</option>
                            <option value="90">3 Bulan Terakhir</option>
                            <option value="365">1 Tahun Terakhir</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Skor</label>
                        <select id="filterScore">
                            <option value="">Semua Skor</option>
                            <option value="90-100">Excellent (90-100)</option>
                            <option value="80-89">Good (80-89)</option>
                            <option value="70-79">Average (70-79)</option>
                            <option value="0-69">Below Average (<70)</option>
                        </select>
                    </div>
                </div>

                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="resultsTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Posisi & Perusahaan</th>
                                <th>Tanggal Melamar</th>
                                <th>Skor CV</th>
                                <th>Ranking</th>
                                <th>Total Pelamar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $results = [
                                    [
                                        'id' => 1,
                                        'position' => 'Senior Frontend Developer',
                                        'company' => 'PT. Teknologi Nusantara',
                                        'color' => '#667eea',
                                        'date' => '2025-12-05',
                                        'score' => 85,
                                        'rank' => 3,
                                        'total_applicants' => 48,
                                        'status' => 'review',
                                        'category' => 'IT & Software'
                                    ],
                                    [
                                        'id' => 2,
                                        'position' => 'UI/UX Designer',
                                        'company' => 'PT. Digital Creative',
                                        'color' => '#11998e',
                                        'date' => '2025-12-01',
                                        'score' => 92,
                                        'rank' => 2,
                                        'total_applicants' => 35,
                                        'status' => 'interview',
                                        'category' => 'Design'
                                    ],
                                    [
                                        'id' => 3,
                                        'position' => 'Full Stack Developer',
                                        'company' => 'PT. Startup Maju',
                                        'color' => '#f7971e',
                                        'date' => '2025-11-28',
                                        'score' => 78,
                                        'rank' => 8,
                                        'total_applicants' => 52,
                                        'status' => 'rejected',
                                        'category' => 'IT & Software'
                                    ],
                                    [
                                        'id' => 4,
                                        'position' => 'Backend Developer',
                                        'company' => 'PT. Tech Solutions',
                                        'color' => '#eb3349',
                                        'date' => '2025-11-25',
                                        'score' => 95,
                                        'rank' => 1,
                                        'total_applicants' => 40,
                                        'status' => 'accepted',
                                        'category' => 'IT & Software'
                                    ],
                                    [
                                        'id' => 5,
                                        'position' => 'Product Designer',
                                        'company' => 'PT. Innovation Hub',
                                        'color' => '#00c6fb',
                                        'date' => '2025-11-20',
                                        'score' => 88,
                                        'rank' => 4,
                                        'total_applicants' => 28,
                                        'status' => 'interview',
                                        'category' => 'Design'
                                    ],
                                    [
                                        'id' => 6,
                                        'position' => 'Software Engineer',
                                        'company' => 'PT. Global Tech',
                                        'color' => '#a855f7',
                                        'date' => '2025-11-15',
                                        'score' => 72,
                                        'rank' => 15,
                                        'total_applicants' => 65,
                                        'status' => 'rejected',
                                        'category' => 'IT & Software'
                                    ],
                                    [
                                        'id' => 7,
                                        'position' => 'Mobile Developer',
                                        'company' => 'PT. App Indonesia',
                                        'color' => '#667eea',
                                        'date' => '2025-11-10',
                                        'score' => 82,
                                        'rank' => 5,
                                        'total_applicants' => 30,
                                        'status' => 'pending',
                                        'category' => 'IT & Software'
                                    ],
                                    [
                                        'id' => 8,
                                        'position' => 'Data Analyst',
                                        'company' => 'PT. Data Solutions',
                                        'color' => '#11998e',
                                        'date' => '2025-11-05',
                                        'score' => 90,
                                        'rank' => 2,
                                        'total_applicants' => 22,
                                        'status' => 'accepted',
                                        'category' => 'IT & Software'
                                    ],
                                    [
                                        'id' => 9,
                                        'position' => 'DevOps Engineer',
                                        'company' => 'PT. Cloud Indonesia',
                                        'color' => '#f7971e',
                                        'date' => '2025-10-28',
                                        'score' => 68,
                                        'rank' => 20,
                                        'total_applicants' => 45,
                                        'status' => 'rejected',
                                        'category' => 'IT & Software'
                                    ],
                                    [
                                        'id' => 10,
                                        'position' => 'QA Engineer',
                                        'company' => 'PT. Quality Tech',
                                        'color' => '#eb3349',
                                        'date' => '2025-10-20',
                                        'score' => 85,
                                        'rank' => 3,
                                        'total_applicants' => 18,
                                        'status' => 'review',
                                        'category' => 'IT & Software'
                                    ],
                                    [
                                        'id' => 11,
                                        'position' => 'Graphic Designer',
                                        'company' => 'PT. Creative Studio',
                                        'color' => '#00c6fb',
                                        'date' => '2025-10-15',
                                        'score' => 76,
                                        'rank' => 7,
                                        'total_applicants' => 25,
                                        'status' => 'pending',
                                        'category' => 'Design'
                                    ],
                                    [
                                        'id' => 12,
                                        'position' => 'System Analyst',
                                        'company' => 'PT. Enterprise Solutions',
                                        'color' => '#a855f7',
                                        'date' => '2025-10-10',
                                        'score' => 88,
                                        'rank' => 4,
                                        'total_applicants' => 32,
                                        'status' => 'pending',
                                        'category' => 'IT & Software'
                                    ],
                                ];
                            @endphp

                            @foreach($results as $index => $result)
                            <tr data-status="{{ $result['status'] }}" data-category="{{ $result['category'] }}" data-score="{{ $result['score'] }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="job-info-cell">
                                        <div class="job-logo" style="background: {{ $result['color'] }};">
                                            {{ strtoupper(substr($result['company'], 4, 2)) }}
                                        </div>
                                        <div class="job-details">
                                            <h6>{{ $result['position'] }}</h6>
                                            <small><i class="fas fa-building mr-1"></i> {{ $result['company'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($result['date'])->format('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="score-progress-mini">
                                        <div class="progress-header">
                                            <span class="score-value">{{ $result['score'] }}%</span>
                                        </div>
                                        <div class="progress-bar-wrapper">
                                            @php
                                                $scoreClass = $result['score'] >= 90 ? 'excellent' : ($result['score'] >= 80 ? 'good' : ($result['score'] >= 70 ? 'average' : 'poor'));
                                            @endphp
                                            <div class="progress-fill {{ $scoreClass }}" style="width: {{ $result['score'] }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="rank-badge {{ $result['rank'] == 1 ? 'rank-1' : ($result['rank'] == 2 ? 'rank-2' : ($result['rank'] == 3 ? 'rank-3' : 'rank-normal')) }}">
                                        #{{ $result['rank'] }}
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="fas fa-users mr-1"></i> {{ $result['total_applicants'] }} orang
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $result['status'] }}">
                                        @if($result['status'] == 'pending')
                                            <i class="fas fa-clock"></i> Pending
                                        @elseif($result['status'] == 'review')
                                            <i class="fas fa-search"></i> Review
                                        @elseif($result['status'] == 'interview')
                                            <i class="fas fa-calendar-check"></i> Interview
                                        @elseif($result['status'] == 'accepted')
                                            <i class="fas fa-check"></i> Diterima
                                        @elseif($result['status'] == 'rejected')
                                            <i class="fas fa-times"></i> Ditolak
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="" class="btn-action view" data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="" class="btn-action ranking" data-toggle="tooltip" title="Lihat Ranking">
                                            <i class="fas fa-trophy"></i>
                                        </a>
                                        @if($result['status'] == 'pending')
                                        <button class="btn-action delete" data-toggle="tooltip" title="Batalkan Lamaran" onclick="cancelApplication({{ $result['id'] }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-light" style="border-radius: 15px; border: 2px solid #e9ecef;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-2"><i class="fas fa-info-circle mr-2 text-primary"></i> Keterangan Skor</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <span><span class="badge" style="background: var(--success-gradient); color: white;">90-100</span> Excellent</span>
                                <span><span class="badge" style="background: var(--info-gradient); color: white;">80-89</span> Good</span>
                                <span><span class="badge" style="background: var(--warning-gradient); color: #333;">70-79</span> Average</span>
                                <span><span class="badge" style="background: var(--danger-gradient); color: white;">&lt;70</span> Below Average</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2"><i class="fas fa-medal mr-2 text-warning"></i> Keterangan Ranking</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <span><span class="badge" style="background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: white;">🥇</span> Ranking 1</span>
                                <span><span class="badge" style="background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%); color: white;">🥈</span> Ranking 2</span>
                                <span><span class="badge" style="background: linear-gradient(135deg, #d35400 0%, #e67e22 100%); color: white;">🥉</span> Ranking 3</span>
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
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<!-- Export Libraries -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#resultsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "<i class='fas fa-chevron-right'></i>",
                previous: "<i class='fas fa-chevron-left'></i>"
            }
        },
        order: [[2, 'desc']], // Sort by date descending
        columnDefs: [
            { orderable: false, targets: [7] } // Disable sorting on action column
        ],
        dom: '<"top"lf>rt<"bottom"ip><"clear">',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn-export excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn-export pdf',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }
        ]
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Filter by status
    $('#filterStatus').on('change', function() {
        var status = $(this).val();
        if (status) {
            table.column(6).search(status).draw();
        } else {
            table.column(6).search('').draw();
        }
    });

    // Filter by category
    $('#filterCategory').on('change', function() {
        var category = $(this).val();
        if (category) {
            table.column(1).search(category).draw();
        } else {
            table.column(1).search('').draw();
        }
    });

    // Filter by period
    $('#filterPeriod').on('change', function() {
        var days = $(this).val();
        // Custom filtering logic for period would go here
        // For now, just redraw the table
        table.draw();
    });

    // Filter by score
    $('#filterScore').on('change', function() {
        var scoreRange = $(this).val();
        // Custom filtering logic for score range would go here
        table.draw();
    });

    // Export Excel button
    $('#exportExcel').on('click', function() {
        // Using DataTable export
        Swal.fire({
            title: 'Export Excel',
            text: 'Data akan diexport ke file Excel',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Export',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // Trigger export
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'File Excel berhasil diunduh',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Export PDF button
    $('#exportPdf').on('click', function() {
        Swal.fire({
            title: 'Export PDF',
            text: 'Data akan diexport ke file PDF',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Export',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                // Trigger export
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'File PDF berhasil diunduh',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Animate progress bars
    setTimeout(function() {
        $('.progress-fill').each(function() {
            var width = $(this).css('width');
            $(this).css('width', '0').animate({width: width}, 800);
        });
    }, 300);
});

// Cancel Application Function
function cancelApplication(id) {
    Swal.fire({
        title: 'Batalkan Lamaran?',
        text: 'Apakah Anda yakin ingin membatalkan lamaran ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Batalkan!',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX call to cancel application would go here
            Swal.fire({
                title: 'Berhasil!',
                text: 'Lamaran telah dibatalkan.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
}
</script>
@endpush