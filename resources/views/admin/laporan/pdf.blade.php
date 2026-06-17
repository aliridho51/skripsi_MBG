<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penyaluran MBG</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #2d3748;
            background: #fff;
            line-height: 1.4;
        }
        /* Kop Surat */
        .kop-surat {
            text-align: center;
            margin-bottom: 15px;
            position: relative;
        }
        .kop-surat h2 {
            font-size: 15px;
            font-weight: bold;
            color: #1a365d;
            text-transform: uppercase;
        }
        .kop-surat h3 {
            font-size: 12px;
            font-weight: bold;
            color: #2b6cb0;
            margin-top: 3px;
            text-transform: uppercase;
        }
        .kop-surat p {
            font-size: 9px;
            color: #718096;
            margin-top: 4px;
        }
        .garis-kop {
            border: none;
            border-top: 2px solid #1a365d;
            border-bottom: 1px solid #1a365d;
            height: 3px;
            margin-top: 8px;
            margin-bottom: 15px;
        }
        /* Meta Table Layout (Alternative to Flexbox) */
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            border-spacing: 0;
            padding: 8px 12px;
        }
        .meta-table td {
            width: 25%;
            border: none;
            padding: 4px 6px;
            vertical-align: top;
        }
        .meta-label {
            font-size: 8px;
            text-transform: uppercase;
            color: #718096;
            font-weight: bold;
            display: block;
        }
        .meta-value {
            font-size: 11px;
            font-weight: bold;
            color: #1a365d;
            margin-top: 1px;
        }
        /* Summary Table Layout (Alternative to Flexbox) */
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 10px 0;
            margin-left: -10px;
            margin-right: -10px;
        }
        .summary-table td {
            width: 33.33%;
            border: none;
            padding: 12px;
            border-radius: 4px;
            text-align: center;
        }
        .card-blue { background: #eff6ff; border: 1px solid #bfdbfe; }
        .card-green { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .card-orange { background: #fff7ed; border: 1px solid #fed7aa; }
        
        .card-label { font-size: 8px; text-transform: uppercase; color: #718096; font-weight: bold; display: block; }
        .card-value { font-size: 16px; font-weight: bold; margin-top: 3px; }
        .card-subtext { font-size: 8px; color: #a0aec0; margin-top: 1px; }

        .card-blue .card-value { color: #2b6cb0; }
        .card-green .card-value { color: #2f855a; }
        .card-orange .card-value { color: #c05621; }

        /* Table Rincian */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1a365d;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #2b6cb0;
            text-transform: uppercase;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 30px;
        }
        .data-table th {
            background: #1a365d;
            color: white;
            padding: 8px 10px;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            font-size: 9px;
            border: 1px solid #1a365d;
        }
        .data-table td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
        }
        .data-table tr:nth-child(even) { background: #f7fafc; }
        
        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-green { background: #c6f6d5; color: #22543d; }
        .badge-yellow { background: #fefcbf; color: #744210; }

        /* Progress Bar (simplified for PDF compatibility) */
        .progress-container {
            display: inline-block;
            vertical-align: middle;
            margin-left: 8px;
            width: 60px;
            background: #edf2f7;
            border-radius: 3px;
            height: 5px;
            border: 1px solid #e2e8f0;
        }
        .progress-bar {
            height: 3px;
            border-radius: 2px;
            background: #3182ce;
        }
        .progress-green { background: #38a169; }
        .progress-yellow { background: #ecc94b; }
        .progress-red { background: #e53e3e; }

        /* Signature Table */
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border: none;
        }
        .signature-table td {
            border: none;
            width: 50%;
            font-size: 10px;
        }
        .signature-title {
            margin-bottom: 50px;
        }
        
        /* Footer */
        .footer-table {
            width: 100%;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            margin-top: 20px;
            font-size: 8px;
            color: #a0aec0;
        }
        .footer-table td {
            border: none;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <h2>Sistem Monitoring Program Makan Bergizi Gratis (MBG)</h2>
        <h3>Laporan Realisasi Penyaluran Harian</h3>
        <p>Alamat Pengelola: Gd. Kantor Pusat Program MBG Kota • Email: support@mbg-monitoring.up.railway.app</p>
        <hr class="garis-kop">
    </div>

    <!-- Meta Info Table (No Flexbox) -->
    <table class="meta-table">
        <tr>
            <td>
                <span class="meta-label">Periode</span>
                <div class="meta-value">
                    @if($tanggal_mulai && $tanggal_akhir)
                        {{ \Carbon\Carbon::parse($tanggal_mulai)->translatedFormat('d F Y') }} &ndash; {{ \Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') }}
                    @elseif($tanggal_mulai)
                        {{ \Carbon\Carbon::parse($tanggal_mulai)->translatedFormat('d F Y') }}
                    @else
                        Semua Data
                    @endif
                </div>
            </td>
            <td>
                <span class="meta-label">Total Hari</span>
                <div class="meta-value">{{ count($laporan) }} Hari</div>
            </td>
            <td>
                <span class="meta-label">Dicetak Pada</span>
                <div class="meta-value">{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>
            </td>
            <td>
                <span class="meta-label">Dicetak Oleh</span>
                <div class="meta-value">Admin Sistem</div>
            </td>
        </tr>
    </table>

    <!-- Summary Cards Table (No Flexbox) -->
    @php
        $totalDisalurkan = collect($laporan)->sum('total_disalurkan');
        $totalTarget = collect($laporan)->sum('target');
        $persenTotal = $totalTarget > 0 ? round(($totalDisalurkan / $totalTarget) * 100, 1) : 0;
    @endphp
    <table class="summary-table">
        <tr>
            <td class="card-blue">
                <span class="card-label">Total Realisasi</span>
                <div class="card-value">{{ number_format($totalDisalurkan) }}</div>
                <span class="card-subtext">Porsi Disalurkan</span>
            </td>
            <td class="card-green">
                <span class="card-label">Total Target</span>
                <div class="card-value">{{ number_format($totalTarget) }}</div>
                <span class="card-subtext">Porsi Target</span>
            </td>
            <td class="card-orange">
                <span class="card-label">Capaian Rata-Rata</span>
                <div class="card-value">{{ $persenTotal }}%</div>
                <span class="card-subtext font-weight-bold">Dari Target Total</span>
            </td>
        </tr>
    </table>

    <!-- Rincian Tabel -->
    <div class="section-title">Rincian Penyaluran Harian</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 25%;">Tanggal</th>
                <th style="width: 20%;">Realisasi (Porsi)</th>
                <th style="width: 20%;">Target (Porsi)</th>
                <th style="width: 18%;">Persentase</th>
                <th style="width: 12%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $i => $lap)
                @php $persen = $lap['target'] > 0 ? ($lap['total_disalurkan'] / $lap['target']) * 100 : 0; @endphp
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td><strong>{{ \Carbon\Carbon::parse($lap['tanggal'])->translatedFormat('d F Y') }}</strong></td>
                    <td>{{ number_format($lap['total_disalurkan']) }} Porsi</td>
                    <td>{{ number_format($lap['target']) }} Porsi</td>
                    <td>
                        {{ round($persen, 1) }}%
                        <div class="progress-container">
                            @php
                                $colorClass = 'progress-red';
                                if ($persen >= 100) { $colorClass = 'progress-green'; }
                                elseif ($persen >= 75) { $colorClass = 'progress-yellow'; }
                            @endphp
                            <div class="progress-bar {{ $colorClass }}" style="width: {{ min($persen, 100) }}%;"></div>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $lap['status'] == 'Selesai' ? 'badge-green' : 'badge-yellow' }}">
                            {{ $lap['status'] }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #a0aec0;">
                        Tidak ada data penyaluran pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Section -->
    <table class="signature-table">
        <tr>
            <td></td>
            <td style="text-align: right; padding-right: 20px;">
                <div class="signature-title">
                    <p>Surabaya, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p><strong>Pengelola Program MBG,</strong></p>
                </div>
                <div style="height: 60px;"></div>
                <p><strong><u>Admin Sistem</u></strong></p>
                <p style="font-size: 8px; color: #718096; margin-top: 2px;">NIP. 19900213 202305 1 001</p>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <table class="footer-table">
        <tr>
            <td>Laporan resmi diunduh dari Sistem Monitoring MBG. Dokumen ini sah dan dicetak secara elektronik.</td>
            <td style="text-align: right;">Halaman 1 dari 1</td>
        </tr>
    </table>

</body>
</html>
