<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() == 'en' ? 'Receipt' : 'Kuitansi' }} #{{ $receipt->receipt_number }}</title>
    <style>
        @page { 
            margin-top: 60px; 
            margin-bottom: 50px; 
            margin-left: 50px; 
            margin-right: 50px; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            margin: 0; 
            padding: 0; 
            font-size: 11px; 
            line-height: 1.6; 
            background: #fff;
        }
        .container { 
            padding: 0; 
            padding-bottom: 350px; 
            position: relative; 
            box-sizing: border-box; 
        }
        
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
            border: 1px solid #334155;
        }
        .items-table td { 
            padding: 18px 15px; 
            border: 1px solid #e2e8f0; 
            vertical-align: middle; 
        }
        .items-table tr:nth-child(even) { background: #fcfdfe; }
        
        .item-desc-primary { font-weight: 700; color: #0f172a; font-size: 11px; }
        .item-desc-secondary { font-size: 9px; color: #94a3b8; margin-top: 2px; }
        
        /* Financials Box Styling */
        .bank-box { background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; }
        .bank-title { font-size: 9pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 12px; display: block; }
        .bank-details { font-size: 9pt; color: #64748b; line-height: 1.6; }

        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }

        .bottom-section {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
        }

        .footer { 
            position: absolute; 
            bottom: 0px; 
            left: 0; 
            right: 0; 
            text-align: center; 
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
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

        <!-- Header / Letterhead Table -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
            <tr>
                <td style="width: 65%; vertical-align: top;">
                    <div style="font-size: 8pt; color: #64748b; font-weight: 300; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Jawa Dan Jaya Rooter</div>
                    <div style="font-size: 14pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin: 0; line-height: 1.2;">{{ strtoupper(\App\Models\Setting::get('company_name', 'J&J GROUP Technical Services')) }}</div>
                    <div style="font-size: 8.5pt; color: #c89d3c; font-weight: 300; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 3px;">{{ strtoupper(app()->getLocale() == 'en' ? 'High-Precision Operational Solutions' : 'Solusi Operasional Presisi Tinggi') }}</div>
                </td>
                <td style="width: 35%; vertical-align: top; text-align: right;">
                    @php
                        $logoPath = public_path('img/logo-jnj.png');
                        $logoData = "";
                        if (file_exists($logoPath)) {
                            $logoData = base64_encode(file_get_contents($logoPath));
                        }
                    @endphp
                    @if($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" style="height: 60px;">
                    @else
                        <div style="font-size: 20px; font-weight: 900; color: #0f172a;">J&J GROUP<span style="color: #c89d3c;">.</span></div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Two-column info table: Address/Contacts & Receipt Details -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <div style="font-size: 9.5pt; color: #475569; line-height: 1.6;">
                        <span style="color: #0f172a; font-weight: bold; display: block; margin-bottom: 6px;">{{ \App\Models\Setting::get('company_address', 'Jl. Dewa RT.002/002 No.70, Ciracas, Jakarta Timur') }}</span>
                        <div style="margin-top: 5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="10" height="10" style="display: inline-block; vertical-align: middle; margin-right: 6px;" fill="none" stroke="#c89d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            <span style="vertical-align: middle;">{{ \App\Models\Setting::get('company_phone', '0812-400-0749 / 0812-8330-0900') }}</span>
                        </div>
                        <div style="margin-top: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="10" height="10" style="display: inline-block; vertical-align: middle; margin-right: 6px;" fill="none" stroke="#c89d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <span style="vertical-align: middle;">{{ strtolower(\App\Models\Setting::get('company_email', 'jayarooter@gmail.com / jawarooter@gmail.com')) }}</span>
                        </div>
                        <div style="margin-top: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="10" height="10" style="display: inline-block; vertical-align: middle; margin-right: 6px;" fill="none" stroke="#c89d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            <span style="vertical-align: middle;">{{ strtolower(\App\Models\Setting::get('company_website', 'jayarooter.com / jawarooter.com')) }}</span>
                        </div>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top; text-align: right; padding-top: 5px;">
                    <div style="font-size: 24pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 2px; letter-spacing: -1px;">{{ app()->getLocale() == 'en' ? 'Receipt' : 'Kuitansi' }}</div>
                    <div style="font-size: 13pt; font-weight: 700; color: #c89d3c; margin-bottom: 15px;">#{{ $receipt->receipt_number }}</div>
                    <div style="font-size: 9.5pt; color: #64748b; line-height: 1.4;">
                        {{ app()->getLocale() == 'en' ? 'Receipt Date' : 'Tanggal Kuitansi' }}: <b style="color: #0f172a;">{{ $receipt->tanggal_receipt->format('d M Y') }}</b><br>
                        {{ app()->getLocale() == 'en' ? 'Expiry Date' : 'Tanggal Kedaluwarsa' }}: <b style="color: #0f172a;">{{ $receipt->expiry_date->format('d M Y') }}</b>
                    </div>
                </td>
            </tr>
        </table>

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
        @if($receipt->notes)
        <div style="margin-top: 25px; clear: both; page-break-inside: avoid;">
            <span class="section-label">{{ app()->getLocale() == 'en' ? 'Notes' : 'Catatan' }}</span>
            <div style="font-size: 9pt; color: #64748b; line-height: 1.5;">{{ $receipt->notes }}</div>
        </div>
        @endif
    </div>

        <!-- Bottom Layout Table: Bank Account Info (Left) & Financial Summary + Standalone Signature (Right) -->
        <div class="bottom-section" style="page-break-inside: avoid;">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
                <tr>
                    <!-- Kolom Kiri (50%): Informasi Rekening Bank -->
                    <td style="width: 50%; vertical-align: top; padding-right: 25px;">
                        <div class="bank-box" style="margin: 0;">
                            <span class="bank-title">{{ app()->getLocale() == 'en' ? 'Bank Account' : 'Rekening Bank' }}</span>
                            <div class="bank-details" style="font-size: 9.5pt; line-height: 1.6; color: #475569;">
                                {!! nl2br(e(\App\Models\Setting::get('bank_account_info') ?: "Bank Central Asia (BCA)\nAccount: 7712 888 123\nName: J&J GROUP Technical Services")) !!}
                            </div>
                        </div>
                    </td>
                    
                    <!-- Kolom Kanan (50%): Ringkasan Keuangan & Tanda Tangan -->
                    <td style="width: 50%; vertical-align: top; padding-left: 25px;">
                        <!-- Ringkasan Keuangan -->
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">Subtotal</td>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #0f172a; font-size: 10pt;">Rp {{ number_format($receipt->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @if($receipt->tax_percent > 0)
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">{{ app()->getLocale() == 'en' ? 'Tax' : 'Pajak' }} ({{ $receipt->tax_percent }}%)</td>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #0f172a; font-size: 10pt;">+ Rp {{ number_format($receipt->subtotal * ($receipt->tax_percent/100), 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($receipt->discount_percent > 0)
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">{{ app()->getLocale() == 'en' ? 'Discount' : 'Diskon' }} ({{ $receipt->discount_percent }}%)</td>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #0f172a; font-size: 10pt;">- Rp {{ number_format($receipt->subtotal * ($receipt->discount_percent/100), 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="padding: 15px; background: #0f172a; border-top-left-radius: 12px; border-bottom-left-radius: 12px; color: rgba(255,255,255,0.8); font-size: 10.5pt; font-weight: 900; text-transform: uppercase;">{{ app()->getLocale() == 'en' ? 'Total Amount' : 'Jumlah Total' }}</td>
                                <td style="padding: 15px; background: #0f172a; border-top-right-radius: 12px; border-bottom-right-radius: 12px; text-align: right; color: #ffffff; font-size: 15pt; font-weight: 900; white-space: nowrap;">Rp {{ number_format($receipt->total, 0, ',', '.') }}</td>
                            </tr>
                        </table>

                        <!-- Tanda Tangan (di bawah ringkasan keuangan, margin-top cukup, dipusatkan) -->
                        <div style="margin-top: 35px; text-align: center;">
                            @if(file_exists(public_path('img/ttd.png')))
                                <img src="{{ public_path('img/ttd.png') }}" style="width: 180px; display: inline-block;">
                            @else
                                <div style="height: 70px; color: #94a3b8; font-style: italic; font-size: 9pt; line-height: 70px;">(Tanda Tangan)</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    <!-- Footer -->
    <div class="footer">
        {{ \App\Models\Setting::get('company_name', 'J&J GROUP') }} &bull; E: {{ explode(' / ', \App\Models\Setting::get('company_email', 'Jayarooter@gmail.com'))[0] }} &bull; T: {{ explode(' / ', \App\Models\Setting::get('company_phone', '0812-400-0749'))[0] }}
    </div>
</body>
</html>
