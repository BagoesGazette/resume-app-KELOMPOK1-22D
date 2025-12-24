<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Penilaian Lamaran - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #667eea;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            margin: 0;
        }
        .info-box {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 5px 10px;
        }
        .info-box .label {
            color: #666;
            font-size: 10px;
        }
        .info-box .value {
            font-weight: bold;
            color: #34395e;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 10px;
        }
        table.data-table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            font-size: 11px;
        }
        table.data-table tr:nth-child(even) {
            background: #f8f9ff;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #cce5ff; color: #004085; }
        .badge-purple { background: #e2d5f1; color: #6f42c1; }
        .text-center { text-align: center; }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
        .no-print { text-align: right; margin-bottom: 20px; }
        .btn-print {
            padding: 10px 25px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
    </div>

    <div class="header">
        <h1>HASIL PENILAIAN LAMARAN KERJA</h1>
        <p>{{ $user->name }} | {{ $user->email }}</p>
        <p style="margin-top: 8px;">Diekspor pada: {{ $exportDate }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td style="width: 50%;">
                    <div class="label">Total Lamaran</div>
                    <div class="value">{{ $totalLamaran }}</div>
                </td>
                <td>
                    <div class="label">Rata-rata Skor</div>
                    <div class="value">{{ $avgScore }}%</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Posisi</th>
                <th>Perusahaan</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Skor</th>
                <th class="text-center">Ranking</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $index => $result)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $result['position'] }}</td>
                <td>{{ $result['company'] }}</td>
                <td class="text-center">{{ $result['date']->format('d/m/Y') }}</td>
                <td class="text-center">
                    @if($result['score'])
                        {{ $result['score'] }}%
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($result['rank'])
                        #{{ $result['rank'] }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @php
                        $badgeClass = match($result['status']) {
                            'submitted' => 'badge-warning',
                            'reviewed' => 'badge-info',
                            'interview' => 'badge-purple',
                            'accepted' => 'badge-success',
                            'rejected' => 'badge-danger',
                            default => 'badge-warning'
                        };
                        $statusLabel = match($result['status']) {
                            'submitted' => 'Pending',
                            'reviewed' => 'Review',
                            'interview' => 'Interview',
                            'accepted' => 'Diterima',
                            'rejected' => 'Ditolak',
                            default => ucfirst($result['status'])
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis pada {{ $exportDate }}</p>
    </div>
</body>
</html>