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
    --gold-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    --silver-gradient: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
    --bronze-gradient: linear-gradient(135deg, #d35400 0%, #e67e22 100%);
}

/* Page Header */
.page-header-card {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 35px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
}

.page-header-card::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 350px;
    height: 350px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.page-header-card::after {
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

.page-header-card h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.page-header-card p {
    opacity: 0.9;
    margin-bottom: 0;
    font-size: 1rem;
}

.page-header-illustration {
    position: absolute;
    right: 40px;
    bottom: -10px;
    height: 180px;
    z-index: 1;
    opacity: 0.9;
}

/* Your Ranking Card */
.your-ranking-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

.your-ranking-header {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    padding: 25px 30px;
    border-bottom: 3px solid #fbbf24;
}

.your-ranking-header h4 {
    margin: 0;
    font-weight: 700;
    color: #92400e;
    display: flex;
    align-items: center;
    gap: 10px;
}

.your-ranking-header h4 i {
    color: #f59e0b;
}

.your-ranking-body {
    padding: 30px;
}

.your-rank-display {
    display: flex;
    align-items: center;
    gap: 30px;
}

.rank-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
}

.rank-circle.gold {
    background: var(--gold-gradient);
    box-shadow: 0 10px 40px rgba(247, 151, 30, 0.4);
}

.rank-circle.silver {
    background: var(--silver-gradient);
    box-shadow: 0 10px 40px rgba(149, 165, 166, 0.4);
}

.rank-circle.bronze {
    background: var(--bronze-gradient);
    box-shadow: 0 10px 40px rgba(211, 84, 0, 0.4);
}

.rank-circle.normal {
    background: var(--primary-gradient);
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
}

.rank-circle .rank-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    line-height: 1;
}

.rank-circle .rank-label {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.9);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.rank-circle .crown {
    position: absolute;
    top: -15px;
    font-size: 1.5rem;
}

.rank-details {
    flex: 1;
}

.rank-details h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #34395e;
    margin-bottom: 5px;
}

.rank-details .job-applied {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 15px;
}

.rank-details .job-applied a {
    color: #667eea;
    font-weight: 600;
}

.rank-stats {
    display: flex;
    gap: 25px;
}

.rank-stat {
    text-align: center;
    padding: 15px 20px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    border-radius: 12px;
}

.rank-stat .stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #667eea;
}

