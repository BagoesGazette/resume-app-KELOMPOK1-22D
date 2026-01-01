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

/* Profile Header Card */
.profile-header-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

.profile-cover {
    height: 200px;
    background: var(--primary-gradient);
    position: relative;
    overflow: hidden;
}

.profile-cover::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 400px;
    height: 400px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.profile-cover::after {
    content: '';
    position: absolute;
    bottom: -150px;
    left: -50px;
    width: 350px;
    height: 350px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.cover-edit-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.cover-edit-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}

.profile-info-section {
    padding: 0 30px 30px;
    position: relative;
}

.profile-avatar-wrapper {
    position: relative;
    width: 150px;
    height: 150px;
    margin-top: -75px;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 20px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    font-weight: 800;
    color: #667eea;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    border: 5px solid white;
    overflow: hidden;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-edit-btn {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 40px;
    height: 40px;
    background: var(--primary-gradient);
    border: 3px solid white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.avatar-edit-btn:hover {
    transform: scale(1.1);
}

.avatar-edit-btn input {
    display: none;
}

.profile-main-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
}

.profile-details h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #34395e;
    margin-bottom: 5px;
}

.profile-details .email {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 10px;
}

.profile-details .email i {
    color: #667eea;
    margin-right: 8px;
}

.profile-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
}

.profile-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 15px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    border-radius: 50px;
    font-size: 0.85rem;
    color: #667eea;
    font-weight: 500;
}

.profile-badge i {
    font-size: 0.9rem;
}

.profile-completion {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    border-radius: 15px;
    padding: 20px;
    min-width: 200px;
}

.profile-completion h6 {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 10px;
}

.completion-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: conic-gradient(#667eea 0% 75%, #e9ecef 75% 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    position: relative;
}

.completion-circle::before {
    content: '';
    position: absolute;
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 50%;
}

.completion-circle span {
    position: relative;
    font-size: 1.2rem;
    font-weight: 800;
    color: #667eea;
}

.completion-text {
    text-align: center;
    font-size: 0.85rem;
    color: #34395e;
    font-weight: 600;
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 25px;
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

.info-card .card-body {
    padding: 25px;
}

/* Form Styling */
.form-section {
    margin-bottom: 30px;
}

.form-section:last-child {
    margin-bottom: 0;
}

.form-section-title {
    font-weight: 700;
    color: #34395e;
    font-size: 1rem;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f4f6f9;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-section-title i {
    color: #667eea;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    color: #555;
    margin-bottom: 8px;
    font-size: 0.9rem;
    display: block;
}

.form-group label .required {
    color: #e74c3c;
    margin-left: 3px;
}


/* Input with Icon */
.input-icon-wrapper {
    position: relative;
}

.input-icon-wrapper i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}


/* Info Display */
.info-display {
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

/* Quick Stats */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.quick-stat-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.quick-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.quick-stat-card .stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 1.3rem;
    color: white;
}

.quick-stat-card .stat-icon.primary { background: var(--primary-gradient); }
.quick-stat-card .stat-icon.success { background: var(--success-gradient); }
.quick-stat-card .stat-icon.warning { background: var(--warning-gradient); }

.quick-stat-card .stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #34395e;
}

