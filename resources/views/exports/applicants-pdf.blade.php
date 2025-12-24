<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Data Pelamar - {{ $job->judul }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }

        .container {
            padding: 20px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            font-size: 18px;
            color: #667eea;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14px;
            color: #34395e;
            font-weight: normal;
            margin-bottom: 10px;
        }

        .header-info {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 10px;
        }

        .header-info-item {
            font-size: 9px;
            color: #666;
        }

        /* Job Info Box */
        .job-info-box {
            background-color: #f8f9ff;
            border: 1px solid #e0e0ff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .job-info-grid {
            display: table;
            width: 100%;
        }

        .job-info-row {
            display: table-row;
        }

        .job-info-cell {
            display: table-cell;
            padding: 5px 10px;
            width: 25%;
        }

        .job-info-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }

        .job-info-value {
            font-size: 10px;
            font-weight: bold;
            color: #34395e;
        }

        /* Stats */
        .stats-row {
            margin-bottom: 20px;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-table td {
            text-align: center;
            padding: 10px;
            background: #f0f2ff;
            border: 1px solid #e0e0ff;
        }

        .stats-value {
            font-size: 16px;
            font-weight: bold;
            color: #667eea;
        }

        .stats-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-color: #667eea;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table thead th:first-child {
            border-radius: 5px 0 0 0;
        }

        .data-table thead th:last-child {
            border-radius: 0 5px 0 0;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9ff;
        }

        .data-table tbody tr:hover {
            background-color: #f0f2ff;
        }

        .data-table tbody td {
            padding: 8px;
            border-bottom: 1px solid #e9ecef;
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Ranking Badge */
        .ranking-badge {
            display: inline-block;
            width: 22px;
            height: 22px;
            line-height: 22px;
            text-align: center;
            border-radius: 50%;
            font-weight: bold;
            font-size: 9px;
            color: white;
        }

        .ranking-1 { background-color: #FFD700; color: #333; }
        .ranking-2 { background-color: #C0C0C0; color: #333; }
        .ranking-3 { background-color: #CD7F32; }
        .ranking-other { background-color: #667eea; }

        /* Score Badge */
        .score-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 8px;
        }

        .score-excellent { background-color: #d4edda; color: #155724; }
        .score-good { background-color: #cce5ff; color: #004085; }
        .score-average { background-color: #fff3cd; color: #856404; }
        .score-poor { background-color: #f8d7da; color: #721c24; }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
        }

        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-reviewed { background-color: #cce5ff; color: #004085; }
        .status-interview { background-color: #e2d5f1; color: #6f42c1; }
        .status-accepted { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 8px;
            color: #999;
        }

        .page-break {
            page-break-after: always;
        }

        /* No Data */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .no-data i {
            font-size: 30px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN DATA PELAMAR</h1>
            <h2>{{ $job->judul }}</h2>
            <div style="font-size: 9px; color: #666; margin-top: 5px;">
                {{ $job->perusahaan }} &bull; {{ $job->lokasi }}
            </div>
            <div style="font-size: 8px; color: #999; margin-top: 8px;">
                Diekspor pada: {{ $exportDate }}
            </div>
        </div>

        <!-- Job Info -->
        <div class="job-info-box">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 25%; padding: 5px;">
                        <div class="job-info-label">Kategori</div>
                        <div class="job-info-value">{{ $job->category ?? '-' }}</div>
                    </td>
                    <td style="width: 25%; padding: 5px;">
                        <div class="job-info-label">Tipe Pekerjaan</div>
                        <div class="job-info-value">{{ ucfirst($job->tipe) }}</div>
                    </td>
                    <td style="width: 25%; padding: 5px;">
                        <div class="job-info-label">Tanggal Posting</div>
                        <div class="job-info-value">{{ $job->created_at?->format('d/m/Y') ?? '-' }}</div>
                    </td>
                    <td style="width: 25%; padding: 5px;">
                        <div class="job-info-label">Batas Lamaran</div>
                        <div class="job-info-value">{{ $job->tanggal_tutup ? \Carbon\Carbon::parse($job->tanggal_tutup)->format('d/m/Y') : '-' }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Statistics -->
        <div class="stats-row">
            <table class="stats-table">
                <tr>
                    <td>
                        <div class="stats-value">{{ $applicants->count() }}</div>
                        <div class="stats-label">Total Pelamar</div>
                    </td>
                    <td>
                        <div class="stats-value">{{ $applicants->where('status', 'submitted')->count() }}</div>
                        <div class="stats-label">Pending</div>
                    </td>
                    <td>
                        <div class="stats-value">{{ $applicants->where('status', 'reviewed')->count() }}</div>
                        <div class="stats-label">Reviewed</div>
                    </td>
                    <td>
                        <div class="stats-value">{{ $applicants->where('status', 'interview')->count() }}</div>
                        <div class="stats-label">Interview</div>
                    </td>
                    <td>
                        <div class="stats-value" style="color: #28a745;">{{ $applicants->where('status', 'accepted')->count() }}</div>
                        <div class="stats-label">Diterima</div>
                    </td>
                    <td>
                        <div class="stats-value" style="color: #dc3545;">{{ $applicants->where('status', 'rejected')->count() }}</div>
                        <div class="stats-label">Ditolak</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Data Table -->
        @if($applicants->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 30px;" class="text-center">No</th>
                        <th style="width: 40px;" class="text-center">Rank</th>
                        <th style="width: 120px;">Nama Pelamar</th>
                        <th style="width: 140px;">Email</th>
                        <th style="width: 60px;" class="text-center">Pendidikan</th>
                        <th style="width: 40px;" class="text-center">IPK</th>
                        <th style="width: 50px;" class="text-center">Pengalaman</th>
                        <th style="width: 50px;" class="text-center">Skor</th>
                        <th style="width: 60px;" class="text-center">Status</th>
                        <th style="width: 70px;" class="text-center">Tgl Lamar</th>
                        <th style="width: 80px;" class="text-center">Interview</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applicants as $index => $applicant)
                        @php
                            $scorePercent = $applicant->score ? round($applicant->score * 100, 1) : 0;
                            $scoreClass = $scorePercent >= 90 ? 'excellent' : 
                                         ($scorePercent >= 80 ? 'good' : 
                                         ($scorePercent >= 70 ? 'average' : 'poor'));
                            
                            $statusClass = match($applicant->status) {
                                'submitted' => 'pending',
                                'reviewed' => 'reviewed',
                                'interview' => 'interview',
                                'accepted' => 'accepted',
                                'rejected' => 'rejected',
                                default => 'pending'
                            };

                            $rankingClass = match($applicant->ranking) {
                                1 => 'ranking-1',
                                2 => 'ranking-2',
                                3 => 'ranking-3',
                                default => 'ranking-other'
                            };
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                @if($applicant->ranking)
                                    <span class="ranking-badge {{ $rankingClass }}">{{ $applicant->ranking }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <strong>{{ $applicant->user->name ?? '-' }}</strong>
                            </td>
                            <td style="font-size: 8px;">{{ $applicant->user->email ?? '-' }}</td>
                            <td class="text-center">{{ $pendidikanLabels[$applicant->cvSubmission->tipe_pendidikan ?? 1] ?? '-' }}</td>
                            <td class="text-center">{{ $applicant->cvSubmission->ipk_nilai_akhir ?? '-' }}</td>
                            <td class="text-center">{{ $applicant->cvSubmission->total_pengalaman ?? 0 }} thn</td>
                            <td class="text-center">
                                @if($applicant->score)
                                    <span class="score-badge score-{{ $scoreClass }}">{{ $scorePercent }}%</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="status-badge status-{{ $statusClass }}">
                                    {{ $statusLabels[$applicant->status] ?? $applicant->status }}
                                </span>
                            </td>
                            <td class="text-center" style="font-size: 8px;">
                                {{ $applicant->created_at?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td class="text-center" style="font-size: 8px;">
                                @if($applicant->interview_date)
                                    {{ \Carbon\Carbon::parse($applicant->interview_date)
                                    ->locale('id')
                                    ->translatedFormat('d F Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <p>Belum ada data pelamar</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini digenerate secara otomatis oleh sistem pada {{ $exportDate }}</p>
            <p style="margin-top: 3px;">© {{ date('Y') }} {{ $job->perusahaan ?? 'Sistem Rekrutmen' }}</p>
        </div>
    </div>
</body>
</html>