.rank-stat .stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.rank-message {
    margin-top: 20px;
    padding: 15px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.rank-message.success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.rank-message.info {
    background: linear-gradient(135deg, #cce5ff 0%, #b8daff 100%);
    color: #004085;
}

.rank-message.warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
    color: #856404;
}

.rank-message i {
    font-size: 1.3rem;
}

/* Score Breakdown */
.score-breakdown {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.score-breakdown h5 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.score-breakdown h5 i {
    color: #667eea;
}

.score-item {
    margin-bottom: 20px;
}

.score-item:last-child {
    margin-bottom: 0;
}

.score-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.score-label {
    font-weight: 600;
    color: #34395e;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.score-label i {
    color: #667eea;
    width: 20px;
}

.score-value {
    font-weight: 700;
    color: #667eea;
}

.score-bar {
    height: 10px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.score-progress {
    height: 100%;
    border-radius: 10px;
    transition: width 1s ease;
}

.score-progress.excellent { background: var(--success-gradient); }
.score-progress.good { background: var(--info-gradient); }
.score-progress.average { background: var(--warning-gradient); }
.score-progress.poor { background: var(--danger-gradient); }

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
    color: #667eea;
}

.info-card .card-body {
    padding: 25px;
}

/* Ranking Table */
.ranking-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

.ranking-table thead th {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    padding: 15px 20px;
    font-weight: 700;
    color: #34395e;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

.ranking-table thead th:first-child {
    border-radius: 12px 0 0 12px;
}

.ranking-table thead th:last-child {
    border-radius: 0 12px 12px 0;
}

.ranking-table tbody tr {
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.ranking-table tbody tr:hover {
    transform: scale(1.01);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.ranking-table tbody tr.is-you {
    background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);
    border: 2px solid #667eea;
}

.ranking-table tbody tr.is-you td:first-child {
    border-left: 4px solid #667eea;
}

.ranking-table tbody td {
    padding: 18px 20px;
    vertical-align: middle;
    border: none;
}

.ranking-table tbody td:first-child {
    border-radius: 12px 0 0 12px;
}

.ranking-table tbody td:last-child {
    border-radius: 0 12px 12px 0;
}

/* Rank Badge */
.rank-badge {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.1rem;
    color: white;
    position: relative;
}

.rank-badge.rank-1 {
    background: var(--gold-gradient);
    box-shadow: 0 5px 15px rgba(247, 151, 30, 0.3);
}

.rank-badge.rank-2 {
    background: var(--silver-gradient);
    box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
}

.rank-badge.rank-3 {
    background: var(--bronze-gradient);
    box-shadow: 0 5px 15px rgba(211, 84, 0, 0.3);
}

.rank-badge.rank-normal {
    background: #e9ecef;
    color: #666;
}

.rank-badge .crown-icon {
    position: absolute;
    top: -10px;
    right: -5px;
    font-size: 0.9rem;
}

.rank-badge.rank-1 .crown-icon { color: #ffd700; }
.rank-badge.rank-2 .crown-icon { color: #c0c0c0; }
.rank-badge.rank-3 .crown-icon { color: #cd7f32; }

/* Applicant Info in Table */
.applicant-info-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.applicant-avatar {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.95rem;
    color: white;
}

.applicant-name {
    font-weight: 600;
    color: #34395e;
    font-size: 0.95rem;
}

.applicant-name.is-you::after {
    content: '(Anda)';
    margin-left: 8px;
    font-size: 0.75rem;
    padding: 2px 8px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 50px;
    font-weight: 600;
}

/* Score Badge */
.score-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 15px;
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

/* Tips Card */
.tips-card {
    background: linear-gradient(135deg, #f0fff4 0%, #dcffe4 100%);
    border: 2px solid #9ae6b4;
    border-radius: 15px;
    padding: 25px;
}

.tips-card h5 {
    font-weight: 700;
    color: #22543d;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.tips-card h5 i {
    color: #38a169;
}

.tips-card ul {
    margin: 0;
    padding-left: 20px;
}

.tips-card li {
    color: #276749;
    margin-bottom: 10px;
    font-size: 0.9rem;
    line-height: 1.6;
}

.tips-card li:last-child {
    margin-bottom: 0;
}

/* Application Summary */
.application-summary {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.application-summary h5 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.application-summary h5 i {
    color: #667eea;
}

.summary-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px dashed #e9ecef;
}

.summary-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.summary-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 1rem;
}

.summary-icon.primary { background: linear-gradient(135deg, #f0f0ff 0%, #e8e8ff 100%); color: #667eea; }
.summary-icon.success { background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); color: #28a745; }
.summary-icon.warning { background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%); color: #ffc107; }
.summary-icon.danger { background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); color: #dc3545; }

.summary-label {
    font-size: 0.85rem;
    color: #6c757d;
}

.summary-value {
    font-weight: 600;
    color: #34395e;
}

/* Status Timeline */
.status-timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    padding: 20px 0;
}

.status-timeline::before {
    content: '';
    position: absolute;
    top: 45px;
    left: 10%;
    right: 10%;
    height: 4px;
    background: #e9ecef;
}

.status-step {
    text-align: center;
    position: relative;
    z-index: 1;
    flex: 1;
}

.status-step .step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 1.1rem;
    color: #6c757d;
    transition: all 0.3s ease;
}

.status-step.completed .step-icon {
    background: var(--success-gradient);
    color: white;
}

.status-step.active .step-icon {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.2);
}

.status-step .step-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
}

.status-step.completed .step-label,
.status-step.active .step-label {
    color: #34395e;
    font-weight: 600;
}

/* Comparison Chart */
.comparison-chart {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.comparison-chart h5 {
    font-weight: 700;
    color: #34395e;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.comparison-chart h5 i {
    color: #667eea;
}

.comparison-bar {
    margin-bottom: 20px;
}

.comparison-bar:last-child {
    margin-bottom: 0;
}

.comparison-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.comparison-label {
    font-weight: 600;
    color: #34395e;
    font-size: 0.9rem;
}

.comparison-values {
    font-size: 0.85rem;
    color: #6c757d;
}

.comparison-values .your-score {
    color: #667eea;
    font-weight: 700;
}

.comparison-values .avg-score {
    color: #6c757d;
}

.comparison-track {
    height: 12px;
    background: #e9ecef;
    border-radius: 10px;
    position: relative;
    overflow: visible;
}

.comparison-fill {
    height: 100%;
    border-radius: 10px;
    background: var(--primary-gradient);
    position: relative;
}

.comparison-marker {
    position: absolute;
    top: -5px;
    width: 22px;
    height: 22px;
    background: #ff6b6b;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    transform: translateX(-50%);
}

.comparison-marker::after {
    content: 'Avg';
    position: absolute;
    top: 25px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.7rem;
    color: #ff6b6b;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 992px) {
    .your-rank-display {
        flex-direction: column;
        text-align: center;
    }

    .rank-stats {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .page-header-card {
        padding: 25px;
        text-align: center;
    }

    .page-header-illustration {
        display: none;
    }

    .rank-circle {
        width: 100px;
        height: 100px;
    }

    .rank-circle .rank-number {
        font-size: 2rem;
    }

    .ranking-table-wrapper {
        overflow-x: auto;
    }

    .ranking-table {
        min-width: 600px;
    }

    .status-timeline {
        flex-direction: column;
        gap: 20px;
    }

    .status-timeline::before {
        width: 4px;
        height: calc(100% - 50px);
        top: 25px;
        left: 25px;
        right: auto;
    }

    .status-step {
        display: flex;
        align-items: center;
        text-align: left;
        gap: 15px;
    }

    .status-step .step-icon {
        margin: 0;
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

.info-card, .your-ranking-card, .score-breakdown, .tips-card {
    animation: fadeInUp 0.5s ease forwards;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.rank-circle {
    animation: pulse 2s ease infinite;
}
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Hasil & Ranking Lamaran</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Hasil Lamaran</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Page Header -->
        <div class="page-header-card">
            <div class="page-header-content">
                <h2><i class="fas fa-trophy mr-2"></i> Hasil Ranking Lamaran Anda</h2>
                <p>Lihat posisi ranking Anda dibandingkan dengan pelamar lainnya berdasarkan skor CV dan kualifikasi.</p>
            </div>
        </div>

        @php
            // Dummy data - ganti dengan data dari controller
            $myRank = 3;
            $totalApplicants = 48;
            $myScore = 85;
            $jobTitle = 'Senior Frontend Developer';
            $companyName = 'PT. Teknologi Nusantara Indonesia';
            
            $applicants = [
                ['rank' => 1, 'name' => 'Budi Santoso', 'score' => 95, 'color' => '#667eea', 'education' => 'S1 Teknik Informatika', 'experience' => 5],
                ['rank' => 2, 'name' => 'Siti Nurhaliza', 'score' => 92, 'color' => '#11998e', 'education' => 'S1 Sistem Informasi', 'experience' => 4],
                ['rank' => 3, 'name' => Auth::user()->name ?? 'Ahmad Rizky Pratama', 'score' => 85, 'color' => '#f7971e', 'education' => 'S1 Teknik Informatika', 'experience' => 4, 'is_you' => true],
                ['rank' => 4, 'name' => 'Dewi Kartika', 'score' => 82, 'color' => '#eb3349', 'education' => 'S1 Ilmu Komputer', 'experience' => 3],
                ['rank' => 5, 'name' => 'Eko Prasetyo', 'score' => 80, 'color' => '#00c6fb', 'education' => 'S1 Teknik Informatika', 'experience' => 6],
                ['rank' => 6, 'name' => 'Fitri Handayani', 'score' => 78, 'color' => '#a855f7', 'education' => 'S1 Sistem Informasi', 'experience' => 2],
                ['rank' => 7, 'name' => 'Gunawan Wibowo', 'score' => 75, 'color' => '#667eea', 'education' => 'D3 Manajemen Informatika', 'experience' => 4],
                ['rank' => 8, 'name' => 'Hana Permata', 'score' => 72, 'color' => '#11998e', 'education' => 'S1 Teknik Informatika', 'experience' => 1],
            ];
        @endphp

        <!-- Your Ranking Card -->
        <div class="your-ranking-card">
            <div class="your-ranking-header">
                <h4><i class="fas fa-medal"></i> Ranking Anda Saat Ini</h4>
            </div>
            <div class="your-ranking-body">
                <div class="your-rank-display">
                    <div class="rank-circle {{ $myRank == 1 ? 'gold' : ($myRank == 2 ? 'silver' : ($myRank == 3 ? 'bronze' : 'normal')) }}">
                        @if($myRank <= 3)
                            <span class="crown">👑</span>
                        @endif
                        <span class="rank-number">#{{ $myRank }}</span>
                        <span class="rank-label">Ranking</span>
                    </div>
                    <div class="rank-details">
                        <h3>Selamat! Anda di Peringkat {{ $myRank }}</h3>
                        <p class="job-applied">
                            Lamaran untuk posisi <a href="#">{{ $jobTitle }}</a> di <strong>{{ $companyName }}</strong>
                        </p>
                        <div class="rank-stats">
                            <div class="rank-stat">
                                <div class="stat-value">{{ $myScore }}%</div>
                                <div class="stat-label">Skor CV Anda</div>
                            </div>
                            <div class="rank-stat">
                                <div class="stat-value">{{ $totalApplicants }}</div>
                                <div class="stat-label">Total Pelamar</div>
                            </div>
                            <div class="rank-stat">
                                <div class="stat-value">Top {{ round(($myRank / $totalApplicants) * 100) }}%</div>
                                <div class="stat-label">Persentil</div>
                            </div>
                        </div>
                        @if($myRank <= 3)
                            <div class="rank-message success">
                                <i class="fas fa-check-circle"></i>
                                <span><strong>Luar biasa!</strong> Anda termasuk dalam 3 kandidat teratas. Peluang untuk dipanggil interview sangat besar!</span>
                            </div>
                        @elseif($myRank <= 10)
                            <div class="rank-message info">
                                <i class="fas fa-info-circle"></i>
                                <span><strong>Bagus!</strong> Anda termasuk dalam 10 besar pelamar. Terus tingkatkan kualifikasi Anda.</span>
                            </div>
                        @else
                            <div class="rank-message warning">
                                <i class="fas fa-exclamation-circle"></i>
                                <span><strong>Tips:</strong> Pertimbangkan untuk meningkatkan skill atau pengalaman untuk meningkatkan ranking Anda.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Application Status Timeline -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-tasks"></i> Status Lamaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="status-timeline">
                            <div class="status-step completed">
                                <div class="step-icon"><i class="fas fa-paper-plane"></i></div>
                                <div class="step-label">Lamaran Terkirim</div>
                            </div>
                            <div class="status-step completed">
                                <div class="step-icon"><i class="fas fa-file-alt"></i></div>
                                <div class="step-label">CV Diproses</div>
                            </div>
                            <div class="status-step active">
                                <div class="step-icon"><i class="fas fa-search"></i></div>
                                <div class="step-label">Dalam Review</div>
                            </div>
                            <div class="status-step">
                                <div class="step-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="step-label">Interview</div>
                            </div>
                            <div class="status-step">
                                <div class="step-icon"><i class="fas fa-trophy"></i></div>
                                <div class="step-label">Keputusan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ranking Table -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i class="fas fa-list-ol"></i> Ranking Pelamar</h4>
                        <span class="badge badge-primary">{{ $totalApplicants }} Pelamar</span>
                    </div>
                    <div class="card-body">
                        <div class="ranking-table-wrapper">
                            <table class="ranking-table">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">Rank</th>
                                        <th>Pelamar</th>
                                        <th>Pendidikan</th>
                                        <th>Pengalaman</th>
                                        <th>Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applicants as $applicant)
                                    <tr class="{{ isset($applicant['is_you']) && $applicant['is_you'] ? 'is-you' : '' }}">
                                        <td>
                                            <div class="rank-badge {{ $applicant['rank'] == 1 ? 'rank-1' : ($applicant['rank'] == 2 ? 'rank-2' : ($applicant['rank'] == 3 ? 'rank-3' : 'rank-normal')) }}">
                                                {{ $applicant['rank'] }}
                                                @if($applicant['rank'] <= 3)
                                                    <span class="crown-icon">👑</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="applicant-info-cell">
                                                <div class="applicant-avatar" style="background: {{ $applicant['color'] }};">
                                                    {{ strtoupper(substr($applicant['name'], 0, 2)) }}
                                                </div>
                                                <span class="applicant-name {{ isset($applicant['is_you']) && $applicant['is_you'] ? 'is-you' : '' }}">
                                                    {{ isset($applicant['is_you']) && $applicant['is_you'] ? 'Anda' : $applicant['name'] }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $applicant['education'] }}</span>
                                        </td>
                                        <td>
                                            <span><i class="fas fa-briefcase mr-1 text-primary"></i> {{ $applicant['experience'] }} Tahun</span>
                                        </td>
                                        <td>
                                            @php
                                                $scoreClass = $applicant['score'] >= 90 ? 'excellent' : ($applicant['score'] >= 80 ? 'good' : ($applicant['score'] >= 70 ? 'average' : 'poor'));
                                            @endphp
                                            <span class="score-badge {{ $scoreClass }}">
                                                <i class="fas fa-star"></i> {{ $applicant['score'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Note -->
                        <div class="alert alert-info mt-4 mb-0" style="border-radius: 12px;">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Catatan:</strong> Ranking didasarkan pada skor CV yang dihitung menggunakan metode Weighted Scoring Model. Nama pelamar lain disamarkan untuk menjaga privasi.
                        </div>
                    </div>
                </div>

                <!-- Score Comparison -->
                <div class="comparison-chart">
                    <h5><i class="fas fa-chart-bar"></i> Perbandingan Skor Anda</h5>
                    
                    <div class="comparison-bar">
                        <div class="comparison-header">
                            <span class="comparison-label"><i class="fas fa-graduation-cap mr-2"></i> Pendidikan</span>
                            <span class="comparison-values">
                                <span class="your-score">Anda: 90%</span> • 
                                <span class="avg-score">Rata-rata: 75%</span>
                            </span>
                        </div>
                        <div class="comparison-track">
                            <div class="comparison-fill" style="width: 90%;"></div>
                            <div class="comparison-marker" style="left: 75%;"></div>
                        </div>
                    </div>

                    <div class="comparison-bar">
                        <div class="comparison-header">
                            <span class="comparison-label"><i class="fas fa-briefcase mr-2"></i> Pengalaman Kerja</span>
                            <span class="comparison-values">
                                <span class="your-score">Anda: 85%</span> • 
                                <span class="avg-score">Rata-rata: 70%</span>
                            </span>
                        </div>
                        <div class="comparison-track">
                            <div class="comparison-fill" style="width: 85%;"></div>
                            <div class="comparison-marker" style="left: 70%;"></div>
                        </div>
                    </div>

                    <div class="comparison-bar">
                        <div class="comparison-header">
                            <span class="comparison-label"><i class="fas fa-code mr-2"></i> Hard Skills</span>
                            <span class="comparison-values">
                                <span class="your-score">Anda: 88%</span> • 
                                <span class="avg-score">Rata-rata: 72%</span>
                            </span>
                        </div>
                        <div class="comparison-track">
                            <div class="comparison-fill" style="width: 88%;"></div>
                            <div class="comparison-marker" style="left: 72%;"></div>
                        </div>
                    </div>

                    <div class="comparison-bar">
                        <div class="comparison-header">
                            <span class="comparison-label"><i class="fas fa-users mr-2"></i> Soft Skills</span>
                            <span class="comparison-values">
                                <span class="your-score">Anda: 80%</span> • 
                                <span class="avg-score">Rata-rata: 78%</span>
                            </span>
                        </div>
                        <div class="comparison-track">
                            <div class="comparison-fill" style="width: 80%;"></div>
                            <div class="comparison-marker" style="left: 78%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Score Breakdown -->
                <div class="score-breakdown mb-4">
                    <h5><i class="fas fa-chart-pie"></i> Rincian Skor CV</h5>
                    
                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><i class="fas fa-graduation-cap"></i> Pendidikan</span>
                            <span class="score-value">90%</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-progress excellent" style="width: 90%;"></div>
                        </div>
                    </div>

                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><i class="fas fa-briefcase"></i> Pengalaman</span>
                            <span class="score-value">85%</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-progress good" style="width: 85%;"></div>
                        </div>
                    </div>

                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><i class="fas fa-code"></i> Hard Skills</span>
                            <span class="score-value">88%</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-progress good" style="width: 88%;"></div>
                        </div>
                    </div>

                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><i class="fas fa-users"></i> Soft Skills</span>
                            <span class="score-value">80%</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-progress good" style="width: 80%;"></div>
                        </div>
                    </div>

                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><i class="fas fa-star"></i> IPK</span>
                            <span class="score-value">75%</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-progress average" style="width: 75%;"></div>
                        </div>
                    </div>

                    <hr>

                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label" style="font-weight: 700;"><i class="fas fa-trophy"></i> Total Skor</span>
                            <span class="score-value" style="font-size: 1.2rem;">{{ $myScore }}%</span>
                        </div>
                        <div class="score-bar" style="height: 14px;">
                            <div class="score-progress good" style="width: {{ $myScore }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Application Summary -->
                <div class="application-summary">
                    <h5><i class="fas fa-file-alt"></i> Ringkasan Lamaran</h5>
                    
                    <div class="summary-item">
                        <div class="summary-icon primary"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <div class="summary-label">Posisi</div>
                            <div class="summary-value">{{ $jobTitle }}</div>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-icon success"><i class="fas fa-building"></i></div>
                        <div>
                            <div class="summary-label">Perusahaan</div>
                            <div class="summary-value">{{ $companyName }}</div>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-icon warning"><i class="fas fa-calendar"></i></div>
                        <div>
                            <div class="summary-label">Tanggal Melamar</div>
                            <div class="summary-value">05 Desember 2025</div>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-icon danger"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="summary-label">Status</div>
                            <div class="summary-value">
                                <span class="badge badge-info">Dalam Review</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="tips-card">
                    <h5><i class="fas fa-lightbulb"></i> Tips Meningkatkan Ranking</h5>
                    <ul>
                        <li><strong>Perbarui CV:</strong> Pastikan CV Anda selalu up-to-date dengan pengalaman dan skill terbaru.</li>
                        <li><strong>Tambah Sertifikasi:</strong> Sertifikasi profesional dapat meningkatkan skor CV Anda.</li>
                        <li><strong>Highlight Pencapaian:</strong> Cantumkan pencapaian konkret dengan angka yang terukur.</li>
                        <li><strong>Sesuaikan Keyword:</strong> Pastikan CV mengandung keyword yang relevan dengan posisi yang dilamar.</li>
                        <li><strong>Cover Letter:</strong> Tulis cover letter yang menarik dan personal.</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 mt-4">
                    <a href="#" class="btn btn-primary btn-lg btn-block" style="border-radius: 12px;">
                        <i class="fas fa-file-alt mr-2"></i> Lihat Detail Lamaran
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-block" style="border-radius: 12px;">
                        <i class="fas fa-edit mr-2"></i> Edit Lamaran
                    </a>
                    <a href="{{ route('lowongan-kerja.index') }}" class="btn btn-light btn-block" style="border-radius: 12px;">
                        <i class="fas fa-search mr-2"></i> Cari Lowongan Lain
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
    // Animate score bars on load
    setTimeout(function() {
        $('.score-progress, .comparison-fill').each(function() {
            var width = $(this).css('width');
            $(this).css('width', '0').animate({width: width}, 1000);
        });
    }, 300);

    // Highlight your row on hover
    $('.ranking-table tbody tr.is-you').hover(
        function() {
            $(this).css('transform', 'scale(1.02)');
        },
        function() {
            $(this).css('transform', 'scale(1.01)');
        }
    );
});
</script>
@endpush