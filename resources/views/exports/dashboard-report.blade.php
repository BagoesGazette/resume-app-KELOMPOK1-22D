<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dashboard Rekrutmen - {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            font-size: 24px;
            color: #667eea;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 11px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #34395e;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stats-row {
            display: table-row;
        }

        .stat-box {
            display: table-cell;
            width: 16.66%;
            padding: 15px;
            text-align: center;
            background: #f8f9ff;
            border: 1px solid #e0e0ff;
        }

        .stat-box .value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .stat-box .label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table th {
            background: #667eea;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        table.data-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
            font-size: 11px;
        }

        table.data-table tr:nth-child(even) {
            background: #f8f9ff;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #cce5ff; color: #004085; }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        .no-print {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-print {
            padding: 10px 25px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-print:hover {
            background: #5a6fd6;
        }

        @media print {
            .no-print { display: none; }
            body { padding: 15px; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
            🖨️ Print / Save as PDF
        </button>
    </div>

    <div class="header">
        <h1>LAPORAN DASHBOARD REKRUTMEN</h1>
        <p>Periode: {{ $year }} | Diekspor pada: {{ $exportDate }}</p>
    </div>

    <!-- Statistik Umum -->
    <div class="section">
        <div class="section-title">📊 Statistik Umum</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box">
                    <div class="value">{{ number_format($stats['pengguna']) }}</div>
                    <div class="label">Total Pengguna</div>
                </div>
                <div class="stat-box">
                    <div class="value">{{ number_format($stats['lowonganAktif']) }}</div>
                    <div class="label">Lowongan Aktif</div>
                </div>
                <div class="stat-box">
                    <div class="value">{{ number_format($stats['totalLamaran']) }}</div>
                    <div class="label">Total Lamaran</div>
                </div>
                <div class="stat-box">
                    <div class="value" style="color: #28a745;">{{ number_format($stats['diterima']) }}</div>
                    <div class="label">Diterima</div>
                </div>
                <div class="stat-box">
                    <div class="value" style="color: #dc3545;">{{ number_format($stats['ditolak']) }}</div>
                    <div class="label">Ditolak</div>
                </div>
                <div class="stat-box">
                    <div class="value" style="color: #6f42c1;">{{ number_format($stats['interviewToday']) }}</div>
                    <div class="label">Interview Hari Ini</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Lamaran -->
    <div class="section">
        <div class="section-title">📋 Status Lamaran</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php $total = array_sum($statusLamaran) ?: 1; @endphp
                <tr>
                    <td><span class="badge badge-warning">Pending</span></td>
                    <td class="text-center">{{ $statusLamaran['pending'] }}</td>
                    <td class="text-center">{{ round(($statusLamaran['pending'] / $total) * 100, 1) }}%</td>
                </tr>
                <tr>
                    <td><span class="badge badge-info">Reviewed</span></td>
                    <td class="text-center">{{ $statusLamaran['reviewed'] }}</td>
                    <td class="text-center">{{ round(($statusLamaran['reviewed'] / $total) * 100, 1) }}%</td>
                </tr>
                <tr>
                    <td><span class="badge" style="background: #e2d5f1; color: #6f42c1;">Interview</span></td>
                    <td class="text-center">{{ $statusLamaran['interview'] }}</td>
                    <td class="text-center">{{ round(($statusLamaran['interview'] / $total) * 100, 1) }}%</td>
                </tr>
                <tr>
                    <td><span class="badge badge-success">Diterima</span></td>
                    <td class="text-center">{{ $statusLamaran['diterima'] }}</td>
                    <td class="text-center">{{ round(($statusLamaran['diterima'] / $total) * 100, 1) }}%</td>
                </tr>
                <tr>
                    <td><span class="badge badge-danger">Ditolak</span></td>
                    <td class="text-center">{{ $statusLamaran['ditolak'] }}</td>
                    <td class="text-center">{{ round(($statusLamaran['ditolak'] / $total) * 100, 1) }}%</td>
                </tr>
                <tr style="font-weight: bold; background: #e9ecef;">
                    <td>Total</td>
                    <td class="text-center">{{ $total }}</td>
                    <td class="text-center">100%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Lowongan Terpopuler -->
    <div class="section">
        <div class="section-title">🏆 Lowongan Terpopuler</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Judul Lowongan</th>
                    <th>Perusahaan</th>
                    <th class="text-center">Pelamar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popularJobs as $index => $job)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $job->judul }}</td>
                    <td>{{ $job->perusahaan }}</td>
                    <td class="text-center"><strong>{{ $job->apply_count }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Statistik Bulanan -->
    <div class="section">
        <div class="section-title">📈 Statistik Bulanan {{ $year }}</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-center">Lamaran Masuk</th>
                    <th class="text-center">Diterima</th>
                    <th class="text-center">Ditolak</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyStats['labels'] as $index => $bulan)
                <tr>
                    <td>{{ $bulan }}</td>
                    <td class="text-center">{{ $monthlyStats['lamaranMasuk'][$index] }}</td>
                    <td class="text-center" style="color: #28a745;">{{ $monthlyStats['diterima'][$index] }}</td>
                    <td class="text-center" style="color: #dc3545;">{{ $monthlyStats['ditolak'][$index] }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background: #e9ecef;">
                    <td>Total</td>
                    <td class="text-center">{{ array_sum($monthlyStats['lamaranMasuk']) }}</td>
                    <td class="text-center" style="color: #28a745;">{{ array_sum($monthlyStats['diterima']) }}</td>
                    <td class="text-center" style="color: #dc3545;">{{ array_sum($monthlyStats['ditolak']) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Top Kandidat -->
    <div class="section">
        <div class="section-title">⭐ Top 10 Kandidat</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;">Rank</th>
                    <th>Nama Kandidat</th>
                    <th>Posisi Dilamar</th>
                    <th class="text-center">Skor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topCandidates as $index => $candidate)
                <tr>
                    <td class="text-center">
                        @if($index == 0) 🥇
                        @elseif($index == 1) 🥈
                        @elseif($index == 2) 🥉
                        @else {{ $index + 1 }}
                        @endif
                    </td>
                    <td>{{ $candidate->user->name ?? '-' }}</td>
                    <td>{{ $candidate->jobOpening->judul ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge badge-success">{{ round($candidate->score * 100, 1) }}%</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada kandidat yang dinilai</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem pada {{ $exportDate }}</p>
        <p>© {{ date('Y') }} Sistem Rekrutmen</p>
    </div>
</body>
</html>