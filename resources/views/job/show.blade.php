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

.btn-action.interview {
    background: linear-gradient(135deg, #e2d5f1 0%, #d4c5e8 100%);
    color: #6f42c1;
}

.btn-action.interview:hover {
    background: var(--purple-gradient);
    color: white;
    transform: translateY(-2px);
}

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
    text-decoration-line: none;
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

/* Modal Custom Styles */
.modal-custom .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}

.modal-custom .modal-header {
    border-radius: 15px 15px 0 0;
    padding: 20px 25px;
    border-bottom: none;
}

.modal-custom .modal-header.accept-header {
    background: var(--success-gradient);
    color: white;
}

.modal-custom .modal-header.reject-header {
    background: var(--danger-gradient);
    color: white;
}

.modal-custom .modal-header.interview-header {
    background: var(--primary-gradient);
    color: white;
}

.modal-custom .modal-header .close {
    color: white;
    opacity: 0.8;
    text-shadow: none;
}

.modal-custom .modal-header .close:hover {
    opacity: 1;
}

.modal-custom .modal-body {
    padding: 25px;
}

.modal-custom .modal-footer {
    border-top: 1px solid #f0f0f0;
    padding: 15px 25px;
}

.modal-custom .form-group label {
    font-weight: 600;
    color: #34395e;
    margin-bottom: 8px;
}

