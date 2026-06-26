<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('invoice.title') }} #{{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            margin: 0; 
            padding: 0; 
            font-size: 10pt; 
            line-height: 1.6; 
            background: #fff;
        }
        .container { 
            padding: 40px 50px; 
            position: relative; 
            min-height: 1050px; 
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

        .divider { border-top: 2px solid #f1f5f9; margin: 25px 0; clear: both; }

        /* Addressing */
        .addressing { margin-bottom: 30px; }
        .bill-to { float: left; width: 50%; }
        .status-box { float: right; width: 45%; text-align: right; }
        .section-label { font-size: 8.5pt; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; display: block; }
        
        .client-card { background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #f1f5f9; }
        .client-name { font-size: 11pt; font-weight: 900; color: #0f172a; margin-bottom: 5px; }
        .client-details { font-size: 9pt; color: #64748b; line-height: 1.5; }

        .badge { display: inline-block; padding: 8px 20px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; font-size: 9pt; border-radius: 50px; }
        .badge-paid { background: #ecfdf5; color: #059669; border: 1px solid #d1fae5; }
        .badge-unpaid { background: #fef2f2; color: #e11d48; border: 1px solid #fee2e2; }

        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { 
            background: #0f172a; 
            color: #fff; 
            text-align: left; 
            padding: 15px; 
            font-size: 8.5pt; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        .items-table td { padding: 18px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .items-table tr:nth-child(even) { background: #fcfdfe; }
        
        .item-desc-primary { font-weight: 700; color: #0f172a; font-size: 10pt; }
        .item-desc-secondary { font-size: 8.5pt; color: #94a3b8; margin-top: 2px; }
        
        /* Financials Box Styling */
        .bank-box { background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; }
        .bank-title { font-size: 9pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 12px; display: block; }
        .bank-details { font-size: 9pt; color: #64748b; line-height: 1.6; }
        .bank-details b { color: #0f172a; }

        .footer { 
            position: absolute; 
            bottom: 40px; 
            left: 50px; 
            right: 50px; 
            text-align: center; 
            font-size: 8pt;
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
        <div class="watermark">{{ $invoice->status === 'paid' ? __('invoice.paid') : __('invoice.unpaid') }}</div>

        <!-- Header / Letterhead Table-based layout to prevent overlaps -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    @if(file_exists(public_path('img/logo-rooterin.png')))
                        <img src="{{ public_path('img/logo-rooterin.png') }}" style="height: 75px; margin-bottom: 12px;">
                    @else
                        <div style="font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 12px;">Rooterin<span style="color: #4f46e5;">.</span></div>
                    @endif
                    <div style="font-size: 12pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin: 0; line-height: 1.2;">Rooterin Technical Services</div>
                    <div style="font-size: 8.5pt; color: #4f46e5; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 3px; margin-bottom: 8px;">{{ app()->getLocale() == 'en' ? 'High-Precision Operational Solutions' : 'Solusi Operasional Presisi Tinggi' }}</div>
                    <div style="font-size: 9.5pt; color: #475569; line-height: 1.5;">
                        Gedung Artha Graha, Lt. 18, Kav. 52-53, Jakarta Selatan<br>
                        T: +62 21 555 1234 &bull; E: billing@rooterin.com &bull; W: www.rooterin.com
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top; text-align: right; padding-top: 10px;">
                    <div style="font-size: 24pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 2px; letter-spacing: -1px;">{{ __('invoice.title') }}</div>
                    <div style="font-size: 13pt; font-weight: 700; color: #4f46e5; margin-bottom: 15px;">#{{ $invoice->invoice_number }}</div>
                    <div style="font-size: 9.5pt; color: #64748b; line-height: 1.4;">
                        {{ __('invoice.date') }}: <b style="color: #0f172a;">{{ $invoice->tanggal_invoice->format('d M Y') }}</b><br>
                        {{ __('invoice.due_date') }}: <b style="color: #0f172a;">{{ $invoice->due_date->format('d M Y') }}</b>
                    </div>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Addressing -->
        <div class="addressing clearfix">
            <div class="bill-to">
                <span class="section-label">{{ __('invoice.bill_to') }}</span>
                <div class="client-card">
                    <div class="client-name">{{ $invoice->client->nama_client }}</div>
                    <div class="client-details">
                        <b>{{ $invoice->client->nama_perusahaan }}</b><br>
                        {{ $invoice->client->alamat }}<br>
                        {{ $invoice->client->kota }}, {{ $invoice->client->provinsi }}<br>
                        {{ __('ui.contact') }}: {{ $invoice->client->no_hp }}
                    </div>
                </div>
            </div>
            <div class="status-box">
                <span class="section-label">{{ __('invoice.payment_status') }}</span>
                <div class="badge {{ $invoice->status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                    {{ $invoice->status === 'paid' ? __('invoice.paid') : __('invoice.unpaid') }}
                </div>
                @if($invoice->warranty)
                <div style="margin-top: 20px;">
                    <span class="section-label" style="margin-bottom: 5px;">{{ __('invoice.warranty') }}</span>
                    <div style="font-size: 10pt; font-weight: 700; color: #0f172a;">{{ $invoice->warranty }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">{{ __('invoice.description') }}</th>
                    <th width="10%" class="text-center">{{ __('invoice.quantity') }}</th>
                    <th width="20%" class="text-right">{{ __('invoice.price') }}</th>
                    <th width="20%" class="text-right">{{ __('invoice.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div class="item-desc-primary">{{ $item->deskripsi }}</div>
                        <div class="item-desc-secondary">{{ app()->getLocale() == 'en' ? 'Technical implementation fulfillment' : 'Pemenuhan implementasi teknis' }}</div>
                    </td>
                    <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Financials & Bank Info with symmetric, robust layout -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 40px; margin-bottom: 20px;">
            <tr>
                <td style="width: 52%; vertical-align: top; padding-right: 30px;">
                    <div class="bank-box">
                        <span class="bank-title">{{ __('invoice.bank_account') }}</span>
                        <div class="bank-details">
                            {!! nl2br(e($invoice->bank_account_info ?: "Bank Central Asia (BCA)\nAccount: 7712 888 123\nName: Rooterin Technical Services")) !!}
                        </div>
                    </div>
                </td>
                <td style="width: 48%; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">{{ __('invoice.subtotal') }}</td>
                            <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #0f172a; font-size: 10pt;">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($invoice->tax_percent > 0)
                        <tr>
                            <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">{{ __('invoice.tax') }} ({{ $invoice->tax_percent }}%)</td>
                            <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #0f172a; font-size: 10pt;">+ Rp {{ number_format($invoice->subtotal * ($invoice->tax_percent/100), 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($invoice->discount_percent > 0)
                        <tr>
                            <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 10pt;">{{ __('invoice.discount') }} ({{ $invoice->discount_percent }}%)</td>
                            <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 700; color: #0f172a; font-size: 10pt;">- Rp {{ number_format($invoice->subtotal * ($invoice->discount_percent/100), 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding: 15px; background: #0f172a; border-top-left-radius: 12px; border-bottom-left-radius: 12px; color: rgba(255,255,255,0.8); font-size: 10.5pt; font-weight: 900; text-transform: uppercase;">{{ __('invoice.grand_total') }}</td>
                            <td style="padding: 15px; background: #0f172a; border-top-right-radius: 12px; border-bottom-right-radius: 12px; text-align: right; color: #ffffff; font-size: 15pt; font-weight: 900; white-space: nowrap;">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @if($invoice->notes)
        <div style="margin-top: 30px; clear: both;">
            <span class="section-label">{{ __('invoice.notes') }}</span>
            <div style="font-size: 9pt; color: #64748b; line-height: 1.5;">{{ $invoice->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            Rooterin Enterprise Billing &bull; tech-ops@rooterin.com &bull; +62 21 555 1234<br>
            {{ app()->getLocale() == 'en' ? 'This is an electronically generated document. No signature required.' : 'Dokumen ini dibuat secara elektronik. Tidak memerlukan tanda tangan.' }}
        </div>
    </div>

    <!-- Documentation Page -->
    @if($invoice->attachments && $invoice->attachments->count() > 0)
    <div style="page-break-before: always;">
        <div class="container">
            <!-- Documentation Header - Styled matching Lembar 1 -->
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 25px;">
                <tr>
                    <td style="width: 60%; vertical-align: top;">
                        @if(file_exists(public_path('img/logo-rooterin.png')))
                            <img src="{{ public_path('img/logo-rooterin.png') }}" style="height: 75px; margin-bottom: 12px;">
                        @else
                            <div style="font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 12px;">Rooterin<span style="color: #4f46e5;">.</span></div>
                        @endif
                        <div style="font-size: 12pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin: 0; line-height: 1.2;">Rooterin Technical Services</div>
                    </td>
                    <td style="width: 40%; vertical-align: top; text-align: right; padding-top: 10px;">
                        <div style="font-size: 20pt; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 2px;">{{ __('invoice.documentation') }}</div>
                        <div style="font-size: 12pt; font-weight: 700; color: #4f46e5;">#{{ $invoice->invoice_number }}</div>
                    </td>
                </tr>
            </table>
            
            <div class="divider"></div>
            
            <div class="clearfix">
                @foreach($invoice->attachments as $attachment)
                <div style="float: left; width: 48%; margin-right: 4%; margin-bottom: 30px; @if($loop->iteration % 2 == 0) margin-right: 0; @endif">
                    @php
                        $directPath = storage_path('app/public/' . $attachment->file_path);
                    @endphp
                    @if(file_exists($directPath))
                        <img src="{{ $directPath }}" style="width: 100%; border-radius: 12px; border: 1px solid #f1f5f9;">
                    @else
                        <div style="width: 100%; height: 150px; background: #f1f5f9; border-radius: 12px; text-align: center; line-height: 150px; color: #94a3b8; font-size: 10px;">
                            {{ app()->getLocale() == 'en' ? 'Image Missing' : 'Gambar Tidak Ditemukan' }}
                        </div>
                    @endif
                    @if($attachment->caption)
                    <div style="font-size: 9.5pt; color: #64748b; margin-top: 10px; font-weight: 700; text-align: center;">{{ $attachment->caption }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            
            <!-- Technical notes section to balance page footer -->
            <div style="margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 20px; clear: both;">
                <span class="section-label" style="margin-bottom: 8px; display: block;">{{ app()->getLocale() == 'en' ? 'TECHNICAL OPERATIONS STATEMENT' : 'PERNYATAAN OPERASIONAL TEKNIS' }}</span>
                <p style="font-size: 9pt; color: #64748b; line-height: 1.6; margin: 0;">
                    {{ app()->getLocale() == 'en' 
                        ? 'Note: The above documentation was captured directly on-site by authorized Rooterin technicians using high-precision equipment to verify task completion and compliance with strict quality standards.' 
                        : 'Catatan: Dokumentasi di atas diambil langsung di lokasi kerja oleh teknisi resmi Rooterin menggunakan peralatan presisi tinggi untuk memverifikasi penyelesaian pekerjaan sesuai standar kualitas yang ketat.' }}
                </p>
            </div>

            <div class="footer">
                Reference Document #{{ $invoice->invoice_number }} &bull; Page 2 (Attachments)
            </div>
        </div>
    </div>
    @endif
</body>
</html>
