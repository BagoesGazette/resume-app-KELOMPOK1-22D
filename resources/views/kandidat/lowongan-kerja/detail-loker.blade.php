<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Detail Lowongan Kerja &mdash; Stisla</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="https://themewagon.github.io/stisla-1/assets/css/style.css">
  <link rel="stylesheet" href="https://themewagon.github.io/stisla-1/assets/css/components.css">

  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
      --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
      --info-gradient: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
    }

    .main-content {
      padding-top: 80px;
    }

    /* Job Header */
    .job-header-card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .job-header {
      background: var(--primary-gradient);
      padding: 40px;
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
      width: 90px;
      height: 90px;
      background: white;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: #667eea;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      position: relative;
      z-index: 1;
    }

    .job-title {
      font-size: 2rem;
      font-weight: 800;
      margin-bottom: 8px;
      position: relative;
      z-index: 1;
    }

    .company-name {
      font-size: 1.2rem;
      opacity: 0.95;
      font-weight: 500;
      position: relative;
      z-index: 1;
    }

    .job-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
      position: relative;
      z-index: 1;
    }

    .job-meta-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.95rem;
      opacity: 0.9;
    }

    .job-meta-item i {
      width: 20px;
    }

    /* Status Badges */
    .status-badge {
      padding: 10px 25px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .status-open {
      background: rgba(40, 167, 69, 0.15);
      color: #28a745;
      border: 2px solid #28a745;
    }

    .status-open::before {
      content: '';
      width: 10px;
      height: 10px;
      background: #28a745;
      border-radius: 50%;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.5; transform: scale(1.2); }
    }

    .status-closed {
      background: rgba(220, 53, 69, 0.15);
      color: #dc3545;
      border: 2px solid #dc3545;
    }

    /* Type Badge */
    .type-badge {
      padding: 8px 20px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.85rem;
      text-transform: capitalize;
    }

    .type-fulltime {
      background: var(--success-gradient);
      color: white;
    }

    .type-parttime {
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
      padding: 6px 15px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 0.8rem;
      background: #f0f0f0;
      color: #666;
      border: 1px solid #ddd;
    }

    /* Info Cards */
    .info-card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      overflow: hidden;
    }

    .info-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.12);
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
      font-size: 1.2rem;
    }

    .info-card .card-body {
      padding: 25px;
    }

    /* Stats Cards */
    .stat-card {
      background: white;
      border-radius: 15px;
      padding: 25px;
      text-align: center;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      border: none;
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
    .stat-card.warning::before { background: var(--warning-gradient); }
    .stat-card.info::before { background: var(--info-gradient); }

    .stat-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      font-size: 1.5rem;
      color: white;
    }

    .stat-icon.primary { background: var(--primary-gradient); }
    .stat-icon.success { background: var(--success-gradient); }
    .stat-icon.warning { background: var(--warning-gradient); }
    .stat-icon.info { background: var(--info-gradient); }

    .stat-value {
      font-size: 2rem;
      font-weight: 800;
      color: #34395e;
      line-height: 1;
      margin-bottom: 5px;
    }

    .stat-label {
      color: #6c757d;
      font-size: 0.9rem;
      font-weight: 500;
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
    }

    .description-content ul li {
      padding: 8px 0 8px 30px;
      position: relative;
    }

    .description-content ul li::before {
      content: '\f00c';
      font-family: 'Font Awesome 5 Free';
      font-weight: 900;
      position: absolute;
      left: 0;
      color: #28a745;
    }

    /* Deadline Card */
    .deadline-card {
      background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 100%);
      border: 2px solid #ffcccc;
      border-radius: 15px;
      padding: 25px;
      text-align: center;
    }

    .deadline-card.urgent {
      animation: urgentPulse 2s infinite;
    }

    @keyframes urgentPulse {
      0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.3); }
      50% { box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
    }

    .deadline-icon {
      font-size: 3rem;
      color: #dc3545;
      margin-bottom: 15px;
    }

    .deadline-label {
      font-size: 0.9rem;
      color: #666;
      margin-bottom: 5px;
    }

    .deadline-date {
      font-size: 1.5rem;
      font-weight: 800;
      color: #dc3545;
    }

    .deadline-remaining {
      font-size: 0.85rem;
      color: #856404;
      background: #fff3cd;
      padding: 5px 15px;
      border-radius: 50px;
      margin-top: 10px;
      display: inline-block;
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
    }

    .quick-info-icon {
      width: 45px;
      height: 45px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      font-size: 1.1rem;
      background: #f8f9fa;
      color: #6777ef;
    }

    .quick-info-label {
      font-size: 0.85rem;
      color: #6c757d;
      margin-bottom: 2px;
    }

    .quick-info-value {
      font-weight: 700;
      color: #34395e;
    }

    /* Action Buttons */
    .action-buttons .btn {
      padding: 12px 30px;
      border-radius: 10px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .action-buttons .btn:hover {
      transform: translateY(-3px);
    }

    .btn-gradient-primary {
      background: var(--primary-gradient);
      border: none;
      color: white;
    }

    .btn-gradient-primary:hover {
      color: white;
      box-shadow: 0 10px 30px rgba(103, 119, 239, 0.4);
    }

    .btn-gradient-success {
      background: var(--success-gradient);
      border: none;
      color: white;
    }

    .btn-gradient-success:hover {
      color: white;
      box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
    }

    .btn-gradient-danger {
      background: var(--danger-gradient);
      border: none;
      color: white;
    }

    .btn-gradient-danger:hover {
      color: white;
      box-shadow: 0 10px 30px rgba(220, 53, 69, 0.4);
    }

    /* Timeline */
    .timeline-horizontal {
      display: flex;
      justify-content: space-between;
      position: relative;
      padding: 20px 0;
    }

    .timeline-horizontal::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 4px;
      background: #e9ecef;
      transform: translateY(-50%);
      z-index: 0;
    }

    .timeline-step {
      text-align: center;
      position: relative;
      z-index: 1;
      flex: 1;
    }

    .timeline-step-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: white;
      border: 4px solid #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 10px;
      font-size: 1.2rem;
      color: #6c757d;
      transition: all 0.3s ease;
    }

    .timeline-step.active .timeline-step-icon {
      background: var(--primary-gradient);
      border-color: #667eea;
      color: white;
    }

    .timeline-step.completed .timeline-step-icon {
      background: var(--success-gradient);
      border-color: #28a745;
      color: white;
    }

    .timeline-step-label {
      font-size: 0.85rem;
      color: #6c757d;
      font-weight: 500;
    }

    .timeline-step.active .timeline-step-label,
    .timeline-step.completed .timeline-step-label {
      color: #34395e;
      font-weight: 700;
    }

    /* Recent Applicants */
    .applicant-item {
      display: flex;
      align-items: center;
      padding: 12px;
      border-radius: 10px;
      margin-bottom: 10px;
      background: #f8f9fa;
      transition: all 0.3s ease;
    }

    .applicant-item:hover {
      background: #e9ecef;
      transform: translateX(5px);
    }

    .applicant-avatar {
      width: 45px;
      height: 45px;
      border-radius: 12px;
      object-fit: cover;
      margin-right: 12px;
    }

    .applicant-info h6 {
      margin: 0;
      font-weight: 600;
      color: #34395e;
      font-size: 0.95rem;
    }

    .applicant-info small {
      color: #6c757d;
    }

    .applicant-status {
      margin-left: auto;
      padding: 4px 12px;
      border-radius: 50px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .applicant-status.pending { background: #fff3cd; color: #856404; }
    .applicant-status.review { background: #cce5ff; color: #004085; }
    .applicant-status.interview { background: #e2d5f1; color: #6f42c1; }
    .applicant-status.accepted { background: #d4edda; color: #155724; }

    /* Share Buttons */
    .share-buttons {
      display: flex;
      gap: 10px;
    }

    .share-btn {
      width: 45px;
      height: 45px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
      transition: all 0.3s ease;
    }

    .share-btn:hover {
      transform: scale(1.1);
      color: white;
    }

    .share-btn.facebook { background: #1877f2; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.linkedin { background: #0077b5; }
    .share-btn.whatsapp { background: #25d366; }
    .share-btn.copy { background: #6c757d; }

    /* Responsive */
    @media (max-width: 768px) {
      .job-header {
        padding: 25px;
        text-align: center;
      }

      .job-title {
        font-size: 1.5rem;
      }

      .job-meta {
        justify-content: center;
      }

      .timeline-horizontal {
        flex-direction: column;
        gap: 20px;
      }

      .timeline-horizontal::before {
        width: 4px;
        height: 100%;
        top: 0;
        left: 25px;
        transform: none;
      }

      .timeline-step {
        display: flex;
        align-items: center;
        text-align: left;
        gap: 15px;
      }

      .timeline-step-icon {
        margin: 0;
      }
    }
  </style>
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <!-- Navbar -->
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
          <div class="search-element">
            <input class="form-control" type="search" placeholder="Cari lowongan..." aria-label="Search" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
          </div>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg beep"><i class="far fa-bell"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              <div class="dropdown-header">Notifications</div>
              <div class="dropdown-list-content dropdown-list-icons">
                <a href="#" class="dropdown-item">
                  <div class="dropdown-item-icon bg-primary text-white"><i class="fas fa-user-plus"></i></div>
                  <div class="dropdown-item-desc">5 Pelamar baru untuk posisi ini<div class="time">10 menit yang lalu</div></div>
                </a>
              </div>
              <div class="dropdown-footer text-center"><a href="#">View All</a></div>
            </div>
          </li>
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="https://themewagon.github.io/stisla-1/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
              <div class="d-sm-none d-lg-inline-block">Hi, Admin HR</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="#" class="dropdown-item has-icon"><i class="far fa-user"></i> Profile</a>
              <a href="#" class="dropdown-item has-icon"><i class="fas fa-cog"></i> Settings</a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
          </li>
        </ul>
      </nav>

      <!-- Sidebar -->
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="#">HRD System</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">HR</a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li><a class="nav-link" href="#"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
            
            <li class="menu-header">Rekrutmen</li>
            <li><a class="nav-link" href="#"><i class="fas fa-users"></i> <span>Lamaran Kerja</span></a></li>
            <li class="dropdown active">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-briefcase"></i> <span>Lowongan Kerja</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">Semua Lowongan</a></li>
                <li class="active"><a class="nav-link" href="#">Detail Lowongan</a></li>
                <li><a class="nav-link" href="#">Tambah Lowongan</a></li>
              </ul>
            </li>
            
            <li class="menu-header">Karyawan</li>
            <li><a class="nav-link" href="#"><i class="fas fa-user-tie"></i> <span>Data Karyawan</span></a></li>
            <li><a class="nav-link" href="#"><i class="fas fa-calendar-alt"></i> <span>Absensi</span></a></li>
            
            <li class="menu-header">Pengaturan</li>
            <li><a class="nav-link" href="#"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <div class="section-header-back">
              <a href="#" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Detail Lowongan Kerja</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Lowongan Kerja</a></div>
              <div class="breadcrumb-item">Detail</div>
            </div>
          </div>

          <div class="section-body">
            <!-- Job Header Card -->
            <div class="card job-header-card mb-4">
              <div class="job-header">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <div class="company-logo">
                      <i class="fas fa-building"></i>
                    </div>
                  </div>
                  <div class="col">
                    <div class="d-flex align-items-center flex-wrap gap-3 mb-2">
                      <h1 class="job-title mb-0">Senior Frontend Developer</h1>
                      <span class="type-badge type-fulltime">Full-time</span>
                    </div>
                    <p class="company-name mb-0"><i class="fas fa-building mr-2"></i>PT. Teknologi Nusantara Indonesia</p>
                    <div class="job-meta">
                      <div class="job-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Jakarta Selatan, DKI Jakarta</span>
                      </div>
                      <div class="job-meta-item">
                        <i class="fas fa-tags"></i>
                        <span class="category-badge">IT & Software</span>
                      </div>
                      <div class="job-meta-item">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Diposting: 01 Desember 2025</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-auto text-right">
                    <span class="status-badge status-open">Open</span>
                  </div>
                </div>
              </div>
              
              <!-- Quick Stats Bar -->
              <div class="card-body bg-light">
                <div class="row text-center">
                  <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <div class="d-flex align-items-center justify-content-center">
                      <i class="fas fa-users text-primary mr-2" style="font-size: 1.5rem;"></i>
                      <div class="text-left">
                        <div class="font-weight-bold" style="font-size: 1.3rem;">48</div>
                        <small class="text-muted">Total Pelamar</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <div class="d-flex align-items-center justify-content-center">
                      <i class="fas fa-eye text-success mr-2" style="font-size: 1.5rem;"></i>
                      <div class="text-left">
                        <div class="font-weight-bold" style="font-size: 1.3rem;">1,234</div>
                        <small class="text-muted">Dilihat</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center justify-content-center">
                      <i class="fas fa-clock text-warning mr-2" style="font-size: 1.5rem;"></i>
                      <div class="text-left">
                        <div class="font-weight-bold" style="font-size: 1.3rem;">12</div>
                        <small class="text-muted">Hari Tersisa</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center justify-content-center">
                      <i class="fas fa-percentage text-info mr-2" style="font-size: 1.5rem;"></i>
                      <div class="text-left">
                        <div class="font-weight-bold" style="font-size: 1.3rem;">65%</div>
                        <small class="text-muted">Kualifikasi Match</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- Left Column -->
              <div class="col-lg-8">
                <!-- Job Description -->
                <div class="card info-card mb-4">
                  <div class="card-header">
                    <h4><i class="fas fa-file-alt"></i> Deskripsi Pekerjaan</h4>
                  </div>
                  <div class="card-body">
                    <div class="description-content">
                      <p>
                        PT. Teknologi Nusantara Indonesia sedang mencari <strong>Senior Frontend Developer</strong> yang berpengalaman dan passionate untuk bergabung dengan tim engineering kami. Anda akan bertanggung jawab dalam pengembangan dan pemeliharaan aplikasi web modern yang melayani jutaan pengguna.
                      </p>

                      <h5><i class="fas fa-tasks"></i> Tanggung Jawab</h5>
                      <ul>
                        <li>Mengembangkan fitur-fitur baru menggunakan React.js dan Next.js</li>
                        <li>Berkolaborasi dengan tim UI/UX untuk implementasi design yang pixel-perfect</li>
                        <li>Melakukan code review dan mentoring untuk junior developer</li>
                        <li>Mengoptimalkan performa aplikasi untuk pengalaman pengguna terbaik</li>
                        <li>Menulis unit test dan integration test untuk menjaga kualitas kode</li>
                        <li>Berpartisipasi dalam architectural decisions dan technical planning</li>
                      </ul>

                      <h5><i class="fas fa-clipboard-check"></i> Kualifikasi</h5>
                      <ul>
                        <li>Minimal 4 tahun pengalaman sebagai Frontend Developer</li>
                        <li>Mahir dalam JavaScript/TypeScript, React.js, dan Next.js</li>
                        <li>Pengalaman dengan state management (Redux, Zustand, atau Recoil)</li>
                        <li>Familiar dengan CSS frameworks (Tailwind CSS, Styled Components)</li>
                        <li>Pemahaman yang baik tentang RESTful API dan GraphQL</li>
                        <li>Pengalaman dengan Git workflow dan CI/CD pipeline</li>
                        <li>Kemampuan komunikasi yang baik dalam tim</li>
                      </ul>

                      <h5><i class="fas fa-star"></i> Nice to Have</h5>
                      <ul>
                        <li>Pengalaman dengan micro-frontend architecture</li>
                        <li>Kontribusi pada open source projects</li>
                        <li>Sertifikasi AWS atau cloud platform lainnya</li>
                        <li>Pengalaman memimpin tim engineering</li>
                      </ul>

                      <h5><i class="fas fa-gift"></i> Benefit</h5>
                      <ul>
                        <li>Gaji kompetitif: Rp 20.000.000 - Rp 35.000.000/bulan</li>
                        <li>BPJS Kesehatan & Ketenagakerjaan</li>
                        <li>Asuransi kesehatan swasta untuk karyawan dan keluarga</li>
                        <li>Bonus tahunan berdasarkan performa</li>
                        <li>Flexible working hours & Remote-friendly</li>
                        <li>Learning budget untuk kursus dan sertifikasi</li>
                        <li>MacBook Pro untuk bekerja</li>
                        <li>Cuti tahunan 15 hari + cuti tambahan</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <!-- Recruitment Process -->
                <div class="card info-card mb-4">
                  <div class="card-header">
                    <h4><i class="fas fa-route"></i> Proses Rekrutmen</h4>
                  </div>
                  <div class="card-body">
                    <div class="timeline-horizontal">
                      <div class="timeline-step completed">
                        <div class="timeline-step-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="timeline-step-label">Screening CV</div>
                      </div>
                      <div class="timeline-step active">
                        <div class="timeline-step-icon"><i class="fas fa-phone"></i></div>
                        <div class="timeline-step-label">Phone Interview</div>
                      </div>
                      <div class="timeline-step">
                        <div class="timeline-step-icon"><i class="fas fa-code"></i></div>
                        <div class="timeline-step-label">Technical Test</div>
                      </div>
                      <div class="timeline-step">
                        <div class="timeline-step-icon"><i class="fas fa-users"></i></div>
                        <div class="timeline-step-label">Team Interview</div>
                      </div>
                      <div class="timeline-step">
                        <div class="timeline-step-icon"><i class="fas fa-handshake"></i></div>
                        <div class="timeline-step-label">Offering</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                  <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card primary">
                      <div class="stat-icon primary"><i class="fas fa-users"></i></div>
                      <div class="stat-value">48</div>
                      <div class="stat-label">Total Pelamar</div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card warning">
                      <div class="stat-icon warning"><i class="fas fa-hourglass-half"></i></div>
                      <div class="stat-value">15</div>
                      <div class="stat-label">Pending Review</div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card info">
                      <div class="stat-icon info"><i class="fas fa-calendar-check"></i></div>
                      <div class="stat-value">8</div>
                      <div class="stat-label">Interview</div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card success">
                      <div class="stat-icon success"><i class="fas fa-user-check"></i></div>
                      <div class="stat-value">3</div>
                      <div class="stat-label">Shortlisted</div>
                    </div>
                  </div>
                </div>

                <!-- Recent Applicants -->
                <div class="card info-card mb-4">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-user-friends"></i> Pelamar Terbaru</h4>
                    <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
                  </div>
                  <div class="card-body">
                    <div class="applicant-item">
                      <img src="https://themewagon.github.io/stisla-1/assets/img/avatar/avatar-1.png" alt="Avatar" class="applicant-avatar">
                      <div class="applicant-info">
                        <h6>Ahmad Rizky Pratama</h6>
                        <small>4 tahun pengalaman • Melamar 2 jam lalu</small>
                      </div>
                      <span class="applicant-status review">Review</span>
                    </div>
                    <div class="applicant-item">
                      <img src="https://themewagon.github.io/stisla-1/assets/img/avatar/avatar-2.png" alt="Avatar" class="applicant-avatar">
                      <div class="applicant-info">
                        <h6>Siti Nurhaliza</h6>
                        <small>3 tahun pengalaman • Melamar 5 jam lalu</small>
                      </div>
                      <span class="applicant-status interview">Interview</span>
                    </div>
                    <div class="applicant-item">
                      <img src="https://themewagon.github.io/stisla-1/assets/img/avatar/avatar-3.png" alt="Avatar" class="applicant-avatar">
                      <div class="applicant-info">
                        <h6>Budi Santoso</h6>
                        <small>5 tahun pengalaman • Melamar 1 hari lalu</small>
                      </div>
                      <span class="applicant-status pending">Pending</span>
                    </div>
                    <div class="applicant-item">
                      <img src="https://themewagon.github.io/stisla-1/assets/img/avatar/avatar-4.png" alt="Avatar" class="applicant-avatar">
                      <div class="applicant-info">
                        <h6>Dewi Kartika</h6>
                        <small>6 tahun pengalaman • Melamar 2 hari lalu</small>
                      </div>
                      <span class="applicant-status accepted">Shortlisted</span>
                    </div>
                    <div class="applicant-item">
                      <img src="https://themewagon.github.io/stisla-1/assets/img/avatar/avatar-5.png" alt="Avatar" class="applicant-avatar">
                      <div class="applicant-info">
                        <h6>Eko Prasetyo</h6>
                        <small>2 tahun pengalaman • Melamar 3 hari lalu</small>
                      </div>
                      <span class="applicant-status pending">Pending</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-lg-4">
                <!-- Deadline Card -->
                <div class="deadline-card urgent mb-4">
                  <div class="deadline-icon">
                    <i class="fas fa-calendar-times"></i>
                  </div>
                  <div class="deadline-label">Batas Akhir Pendaftaran</div>
                  <div class="deadline-date">18 Desember 2025</div>
                  <div class="deadline-remaining">
                    <i class="fas fa-exclamation-triangle mr-1"></i> 12 hari lagi
                  </div>
                </div>

                <!-- Quick Info -->
                <div class="card info-card mb-4">
                  <div class="card-header">
                    <h4><i class="fas fa-info-circle"></i> Informasi Lowongan</h4>
                  </div>
                  <div class="card-body">
                    <div class="quick-info-item">
                      <div class="quick-info-icon"><i class="fas fa-briefcase"></i></div>
                      <div>
                        <div class="quick-info-label">Tipe Pekerjaan</div>
                        <div class="quick-info-value">Full-time</div>
                      </div>
                    </div>
                    <div class="quick-info-item">
                      <div class="quick-info-icon"><i class="fas fa-building"></i></div>
                      <div>
                        <div class="quick-info-label">Perusahaan</div>
                        <div class="quick-info-value">PT. Teknologi Nusantara Indonesia</div>
                      </div>
                    </div>
                    <div class="quick-info-item">
                      <div class="quick-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                      <div>
                        <div class="quick-info-label">Lokasi</div>
                        <div class="quick-info-value">Jakarta Selatan, DKI Jakarta</div>
                      </div>
                    </div>
                    <div class="quick-info-item">
                      <div class="quick-info-icon"><i class="fas fa-tags"></i></div>
                      <div>
                        <div class="quick-info-label">Kategori</div>
                        <div class="quick-info-value">IT & Software</div>
                      </div>
                    </div>
                    <div class="quick-info-item">
                      <div class="quick-info-icon"><i class="fas fa-toggle-on"></i></div>
                      <div>
                        <div class="quick-info-label">Status</div>
                        <div class="quick-info-value">
                          <span class="badge badge-success">Open</span>
                        </div>
                      </div>
                    </div>
                    <div class="quick-info-item">
                      <div class="quick-info-icon"><i class="fas fa-calendar-alt"></i></div>
                      <div>
                        <div class="quick-info-label">Tanggal Tutup</div>
                        <div class="quick-info-value">18 Desember 2025</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Share Job -->
                <div class="card info-card mb-4">
                  <div class="card-header">
                    <h4><i class="fas fa-share-alt"></i> Bagikan Lowongan</h4>
                  </div>
                  <div class="card-body">
                    <div class="share-buttons">
                      <a href="#" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                      <a href="#" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
                      <a href="#" class="share-btn linkedin"><i class="fab fa-linkedin-in"></i></a>
                      <a href="#" class="share-btn whatsapp"><i class="fab fa-whatsapp"></i></a>
                      <a href="#" class="share-btn copy" title="Copy Link"><i class="fas fa-link"></i></a>
                    </div>
                    <div class="mt-3">
                      <div class="input-group">
                        <input type="text" class="form-control" value="https://hrd.company.com/job/123" readonly>
                        <div class="input-group-append">
                          <button class="btn btn-primary" type="button"><i class="fas fa-copy"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Company Info -->
                <div class="card info-card mb-4">
                  <div class="card-header">
                    <h4><i class="fas fa-building"></i> Tentang Perusahaan</h4>
                  </div>
                  <div class="card-body text-center">
                    <div class="company-logo mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                      <i class="fas fa-building"></i>
                    </div>
                    <h5 class="mb-1">PT. Teknologi Nusantara Indonesia</h5>
                    <p class="text-muted small mb-3">Technology • 500+ Karyawan</p>
                    <p class="text-muted small">
                      Perusahaan teknologi terkemuka di Indonesia yang fokus pada pengembangan solusi digital untuk berbagai industri.
                    </p>
                    <a href="#" class="btn btn-outline-primary btn-sm btn-block">
                      <i class="fas fa-external-link-alt mr-1"></i> Lihat Profil Perusahaan
                    </a>
                  </div>
                </div>

                <!-- Similar Jobs -->
                <div class="card info-card mb-4">
                  <div class="card-header">
                    <h4><i class="fas fa-clone"></i> Lowongan Serupa</h4>
                  </div>
                  <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                      <h6 class="mb-1"><a href="#" class="text-dark">Frontend Developer</a></h6>
                      <small class="text-muted d-block">PT. Digital Indonesia</small>
                      <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> Bandung</small>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                      <h6 class="mb-1"><a href="#" class="text-dark">React Developer</a></h6>
                      <small class="text-muted d-block">PT. Startup Maju</small>
                      <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> Jakarta</small>
                    </div>
                    <div>
                      <h6 class="mb-1"><a href="#" class="text-dark">Full Stack Developer</a></h6>
                      <small class="text-muted d-block">PT. Tech Solutions</small>
                      <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> Surabaya</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="card info-card">
              <div class="card-body">
                <div class="action-buttons d-flex flex-wrap justify-content-between align-items-center">
                  <div class="mb-2">
                    <a href="#" class="btn btn-light mr-2"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                    <button class="btn btn-light mr-2"><i class="fas fa-print mr-1"></i> Cetak</button>
                    <button class="btn btn-light"><i class="fas fa-download mr-1"></i> Export PDF</button>
                  </div>
                  <div class="mb-2">
                    <button class="btn btn-gradient-danger mr-2"><i class="fas fa-times-circle mr-1"></i> Tutup Lowongan</button>
                    <button class="btn btn-warning mr-2"><i class="fas fa-edit mr-1"></i> Edit</button>
                    <button class="btn btn-gradient-success"><i class="fas fa-check-circle mr-1"></i> Publikasikan Ulang</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </section>
      </div>

      <!-- Footer -->
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2025 <div class="bullet"></div> HRD System
        </div>
        <div class="footer-right">
          Version 2.3.0
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="https://themewagon.github.io/stisla-1/assets/js/stisla.js"></script>
  <script src="https://themewagon.github.io/stisla-1/assets/js/scripts.js"></script>

  <script>
    $(document).ready(function() {
      // Copy link functionality
      $('.share-btn.copy, .input-group-append .btn').click(function(e) {
        e.preventDefault();
        const linkInput = $('input[value="https://hrd.company.com/job/123"]');
        linkInput.select();
        document.execCommand('copy');
        
        // Show feedback
        const originalHtml = $(this).html();
        $(this).html('<i class="fas fa-check"></i>');
        setTimeout(() => {
          $(this).html(originalHtml);
        }, 2000);
      });

      // Confirm close job
      $('.btn-gradient-danger').click(function(e) {
        if(!confirm('Apakah Anda yakin ingin menutup lowongan ini? Pelamar baru tidak akan bisa mendaftar.')) {
          e.preventDefault();
        }
      });

      // Sidebar toggle
      $('[data-toggle="sidebar"]').click(function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mini');
      });
    });
  </script>
</body>
</html>