<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('receipt.title') }} #{{ $receipt->receipt_number }}</title>
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
        
        .header { margin-bottom: 50px; border-bottom: 2px solid #f1f5f9; padding-bottom: 30px; }
        .logo-container { float: left; width: 50%; }
        .logo-text { font-size: 28px; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: -1.5px; }
        .logo-dot { color: #4f46e5; }
        .company-details { font-size: 9px; color: #64748b; margin-top: 5px; }
        
        .receipt-info { float: right; width: 45%; text-align: right; }
        .receipt-label { font-size: 36px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 10px; line-height: 1; }
        .receipt-meta { font-size: 10px; color: #64748b; }
        .receipt-meta b { color: #0f172a; }

        .address-box { margin-bottom: 40px; }
        .bill-to { float: left; width: 50%; }
        .bill-from { float: right; width: 45%; text-align: right; }
        .section-title { font-size: 8px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 5px; display: inline-block; }
        
        .client-name { font-size: 14px; font-weight: 900; color: #0f172a; margin-bottom: 5px; }
        .client-details { font-size: 10px; color: #64748b; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .items-table th { 
            background: #4f46e5; 
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
        .summary-box { float: right; width: 40%; }
        .summary-row { padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        .summary-row.grand-total { border-bottom: none; margin-top: 10px; background: #f4f7ff; padding: 15px; border-radius: 8px; }
        .summary-label { float: left; color: #64748b; font-weight: 600; }
        .summary-value { float: right; text-align: right; font-weight: 700; color: #0f172a; }
        .total-label { font-size: 12px; font-weight: 900; color: #0f172a; text-transform: uppercase; }
        .total-value { font-size: 18px; font-weight: 900; color: #4f46e5; }

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
            <div class="receipt-info">
                <div class="receipt-label">{{ __('receipt.title') }}</div>
                <div class="receipt-meta">
                    {{ __('receipt.receipt_number') }}: <b>{{ $receipt->receipt_number }}</b><br>
                    {{ __('receipt.date') }}: <b>{{ $receipt->tanggal_receipt->format('d M Y') }}</b><br>
                    {{ __('receipt.expiry_date') }}: <b>{{ $receipt->expiry_date->format('d M Y') }}</b>
                </div>
            </div>
        </div>

        <!-- Address Area -->
        <div class="address-box clearfix">
            <div class="bill-to">
                <div class="section-title">{{ __('receipt.client') }}</div>
                <div class="client-name">{{ $receipt->client->nama_client }}</div>
                <div class="client-details">
                    <b>{{ $receipt->client->nama_perusahaan }}</b><br>
                    {{ $receipt->client->alamat }}<br>
                    {{ $receipt->client->kota }}, {{ $receipt->client->provinsi }}
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">{{ __('receipt.description') }}</th>
                    <th width="10%" class="text-center">{{ __('receipt.quantity') }}</th>
                    <th width="20%" class="text-right">{{ __('receipt.price') }}</th>
                    <th width="20%" class="text-right">{{ __('receipt.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt->items as $item)
                <tr>
                    <td>
                        <span class="item-name">{{ $item->deskripsi }}</span>
                        <span class="item-desc">Service provision</span>
                    </td>
                    <td class="text-center">{{ number_format($item->qty, 0) }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-container clearfix">
            <div class="summary-box">
                <div class="summary-row clearfix">
                    <span class="summary-label">{{ __('receipt.subtotal') }}</span>
                    <span class="summary-value">Rp {{ number_format($receipt->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($receipt->tax_percent > 0)
                <div class="summary-row clearfix">
                    <span class="summary-label">{{ __('receipt.tax') }} ({{ $receipt->tax_percent }}%)</span>
                    <span class="summary-value">+ Rp {{ number_format($receipt->subtotal * ($receipt->tax_percent/100), 0, ',', '.') }}</span>
                </div>
                @endif
                @if($receipt->discount_percent > 0)
                <div class="summary-row clearfix">
                    <span class="summary-label">{{ __('receipt.discount') }} ({{ $receipt->discount_percent }}%)</span>
                    <span class="summary-value">- Rp {{ number_format($receipt->subtotal * ($receipt->discount_percent/100), 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="summary-row grand-total clearfix">
                    <span class="summary-label total-label">{{ __('receipt.grand_total') }}</span>
                    <span class="summary-value total-value">Rp {{ number_format($receipt->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Rooterin Enterprise Billing Solution &bull; technical.ops@rooterin.com &bull; www.rooterin.com<br>
            Thank you for your trust. This is an official payment receipt.
        </div>
    </div>
</body>
</html>