.quick-stat-card .stat-label {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Sidebar Cards */
.sidebar-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.sidebar-card h5 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar-card h5 i {
    color: #667eea;
}

/* Account Status */
.account-status {
    text-align: center;
    padding: 20px;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-radius: 12px;
    margin-bottom: 20px;
}

.account-status.verified {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
}

.account-status.unverified {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
}

.account-status i {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.account-status.verified i {
    color: #28a745;
}

.account-status.unverified i {
    color: #ffc107;
}

.account-status h6 {
    font-weight: 700;
    margin-bottom: 5px;
}

.account-status.verified h6 {
    color: #155724;
}

.account-status.unverified h6 {
    color: #856404;
}

.account-status p {
    font-size: 0.85rem;
    margin: 0;
}

.account-status.verified p {
    color: #155724;
}

.account-status.unverified p {
    color: #856404;
}

/* Activity List */
.activity-list {
    max-height: 300px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px dashed #e9ecef;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 35px;
    height: 35px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    flex-shrink: 0;
}

.activity-icon.login { background: linear-gradient(135deg, #cce5ff 0%, #b8daff 100%); color: #004085; }
.activity-icon.update { background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); color: #155724; }
.activity-icon.apply { background: linear-gradient(135deg, #e2d5f1 0%, #c9b3e6 100%); color: #6f42c1; }

.activity-content {
    flex: 1;
}

.activity-content h6 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #34395e;
    margin: 0 0 3px 0;
}

.activity-content small {
    color: #6c757d;
    font-size: 0.8rem;
}

/* Buttons */
.btn-save {
    padding: 12px 30px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-save.btn-primary {
    background: var(--primary-gradient);
    border: none;
    color: white;
}

.btn-save.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.btn-save.btn-secondary {
    background: #f0f0f0;
    border: none;
    color: #666;
}

.btn-save.btn-secondary:hover {
    background: #e0e0e0;
}

/* Password Strength */
.password-strength {
    margin-top: 10px;
}

.strength-bar {
    height: 5px;
    background: #e9ecef;
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 5px;
}

.strength-fill {
    height: 100%;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.strength-fill.weak { width: 25%; background: #dc3545; }
.strength-fill.fair { width: 50%; background: #ffc107; }
.strength-fill.good { width: 75%; background: #17a2b8; }
.strength-fill.strong { width: 100%; background: #28a745; }

.strength-text {
    font-size: 0.8rem;
    font-weight: 500;
}

.strength-text.weak { color: #dc3545; }
.strength-text.fair { color: #ffc107; }
.strength-text.good { color: #17a2b8; }
.strength-text.strong { color: #28a745; }

/* Toggle Password */
.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 5px;
}

.password-toggle:hover {
    color: #667eea;
}

/* Danger Zone */
.danger-zone {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
    border: 2px solid #feb2b2;
    border-radius: 15px;
    padding: 25px;
}

.danger-zone h5 {
    color: #c53030;
    font-weight: 700;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.danger-zone p {
    color: #742a2a;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.danger-zone .btn-danger {
    background: var(--danger-gradient);
    border: none;
    padding: 10px 25px;
    border-radius: 10px;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 992px) {
    .info-display {
        grid-template-columns: 1fr;
    }

    .quick-stats {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .profile-cover {
        height: 150px;
    }

    .profile-avatar-wrapper {
        margin: -50px auto 20px;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        font-size: 2.5rem;
    }

    .profile-main-info {
        flex-direction: column;
        text-align: center;
    }

    .profile-details h2 {
        font-size: 1.4rem;
    }

    .profile-badges {
        justify-content: center;
    }

    .profile-completion {
        width: 100%;
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

.info-card, .sidebar-card {
    animation: fadeInUp 0.5s ease forwards;
}
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Profile Saya</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Profile</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="profile-cover">
            </div>
            <div class="profile-info-section">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
                        @else
                            {{ strtoupper(substr(Auth::user()->name ?? 'User', 0, 2)) }}
                        @endif
                    </div>
                </div>
                <div class="profile-main-info">
                    <div class="profile-details">
                        <h2>{{ Auth::user()->name ?? 'Ahmad Rizky Pratama' }}</h2>
                        <p class="email">
                            <i class="fas fa-envelope"></i>
                            {{ Auth::user()->email ?? 'ahmad.rizky@email.com' }}
                        </p>
                        <div class="profile-badges">
                            <span class="profile-badge">
                                <i class="fas fa-user"></i> Kandidat
                            </span>
                            <span class="profile-badge">
                                <i class="fas fa-calendar"></i> Bergabung {{ Auth::user()->created_at->format('M Y') ?? 'Des 2025' }}
                            </span>
                            @if(Auth::user()->email_verified_at)
                                <span class="profile-badge" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); color: #155724;">
                                    <i class="fas fa-check-circle"></i> Terverifikasi
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Personal Information Form -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-user bg-primary"></i> Informasi Pribadi</h4>
                        <button class="btn btn-sm btn-outline-primary" id="editPersonalBtn">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" id="personalForm" autocomplete="off">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap <span class="required">*</span></label>
                                        <div class="input-icon-wrapper">
                                            <input type="text" 
                                                name="name" 
                                                id="name" 
                                                class="form-control @error('name') is-invalid @enderror" 
                                                value="{{ old('name', Auth::user()->name) }}"
                                                placeholder="Masukkan nama lengkap"
                                                disabled>
                                        </div>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="required">*</span></label>
                                        <div class="input-icon-wrapper">
                                            <input type="email" 
                                                name="email" 
                                                id="email" 
                                                class="form-control @error('email') is-invalid @enderror" 
                                                value="{{ old('email', Auth::user()->email) }}"
                                                placeholder="Masukkan email"
                                                disabled>
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2" id="personalFormButtons" style="display: none !important;">
                                <button type="button" class="btn btn-save btn-secondary mr-2" id="cancelPersonalBtn">
                                    <i class="fas fa-times mr-1"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-save btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-lock bg-warning"></i> Ubah Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.password') }}" method="POST" id="passwordForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="current_password">Password Saat Ini <span class="required">*</span></label>
                                <div class="input-group mb-3">
                                    <input type="password" 
                                        name="current_password" 
                                        id="current_password" 
                                        class="form-control @error('current_password') is-invalid @enderror" 
                                        placeholder="Masukkan password saat ini">
                                    <div class="input-group-append">
                                        <a type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>

                                @error('current_password')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_password">Password Baru <span class="required">*</span></label>
                                        <div class="input-group">
                                            <input type="password" 
                                                name="new_password" 
                                                id="new_password" 
                                                class="form-control @error('new_password') is-invalid @enderror" 
                                                placeholder="Masukkan password baru"
                                                onkeyup="checkPasswordStrength()">
                                            <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength" id="passwordStrength" style="display: none;">
                                            <div class="strength-bar">
                                                <div class="strength-fill" id="strengthFill"></div>
                                            </div>
                                            <span class="strength-text" id="strengthText"></span>
                                        </div>
                                        @error('new_password')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_password_confirmation">Konfirmasi Password Baru <span class="required">*</span></label>
                                        <div class="input-group" style="position: relative;">
                                            <input type="password" 
                                                name="new_password_confirmation" 
                                                id="new_password_confirmation" 
                                                class="form-control" 
                                                placeholder="Ulangi password baru">
                                            <button type="button" class="password-toggle" onclick="togglePassword('new_password_confirmation')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info" style="border-radius: 10px;">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Tips Password Aman:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Minimal 8 karakter</li>
                                    <li>Kombinasi huruf besar dan kecil</li>
                                    <li>Sertakan angka dan simbol</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-save btn-primary">
                                    <i class="fas fa-key mr-1"></i> Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Account Status -->
                <div class="sidebar-card">
                    <h5><i class="fas fa-shield-alt"></i> Status Akun</h5>
                    
                    @if(Auth::user()->email_verified_at)
                        <div class="account-status verified">
                            <i class="fas fa-check-circle"></i>
                            <h6>Email Terverifikasi</h6>
                            <p>Akun Anda sudah diverifikasi pada {{ Auth::user()->email_verified_at->format('d M Y') }}</p>
                        </div>
                    @else
                        <div class="account-status unverified">
                            <i class="fas fa-exclamation-circle"></i>
                            <h6>Email Belum Terverifikasi</h6>
                            <p>Silakan cek email Anda untuk verifikasi</p>
                        </div>
                        <form action="{{ route('verification.send') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-block" style="border-radius: 10px;">
                                <i class="fas fa-envelope mr-1"></i> Kirim Ulang Verifikasi
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Account Info -->
                <div class="sidebar-card">
                    <h5><i class="fas fa-info-circle"></i> Informasi Akun</h5>
                    
                    <div class="info-item mb-3" style="padding: 12px; background: #f8f9fa; border-radius: 10px;">
                        <div class="item-icon" style="width: 35px; height: 35px; font-size: 0.9rem;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="item-content">
                            <div class="item-label">User ID</div>
                            <div class="item-value">#{{ Auth::user()->id ?? '12345' }}</div>
                        </div>
                    </div>

                    <div class="info-item mb-3" style="padding: 12px; background: #f8f9fa; border-radius: 10px;">
                        <div class="item-icon" style="width: 35px; height: 35px; font-size: 0.9rem;">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="item-content">
                            <div class="item-label">Tanggal Daftar</div>
                            <div class="item-value">{{ Auth::user()->created_at
                                ? Auth::user()->created_at->locale('id')->translatedFormat('d M Y, H:i')
                                : ''
                            }}
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-3" style="padding: 12px; background: #f8f9fa; border-radius: 10px;">
                        <div class="item-icon" style="width: 35px; height: 35px; font-size: 0.9rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="item-content">
                            <div class="item-label">Terakhir Update</div>
                            <div class="item-value">{{ Auth::user()->updated_at
                                ? Auth::user()->updated_at->locale('id')->translatedFormat('d M Y, H:i')
                                : ''
                            }}</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="sidebar-card">
                    <h5><i class="fas fa-link"></i> Link Cepat</h5>
                    
                    <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-outline-primary btn-block mb-2" style="border-radius: 10px;">
                        <i class="fas fa-search mr-2"></i> Cari Lowongan
                    </a>
                    <a href="{{ route('hasil.index') }}" class="btn btn-outline-primary btn-block mb-2" style="border-radius: 10px;">
                        <i class="fas fa-file-alt mr-2"></i> Riwayat Lamaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('custom-js')
<script>
$(document).ready(function() {
    // Edit Personal Info
    $('#editPersonalBtn').on('click', function() {
        $('#personalForm input, #personalForm select, #personalForm textarea').prop('disabled', false);
        $('#personalFormButtons').css('display', 'flex !important').show();
        $(this).hide();
    });

    $('#cancelPersonalBtn').on('click', function() {
        $('#personalForm input, #personalForm select, #personalForm textarea').prop('disabled', true);
        $('#personalFormButtons').hide();
        $('#editPersonalBtn').show();
        $('#personalForm')[0].reset();
    });

    // Edit Social Links
    $('#editSocialBtn').on('click', function() {
        $('#socialForm input').prop('disabled', false);
        $('#socialFormButtons').css('display', 'flex !important').show();
        $(this).hide();
    });

    $('#cancelSocialBtn').on('click', function() {
        $('#socialForm input').prop('disabled', true);
        $('#socialFormButtons').hide();
        $('#editSocialBtn').show();
        $('#socialForm')[0].reset();
    });

    // Avatar Upload Preview
    $('#avatarInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format Tidak Didukung',
                    text: 'Silakan upload file gambar (JPG, PNG, GIF, WEBP)'
                });
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal adalah 2 MB'
                });
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $('.profile-avatar').html('<img src="' + e.target.result + '" alt="Avatar">');
                
                // Show confirmation to upload
                Swal.fire({
                    title: 'Upload Avatar?',
                    text: 'Apakah Anda ingin menggunakan foto ini sebagai avatar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Upload!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create form data and submit
                        const formData = new FormData();
                        formData.append('avatar', file);
                        formData.append('_token', '{{ csrf_token() }}');

                        // AJAX upload would go here
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Avatar berhasil diupdate',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            };
            reader.readAsDataURL(file);
        }
    });
});

// Toggle Password Visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.parentElement.querySelector('.password-toggle i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Check Password Strength
function checkPasswordStrength() {
    const password = document.getElementById('new_password').value;
    const strengthDiv = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    if (password.length === 0) {
        strengthDiv.style.display = 'none';
        return;
    }

    strengthDiv.style.display = 'block';

    let strength = 0;
    
    // Length check
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // Character checks
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    // Remove all classes
    strengthFill.className = 'strength-fill';
    strengthText.className = 'strength-text';

    if (strength <= 2) {
        strengthFill.classList.add('weak');
        strengthText.classList.add('weak');
        strengthText.textContent = 'Lemah';
    } else if (strength <= 3) {
        strengthFill.classList.add('fair');
        strengthText.classList.add('fair');
        strengthText.textContent = 'Cukup';
    } else if (strength <= 4) {
        strengthFill.classList.add('good');
        strengthText.classList.add('good');
        strengthText.textContent = 'Bagus';
    } else {
        strengthFill.classList.add('strong');
        strengthText.classList.add('strong');
        strengthText.textContent = 'Kuat';
    }
}

// Form Validation
$('#passwordForm').on('submit', function(e) {
    const newPassword = $('#new_password').val();
    const confirmPassword = $('#new_password_confirmation').val();

    if (newPassword !== confirmPassword) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Password Tidak Cocok',
            text: 'Password baru dan konfirmasi password harus sama'
        });
        return false;
    }

    if (newPassword.length < 8) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Password Terlalu Pendek',
            text: 'Password minimal harus 8 karakter'
        });
        return false;
    }
});
</script>
@endpush