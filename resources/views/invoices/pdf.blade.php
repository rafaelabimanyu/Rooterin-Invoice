<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; margin: 0; padding: 40px; font-size: 11px; line-height: 1.5; }
        .header { margin-bottom: 40px; }
        .logo { font-size: 24px; font-weight: 900; color: #4f46e5; margin-bottom: 5px; }
        .company-info { color: #64748b; font-size: 9px; }
        .invoice-title { font-size: 32px; font-weight: 900; text-align: right; margin-bottom: 10px; color: #0f172a; text-transform: uppercase; letter-spacing: -1px; }
        
        .meta-container { width: 100%; border-bottom: 1px solid #f1f5f9; padding-bottom: 40px; margin-bottom: 40px; }
        .bill-to { width: 50%; float: left; }
        .bill-from { width: 45%; float: right; text-align: right; }
        .label { font-size: 8px; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .value { font-size: 11px; font-weight: bold; color: #0f172a; }
        .sub-value { font-size: 9px; color: #64748b; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .items-table th { background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 12px 10px; text-align: left; font-size: 8px; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
        .items-table td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .items-table .text-right { text-align: right; }
        .item-desc { font-weight: bold; font-size: 11px; color: #0f172a; display: block; margin-bottom: 3px; }
        .item-sub { font-size: 9px; color: #94a3b8; }

        .summary-container { width: 100%; }
        .summary-left { width: 55%; float: left; }
        .summary-right { width: 35%; float: right; }
        .summary-row { padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        .summary-row.total { border-bottom: none; padding-top: 15px; }
        .summary-label { float: left; color: #64748b; font-weight: bold; }
        .summary-value { float: right; font-weight: bold; color: #0f172a; }
        .summary-total-label { font-size: 14px; color: #0f172a; font-weight: 900; }
        .summary-total-value { font-size: 18px; color: #4f46e5; font-weight: 900; }

        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .status-paid { background: #ecfdf5; color: #059669; }
        .status-unpaid { background: #fffbeb; color: #d97706; }

        .footer { position: fixed; bottom: 40px; left: 40px; right: 40px; border-top: 1px solid #f1f5f9; padding-top: 20px; font-size: 8px; color: #94a3b8; text-align: center; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="header clearfix">
        <div style="float: left;">
            <div class="logo">Rooterin.</div>
            <div class="company-info">
                Enterprise Business System<br>
                Technical Services & Maintenance<br>
                contact@rooterin.com
            </div>
        </div>
        <div style="float: right;">
            <div class="invoice-title">Invoice</div>
            <div style="text-align: right;">
                <span class="label">Invoice No:</span> <span class="value">{{ $invoice->invoice_number }}</span><br>
                <span class="label">Issued Date:</span> <span class="value">{{ $invoice->tanggal_invoice->format('M d, Y') }}</span><br>
                <span class="label">Due Date:</span> <span class="value">{{ $invoice->due_date->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    <div class="meta-container clearfix">
        <div class="bill-to">
            <div class="label">Billed To</div>
            <div class="value">{{ $invoice->client->nama_client }}</div>
            <div class="sub-value">
                {{ $invoice->client->nama_perusahaan }}<br>
                {{ $invoice->client->alamat }}<br>
                {{ $invoice->client->no_hp }}
            </div>
        </div>
        <div class="bill-from">
            <div class="label">Payment Status</div>
            <div class="status-badge {{ $invoice->status === 'paid' ? 'status-paid' : 'status-unpaid' }}">
                {{ strtoupper($invoice->status) }}
            </div>
            <div style="margin-top: 10px;">
                <span class="label">Amount Paid:</span><br>
                <span class="value">Rp {{ number_format($invoice->amountPaid, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Description</th>
                <th width="10%" class="text-right">Qty</th>
                <th width="20%" class="text-right">Rate</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>
                    <span class="item-desc">{{ $item->deskripsi }}</span>
                    <span class="item-sub">Job fulfillment for technical services</span>
                </td>
                <td class="text-right">{{ $item->qty }}</td>
                <td class="text-right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-container clearfix">
        <div class="summary-left">
            <div class="label">Notes & Conditions</div>
            <p style="font-size: 9px; color: #64748b; padding-right: 40px;">
                {{ $invoice->terms_condition ?: 'Payment is due within the specified period. Please remit payment via bank transfer.' }}
            </p>
        </div>
        <div class="summary-right">
            <div class="summary-row clearfix">
                <span class="summary-label">Subtotal</span>
                <span class="summary-value">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($invoice->tax_percent > 0)
            <div class="summary-row clearfix">
                <span class="summary-label">Tax ({{ $invoice->tax_percent }}%)</span>
                <span class="summary-value">Rp {{ number_format($invoice->subtotal * ($invoice->tax_percent / 100), 0, ',', '.') }}</span>
            </div>
            @endif
            @if($invoice->discount_percent > 0)
            <div class="summary-row clearfix">
                <span class="summary-label">Discount ({{ $invoice->discount_percent }}%)</span>
                <span class="summary-value">- Rp {{ number_format($invoice->subtotal * ($invoice->discount_percent / 100), 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="summary-row total clearfix">
                <span class="summary-label summary-total-label">Total Amount</span>
                <span class="summary-value summary-total-value">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="footer">
        Rooterin Technical Services — SCBD Jakarta, Indonesia — www.rooterin.com<br>
        Thank you for your business. This is a computer generated document.
    </div>
</body>
</html>
