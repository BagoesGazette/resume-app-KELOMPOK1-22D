@extends('layouts.app')

@push('custom-css')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    --info-gradient: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
    --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
}

/* Page Header */
.page-header-banner {
    background: var(--primary-gradient);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
    color: white;
}

.page-header-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.page-header-banner::after {
    content: '';
    position: absolute;
    bottom: -60%;
    left: 5%;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.page-header-banner h2 {
    font-weight: 700;
    margin-bottom: 5px;
    position: relative;
    z-index: 1;
}

.page-header-banner p {
    opacity: 0.9;
    margin-bottom: 0;
    position: relative;
    z-index: 1;
}

.header-stats {
    display: flex;
    gap: 30px;
    margin-top: 20px;
    position: relative;
    z-index: 1;
}

.header-stat-item {
    text-align: center;
}

.header-stat-item .stat-number {
    font-size: 1.8rem;
    font-weight: 800;
}

.header-stat-item .stat-label {
    font-size: 0.85rem;
    opacity: 0.8;
}

/* Filter Bar */
.filter-bar {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.filter-bar .form-control,
.filter-bar .btn {
    border-radius: 8px;
}

.filter-bar .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Job Cards */
.job-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    margin-bottom: 25px;
    border: none;
    position: relative;
}

.job-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.job-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.job-card:hover::before {
    transform: scaleX(1);
}

.job-card-header {
    padding: 20px 20px 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.job-category {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.category-badge {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%);
    color: #667eea;
}

.job-time {
    font-size: 0.8rem;
    color: #999;
    display: flex;
    align-items: center;
    gap: 5px;
}

.job-time i {
    font-size: 0.7rem;
}

.job-status {
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.job-status.open {
    background: #d4edda;
    color: #155724;
}

.job-status.closed {
    background: #f8d7da;
    color: #721c24;
}

.job-card-body {
    padding: 15px 20px;
}

.job-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #34395e;
    margin-bottom: 8px;
    transition: color 0.3s ease;
    display: block;
    text-decoration: none;
}

.job-title:hover {
    color: #667eea;
    text-decoration: none;
}

.job-company {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.company-logo {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1rem;
}

.company-info h6 {
    margin: 0;
    font-weight: 600;
    color: #34395e;
    font-size: 0.95rem;
}

.company-info small {
    color: #6c757d;
    font-size: 0.8rem;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}

.job-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: #6c757d;
}

.job-meta-item i {
    color: #667eea;
    font-size: 0.9rem;
}

.job-type-badge {
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}

.job-type-badge.full-time {
    background: var(--success-gradient);
    color: white;
}

.job-type-badge.part-time {
    background: var(--info-gradient);
    color: white;
}

.job-type-badge.contract {
    background: var(--warning-gradient);
    color: #333;
}

.job-type-badge.internship {
    background: var(--danger-gradient);
    color: white;
}

.job-description {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.job-card-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #eee;
}

.job-deadline {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
}

.job-deadline i {
    color: #dc3545;
}

.job-deadline.urgent {
    color: #dc3545;
    font-weight: 600;
}

.job-deadline.normal {
    color: #6c757d;
}

.btn-apply {
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    background: var(--primary-gradient);
    border: none;
    color: white;
    transition: all 0.3s ease;
}

.btn-apply:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-detail {
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    background: transparent;
    border: 2px solid #667eea;
    color: #667eea;
    transition: all 0.3s ease;
}

.btn-detail:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
}

/* Tags */
.job-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 15px;
}

.job-tag {
    padding: 3px 10px;
    background: #f0f0f0;
    border-radius: 4px;
    font-size: 0.75rem;
    color: #666;
    transition: all 0.2s ease;
}

.job-tag:hover {
    background: #667eea;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #34395e;
    margin-bottom: 10px;
}

.empty-state p {
    color: #6c757d;
}

/* Pagination Custom */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.pagination .page-link {
    border-radius: 8px;
    margin: 0 3px;
    border: none;
    color: #667eea;
    font-weight: 500;
}