.modal-custom .form-control {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.modal-custom .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.modal-custom .applicant-preview {
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.modal-custom .applicant-preview.accept-preview {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
}

.modal-custom .applicant-preview.reject-preview {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
}

.modal-custom .applicant-preview.interview-preview {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
}

.modal-custom .applicant-preview .avatar {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
}

.modal-custom .applicant-preview .info h6 {
    margin: 0 0 3px 0;
    font-weight: 600;
    color: #34395e;
}

.modal-custom .applicant-preview .info small {
    color: #6c757d;
}

.modal-custom .info-box {
    border-left: 4px solid;
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 20px;
    font-size: 0.85rem;
}

.modal-custom .info-box.success-box {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-color: #28a745;
    color: #155724;
}

.modal-custom .info-box.danger-box {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-color: #dc3545;
    color: #721c24;
}

.modal-custom .info-box.warning-box {
    background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
    border-color: #ffc107;
    color: #856404;
}

.modal-custom .info-box i {
    margin-right: 8px;
}

.modal-custom .btn-accept {
    background: var(--success-gradient);
    border: none;
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.modal-custom .btn-accept:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
}

.modal-custom .btn-reject {
    background: var(--danger-gradient);
    border: none;
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.modal-custom .btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(220, 53, 69, 0.4);
}

.modal-custom .btn-schedule {
    background: var(--primary-gradient);
    border: none;
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.modal-custom .btn-schedule:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

.modal-custom .btn-cancel {
    background: #f4f4f4;
    border: none;
    color: #666;
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
}

.modal-custom .btn-cancel:hover {
    background: #e9e9e9;
}

/* Rejection Reason Cards */
.rejection-reasons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 15px;
}

.rejection-reason-card {
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    font-size: 0.85rem;
    color: #666;
}

.rejection-reason-card:hover {
    border-color: #dc3545;
    color: #dc3545;
}

.rejection-reason-card.selected {
    border-color: #dc3545;
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.rejection-reason-card i {
    display: block;
    font-size: 1.2rem;
    margin-bottom: 5px;
}

/* Custom checkbox */
.custom-control-label {
    font-weight: 500;
    color: #34395e;
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
    
    .rejection-reasons {
        grid-template-columns: 1fr;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.applicant-table tbody tr {
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
                                <span>Diposting: {{ $detail->tanggal_buat_indo }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="status-badge {{ $detail->status }}">{{ ucfirst($detail->status) }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="row">
                    <div class="col-6 col-md-2 mb-3 mb-md-0">
                        <div class="stat-box">
                            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
                            <div class="stat-value">{{ $detail->apply->count() }}</div>
                            <div class="stat-label">Total Pelamar</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3 mb-md-0">
                        <div class="stat-box">
                            <div class="stat-icon warning"><i class="fas fa-clock"></i></div>
                            <div class="stat-value">{{ $detail->apply->where('status', 'submitted')->count() }}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 mb-3 mb-md-0">
                        <div class="stat-box">
                            <div class="stat-icon info"><i class="fas fa-search"></i></div>
                            <div class="stat-value">{{ $detail->apply->where('status', 'reviewed')->count() }}</div>
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
                            @if ($detail->apply->where('status', 'reviewed')->count() === 0)
                                <a href="{{ route('job.scoring', $detail->id) }}" class="btn-export perhitungan">
                                    <i class="fas fa-chart-line"></i> Perhitungan
                                </a>
                            @endif
                            <a href="{{ route('job.export.excel', $detail->id) }}" class="btn-export excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                            <a href="{{ route('job.export.pdf', $detail->id) }}" class="btn-export pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Tabs -->
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="all">
                                Semua <span class="count">{{ $detail->apply->count() }}</span>
                            </button>
                            <button class="filter-tab" data-filter="submitted">
                                <i class="fas fa-clock"></i> Pending <span class="count">{{ $detail->apply->where('status', 'submitted')->count() }}</span>
                            </button>
                            <button class="filter-tab" data-filter="reviewed">
                                <i class="fas fa-search"></i> Review <span class="count">{{ $detail->apply->where('status', 'reviewed')->count() }}</span>
                            </button>
                            <button class="filter-tab" data-filter="interview">
                                <i class="fas fa-calendar-check"></i> Interview <span class="count">{{ $detail->apply->where('status', 'interview')->count() }}</span>
                            </button>
                            <button class="filter-tab" data-filter="accepted">
                                <i class="fas fa-check"></i> Diterima <span class="count">{{ $detail->apply->where('status', 'accepted')->count() }}</span>
                            </button>
                            <button class="filter-tab" data-filter="rejected">
                                <i class="fas fa-times"></i> Ditolak <span class="count">{{ $detail->apply->where('status', 'rejected')->count() }}</span>
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
                            </select>
                        </div>

                        @php
                            $perPage = 10;
                            $page = request()->get('page', 1);
                            $total = $detail->apply->count();
                            $offset = ($page - 1) * $perPage;
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
                                        <th>Nilai</th>
                                        <th>Skor CV</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($pelamars as $index => $pelamar)
                                    @php
                                        $colors = ['#667eea', '#11998e', '#f7971e', '#eb3349', '#00c6fb', '#a855f7'];
                                        $avatarColor = $colors[$pelamar->id % count($colors)];
                                    @endphp
                                    <tr data-status="{{ $pelamar->status }}" style="animation-delay: {{ $index * 0.05 }}s">
                                        <td>
                                            <div class="applicant-info">
                                                <div class="applicant-avatar-placeholder" style="background: {{ $avatarColor }}">
                                                    {{ strtoupper(substr($pelamar->user->name ?? 'U', 0, 2)) }}
                                                </div>
                                                <div class="applicant-details">
                                                    <h6>{{ $pelamar->user->name ?? 'Nama tidak tersedia' }}</h6>
                                                    <small><i class="fas fa-envelope mr-1"></i> {{ $pelamar->user->email ?? '-' }}</small>
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
                                                {{ $pelamar->cvSubmission->total_pengalaman ?? 0 }} Tahun
                                            </span>
                                        </td>
                                        <td>
                                            <span>{{ $pelamar->cvSubmission->ipk_nilai_akhir ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $score = $pelamar->score ?? 0;
                                                $scoreClass = $score >= 90 ? 'excellent' :
                                                            ($score >= 80 ? 'good' :
                                                            ($score >= 70 ? 'average' : 'poor'));
                                            @endphp
                                            <span class="score-badge {{ $scoreClass }}">
                                                <i class="fas fa-star"></i> {{ $score }}%
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
                                                @if (!in_array($pelamar->status, ['interview', 'accepted', 'rejected']))
                                                    <button class="btn-action interview btn-interview-modal" 
                                                            title="Jadwalkan Interview"
                                                            data-id="{{ $pelamar->id }}"
                                                            data-name="{{ $pelamar->user->name }}"
                                                            data-email="{{ $pelamar->user->email }}"
                                                            data-avatar="{{ strtoupper(substr($pelamar->user->name ?? 'U', 0, 2)) }}"
                                                            data-color="{{ $avatarColor }}">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </button>
                                                @endif
                                                
                                                @if(!in_array($pelamar->status, ['accepted', 'rejected']))
                                                    <button class="btn-action accept btn-accept-modal" 
                                                            title="Terima"
                                                            data-id="{{ $pelamar->id }}"
                                                            data-name="{{ $pelamar->user->name }}"
                                                            data-email="{{ $pelamar->user->email }}"
                                                            data-score="{{ $score }}"
                                                            data-avatar="{{ strtoupper(substr($pelamar->user->name ?? 'U', 0, 2)) }}"
                                                            data-color="{{ $avatarColor }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn-action reject btn-reject-modal" 
                                                            title="Tolak"
                                                            data-id="{{ $pelamar->id }}"
                                                            data-name="{{ $pelamar->user->name }}"
                                                            data-email="{{ $pelamar->user->email }}"
                                                            data-avatar="{{ strtoupper(substr($pelamar->user->name ?? 'U', 0, 2)) }}"
                                                            data-color="{{ $avatarColor }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada pelamar untuk lowongan ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($total > $perPage)
                        <div class="pagination-wrapper">
                            <div class="pagination-info">
                                Menampilkan {{ $total ? $offset + 1 : 0 }}–{{ min($offset + $perPage, $total) }} dari {{ $total }} pelamar
                            </div>
                            <div class="pagination-buttons">
                                <a class="btn btn-outline-primary {{ $page <= 1 ? 'disabled' : '' }}"
                                   href="{{ $page > 1 ? request()->fullUrlWithQuery(['page' => $page - 1]) : '#' }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                @for ($i = 1; $i <= $lastPage; $i++)
                                    <a class="btn {{ $i == $page ? 'btn-primary' : 'btn-outline-primary' }}"
                                       href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                                @endfor
                                <a class="btn btn-outline-primary {{ $page >= $lastPage ? 'disabled' : '' }}"
                                   href="{{ $page < $lastPage ? request()->fullUrlWithQuery(['page' => $page + 1]) : '#' }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        @endif
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
                        {{ isset($detail->tanggal_tutup) ? \Carbon\Carbon::parse($detail->tanggal_tutup)->locale('id')->translatedFormat('d F Y') : '-' }}
                    </div>
                    @if(isset($detail->tanggal_tutup))
                        @php
                            $tanggalTutup = \Carbon\Carbon::parse($detail->tanggal_tutup);
                            $sisaHari = now()->diffInDays($tanggalTutup, false);
                        @endphp
                        <div class="deadline-remaining">
                            @if($sisaHari > 1)
                                <i class="fas fa-exclamation-triangle mr-1"></i> {{ ceil($sisaHari) }} hari lagi
                            @elseif($sisaHari >= 0)
                                <i class="fas fa-exclamation-circle mr-1"></i> Hari ini terakhir
                            @else
                                <i class="fas fa-times-circle mr-1"></i> Lowongan ditutup
                            @endif
                        </div>
                    @endif
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
                            <div class="quick-info-value">{{ $detail->lokasi ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <div class="quick-info-icon"><i class="fas fa-tags"></i></div>
                        <div>
                            <div class="quick-info-label">Kategori</div>
                            <div class="quick-info-value">{{ $detail->category ?? '-' }}</div>
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
                    @forelse($topCandidates as $index => $candidate)
                        @php
                            $medalColors = [0 => '#FFD700', 1 => '#C0C0C0', 2 => '#CD7F32'];
                            $medalColor = $medalColors[$index] ?? '#667eea';
                            $avatarColors = ['#667eea', '#11998e', '#f7971e', '#eb3349', '#a855f7'];
                            $avatarColor = $avatarColors[$index % count($avatarColors)];
                            $scoreClass = $candidate->score >= 90 ? 'excellent' : ($candidate->score >= 80 ? 'good' : ($candidate->score >= 70 ? 'average' : 'poor'));
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3" style="width: 30px; height: 30px; border-radius: 50%; background: {{ $medalColor }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.8rem;">
                                {{ $index + 1 }}
                            </div>
                            <div class="mr-2" style="width: 35px; height: 35px; border-radius: 10px; background: {{ $avatarColor }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem;">
                                {{ strtoupper(substr($candidate->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div style="font-weight: 600; color: #34395e; font-size: 0.9rem;">
                                    {{ Str::limit($candidate->user->name ?? '-', 15) }}
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-briefcase mr-1"></i>{{ $candidate->cvSubmission->total_pengalaman ?? 0 }} thn
                                </small>
                            </div>
                            <div class="score-badge {{ $scoreClass }}" style="padding: 4px 10px; font-size: 0.75rem;">
                                {{ $candidate->score }}%
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-calculator fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0" style="font-size: 0.85rem;">Belum ada perhitungan skor</p>
                            <a href="{{ route('job.scoring', $detail->id) }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-chart-line mr-1"></i> Hitung Skor
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Accept Modal -->
<div class="modal fade modal-custom" id="acceptModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header accept-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i> Terima Pelamar
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="acceptForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Applicant Preview -->
                    <div class="applicant-preview accept-preview">
                        <div class="avatar" id="acceptModalAvatar" style="background: #28a745;">BS</div>
                        <div class="info">
                            <h6 id="acceptModalName">Nama Pelamar</h6>
                            <small id="acceptModalEmail"><i class="fas fa-envelope mr-1"></i> email@example.com</small>
                            <div class="mt-1">
                                <span class="score-badge" id="acceptModalScore" style="padding: 3px 8px; font-size: 0.75rem;">
                                    <i class="fas fa-star"></i> 85%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="info-box success-box">
                        <i class="fas fa-info-circle"></i>
                        Pelamar akan menerima email konfirmasi penerimaan setelah Anda mengklik tombol Terima.
                    </div>

                    <!-- Start Date -->
                    <div class="form-group">
                        <label for="start_date">
                            <i class="fas fa-calendar-alt mr-1 text-success"></i> Tanggal Mulai Kerja
                        </label>
                        <input type="date" 
                               class="form-control" 
                               id="start_date" 
                               name="start_date"
                               min="{{ now()->addDays(7)->format('Y-m-d') }}">
                        <small class="text-muted">Tanggal kandidat diharapkan mulai bekerja</small>
                    </div>

                    <!-- Offered Salary -->
                    <div class="form-group">
                        <label for="offered_salary">
                            <i class="fas fa-money-bill-wave mr-1 text-success"></i> Gaji yang Ditawarkan
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" 
                                   class="form-control" 
                                   id="offered_salary" 
                                   name="offered_salary"
                                   placeholder="10000000">
                        </div>
                        <small class="text-muted">Gaji bulanan yang akan ditawarkan (opsional)</small>
                    </div>

                    <!-- Acceptance Notes -->
                    <div class="form-group">
                        <label for="acceptance_notes">
                            <i class="fas fa-sticky-note mr-1 text-success"></i> Catatan Penerimaan
                        </label>
                        <textarea class="form-control" 
                                  id="acceptance_notes" 
                                  name="acceptance_notes" 
                                  rows="3"
                                  placeholder="Contoh: Selamat bergabung! Silakan datang untuk orientasi pada tanggal..."></textarea>
                        <small class="text-muted">Catatan ini akan dikirimkan kepada pelamar</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-accept text-white">
                        <i class="fas fa-check mr-1"></i> Terima Pelamar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade modal-custom" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header reject-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle mr-2"></i> Tolak Pelamar
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Applicant Preview -->
                    <div class="applicant-preview reject-preview">
                        <div class="avatar" id="rejectModalAvatar" style="background: #dc3545;">BS</div>
                        <div class="info">
                            <h6 id="rejectModalName">Nama Pelamar</h6>
                            <small id="rejectModalEmail"><i class="fas fa-envelope mr-1"></i> email@example.com</small>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="info-box danger-box">
                        <i class="fas fa-exclamation-triangle"></i>
                        Tindakan ini tidak dapat dibatalkan. Pastikan Anda sudah yakin dengan keputusan ini.
                    </div>

                    <!-- Rejection Reason -->
                    <div class="form-group">
                        <label>
                            <i class="fas fa-list-alt mr-1 text-danger"></i> Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <div class="rejection-reasons">
                            <div class="rejection-reason-card" data-reason="Kualifikasi tidak sesuai">
                                <i class="fas fa-user-times"></i>
                                Kualifikasi tidak sesuai
                            </div>
                            <div class="rejection-reason-card" data-reason="Pengalaman kurang">
                                <i class="fas fa-briefcase"></i>
                                Pengalaman kurang
                            </div>
                            <div class="rejection-reason-card" data-reason="Skill tidak memenuhi">
                                <i class="fas fa-cogs"></i>
                                Skill tidak memenuhi
                            </div>
                            <div class="rejection-reason-card" data-reason="Tidak lolos interview">
                                <i class="fas fa-calendar-times"></i>
                                Tidak lolos interview
                            </div>
                            <div class="rejection-reason-card" data-reason="Ekspektasi gaji tidak sesuai">
                                <i class="fas fa-money-bill"></i>
                                Ekspektasi gaji tidak sesuai
                            </div>
                            <div class="rejection-reason-card" data-reason="Lainnya">
                                <i class="fas fa-ellipsis-h"></i>
                                Lainnya
                            </div>
                        </div>
                        <input type="hidden" name="rejection_reason" id="rejection_reason" required>
                    </div>

                    <!-- Rejection Notes -->
                    <div class="form-group">
                        <label for="rejection_notes">
                            <i class="fas fa-sticky-note mr-1 text-danger"></i> Catatan Tambahan
                        </label>
                        <textarea class="form-control" 
                                  id="rejection_notes" 
                                  name="rejection_notes" 
                                  rows="3"
                                  placeholder="Berikan feedback konstruktif untuk pelamar (opsional)..."></textarea>
                        <small class="text-muted">Catatan ini dapat dikirimkan kepada pelamar jika dipilih</small>
                    </div>

                    <!-- Send Notification -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="send_notification" name="send_notification" value="1" checked>
                        <label class="custom-control-label" for="send_notification">
                            <i class="fas fa-envelope mr-1"></i> Kirim email notifikasi ke pelamar
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-arrow-left mr-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-reject text-white" id="btnRejectSubmit" disabled>
                        <i class="fas fa-times mr-1"></i> Tolak Pelamar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Interview Modal -->
<div class="modal fade modal-custom" id="interviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header interview-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-check mr-2"></i> Jadwalkan Interview
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="interviewForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Applicant Preview -->
                    <div class="applicant-preview interview-preview">
                        <div class="avatar" id="interviewModalAvatar" style="background: #667eea;">BS</div>
                        <div class="info">
                            <h6 id="interviewModalName">Nama Pelamar</h6>
                            <small id="interviewModalEmail"><i class="fas fa-envelope mr-1"></i> email@example.com</small>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="info-box warning-box">
                        <i class="fas fa-info-circle"></i>
                        Pelamar akan menerima notifikasi email setelah jadwal interview disimpan.
                    </div>

                    <!-- Interview Date & Time -->
                    <div class="form-group">
                        <label for="interview_date">
                            <i class="fas fa-calendar-alt mr-1 text-primary"></i> Tanggal & Waktu Interview
                        </label>
                        <input type="datetime-local" 
                               class="form-control" 
                               id="interview_date" 
                               name="interview_date" 
                               required
                               min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <!-- Interview Type -->
                    <div class="form-group">
                        <label for="interview_type">
                            <i class="fas fa-video mr-1 text-primary"></i> Tipe Interview
                        </label>
                        <select class="form-control" id="interview_type" name="interview_type">
                            <option value="online">Online (Video Call)</option>
                            <option value="onsite">Onsite (Datang ke Kantor)</option>
                            <option value="phone">Phone Interview</option>
                        </select>
                    </div>

                    <!-- Interview Location -->
                    <div class="form-group">
                        <label for="interview_location">
                            <i class="fas fa-link mr-1 text-primary"></i> <span id="locationLabel">Link Meeting</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="interview_location" 
                               name="interview_location"
                               placeholder="https://meet.google.com/xxx-xxxx-xxx">
                        <small class="text-muted" id="locationHint">Link Zoom, Google Meet, atau Microsoft Teams</small>
                    </div>

                    <!-- Interview Notes -->
                    <div class="form-group">
                        <label for="interview_notes">
                            <i class="fas fa-sticky-note mr-1 text-primary"></i> Catatan Interview
                        </label>
                        <textarea class="form-control" 
                                  id="interview_notes" 
                                  name="interview_notes" 
                                  rows="3"
                                  placeholder="Contoh: Persiapkan portfolio, bawa dokumen asli..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-schedule text-white">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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

    // ==================== ACCEPT MODAL ====================
    $('.btn-accept-modal').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
        const score = $(this).data('score');
        const avatar = $(this).data('avatar');
        const color = $(this).data('color');
        
        // Set form action
        $('#acceptForm').attr('action', '/pelamar/' + id + '/accept');
        
        // Set applicant info
        $('#acceptModalName').text(name);
        $('#acceptModalEmail').html('<i class="fas fa-envelope mr-1"></i> ' + email);
        $('#acceptModalAvatar').text(avatar).css('background', color);
        $('#acceptModalScore').html('<i class="fas fa-star"></i> ' + score + '%');
        
        // Determine score class
        let scoreClass = score >= 90 ? 'excellent' : (score >= 80 ? 'good' : (score >= 70 ? 'average' : 'poor'));
        $('#acceptModalScore').removeClass('excellent good average poor').addClass(scoreClass);
        
        // Reset form
        $('#acceptForm')[0].reset();
        
        // Show modal
        $('#acceptModal').modal('show');
    });

    // Accept Form Submit
    $('#acceptForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#acceptModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    confirmButtonColor: '#28a745'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });

    // ==================== REJECT MODAL ====================
    $('.btn-reject-modal').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
        const avatar = $(this).data('avatar');
        const color = $(this).data('color');
        
        // Set form action
        $('#rejectForm').attr('action', '/pelamar/' + id + '/reject');
        
        // Set applicant info
        $('#rejectModalName').text(name);
        $('#rejectModalEmail').html('<i class="fas fa-envelope mr-1"></i> ' + email);
        $('#rejectModalAvatar').text(avatar).css('background', color);
        
        // Reset form
        $('#rejectForm')[0].reset();
        $('.rejection-reason-card').removeClass('selected');
        $('#rejection_reason').val('');
        $('#btnRejectSubmit').prop('disabled', true);
        
        // Show modal
        $('#rejectModal').modal('show');
    });

    // Rejection Reason Selection
    $('.rejection-reason-card').on('click', function() {
        $('.rejection-reason-card').removeClass('selected');
        $(this).addClass('selected');
        
        const reason = $(this).data('reason');
        $('#rejection_reason').val(reason);
        $('#btnRejectSubmit').prop('disabled', false);
    });

    // Reject Form Submit
    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#rejection_reason').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Alasan',
                text: 'Silakan pilih alasan penolakan terlebih dahulu.',
                confirmButtonColor: '#dc3545'
            });
            return;
        }
        
        const form = $(this);
        const url = form.attr('action');
        
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#rejectModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    confirmButtonColor: '#28a745'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });

    // ==================== INTERVIEW MODAL ====================
    $('.btn-interview-modal').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
        const avatar = $(this).data('avatar');
        const color = $(this).data('color');
        
        // Set form action
        $('#interviewForm').attr('action', '/pelamar/' + id + '/interview');
        
        // Set applicant info
        $('#interviewModalName').text(name);
        $('#interviewModalEmail').html('<i class="fas fa-envelope mr-1"></i> ' + email);
        $('#interviewModalAvatar').text(avatar).css('background', color);
        
        // Reset form
        $('#interviewForm')[0].reset();
        
        // Show modal
        $('#interviewModal').modal('show');
    });

    // Interview Type Change Handler
    $('#interview_type').on('change', function() {
        const type = $(this).val();
        
        if (type === 'online') {
            $('#locationLabel').text('Link Meeting');
            $('#interview_location').attr('placeholder', 'https://meet.google.com/xxx-xxxx-xxx');
            $('#locationHint').text('Link Zoom, Google Meet, atau Microsoft Teams');
        } else if (type === 'onsite') {
            $('#locationLabel').text('Alamat Kantor');
            $('#interview_location').attr('placeholder', 'Jl. Sudirman No. 123, Jakarta Selatan');
            $('#locationHint').text('Alamat lengkap lokasi interview');
        } else if (type === 'phone') {
            $('#locationLabel').text('Nomor Telepon');
            $('#interview_location').attr('placeholder', '+62 812-xxxx-xxxx');
            $('#locationHint').text('Nomor telepon yang akan dihubungi');
        }
    });

    // Interview Form Submit
    $('#interviewForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#interviewModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Jadwal interview berhasil disimpan.',
                    confirmButtonColor: '#667eea'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });

    // Export buttons
    $('.btn-export.excel, .btn-export.pdf').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const type = $(this).hasClass('excel') ? 'Excel' : 'PDF';
        
        Swal.fire({
            title: 'Export ' + type,
            text: 'Data pelamar akan diexport ke file ' + type,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: type === 'Excel' ? '#28a745' : '#dc3545',
            confirmButtonText: '<i class="fas fa-download"></i> Download',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>
@endpush