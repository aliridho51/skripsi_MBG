<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penyaluran MBG</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1a202c;
            background: #fff;
        }
        .header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: white;
            padding: 20px 30px;
            margin-bottom: 20px;
        }
        .header-top {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 11px;
            opacity: 0.85;
            margin-top: 3px;
        }
        .meta-box {
            margin: 0 30px 20px 30px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
        }
        .meta-item label {
            font-size: 9px;
            text-transform: uppercase;
            color: #718096;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        .meta-item p {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a5f;
            margin-top: 2px;
        }
        .summary-cards {
            margin: 0 30px 20px 30px;
            display: flex;
            gap: 12px;
        }
        .card {
            flex: 1;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
        }
        .card.blue { background: #eff6ff; border: 1px solid #bfdbfe; }
        .card.green { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .card.orange { background: #fff7ed; border: 1px solid #fed7aa; }
        .card label { font-size: 9px; text-transform: uppercase; color: #6b7280; font-weight: bold; }
        .card p { font-size: 18px; font-weight: bold; margin-top: 4px; }
        .card.blue p { color: #1d4ed8; }
        .card.green p { color: #16a34a; }
        .card.orange p { color: #ea580c; }
        .table-wrapper { margin: 0 30px 30px 30px; }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #2563eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        thead tr {
            background: #1e3a5f;
            color: white;
        }
        thead th {
            padding: 9px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd) { background: #ffffff; }
        tbody td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .progress-bar-bg {
            background: #e2e8f0;
            border-radius: 10px;
            height: 6px;
            width: 80px;
            display: inline-block;
            vertical-align: middle;
            margin-left: 5px;
        }
        .progress-bar-fill {
            background: #2563eb;
            border-radius: 10px;
            height: 6px;
        }
        .footer {
            margin: 0 30px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #9ca3af;
        }
        .no-col { width: 30px; text-align: center; }
        .persen-col { width: 120px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>&#128209; Laporan Penyaluran MBG</h1>
        <p>Makan Bergizi Gratis &mdash; Rekap Harian Distribusi</p>
    </div>

    <!-- Meta Info -->
    <div class="meta-box">
        <div class="meta-item">
            <label>Periode</label>
            <p>
                @if($tanggal_mulai && $tanggal_akhir)
                    {{ \Carbon\Carbon::parse($tanggal_mulai)->translatedFormat('d F Y') }} &ndash; {{ \Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') }}
                @elseif($tanggal_mulai)
                    {{ \Carbon\Carbon::parse($tanggal_mulai)->translatedFormat('d F Y') }}
                @else
                    Semua Data
                @endif
            </p>
        </div>
        <div class="meta-item">
            <label>Total Hari</label>
            <p>{{ count($laporan) }} Hari</p>
        </div>
        <div class="meta-item">
            <label>Dicetak Pada</label>
            <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>
        <div class="meta-item">
            <label>Dicetak Oleh</label>
            <p>Admin Sistem</p>
        </div>
    </div>

    <!-- Summary Cards -->
    @php
        $totalDisalurkan = collect($laporan)->sum('total_disalurkan');
        $totalTarget = collect($laporan)->sum('target');
        $persenTotal = $totalTarget > 0 ? round(($totalDisalurkan / $totalTarget) * 100, 1) : 0;
    @endphp
    <div class="summary-cards">
        <div class="card blue">
            <label>Total Realisasi</label>
            <p>{{ number_format($totalDisalurkan) }}</p>
            <span style="font-size:9px;color:#6b7280;">Porsi Disalurkan</span>
        </div>
        <div class="card green">
            <label>Total Target</label>
            <p>{{ number_format($totalTarget) }}</p>
            <span style="font-size:9px;color:#6b7280;">Porsi Target</span>
        </div>
        <div class="card orange">
            <label>Capaian Rata-rata</label>
            <p>{{ $persenTotal }}%</p>
            <span style="font-size:9px;color:#6b7280;">Dari Target</span>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <div class="section-title">Rincian Laporan Harian</div>
        <table>
            <thead>
                <tr>
                    <th class="no-col">No</th>
                    <th>Tanggal</th>
                    <th>Realisasi (Porsi)</th>
                    <th>Target (Porsi)</th>
                    <th class="persen-col">Persentase</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $i => $lap)
                    @php $persen = $lap['target'] > 0 ? ($lap['total_disalurkan'] / $lap['target']) * 100 : 0; @endphp
                    <tr>
                        <td class="no-col" style="text-align:center">{{ $i + 1 }}</td>
                        <td><strong>{{ \Carbon\Carbon::parse($lap['tanggal'])->translatedFormat('d F Y') }}</strong></td>
                        <td>{{ number_format($lap['total_disalurkan']) }}</td>
                        <td>{{ number_format($lap['target']) }}</td>
                        <td>
                            {{ round($persen, 1) }}%
                            <span class="progress-bar-bg">
                                <span class="progress-bar-fill" style="width: {{ min($persen,100) }}%;"></span>
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $lap['status'] == 'Selesai' ? 'badge-green' : 'badge-yellow' }}">
                                {{ $lap['status'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 20px; color:#9ca3af;">
                            Tidak ada data untuk periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <span>Laporan ini dicetak secara otomatis oleh Sistem MBG</span>
        <span>Halaman 1</span>
    </div>
</body>
</html>
