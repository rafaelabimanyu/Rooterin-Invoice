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
        .container { padding: 50px; position: relative; min-height: 1000px; }
        
        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            font-weight: 900;
            color: rgba(241, 245, 249, 0.05);
            z-index: -1;
            text-transform: uppercase;
            letter-spacing: 20px;
        }

        .header { margin-bottom: 50px; border-bottom: 2px solid #f1f5f9; padding-bottom: 30px; }
        .logo-container { float: left; width: 50%; }
        .logo-text { font-size: 28px; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: -1.5px; }
        .logo-dot { color: #4f46e5; }
        .company-details { font-size: 9px; color: #64748b; margin-top: 5px; }
        
        .invoice-info { float: right; width: 45%; text-align: right; }
        .invoice-label { font-size: 36px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 10px; line-height: 1; }
        .invoice-meta { font-size: 10px; color: #64748b; }
        .invoice-meta b { color: #0f172a; }

        .address-box { margin-bottom: 40px; }
        .bill-to { float: left; width: 50%; }
        .bill-from { float: right; width: 45%; text-align: right; }
        .section-title { font-size: 8px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 5px; display: inline-block; }
        
        .client-name { font-size: 14px; font-weight: 900; color: #0f172a; margin-bottom: 5px; }
        .client-details { font-size: 10px; color: #64748b; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .items-table th { 
            background: #0f172a; 
            color: #fff; 
            text-align: left; 
            padding: 12px 15px; 
            font-size: 8px; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
        }
        .items-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .items-table tr:nth-child(even) { background: #fcfdfe; }
        
        .item-name { font-weight: 700; color: #0f172a; font-size: 11px; margin-bottom: 2px; display: block; }
        .item-desc { font-size: 9px; color: #94a3b8; }
        
        .totals-container { margin-top: 30px; }
        .bank-details { float: left; width: 55%; background: #f8fafc; padding: 20px; border-radius: 12px; }
        .bank-title { font-size: 9px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 10px; display: block; }
        .bank-info { font-size: 10px; color: #64748b; }
        .bank-info b { color: #0f172a; }

        .summary-box { float: right; width: 35%; }
        .summary-row { padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        .summary-row.grand-total { border-bottom: none; margin-top: 10px; background: #f4f7ff; padding: 15px; border-radius: 8px; }
        .summary-label { float: left; color: #64748b; font-weight: 600; }
        .summary-value { float: right; text-align: right; font-weight: 700; color: #0f172a; }
        .total-label { font-size: 12px; font-weight: 900; color: #0f172a; text-transform: uppercase; }
        .total-value { font-size: 18px; font-weight: 900; color: #4f46e5; }

        .notes-section { margin-top: 50px; border-left: 4px solid #e2e8f0; padding-left: 20px; }
        .notes-title { font-size: 9px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 5px; }
        .notes-content { font-size: 9px; color: #64748b; font-style: italic; }

        .footer { 
            position: absolute; 
            bottom: 50px; 
            left: 50px; 
            right: 50px; 
            text-align: center; 
            border-top: 1px solid #f1f5f9; 
            padding-top: 20px;
            font-size: 8px;
            color: #94a3b8;
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

        <!-- Header -->
        <div class="header clearfix">
            <div class="logo-container">
                <div class="logo-text">Rooterin<span class="logo-dot">.</span></div>
                <div class="company-details">
                    <b>Rooterin Technical Services</b><br>
                    Enterprise Billing Solution<br>
                    Jakarta, Indonesia | contact@rooterin.com
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-label">{{ __('invoice.title') }}</div>
                <div class="invoice-meta">
                    {{ __('invoice.invoice_number') }}: <b>{{ $invoice->invoice_number }}</b><br>
                    {{ __('invoice.date') }}: <b>{{ $invoice->tanggal_invoice->format('d M Y') }}</b><br>
                    {{ __('invoice.due_date') }}: <b>{{ $invoice->due_date->format('d M Y') }}</b>
                </div>
            </div>
        </div>

        <!-- Address Area -->
        <div class="address-box clearfix">
            <div class="bill-to">
                <div class="section-title">{{ __('invoice.bill_to') }}</div>
                <div class="client-name">{{ $invoice->client->nama_client }}</div>
                <div class="client-details">
                    <b>{{ $invoice->client->nama_perusahaan }}</b><br>
                    {{ $invoice->client->alamat }}<br>
                    {{ $invoice->client->kota }}, {{ $invoice->client->provinsi }}<br>
                    Phone: {{ $invoice->client->no_hp }}
                </div>
            </div>
            <div class="bill-from">
                <div class="section-title">{{ __('invoice.payment_status') }}</div>
                <div style="margin-top: 5px;">
                    <div style="font-size: 20px; font-weight: 900; color: {{ $invoice->status === 'paid' ? '#059669' : '#e11d48' }}; text-transform: uppercase;">
                        {{ $invoice->status === 'paid' ? __('invoice.paid') : __('invoice.unpaid') }}
                    </div>
                    @if($invoice->warranty)
                    <div style="margin-top: 10px; font-size: 10px; color: #64748b;">
                        {{ __('invoice.warranty') }}: <b>{{ $invoice->warranty }}</b>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
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
                        <span class="item-name">{{ $item->deskripsi }}</span>
                        <span class="item-desc">Professional service fulfillment</span>
                    </td>
                    <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals & Bank -->
        <div class="totals-container clearfix">
            <div class="bank-details">
                <span class="bank-title">{{ __('invoice.bank_account') }}</span>
                <div class="bank-info">
                    {!! nl2br(e($invoice->bank_account_info ?: "Bank Central Asia (BCA)\nAcc No: 123-456-7890\nName: Rooterin Technical Services")) !!}
                </div>
            </div>
            <div class="summary-box">
                <div class="summary-row clearfix">
                    <span class="summary-label">{{ __('invoice.subtotal') }}</span>
                    <span class="summary-value">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($invoice->tax_percent > 0)
                <div class="summary-row clearfix">
                    <span class="summary-label">{{ __('invoice.tax') }} ({{ $invoice->tax_percent }}%)</span>
                    <span class="summary-value">+ Rp {{ number_format($invoice->subtotal * ($invoice->tax_percent/100), 0, ',', '.') }}</span>
                </div>
                @endif
                @if($invoice->discount_percent > 0)
                <div class="summary-row clearfix">
                    <span class="summary-label">{{ __('invoice.discount') }} ({{ $invoice->discount_percent }}%)</span>
                    <span class="summary-value">- Rp {{ number_format($invoice->subtotal * ($invoice->discount_percent/100), 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="summary-row grand-total clearfix">
                    <span class="summary-label total-label">{{ __('invoice.grand_total') }}</span>
                    <span class="summary-value total-value">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Documentation Photos if any -->
        @if($invoice->attachments && $invoice->attachments->count() > 0)
        <div style="page-break-before: always; padding-top: 50px;">
            <div class="section-title">{{ __('invoice.documentation') }}</div>
            <div class="clearfix" style="margin-top: 20px;">
                @foreach($invoice->attachments as $attachment)
                <div style="float: left; width: 48%; margin-right: 4%; margin-bottom: 20px; @if($loop->iteration % 2 == 0) margin-right: 0; @endif">
                    <img src="{{ public_path('storage/' . $attachment->file_path) }}" style="width: 100%; border-radius: 8px; border: 1px solid #e2e8f0;">
                    @if($attachment->caption)
                    <p style="font-size: 9px; color: #64748b; margin-top: 5px; text-align: center;">{{ $attachment->caption }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            Rooterin Enterprise Billing Solution &bull; technical.ops@rooterin.com &bull; www.rooterin.com<br>
            This document is a computer-generated confirmation. No physical signature is required.
        </div>
    </div>
</body>
</html>
