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

/* Page Header */
.page-header-banner {
    background: var(--purple-gradient);
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.page-header-content h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.page-header-content p {
    opacity: 0.9;
    margin: 0;
    font-size: 1rem;
}

.header-actions .btn {
    padding: 12px 25px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.header-actions .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
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
    position: relative;
    overflow: hidden;
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
.stat-card.purple::before { background: var(--purple-gradient); }
.stat-card.info::before { background: var(--info-gradient); }

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
.stat-card .stat-icon.purple { background: var(--purple-gradient); }
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
    color: #a855f7;
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
    background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
    border-radius: 15px;
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group label {
    display: block;
    font-size: 0.75rem;
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
    border-color: #a855f7;
    outline: none;
    box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
}

.filter-actions {
    display: flex;
    align-items: flex-end;
    gap: 10px;
}

.filter-actions .btn {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
}

.filter-actions .btn-primary {
    background: var(--purple-gradient);
    border: none;
}

/* DataTable Styling */
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
    border-color: #a855f7;
    outline: none;
    box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px !important;
    margin: 0 3px;
    border: none !important;
    background: #f0f0f0 !important;
    color: #666 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--purple-gradient) !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--purple-gradient) !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_info {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Table Styling */
.datatable {
    width: 100% !important;
    border-collapse: separate;
    border-spacing: 0 8px;
}

.datatable thead th {
    background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
    padding: 15px 20px;
    font-weight: 700;
    color: #34395e;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    white-space: nowrap;
}

.datatable thead th:first-child {
    border-radius: 12px 0 0 12px;
}

.datatable thead th:last-child {
    border-radius: 0 12px 12px 0;
}

.datatable tbody tr {
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

.datatable tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.datatable tbody td {
    padding: 18px 20px;
    vertical-align: middle;
    border: none;
    background: white;
}

.datatable tbody td:first-child {
    border-radius: 12px 0 0 12px;
}

.datatable tbody td:last-child {
    border-radius: 0 12px 12px 0;
}

/* User Info Cell */
.user-info-cell {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-avatar {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
    flex-shrink: 0;
    overflow: hidden;
}

.user-details h6 {
    margin: 0 0 3px 0;
    font-weight: 600;
    color: #34395e;
    font-size: 0.95rem;
}

.user-details small {
    color: #6c757d;
    font-size: 0.8rem;
}

/* Role Badges */
.role-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-badge.admin {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.role-badge.kandidat {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
}

.role-badge.hr {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.role-badge.default {
    background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
    color: #6b21a8;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.8rem;
}

.status-badge.active {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.status-badge.inactive {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

/* Date Badge */
.date-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #6c757d;
}

.date-badge i {
    color: #a855f7;
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
    text-decoration: none;
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

.btn-action.edit {
    background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
    color: #7c3aed;
}

.btn-action.edit:hover {
    background: var(--purple-gradient);
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

/* Recent Users Sidebar */
.recent-users-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    overflow: hidden;
}

.recent-users-card .card-header {
    background: var(--purple-gradient);
    color: white;
    padding: 20px;
    border: none;
}

.recent-users-card .card-header h5 {
    margin: 0;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.recent-users-card .card-body {
    padding: 20px;
}

.recent-user-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 10px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.recent-user-item:hover {
    background: #f3e8ff;
    transform: translateX(5px);
}

.recent-user-item:last-child {
    margin-bottom: 0;
}

.recent-user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    color: white;
    flex-shrink: 0;
    overflow: hidden;
}

.recent-user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recent-user-info {
    flex: 1;
    min-width: 0;
}

.recent-user-info h6 {
    margin: 0 0 2px 0;
    font-weight: 600;
    font-size: 0.9rem;
    color: #34395e;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.recent-user-info small {
    color: #6c757d;
    font-size: 0.75rem;
}

.recent-user-role {
    font-size: 0.7rem;
    padding: 4px 10px;
    border-radius: 50px;
    font-weight: 600;
}

/* Role Distribution */
.role-distribution {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.role-distribution h6 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

.role-bar {
    margin-bottom: 12px;
}

.role-bar .role-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 0.85rem;
}

.role-bar .role-name {
    font-weight: 600;
    color: #34395e;
}

.role-bar .role-count {
    color: #6c757d;
}

.role-bar .progress {
    height: 8px;
    border-radius: 10px;
    background: #e9ecef;
}

.role-bar .progress-bar {
    border-radius: 10px;
}

.role-bar .progress-bar.admin { background: var(--warning-gradient); }
.role-bar .progress-bar.kandidat { background: var(--info-gradient); }
.role-bar .progress-bar.hr { background: var(--success-gradient); }

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 15px;
    border-radius: 12px;
    background: #f8f9fa;
    text-decoration: none;
    color: #34395e;
    transition: all 0.3s ease;
    text-align: center;
}

.quick-action-btn:hover {
    background: var(--purple-gradient);
    color: white;
    transform: translateY(-3px);
    text-decoration: none;
}

.quick-action-btn i {
    font-size: 1.5rem;
    margin-bottom: 8px;
    color: #a855f7;
}

.quick-action-btn:hover i {
    color: white;
}

.quick-action-btn span {
    font-size: 0.8rem;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 992px) {
    .page-header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .filter-section {
        flex-direction: column;
    }
    
    .filter-group {
        min-width: 100%;
    }
}

@media (max-width: 768px) {
    .page-header-banner {
        padding: 25px;
    }

    .page-header-content h2 {
        font-size: 1.4rem;
    }

    .user-info-cell {
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

.stat-card, .table-card, .recent-users-card {
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
        <h1>Pengguna</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Pengguna</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Page Header Banner -->
        <div class="page-header-banner">
            <div class="page-header-content">
                <div>
                    <h2><i class="fas fa-users mr-2"></i> Manajemen Pengguna</h2>
                    <p>Kelola semua akun pengguna sistem rekrutmen</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('users.create') }}" class="btn btn-light">
                        <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card primary">
                    <div class="stat-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Pengguna</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['admin'] }}</div>
                        <div class="stat-label">Admin</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card purple">
                    <div class="stat-icon purple">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['kandidat'] }}</div>
                        <div class="stat-label">Kandidat</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                <div class="stat-card info">
                    <div class="stat-icon info">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['thisMonth'] }}</div>
                        <div class="stat-label">Baru Bulan Ini</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Table -->
            <div class="col-lg-9">
                <div class="table-card">
                    <div class="card-header">
                        <h4><i class="fas fa-list-alt"></i> Daftar Pengguna</h4>
                    </div>
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="filter-section">
                            <div class="filter-group">
                                <label>Role</label>
                                <select id="filterRole">
                                    <option value="">Semua Role</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>Cari</label>
                                <input type="text" id="filterSearch" placeholder="Cari nama atau email...">
                            </div>
                            <div class="filter-actions">
                                <button class="btn btn-primary" id="btnApplyFilter">
                                    <i class="fas fa-filter mr-1"></i> Filter
                                </button>
                                <button class="btn btn-secondary" id="btnResetFilter">
                                    <i class="fas fa-redo mr-1"></i> Reset
                                </button>
                            </div>
                        </div>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pengguna</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Terdaftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Recent Users -->
                <div class="recent-users-card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-user-clock"></i> Pengguna Terbaru</h5>
                    </div>
                    <div class="card-body">
                        @forelse($recentUsers as $user)
                        @php
                            $colors = ['#667eea', '#11998e', '#f7971e', '#eb3349', '#00c6fb', '#a855f7'];
                            $color = $colors[$user->id % count($colors)];
                            $roleName = $user->getRoleNames()->first() ?? 'user';
                            $roleClass = match(strtolower($roleName)) {
                                'admin' => 'admin',
                                'kandidat' => 'kandidat',
                                'hr' => 'hr',
                                default => 'default'
                            };
                        @endphp
                        <div class="recent-user-item">
                            <div class="recent-user-avatar" style="background: {{ $color }};">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Avatar">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                @endif
                            </div>
                            <div class="recent-user-info">
                                <h6>{{ Str::limit($user->name, 15) }}</h6>
                                <small>{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="recent-user-role role-badge {{ $roleClass }}">{{ $roleName }}</span>
                        </div>
                        @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada pengguna</p>
                        </div>
                        @endforelse

                        <!-- Role Distribution -->
                        <div class="role-distribution">
                            <h6><i class="fas fa-chart-pie mr-2"></i> Distribusi Role</h6>
                            @php
                                $total = $stats['total'] ?: 1;
                            @endphp
                            <div class="role-bar">
                                <div class="role-header">
                                    <span class="role-name">Admin</span>
                                    <span class="role-count">{{ $stats['admin'] }}</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar admin" style="width: {{ ($stats['admin'] / $total) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="role-bar">
                                <div class="role-header">
                                    <span class="role-name">Kandidat</span>
                                    <span class="role-count">{{ $stats['kandidat'] }}</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar kandidat" style="width: {{ ($stats['kandidat'] / $total) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="recent-users-card">
                    <div class="card-header">
                        <h5><i class="fas fa-bolt"></i> Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="{{ route('users.create') }}" class="quick-action-btn">
                                <i class="fas fa-user-plus"></i>
                                <span>Tambah User</span>
                            </a>
                            <a href="#" class="quick-action-btn">
                                <i class="fas fa-file-export"></i>
                                <span>Export Data</span>
                            </a>
                            <a href="#" class="quick-action-btn">
                                <i class="fas fa-user-cog"></i>
                                <span>Kelola Role</span>
                            </a>
                            <a href="#" class="quick-action-btn">
                                <i class="fas fa-shield-alt"></i>
                                <span>Permissions</span>
                            </a>
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
    // Initialize DataTable
    var table = $(".datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("users.index") }}',
            data: function(d) {
                d.role = $('#filterRole').val();
            }
        },
        columns: [
            { data: "DT_RowIndex", name: "id", className: "text-center", width: "50px" },
            { data: "user_info", name: "name" },
            { data: "role", name: "role", className: "text-center" },
            { data: "status", name: "status", className: "text-center" },
            { data: "created", name: "created_at", className: "text-center" },
            { data: "action", name: "action", orderable: false, searchable: false, className: "text-center" },
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "<i class='fas fa-chevron-right'></i>",
                previous: "<i class='fas fa-chevron-left'></i>"
            }
        },
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
        dom: '<"top"l>rt<"bottom"ip><"clear">'
    });

    // Reinitialize tooltips after table draw
    table.on("draw", function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Filter by role
    $('#btnApplyFilter').on('click', function() {
        table.draw();
    });

    // Reset filter
    $('#btnResetFilter').on('click', function() {
        $('#filterRole').val('');
        $('#filterSearch').val('');
        table.search('').draw();
    });

    // Custom search
    $('#filterSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});

// Delete function
function Delete(id) {
    Swal.fire({
        title: "Hapus Pengguna?",
        text: "Data pengguna akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!',
        cancelButtonText: "Batal",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            $.ajax({
                url: "/users/" + id,
                type: "DELETE",
                data: { id: id },
                success: function(response) {
                    if (response.status === "success") {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Pengguna berhasil dihapus.",
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('.datatable').DataTable().ajax.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat menghapus data.",
                            icon: "error"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan. Silakan coba lagi.",
                        icon: "error"
                    });
                }
            });
        }
    });
}
</script>
@endpush