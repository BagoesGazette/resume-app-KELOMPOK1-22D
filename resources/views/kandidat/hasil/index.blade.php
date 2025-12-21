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
    text-decoration: none;
}

.btn-export:hover {
    transform: translateY(-2px);
    text-decoration: none;
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
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
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
                        <div class="stat-number">{{ $totalLamaran }}</div>
                        <div class="stat-label">Total Lamaran</div>
                    </div>
                    <div class="header-stat">
                        <div class="stat-number">{{ $avgScore }}%</div>
                        <div class="stat-label">Rata-rata Skor</div>
                    </div>
                    <div class="header-stat">
                        <div class="stat-number">{{ $bestRank }}</div>
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
                        <div class="stat-value">{{ $totalLamaran }}</div>
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
                        <div class="stat-value">{{ $pendingCount }}</div>
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
                        <div class="stat-value">{{ $acceptedCount }}</div>
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
                        <div class="stat-value">{{ $rejectedCount }}</div>
                        <div class="stat-label">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="card-header">
                <h4><i class="fas fa-list-alt"></i> Daftar Hasil Penilaian</h4>
                @if($results->count() > 0)
                <div class="export-buttons">
                    <a href="{{ route('kandidat.hasil.export.excel') }}" class="btn-export excel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('kandidat.hasil.export.pdf') }}" class="btn-export pdf" target="_blank">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body">
                @if($results->count() > 0)
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
                            @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Skor</label>
                        <select id="filterScore">
                            <option value="">Semua Skor</option>
                            <option value="excellent">Excellent (90-100)</option>
                            <option value="good">Good (80-89)</option>
                            <option value="average">Average (70-79)</option>
                            <option value="poor">Below Average (&lt;70)</option>
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
                            @foreach($results as $index => $result)
                            @php
                                $scoreClass = 'poor';
                                if ($result['score'] >= 90) $scoreClass = 'excellent';
                                elseif ($result['score'] >= 80) $scoreClass = 'good';
                                elseif ($result['score'] >= 70) $scoreClass = 'average';

                                $rankClass = 'rank-normal';
                                if ($result['rank'] == 1) $rankClass = 'rank-1';
                                elseif ($result['rank'] == 2) $rankClass = 'rank-2';
                                elseif ($result['rank'] == 3) $rankClass = 'rank-3';
                            @endphp
                            <tr data-status="{{ $result['status'] }}" 
                                data-category="{{ $result['category'] }}" 
                                data-score="{{ $scoreClass }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="job-info-cell">
                                        <div class="job-logo" style="background: {{ $result['color'] }};">
                                            {{ strtoupper(substr($result['company'], 0, 2)) }}
                                        </div>
                                        <div class="job-details">
                                            <h6>{{ Str::limit($result['position'], 25) }}</h6>
                                            <small><i class="fas fa-building mr-1"></i> {{ $result['company'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $result['date']->format('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($result['score'])
                                    <div class="score-progress-mini">
                                        <div class="progress-header">
                                            <span class="score-value">{{ $result['score'] }}%</span>
                                        </div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-fill {{ $scoreClass }}" style="width: {{ $result['score'] }}%;"></div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted">Belum dinilai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($result['rank'])
                                    <div class="rank-badge {{ $rankClass }}">
                                        #{{ $result['rank'] }}
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
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
                                        <a href="{{ route('lowongan-kerja.show', $result['job_id']) }}" 
                                           class="btn-action view" 
                                           data-toggle="tooltip" 
                                           title="Lihat Detail Lowongan">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($result['status_raw'] == 'submitted')
                                        <button class="btn-action delete" 
                                                data-toggle="tooltip" 
                                                title="Batalkan Lamaran" 
                                                onclick="cancelApplication({{ $result['id'] }})">
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
                @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <h4>Belum Ada Lamaran</h4>
                    <p>Anda belum melamar pekerjaan apapun. Mulai cari lowongan yang sesuai dengan keahlian Anda.</p>
                    <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-primary">
                        <i class="fas fa-search mr-2"></i> Cari Lowongan
                    </a>
                </div>
                @endif
            </div>
        </div>

        @if($results->count() > 0)
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
        @endif
    </div>
</section>
@endsection

@push('custom-js')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    @if($results->count() > 0)
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
        order: [[2, 'desc']],
        columnDefs: [
            { orderable: false, targets: [7] }
        ]
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Custom filter function for status
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var status = $('#filterStatus').val();
        var category = $('#filterCategory').val();
        var score = $('#filterScore').val();
        
        var row = table.row(dataIndex).node();
        var rowStatus = $(row).data('status');
        var rowCategory = $(row).data('category');
        var rowScore = $(row).data('score');

        var statusMatch = !status || rowStatus === status;
        var categoryMatch = !category || rowCategory === category;
        var scoreMatch = !score || rowScore === score;

        return statusMatch && categoryMatch && scoreMatch;
    });

    // Filter events
    $('#filterStatus, #filterCategory, #filterScore').on('change', function() {
        table.draw();
    });

    // Animate progress bars
    setTimeout(function() {
        $('.progress-fill').each(function() {
            var width = $(this).css('width');
            $(this).css('width', '0').animate({width: width}, 800);
        });
    }, 300);
    @endif
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
            $.ajax({
                url: '/kandidat/hasil/' + id + '/cancel',
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat membatalkan lamaran.',
                        icon: 'error'
                    });
                }
            });
        }
    });
}
</script>
@endpush