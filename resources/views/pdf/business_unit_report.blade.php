<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kinerja Unit Bisnis - {{ $businessUnit->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #0F2A44;
            color: #ffffff;
            padding: 24px;
            border-bottom: 4px solid #1FAF5A;
        }
        .header h1 {
            margin: 0;
            font-size: 20pt;
            font-weight: 700;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 10pt;
            color: #cbd5e1;
        }
        .container {
            padding: 24px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 24px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 0;
            font-size: 9.5pt;
        }
        .meta-table td.label {
            font-weight: bold;
            color: #475569;
            width: 120px;
        }
        .meta-table td.value {
            color: #0f172a;
        }
        .meta-table td.date {
            text-align: right;
            color: #64748b;
        }
        .metrics-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .metric-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }
        .metric-card.highlight {
            border-left: 4px solid #1FAF5A;
        }
        .metric-card .title {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .metric-card .value {
            font-size: 16pt;
            font-weight: bold;
            color: #0F2A44;
        }
        .section-title {
            font-size: 13pt;
            font-weight: bold;
            color: #0F2A44;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
            margin-top: 24px;
            margin-bottom: 12px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .data-table th {
            background-color: #0F2A44;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 12px;
            font-size: 9.5pt;
        }
        .data-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9.5pt;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 8pt;
            font-weight: bold;
            border-radius: 9999px;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>
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