.pagination .page-item.active .page-link {
    background: var(--primary-gradient);
    border: none;
}

/* View Toggle */
.view-toggle {
    display: flex;
    gap: 5px;
    background: #f0f0f0;
    padding: 4px;
    border-radius: 8px;
}

.view-toggle .btn {
    padding: 6px 12px;
    border: none;
    background: transparent;
    color: #666;
    border-radius: 6px;
}

.view-toggle .btn.active {
    background: white;
    color: #667eea;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* List View */
.job-card.list-view {
    display: flex;
    align-items: center;
}

.job-card.list-view .job-card-header {
    flex-shrink: 0;
    width: 200px;
    padding: 20px;
    border-right: 1px solid #eee;
}

.job-card.list-view .job-card-body {
    flex: 1;
}

.job-card.list-view .job-card-footer {
    flex-shrink: 0;
    width: 200px;
    flex-direction: column;
    gap: 10px;
    border-top: none;
    border-left: 1px solid #eee;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header-banner {
        padding: 20px;
    }

    .header-stats {
        gap: 15px;
    }

    .header-stat-item .stat-number {
        font-size: 1.3rem;
    }

    .job-card.list-view {
        flex-direction: column;
    }

    .job-card.list-view .job-card-header,
    .job-card.list-view .job-card-footer {
        width: 100%;
        border: none;
    }

    .filter-bar .row > div {
        margin-bottom: 10px;
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

.job-card {
    animation: fadeInUp 0.5s ease forwards;
}

.job-card:nth-child(1) { animation-delay: 0.1s; }
.job-card:nth-child(2) { animation-delay: 0.2s; }
.job-card:nth-child(3) { animation-delay: 0.3s; }
.job-card:nth-child(4) { animation-delay: 0.4s; }
.job-card:nth-child(5) { animation-delay: 0.5s; }
.job-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Lowongan Kerja</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Lowongan Kerja</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Page Header Banner -->
        <div class="page-header-banner">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><i class="fas fa-briefcase mr-2"></i> Temukan Lowongan Terbaik</h2>
                    <p>Jelajahi berbagai peluang karir yang sesuai dengan keahlian Anda</p>
                    <div class="header-stats">
                        <div class="header-stat-item">
                            <div class="stat-number">{{ $job->count() }}</div>
                            <div class="stat-label">Total Lowongan</div>
                        </div>
                        <div class="header-stat-item">
                            <div class="stat-number">{{ $job->where('status', 'open')->count() }}</div>
                            <div class="stat-label">Lowongan Aktif</div>
                        </div>
                        <div class="header-stat-item">
                            <div class="stat-number">{{ $job->unique('perusahaan')->count() }}</div>
                            <div class="stat-label">Perusahaan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <form action="" method="GET">
                <div class="row align-items-center">
                    <div class="col-md-2 mb-2 mb-md-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control border-left-0" name="search" placeholder="Cari lowongan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <select class="form-control" name="category">
                            <option value="">Semua Kategori</option>
                            <option value="programmer">Programmer</option>
                            <option value="designer">Designer</option>
                            <option value="marketing">Marketing</option>
                            <option value="finance">Finance</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <select class="form-control" name="tipe">
                            <option value="" {{ request('tipe') == null ? 'selected' : '' }}>Semua Tipe</option>
                            <option value="full-time" {{ request('tipe') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="part-time" {{ request('tipe') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="contract" {{ request('tipe') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ request('tipe') == 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <select class="form-control" name="lokasi">
                            <option value="">Semua Lokasi</option>
                            <option value="jakarta">Jakarta</option>
                            <option value="yogyakarta">Yogyakarta</option>
                            <option value="surabaya">Surabaya</option>
                            <option value="bandung">Bandung</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-light btn-block">
                            <i class="fa fa-undo mr-1"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Job Listings -->
        @if($job->count() > 0)
        <div class="row">
            @foreach ($job as $index => $item)
            <div class="col-12 col-md-6 col-lg-6">
                <div class="job-card">
                    <!-- Card Header -->
                    <div class="job-card-header">
                        <div class="job-category">
                            <span class="category-badge">{{ $item->category ?? 'General' }}</span>
                            <span class="job-time">
                                <i class="fas fa-clock"></i>
                                {{ $item->updated_at->diffForHumans() }}
                            </span>
                        </div>
                        @if($item->status == 'open')
                            <span class="job-status open"><i class="fas fa-circle" style="font-size: 6px;"></i> Open</span>
                        @else
                            <span class="job-status closed"><i class="fas fa-circle" style="font-size: 6px;"></i> Closed</span>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="job-card-body">
                        <a href="{{ route('lowongan-kerja.show', $item->id) }}" class="job-title">{{ $item->judul }}</a>
                        
                        <div class="job-company">
                            <div class="company-logo">
                                {{ strtoupper(substr($item->perusahaan, 0, 2)) }}
                            </div>
                            <div class="company-info">
                                <h6>{{ $item->perusahaan }}</h6>
                                <small><i class="fas fa-map-marker-alt mr-1"></i> {{ $item->lokasi ?? 'Indonesia' }}</small>
                            </div>
                        </div>

                        <div class="job-meta">
                            @php
                                $tipeClass = str_replace(' ', '-', strtolower($item->tipe ?? 'full-time'));
                            @endphp
                            <span class="job-type-badge {{ $tipeClass }}">
                                <i class="fas fa-briefcase mr-1"></i> {{ ucfirst($item->tipe ?? 'Full-time') }}
                            </span>
                            @if($item->tanggal_tutup)
                                @php
                                    $daysLeft = now()->diffInDays($item->tanggal_tutup, false);
                                @endphp
                                @if($daysLeft > 0 && $daysLeft <= 7)
                                    <span class="job-meta-item text-danger">
                                        <i class="fas fa-fire"></i> {{ $daysLeft }} hari lagi
                                    </span>
                                @endif
                            @endif
                        </div>

                        <p class="job-description">
                            {{ Str::limit(strip_tags($item->deskripsi), 120, '...') }}
                        </p>

                        @if($item->category)
                        <div class="job-tags">
                            <span class="job-tag">{{ $item->category }}</span>
                            @if($item->tipe)
                                <span class="job-tag">{{ ucfirst($item->tipe) }}</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Card Footer -->
                    <div class="job-card-footer">
                        @if($item->tanggal_tutup)
                            @php
                                $daysLeft = now()->diffInDays($item->tanggal_tutup, false);
                            @endphp
                            <div class="job-deadline {{ $daysLeft <= 7 ? 'urgent' : 'normal' }}">
                                <i class="fas fa-calendar-times"></i>
                                <span>
                                    @if($daysLeft < 0)
                                        Sudah Ditutup
                                    @elseif($daysLeft == 0)
                                        Hari Terakhir!
                                    @else
                                        Tutup: {{ \Carbon\Carbon::parse($item->tanggal_tutup)->format('d M Y') }}
                                    @endif
                                </span>
                            </div>
                        @else
                            <div class="job-deadline normal">
                                <i class="fas fa-infinity"></i>
                                <span>Selalu Dibuka</span>
                            </div>
                        @endif
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('lowongan-kerja.show', $item->id) }}" class="btn btn-detail">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($job instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="pagination-wrapper">
            {{ $job->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-briefcase"></i>
            <h4>Belum Ada Lowongan</h4>
            <p>Saat ini belum ada lowongan kerja yang tersedia. Silakan cek kembali nanti.</p>
        </div>
        @endif
    </div>
</section>
@endsection

@push('custom-js')
<script>
$(document).ready(function() {
    // Add hover effect sound (optional)
    $('.job-card').hover(
        function() {
            $(this).find('.company-logo').css('transform', 'scale(1.1) rotate(5deg)');
        },
        function() {
            $(this).find('.company-logo').css('transform', 'scale(1) rotate(0deg)');
        }
    );
});
</script>
@endpush