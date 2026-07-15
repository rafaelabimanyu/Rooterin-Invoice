<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kinerja Unit Bisnis - {{ $businessUnit->name }}</title>
    <style>
        @page { 
            margin-top: 40px; 
            margin-bottom: 50px; 
            margin-left: 40px; 
            margin-right: 40px; 
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 8.5pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        
        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 70px;
            font-weight: 900;
            color: rgba(241, 245, 249, 0.08);
            z-index: -1;
            text-transform: uppercase;
            letter-spacing: 15px;
        }

        /* Kop Surat Resmi (Official Header) */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-bottom: 3px double #0F2A44;
            padding-bottom: 10px;
        }
        .kop-logo {
            width: 70px;
            vertical-align: middle;
        }
        .kop-logo img {
            height: 55px;
            display: block;
        }
        .kop-text {
            padding-left: 15px;
            vertical-align: middle;
        }
        .company-name {
            font-size: 14pt;
            font-weight: 900;
            color: #0F2A44;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .company-tagline {
            font-size: 7.5pt;
            color: #c89d3c;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1px;
        }
        .report-title-right {
            text-align: right;
            vertical-align: middle;
        }
        .report-label {
            font-size: 13pt;
            font-weight: 900;
            color: #0F2A44;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }
        .report-subtitle {
            font-size: 8pt;
            color: #64748b;
            font-weight: bold;
            margin-top: 2px;
        }

        .meta-info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .meta-info-table td {
            padding: 2px 0;
            font-size: 8.5pt;
        }
        .meta-info-table td.label {
            font-weight: bold;
            color: #64748b;
            width: 100px;
            text-transform: uppercase;
            font-size: 7.5pt;
        }
        .meta-info-table td.value {
            color: #0F2A44;
            font-weight: bold;
        }
        .meta-info-table td.date {
            text-align: right;
            color: #64748b;
            font-weight: bold;
            font-size: 8pt;
        }

        /* Section Titles */
        .section-title {
            font-size: 9.5pt;
            font-weight: 950;
            color: #0F2A44;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #1FAF5A;
            padding-bottom: 4px;
            margin-top: 18px;
            margin-bottom: 10px;
        }

        /* 6 KPI Cards Grid Layout (Table-Based) */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin-bottom: 15px;
        }
        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            vertical-align: top;
        }
        .kpi-card.highlight-green {
            border-left: 3px solid #1FAF5A;
        }
        .kpi-card.highlight-navy {
            border-left: 3px solid #0F2A44;
        }
        .kpi-card.highlight-amber {
            border-left: 3px solid #c89d3c;
        }
        .kpi-card.highlight-blue {
            border-left: 3px solid #3b82f6;
        }
        .kpi-card.highlight-rose {
            border-left: 3px solid #ef4444;
        }
        .kpi-card.highlight-indigo {
            border-left: 3px solid #6366f1;
        }
        .kpi-title {
            font-size: 7pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
            font-weight: bold;
        }
        .kpi-value {
            font-size: 11.5pt;
            font-weight: bold;
            color: #0f172a;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #0F2A44;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
            padding: 8px 10px;
            font-size: 7.5pt;
            border: 1px solid #0F2A44;
        }
        .data-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            font-size: 8pt;
            vertical-align: middle;
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
        
        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 7pt;
            font-weight: bold;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-paid {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #d1fae5;
        }
        .badge-unpaid {
            background-color: #fef2f2;
            color: #e11d48;
            border: 1px solid #fee2e2;
        }
        .badge-pending {
            background-color: #fffbeb;
            color: #d97706;
            border: 1px solid #fef3c7;
        }
        .badge-default {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* Avoid splitting rows across pages */
        tr { 
            page-break-inside: avoid; 
        }

        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">INTERNAL</div>

    <!-- Kop Surat Resmi (Official Header) -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}">
                @else
                    <div style="font-size: 14px; font-weight: 900; color: #0F2A44;">J&J GROUP</div>
                @endif
            </td>
            <td class="kop-text">
                <div class="company-name">J&J GROUP ENTERPRISE</div>
                <div class="company-tagline">Solusi Pintar, Saluran Lancar, Tanpa Bongkar!</div>
            </td>
            <td class="report-title-right">
                <div class="report-label">Laporan Kinerja Unit Bisnis</div>
                <div class="report-subtitle">Unit Bisnis: {{ $businessUnit->name }}</div>
            </td>
        </tr>
    </table>

    <table class="meta-info-table">
        <tr>
            <td class="label">Periode Laporan</td>
            <td class="value">: {{ $dateRange }}</td>
            <td class="date">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</td>
        </tr>
    </table>

    <!-- Executive Summary Section -->
    <div class="section-title">Ringkasan Eksekutif (6 KPI Metrik)</div>
    @php
        $feePercentage = $businessUnit->fee_percentage ?? 0.00;
        $feeNominal = round(($stats['total_revenue'] * $feePercentage) / 100, 2);
        $netRevenue = round($stats['total_revenue'] - $feeNominal, 2);
    @endphp
    <table class="kpi-table" cellspacing="0" cellpadding="0">
        <tr>
            <td width="33.3%">
                <div class="kpi-card highlight-navy">
                    <div class="kpi-title">Total Penagihan</div>
                    <div class="kpi-value">Rp {{ number_format($stats['total_billed'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="33.3%">
                <div class="kpi-card highlight-green">
                    <div class="kpi-title">Omset Kotor</div>
                    <div class="kpi-value" style="color: #1FAF5A;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="33.3%">
                <div class="kpi-card highlight-amber">
                    <div class="kpi-title">Fee Manajemen ({{ number_format($feePercentage, 1, ',', '.') }}%)</div>
                    <div class="kpi-value" style="color: #c89d3c;">Rp {{ number_format($feeNominal, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="33.3%">
                <div class="kpi-card highlight-blue">
                    <div class="kpi-title">Pendapatan Bersih</div>
                    <div class="kpi-value" style="color: #3b82f6;">Rp {{ number_format($netRevenue, 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="33.3%">
                <div class="kpi-card highlight-rose">
                    <div class="kpi-title">Sisa Piutang</div>
                    <div class="kpi-value" style="color: #ef4444;">Rp {{ number_format($stats['total_outstanding'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="33.3%">
                <div class="kpi-card highlight-indigo">
                    <div class="kpi-title">Tingkat Koleksi</div>
                    <div class="kpi-value" style="color: #6366f1;">{{ number_format($stats['collection_rate'], 1, ',', '.') }}%</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Ledger Transaksi (Detail Transaksi) -->
    <div class="section-title">Detail Ledger Transaksi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">Tanggal</th>
                <th width="16%">No. Invoice</th>
                <th>Nama Klien</th>
                <th width="16%" class="text-right">Total Tagihan</th>
                <th width="16%" class="text-right">Terbayar</th>
                <th width="16%" class="text-right">Sisa Piutang</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sumTotal = 0;
                $sumPaid = 0;
                $sumOutstanding = 0;
            @endphp
            @forelse($invoices as $invoice)
                @php
                    $paidAmount = $invoice->payments->sum('amount');
                    $outstandingAmount = $invoice->amount_due;
                    $sumTotal += $invoice->total;
                    $sumPaid += $paidAmount;
                    $sumOutstanding += $outstandingAmount;
                    
                    $status = strtolower($invoice->status);
                    $badgeClass = match ($status) {
                        'aktif', 'paid', 'approved' => 'badge-paid',
                        'cancelled', 'overdue', 'rejected' => 'badge-unpaid',
                        'pending', 'dp', 'partial' => 'badge-pending',
                        default => 'badge-default'
                    };
                @endphp
                <tr>
                    <td>{{ ($invoice->tanggal_invoice ?: $invoice->created_at)->format('d/m/Y') }}</td>
                    <td><strong>{{ $invoice->invoice_number }}</strong></td>
                    <td>
                        {{ $invoice->client->nama_client }}
                        @if($invoice->client->nama_perusahaan)
                            <br><span style="font-size: 7pt; color: #64748b;">{{ $invoice->client->nama_perusahaan }}</span>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #1FAF5A;">Rp {{ number_format($paidAmount, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #ef4444;">Rp {{ number_format($outstandingAmount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }}">
                            {{ $status === 'paid' ? 'Lunas' : ($status === 'unpaid' ? 'Belum Lunas' : strtoupper($invoice->status)) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="font-style: italic; color: #64748b;">
                        Tidak ada rekaman transaksi pada unit bisnis ini dalam periode terpilih.
                    </td>
                </tr>
            @endforelse
            
            @if($invoices->isNotEmpty())
                <tr style="font-weight: bold; background-color: #f8fafc; border-top: 2px solid #0F2A44; border-bottom: 2px solid #0F2A44;">
                    <td colspan="3" style="text-align: right; padding: 10px; font-size: 8.5pt;">TOTAL RINGKASAN</td>
                    <td class="text-right" style="padding: 10px; font-size: 8.5pt;">Rp {{ number_format($sumTotal, 0, ',', '.') }}</td>
                    <td class="text-right" style="padding: 10px; font-size: 8.5pt; color: #1FAF5A;">Rp {{ number_format($sumPaid, 0, ',', '.') }}</td>
                    <td class="text-right" style="padding: 10px; font-size: 8.5pt; color: #ef4444;">Rp {{ number_format($sumOutstanding, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Monthly Cash Flow Trend (Summary Table) -->
    <div class="section-title">Tren Aliran Kas Masuk & Piutang (6 Bulan Terakhir)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="text-right">Total Penagihan</th>
                <th class="text-right">Pendapatan Terbayar (Kas Masuk)</th>
                <th class="text-right">Sisa Piutang Berjalan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trend as $t)
                <tr>
                    <td><strong>{{ $t['month_label'] }}</strong></td>
                    <td class="text-right">Rp {{ number_format($t['total_billed'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #1FAF5A; font-weight: bold;">Rp {{ number_format($t['revenue'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #ef4444;">Rp {{ number_format($t['receivables'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="font-style: italic; color: #64748b;">
                        Tidak ada data tren aliran kas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh Sistem Pembukuan Keuangan J&J Group. Rahasia & Hanya untuk Keperluan Internal. Halaman 1 dari 1
    </div>
</body>
</html>
