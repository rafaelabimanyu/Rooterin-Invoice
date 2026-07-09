<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kinerja Unit Bisnis - {{ $businessUnit->name }}</title>
    <style>
        @page { 
            margin-top: 50px; 
            margin-bottom: 50px; 
            margin-left: 50px; 
            margin-right: 50px; 
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 10pt;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .header {
            background-color: #0F2A44;
            color: #ffffff;
            padding: 30px;
            border-bottom: 5px solid #1FAF5A;
            border-radius: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 22pt;
            font-weight: 900;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 10pt;
            color: #94a3b8;
            font-weight: bold;
        }
        .container {
            padding: 0;
        }
        
        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: 900;
            color: rgba(241, 245, 249, 0.15);
            z-index: -1;
            text-transform: uppercase;
            letter-spacing: 15px;
        }

        .divider { border-top: 2px solid #f1f5f9; margin: 20px 0; clear: both; }

        .meta-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 6px 0;
            font-size: 9.5pt;
        }
        .meta-table td.label {
            font-weight: 900;
            color: #64748b;
            text-transform: uppercase;
            width: 130px;
        }
        .meta-table td.value {
            color: #0f172a;
            font-weight: bold;
        }
        .meta-table td.date {
            text-align: right;
            color: #94a3b8;
            font-weight: bold;
        }
        .metrics-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .metric-card {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }
        .metric-card.highlight {
            border-left: 4px solid #1FAF5A;
        }
        .metric-card .title {
            font-size: 8.5pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 900;
        }
        .metric-card .value {
            font-size: 15pt;
            font-weight: 900;
            color: #0F2A44;
        }
        .section-title {
            font-size: 11pt;
            font-weight: 900;
            color: #0F2A44;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #1FAF5A;
            padding-bottom: 6px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th {
            background-color: #0F2A44;
            color: #ffffff;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
            padding: 14px 16px;
            font-size: 8.5pt;
            border: 1px solid #1e293b;
        }
        .data-table td {
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            font-size: 9.5pt;
        }
        .data-table tr:nth-child(even) td {
            background-color: #fcfdfe;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 8pt;
            font-weight: 900;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-success {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #d1fae5;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">INTERNAL</div>

    <div class="header">
        <h1>LAPORAN KINERJA UNIT BISNIS</h1>
        <p>J&J Group Enterprise • Unit Bisnis: {{ $businessUnit->name }}</p>
    </div>

    <div class="container">
        <table class="meta-table">
            <tr>
                <td class="label">Periode Laporan</td>
                <td class="value">: {{ $dateRange }}</td>
                <td class="date">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</td>
            </tr>
        </table>

        <table class="metrics-grid" cellspacing="10">
            <tr>
                <td width="25%">
                    <div class="metric-card highlight">
                        <div class="title">Total Penagihan</div>
                        <div class="value">Rp {{ number_format($stats['total_billed'], 0, ',', '.') }}</div>
                    </div>
                </td>
                <td width="25%">
                    <div class="metric-card">
                        <div class="title">Total Pendapatan</div>
                        <div class="value" style="color: #1FAF5A;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                    </div>
                </td>
                <td width="25%">
                    <div class="metric-card">
                        <div class="title">Total Piutang</div>
                        <div class="value" style="color: #ef4444;">Rp {{ number_format($stats['total_outstanding'], 0, ',', '.') }}</div>
                    </div>
                </td>
                <td width="25%">
                    <div class="metric-card">
                        <div class="title">Tingkat Koleksi</div>
                        <div class="value">{{ number_format($stats['collection_rate'], 1) }}%</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">Tren Pendapatan & Piutang (6 Bulan Terakhir)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-right">Total Penagihan</th>
                    <th class="text-right">Pendapatan Terbayar</th>
                    <th class="text-right">Sisa Piutang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trend as $t)
                    <tr>
                        <td>{{ $t['month_label'] }}</td>
                        <td class="text-right">Rp {{ number_format($t['total_billed'], 0, ',', '.') }}</td>
                        <td class="text-right" style="color: #1FAF5A;">Rp {{ number_format($t['revenue'], 0, ',', '.') }}</td>
                        <td class="text-right" style="color: #ef4444;">Rp {{ number_format($t['receivables'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data tren dalam periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Kontribusi Klien Utama (Top 5)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Klien</th>
                    <th>Nama Perusahaan</th>
                    <th class="text-center">Jumlah Invoice</th>
                    <th class="text-right">Total Kontribusi Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topClients as $client)
                    <tr>
                        <td>{{ $client->nama_client }}</td>
                        <td>{{ $client->nama_perusahaan ?: '-' }}</td>
                        <td class="text-center">{{ $client->invoices_count }}</td>
                        <td class="text-right" style="font-weight: bold; color: #1FAF5A;">
                            Rp {{ number_format($client->invoices_sum_total, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data kontribusi klien.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Sistem Keuangan J&J Group. Rahasia & Hanya untuk Internal. Halaman 1 dari 1
    </div>
</body>
</html>
