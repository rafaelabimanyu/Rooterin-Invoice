<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() == 'en' ? 'Receipt' : 'Kuitansi' }} #{{ $receipt->receipt_number }}</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            margin: 0; 
            padding: 0; 
            font-size: 11px; 
            line-height: 1.6; 
            background: #fff;
        }
        .container { padding: 40px 50px; position: relative; min-height: 1000px; }
        
        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            font-weight: 900;
            color: rgba(241, 245, 249, 0.08);
            z-index: -1;
            text-transform: uppercase;
            letter-spacing: 20px;
        }

        /* Letterhead */
        .header { margin-bottom: 40px; }
        .logo-box { float: left; width: 60%; }
        .logo-img { height: 60px; margin-bottom: 15px; }
        .company-name { font-size: 16px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin: 0; }
        .company-tagline { font-size: 9px; color: #c89d3c; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
        .company-contact { font-size: 9px; color: #64748b; line-height: 1.4; }
        
        .doc-info { float: right; width: 35%; text-align: right; margin-top: 10px; }
        .doc-type { font-size: 28px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 5px; letter-spacing: -1px; }
        .doc-id { font-size: 14px; font-weight: 700; color: #c89d3c; margin-bottom: 15px; }
        .doc-meta { font-size: 10px; color: #64748b; }
        .doc-meta b { color: #0f172a; }

        .divider { border-top: 2px solid #f1f5f9; margin: 30px 0; clear: both; }

        /* Addressing */
        .addressing { margin-bottom: 40px; }
        .bill-to { float: left; width: 45%; }
        .section-label { font-size: 9px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; display: block; }
        
        .client-card { background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #f1f5f9; }
        .client-name { font-size: 14px; font-weight: 900; color: #0f172a; margin-bottom: 5px; }
        .client-details { font-size: 10px; color: #64748b; line-height: 1.5; }

        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { 
            background: #0f172a; 
            color: #fff; 
            text-align: left; 
            padding: 15px; 
            font-size: 9px; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        .items-table td { padding: 18px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .items-table tr:nth-child(even) { background: #fcfdfe; }
        
        .item-desc-primary { font-weight: 700; color: #0f172a; font-size: 11px; }
        .item-desc-secondary { font-size: 9px; color: #94a3b8; margin-top: 2px; }
        
        /* Summary */
        .financials { margin-top: 40px; }
        .summary-box { float: right; width: 40%; }
        .summary-line { padding: 10px 0; border-bottom: 1px solid #f1f5f9; clear: both; }
        .summary-line.total { border-bottom: none; margin-top: 15px; background: #0f172a; padding: 20px; border-radius: 12px; color: #fff; }
        .summary-label { float: left; color: #64748b; font-weight: 600; }
        .summary-value { float: right; text-align: right; font-weight: 700; color: #0f172a; }
        .total .summary-label { color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .total .summary-value { color: #fff; font-size: 20px; font-weight: 900; }

        .footer { 
            position: absolute; 
            bottom: 40px; 
            left: 50px; 
            right: 50px; 
            text-align: center; 
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
        }

        .clearfix::after { content: ""; clear: both; display: table; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Watermark -->
        <div class="watermark">{{ app()->getLocale() == 'en' ? 'RECEIPT' : 'KUITANSI' }}</div>

        <!-- Header / Letterhead -->
        <div class="header clearfix">
            <div class="logo-box">
                @php
                    $logoPath = public_path('img/logo-jnj.png');
                    $logoData = "";
                    if (file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                    }
                @endphp
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" class="logo-img">
                @else
                    <div style="font-size: 24px; font-weight: 900; color: #0f172a; margin-bottom: 15px;">J&J GROUP<span style="color: #c89d3c;">.</span></div>
                @endif
                <div class="company-name">J&J GROUP Technical Services</div>
                <div class="company-tagline">High-Precision Operational Solutions</div>
                <div class="company-contact">
                    Gedung Artha Graha, Lt. 18, Kav. 52-53, Jakarta Selatan<br>
                    T: +62 21 555 1234 | E: billing@jnjgroup.com | W: www.jnjgroup.com
                </div>
            </div>
            <div class="doc-info">
                <div class="doc-type">{{ app()->getLocale() == 'en' ? 'Receipt' : 'Kuitansi' }}</div>
                <div class="doc-id">#{{ $receipt->receipt_number }}</div>
                <div class="doc-meta">
                    {{ app()->getLocale() == 'en' ? 'Receipt Date' : 'Tanggal Kuitansi' }}: <b>{{ $receipt->tanggal_receipt->format('d M Y') }}</b><br>
                    {{ app()->getLocale() == 'en' ? 'Expiry Date' : 'Tanggal Kedaluwarsa' }}: <b>{{ $receipt->expiry_date->format('d M Y') }}</b>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Addressing -->
        <div class="addressing clearfix">
            <div class="bill-to">
                <span class="section-label">{{ app()->getLocale() == 'en' ? 'CLIENT' : 'KLIEN' }}</span>
                <div class="client-card">
                    <div class="client-name">{{ $receipt->client->nama_client }}</div>
                    <div class="client-details">
                        <b>{{ $receipt->client->nama_perusahaan }}</b><br>
                        {{ $receipt->client->alamat }}<br>
                        {{ $receipt->client->kota }}, {{ $receipt->client->provinsi }}<br>
                        {{ app()->getLocale() == 'en' ? 'Contact' : 'Kontak' }}: {{ $receipt->client->no_hp }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">{{ app()->getLocale() == 'en' ? 'Description' : 'Deskripsi' }}</th>
                    <th width="10%" class="text-center">{{ app()->getLocale() == 'en' ? 'Qty' : 'Jumlah' }}</th>
                    <th width="20%" class="text-right">{{ app()->getLocale() == 'en' ? 'Rate' : 'Harga' }}</th>
                    <th width="20%" class="text-right">{{ app()->getLocale() == 'en' ? 'Total' : 'Total' }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt->items as $item)
                <tr>
                    <td>
                        <div class="item-desc-primary">{{ $item->deskripsi }}</div>
                        <div class="item-desc-secondary">{{ app()->getLocale() == 'en' ? 'Service quotation fulfillment' : 'Pemenuhan penawaran layanan' }}</div>
                    </td>
                    <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Financials -->
        <div class="financials clearfix">
            <div class="summary-box">
                <div class="summary-line clearfix">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">Rp {{ number_format($receipt->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($receipt->tax_percent > 0)
                <div class="summary-line clearfix">
                    <span class="summary-label">{{ app()->getLocale() == 'en' ? 'Tax' : 'Pajak' }} ({{ $receipt->tax_percent }}%)</span>
                    <span class="summary-value">+ Rp {{ number_format($receipt->subtotal * ($receipt->tax_percent/100), 0, ',', '.') }}</span>
                </div>
                @endif
                @if($receipt->discount_percent > 0)
                <div class="summary-line clearfix">
                    <span class="summary-label">{{ app()->getLocale() == 'en' ? 'Discount' : 'Diskon' }} ({{ $receipt->discount_percent }}%)</span>
                    <span class="summary-value">- Rp {{ number_format($receipt->subtotal * ($receipt->discount_percent/100), 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="summary-line total clearfix">
                    <span class="summary-label">{{ app()->getLocale() == 'en' ? 'Total Amount' : 'Jumlah Total' }}</span>
                    <span class="summary-value">Rp {{ number_format($receipt->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if($receipt->notes)
        <div style="margin-top: 40px; clear: both;">
            <span class="section-label">{{ app()->getLocale() == 'en' ? 'Notes' : 'Catatan' }}</span>
            <div style="font-size: 10px; color: #64748b; line-height: 1.5;">{{ $receipt->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            J&J GROUP Enterprise Billing &bull; tech-ops@jnjgroup.com &bull; +62 21 555 1234<br>
            {{ app()->getLocale() == 'en' ? 'This document is a formal quotation receipt. Subject to terms and conditions.' : 'Dokumen ini adalah kuitansi penawaran resmi. Tunduk pada syarat dan ketentuan.' }}
        </div>
    </div>
</body>
</html>
