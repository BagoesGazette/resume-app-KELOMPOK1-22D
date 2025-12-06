@extends('layouts.app')

@push('custom-css')
    <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
      --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
      --info-gradient: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
    }

    /* Job Header Card */
    .job-header-card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
      margin-bottom: 25px;
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
      left: -100px;
      width: 400px;
      height: 400px;
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
      flex-shrink: 0;
    }

    .job-title {
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 5px;
      position: relative;
      z-index: 1;
      color: white;
    }

    .company-name {
      font-size: 1rem;
      opacity: 0.95;
      font-weight: 500;
      position: relative;
      z-index: 1;
      margin-bottom: 0;
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

    .job-meta-item i {
      width: 16px;
    }

    /* Status Badges */
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
    }

    .status-open {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      border: 2px solid rgba(255,255,255,0.5);
    }

    .status-open::before {
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

    .status-closed {
      background: rgba(220, 53, 69, 0.2);
      color: #fff;
      border: 2px solid rgba(220, 53, 69, 0.5);
    }

    /* Type Badge */
    .type-badge {
      padding: 6px 16px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: capitalize;
      position: relative;
      z-index: 1;
    }

    .type-fulltime,
    .type-full-time {
      background: var(--success-gradient);
      color: white;
    }

    .type-parttime,
    .type-part-time {
      background: var(--info-gradient);
      color: white;
    }

    .type-contract {
      background: var(--warning-gradient);
      color: #333;
    }

    .type-internship {
      background: var(--primary-gradient);
      color: white;
    }

    /* Category Badge */
    .category-badge {
      padding: 4px 12px;
      border-radius: 4px;
      font-weight: 500;
      font-size: 0.8rem;
      background: rgba(255,255,255,0.2);
      color: #fff;
      border: 1px solid rgba(255,255,255,0.3);
    }

    /* Stats Bar */
    .stats-bar {
      background: #fff;
      padding: 20px 35px;
      border-top: 1px solid rgba(0,0,0,0.05);
    }

    .stat-item {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-item i {
      font-size: 1.5rem;
      margin-right: 12px;
    }

    .stat-item .stat-number {
      font-size: 1.4rem;
      font-weight: 700;
      color: #34395e;
      line-height: 1.2;
    }

    .stat-item .stat-label {
      font-size: 0.8rem;
      color: #6c757d;
    }

    /* Info Cards */
    .info-card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      overflow: hidden;
      margin-bottom: 25px;
    }

    .info-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 35px rgba(0,0,0,0.12);
    }

    .info-card .card-header {
      background: transparent;
      border-bottom: 2px solid #f4f6f9;
      padding: 20px 25px;
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
      color: #6777ef;
      font-size: 1.1rem;
    }

    .info-card .card-body {
      padding: 25px;
    }

    /* Description Content */
    .description-content {
      line-height: 1.8;
      color: #555;
    }

    .description-content h5 {
      color: #34395e;
      font-weight: 700;
      margin-top: 25px;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .description-content h5:first-child {
      margin-top: 0;
    }

    .description-content h5 i {
      color: #6777ef;
    }

    .description-content ul {
      padding-left: 0;
      list-style: none;
      margin-bottom: 0;
    }

    .description-content ul li {
      padding: 8px 0 8px 28px;
      position: relative;
    }

    .description-content ul li::before {
      content: '\f00c';
      font-family: 'Font Awesome 5 Free';
      font-weight: 900;
      position: absolute;
      left: 0;
      color: #28a745;
      font-size: 0.85rem;
    }

    /* Quick Info */
    .quick-info-item {
      display: flex;
      align-items: center;
      padding: 15px 0;
      border-bottom: 1px dashed #e9ecef;
    }

    .quick-info-item:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .quick-info-item:first-child {
      padding-top: 0;
    }

    .quick-info-icon {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      font-size: 1rem;
      background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%);
      color: #6777ef;
    }

    .quick-info-label {
      font-size: 0.8rem;
      color: #6c757d;
      margin-bottom: 2px;
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
      padding: 25px;
      text-align: center;
      margin-bottom: 25px;
    }

    .deadline-card.urgent {
      animation: urgentPulse 2s infinite;
    }

    @keyframes urgentPulse {
      0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.3); }
      50% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    }

    .deadline-icon {
      font-size: 2.5rem;
      color: #dc3545;
      margin-bottom: 12px;
    }

    .deadline-label {
      font-size: 0.85rem;
      color: #666;
      margin-bottom: 5px;
    }

    .deadline-date {
      font-size: 1.3rem;
      font-weight: 800;
      color: #dc3545;
    }

    .deadline-remaining {
      font-size: 0.8rem;
      color: #856404;
      background: #fff3cd;
      padding: 5px 15px;
      border-radius: 50px;
      margin-top: 10px;
      display: inline-block;
    }

    /* Action Buttons */
    .action-buttons .btn {
      padding: 10px 25px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .action-buttons .btn:hover {
      transform: translateY(-2px);
    }

    .btn-gradient-primary {
      background: var(--primary-gradient);
      border: none;
      color: white;
    }

    .btn-gradient-primary:hover {
      color: white;
      box-shadow: 0 8px 25px rgba(103, 119, 239, 0.4);
    }

    .btn-gradient-success {
      background: var(--success-gradient);
      border: none;
      color: white;
    }

    .btn-gradient-success:hover {
      color: white;
      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    }

    .btn-gradient-danger {
      background: var(--danger-gradient);
      border: none;
      color: white;
    }

    .btn-gradient-danger:hover {
      color: white;
      box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
    }

    /* Responsive */
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

      .job-title {
        font-size: 1.4rem;
      }

      .job-meta {
        justify-content: center;
      }

      .stats-bar {
        padding: 15px 20px;
      }

      .stat-item {
        margin-bottom: 15px;
      }
    }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
              <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Detail Lowongan Kerja</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="{{ route('lowongan-kerja.index') }}">Lowongan Kerja</a></div>
              <div class="breadcrumb-item">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Job Header Card -->
            <div class="card job-header-card">
                <!-- Header dengan Gradient -->
                <div class="job-header">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="company-logo">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center flex-wrap mb-2" style="gap: 10px;">
                                <h1 class="job-title mb-0">{{ $lowongan->judul ?? 'Senior Frontend Developer' }}</h1>
                                @php
                                    $tipe = $lowongan->tipe ?? 'full-time';
                                    $tipeClass = 'type-' . str_replace(' ', '-', strtolower($tipe));
                                @endphp
                                <span class="type-badge {{ $tipeClass }}">{{ ucfirst($tipe) }}</span>
                            </div>
                            <p class="company-name"><i class="fas fa-building mr-2"></i>{{ $lowongan->perusahaan ?? 'PT. Teknologi Nusantara Indonesia' }}</p>
                            <div class="job-meta">
                                <div class="job-meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $lowongan->lokasi ?? 'Jakarta Selatan, DKI Jakarta' }}</span>
                                </div>
                                <div class="job-meta-item">
                                    <i class="fas fa-tags"></i>
                                    <span class="category-badge">{{ $lowongan->category ?? 'IT & Software' }}</span>
                                </div>
                                <div class="job-meta-item">
                                    <i class="fas fa-calendar-plus"></i>
                                    <span>Diposting: {{ $lowongan->tanggal_tutup_indo }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            @if(($lowongan->status ?? 'open') == 'open')
                                <span class="status-badge status-open">Open</span>
                            @else
                                <span class="status-badge status-closed">Closed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stats Bar -->
                <div class="stats-bar">
                    <div class="row">
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <div class="stat-item">
                                <i class="fas fa-users text-primary"></i>
                                <div>
                                    <div class="stat-number">{{ $totalPelamar ?? 48 }}</div>
                                    <div class="stat-label">Total Pelamar</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <div class="stat-item">
                                <i class="fas fa-eye text-success"></i>
                                <div>
                                    <div class="stat-number">{{ number_format($totalViews ?? 1234) }}</div>
                                    <div class="stat-label">Dilihat</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <i class="fas fa-clock text-warning"></i>
                                <div>
                                    @php
                                        $hariTersisa = 12;
                                        if(isset($lowongan->tanggal_tutup)) {
                                            $hariTersisa = now()->diffInDays($lowongan->tanggal_tutup, false);
                                            $hariTersisa = $hariTersisa < 0 ? 0 : $hariTersisa;
                                        }
                                    @endphp
                                    <div class="stat-number">{{ ceil($hariTersisa) }}</div>
                                    <div class="stat-label">Hari Tersisa</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <i class="fas fa-percentage text-info"></i>
                                <div>
                                    <div class="stat-number">{{ $matchPercentage ?? 65 }}%</div>
                                    <div class="stat-label">Kualifikasi Match</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Deskripsi Pekerjaan -->
                    <div class="card info-card">
                        <div class="card-header">
                            <h4><i class="fas fa-file-alt"></i> Deskripsi Pekerjaan</h4>
                        </div>
                        <div class="card-body">
                            <div class="description-content">
                                @if(isset($lowongan->deskripsi) && $lowongan->deskripsi)
                                    {!! $lowongan->deskripsi !!}
                                @else
                                    <p>
                                        Kami sedang mencari kandidat yang berpengalaman dan passionate untuk bergabung dengan tim kami. 
                                        Anda akan bertanggung jawab dalam pengembangan dan pemeliharaan aplikasi yang melayani banyak pengguna.
                                    </p>

                                    <h5><i class="fas fa-tasks"></i> Tanggung Jawab</h5>
                                    <ul>
                                        <li>Mengembangkan fitur-fitur baru sesuai kebutuhan</li>
                                        <li>Berkolaborasi dengan tim untuk implementasi yang optimal</li>
                                        <li>Melakukan code review dan mentoring untuk tim</li>
                                        <li>Mengoptimalkan performa aplikasi</li>
                                    </ul>

                                    <h5><i class="fas fa-clipboard-check"></i> Kualifikasi</h5>
                                    <ul>
                                        <li>Minimal 2 tahun pengalaman di bidang terkait</li>
                                        <li>Memiliki kemampuan komunikasi yang baik</li>
                                        <li>Mampu bekerja dalam tim maupun individu</li>
                                        <li>Memiliki motivasi tinggi untuk belajar</li>
                                    </ul>

                                    <h5><i class="fas fa-gift"></i> Benefit</h5>
                                    <ul>
                                        <li>Gaji kompetitif</li>
                                        <li>BPJS Kesehatan & Ketenagakerjaan</li>
                                        <li>Lingkungan kerja yang nyaman</li>
                                        <li>Kesempatan pengembangan karir</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Deadline Card -->
                    @php
                        $isUrgent = isset($hariTersisa) && $hariTersisa <= 7;
                    @endphp
                    <div class="deadline-card {{ $isUrgent ? 'urgent' : '' }}">
                        <div class="deadline-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <div class="deadline-label">Batas Akhir Pendaftaran</div>
                        <div class="deadline-date">
                            {{ $lowongan->tanggal_tutup_indo  }}
                        </div>
                        @if($hariTersisa > 0)
                            <div class="deadline-remaining">
                                <i class="fas fa-exclamation-triangle mr-1"></i> {{ ceil($hariTersisa) }} hari lagi
                            </div>
                        @else
                            <div class="deadline-remaining" style="background: #f8d7da; color: #721c24;">
                                <i class="fas fa-times-circle mr-1"></i> Sudah Ditutup
                            </div>
                        @endif
                    </div>

                    <!-- Quick Info -->
                    <div class="card info-card">
                        <div class="card-header">
                            <h4><i class="fas fa-info-circle"></i> Informasi Lowongan</h4>
                        </div>
                        <div class="card-body">
                            <div class="quick-info-item">
                                <div class="quick-info-icon"><i class="fas fa-briefcase"></i></div>
                                <div>
                                    <div class="quick-info-label">Tipe Pekerjaan</div>
                                    <div class="quick-info-value">{{ ucfirst($lowongan->tipe ?? 'Full-time') }}</div>
                                </div>
                            </div>
                            <div class="quick-info-item">
                                <div class="quick-info-icon"><i class="fas fa-building"></i></div>
                                <div>
                                    <div class="quick-info-label">Perusahaan</div>
                                    <div class="quick-info-value">{{ $lowongan->perusahaan ?? 'PT. Teknologi Nusantara Indonesia' }}</div>
                                </div>
                            </div>
                            <div class="quick-info-item">
                                <div class="quick-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div>
                                    <div class="quick-info-label">Lokasi</div>
                                    <div class="quick-info-value">{{ $lowongan->lokasi ?? 'Jakarta Selatan' }}</div>
                                </div>
                            </div>
                            <div class="quick-info-item">
                                <div class="quick-info-icon"><i class="fas fa-tags"></i></div>
                                <div>
                                    <div class="quick-info-label">Kategori</div>
                                    <div class="quick-info-value">{{ $lowongan->category ?? 'IT & Software' }}</div>
                                </div>
                            </div>
                            <div class="quick-info-item">
                                <div class="quick-info-icon"><i class="fas fa-toggle-on"></i></div>
                                <div>
                                    <div class="quick-info-label">Status</div>
                                    <div class="quick-info-value">
                                        @if(($lowongan->status ?? 'open') == 'open')
                                            <span class="badge badge-success">Open</span>
                                        @else
                                            <span class="badge badge-danger">Closed</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="quick-info-item">
                                <div class="quick-info-icon"><i class="fas fa-calendar-alt"></i></div>
                                <div>
                                    <div class="quick-info-label">Tanggal Tutup</div>
                                    <div class="quick-info-value">
                                        {{ $lowongan->tanggal_tutup_indo  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card info-card">
                <div class="card-body">
                    <div class="action-buttons d-flex flex-wrap justify-content-between align-items-center">
                        <div class="mb-2 mb-md-0">
                            <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-light mr-2">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('lowongan-kerja.form', $lowongan->id) }}" class="btn btn-primary">
                                <i class="fas fa-check mr-1"></i> Lamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection