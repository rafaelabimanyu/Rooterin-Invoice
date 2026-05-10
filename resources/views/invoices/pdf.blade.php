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
        .company-tagline { font-size: 9px; color: #4f46e5; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
        .company-contact { font-size: 9px; color: #64748b; line-height: 1.4; }
        
        .doc-info { float: right; width: 35%; text-align: right; margin-top: 10px; }
        .doc-type { font-size: 28px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 5px; letter-spacing: -1px; }
        .doc-id { font-size: 14px; font-weight: 700; color: #4f46e5; margin-bottom: 15px; }
        .doc-meta { font-size: 10px; color: #64748b; }
        .doc-meta b { color: #0f172a; }

        .divider { border-top: 2px solid #f1f5f9; margin: 30px 0; clear: both; }

        /* Addressing */
        .addressing { margin-bottom: 40px; }
        .bill-to { float: left; width: 45%; }
        .status-box { float: right; width: 45%; text-align: right; }
        .section-label { font-size: 9px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; display: block; }
        
        .client-card { background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #f1f5f9; }
        .client-name { font-size: 14px; font-weight: 900; color: #0f172a; margin-bottom: 5px; }
        .client-details { font-size: 10px; color: #64748b; line-height: 1.5; }

        .badge { display: inline-block; padding: 8px 20px; rounded: 30px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; font-size: 10px; border-radius: 50px; }
        .badge-paid { background: #ecfdf5; color: #059669; border: 1px solid #d1fae5; }
        .badge-unpaid { background: #fef2f2; color: #e11d48; border: 1px solid #fee2e2; }

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
        
        /* Summary & Bank */
        .financials { margin-top: 40px; }
        .bank-box { float: left; width: 50%; background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; }
        .bank-title { font-size: 10px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 12px; display: block; }
        .bank-details { font-size: 10px; color: #64748b; line-height: 1.6; }
        .bank-details b { color: #0f172a; }

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
        <div class="watermark">{{ $invoice->status === 'paid' ? __('invoice.paid') : __('invoice.unpaid') }}</div>

        <!-- Header / Letterhead -->
        <div class="header clearfix">
            <div class="logo-box">
                @if(file_exists(public_path('img/logo-rooterin.png')))
                    <img src="{{ public_path('img/logo-rooterin.png') }}" class="logo-img">
                @else
                    <div style="font-size: 24px; font-weight: 900; color: #0f172a; margin-bottom: 15px;">Rooterin<span style="color: #4f46e5;">.</span></div>
                @endif
                <div class="company-name">Rooterin Technical Services</div>
                <div class="company-tagline">High-Precision Operational Solutions</div>
                <div class="company-contact">
                    Gedung Artha Graha, Lt. 18, Kav. 52-53, Jakarta Selatan<br>
                    T: +62 21 555 1234 | E: billing@rooterin.com | W: www.rooterin.com
                </div>
            </div>
            <div class="doc-info">
                <div class="doc-type">{{ __('invoice.title') }}</div>
                <div class="doc-id">#{{ $invoice->invoice_number }}</div>
                <div class="doc-meta">
                    {{ __('invoice.date') }}: <b>{{ $invoice->tanggal_invoice->format('d M Y') }}</b><br>
                    {{ __('invoice.due_date') }}: <b>{{ $invoice->due_date->format('d M Y') }}</b>
                </div>
            </div>
        </div>

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
                    <div style="font-size: 11px; font-weight: 700; color: #0f172a;">{{ $invoice->warranty }}</div>
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
                        <div class="item-desc-secondary">Technical implementation fulfillment</div>
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
            <div class="bank-box">
                <span class="bank-title">{{ __('invoice.bank_account') }}</span>
                <div class="bank-details">
                    {!! nl2br(e($invoice->bank_account_info ?: "Bank Central Asia (BCA)\nAccount: 7712 888 123\nName: Rooterin Technical Services")) !!}
                </div>
            </div>
            <div class="summary-box">
                <div class="summary-line clearfix">
                    <span class="summary-label">{{ __('invoice.subtotal') }}</span>
                    <span class="summary-value">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($invoice->tax_percent > 0)
                <div class="summary-line clearfix">
                    <span class="summary-label">{{ __('invoice.tax') }} ({{ $invoice->tax_percent }}%)</span>
                    <span class="summary-value">+ Rp {{ number_format($invoice->subtotal * ($invoice->tax_percent/100), 0, ',', '.') }}</span>
                </div>
                @endif
                @if($invoice->discount_percent > 0)
                <div class="summary-line clearfix">
                    <span class="summary-label">{{ __('invoice.discount') }} ({{ $invoice->discount_percent }}%)</span>
                    <span class="summary-value">- Rp {{ number_format($invoice->subtotal * ($invoice->discount_percent/100), 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="summary-line total clearfix">
                    <span class="summary-label">{{ __('invoice.grand_total') }}</span>
                    <span class="summary-value">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if($invoice->notes)
        <div style="margin-top: 40px; clear: both;">
            <span class="section-label">{{ __('invoice.notes') }}</span>
            <div style="font-size: 10px; color: #64748b; line-height: 1.5;">{{ $invoice->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            Rooterin Enterprise Billing &bull; tech-ops@rooterin.com &bull; +62 21 555 1234<br>
            This is an electronically generated document. No signature required.
        </div>
    </div>

    <!-- Documentation Page -->
    @if($invoice->attachments && $invoice->attachments->count() > 0)
    <div style="page-break-before: always;">
        <div class="container">
            <div class="header clearfix">
                <div class="logo-box">
                    @if(file_exists(public_path('img/logo-rooterin.png')))
                        <img src="{{ public_path('img/logo-rooterin.png') }}" class="logo-img">
                    @else
                        <div style="font-size: 24px; font-weight: 900; color: #0f172a; margin-bottom: 15px;">Rooterin<span style="color: #4f46e5;">.</span></div>
                    @endif
                    <div class="company-name">Rooterin Technical Services</div>
                </div>
                <div class="doc-info">
                    <div class="doc-type">{{ __('invoice.documentation') }}</div>
                    <div class="doc-id">#{{ $invoice->invoice_number }}</div>
                </div>
            </div>
            
            <div class="divider"></div>
            
            <div class="clearfix">
                @foreach($invoice->attachments as $attachment)
                <div style="float: left; width: 48%; margin-right: 4%; margin-bottom: 30px; @if($loop->iteration % 2 == 0) margin-right: 0; @endif">
                    @if(file_exists(public_path('storage/' . $attachment->file_path)))
                        <img src="{{ public_path('storage/' . $attachment->file_path) }}" style="width: 100%; border-radius: 12px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    @endif
                    @if($attachment->caption)
                    <div style="font-size: 10px; color: #64748b; margin-top: 10px; font-weight: 700; text-align: center;">{{ $attachment->caption }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            
            <div class="footer">
                Reference Document #{{ $invoice->invoice_number }} &bull; Page 2 (Attachments)
            </div>
        </div>
    </div>
    @endif
</body>
</html>
