<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt | {{ $transaction->transaction_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 13px;
            line-height: 1.4;
        }
        .header {
            margin-bottom: 25px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-table td {
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        .items-table td {
            padding: 8px;
            border: 1px solid #e5e7eb;
        }
        .totals-table {
            width: 300px;
            margin-left: auto;
            margin-bottom: 20px;
        }
        .totals-table td {
            padding: 6px;
        }
        .total-row {
            font-weight: bold;
            color: #10b981;
            font-size: 15px;
        }
        .status-badge {
            background-color: #d1fae5;
            color: #065f46;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width:100%">
            <tr>
                <td>
                    <div class="title">OFFICIAL RECEIPT (KWITANSI LUNAS)</div>
                    <div>Rooterin Eco-Plumbing Services</div>
                </td>
                <td style="text-align:right">
                    <strong>No: {{ $transaction->transaction_number }}</strong><br>
                    Receipt Date: {{ $transaction->payment_date ? $transaction->payment_date->format('d M Y') : $transaction->created_at->format('d M Y') }}<br>
                    Payment Method: {{ $transaction->payment_method ?: 'Transfer' }}
                </td>
            </tr>
        </table>
    </div>

    <hr style="border:0; border-top: 1px solid #e5e7eb; margin-bottom:20px;">

    <table class="meta-table">
        <tr>
            <td style="width:50%">
                <strong>RECEIVED FROM:</strong><br>
                {{ $transaction->client->nama_client }}<br>
                {{ $transaction->client->nama_perusahaan ?: '-' }}<br>
                {{ $transaction->client->alamat ?: '-' }}
            </td>
            <td style="width:50%; text-align:right">
                <strong>BUSINESS UNIT:</strong><br>
                {{ $transaction->businessUnit->name }}<br><br>
                <div class="status-badge">LUNAS / PAID</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="width:15%; text-align:right">Qty</th>
                <th style="width:25%; text-align:right">Unit Price</th>
                <th style="width:25%; text-align:right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td style="text-align:right">{{ number_format($item->qty, 2) }}</td>
                    <td style="text-align:right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align:right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td style="text-align:right">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($transaction->discount > 0)
            <tr>
                <td>Discount:</td>
                <td style="text-align:right; color:#ef4444;">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if($transaction->projectDetail && $transaction->projectDetail->ppn_amount > 0)
            <tr>
                <td>PPN ({{ $transaction->projectDetail->ppn_percentage }}%):</td>
                <td style="text-align:right">Rp {{ number_format($transaction->projectDetail->ppn_amount, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if($transaction->projectDetail && $transaction->projectDetail->pph_amount > 0)
            <tr>
                <td>PPh Deduction ({{ $transaction->projectDetail->pph_percentage }}%):</td>
                <td style="text-align:right; color:#ef4444;">- Rp {{ number_format($transaction->projectDetail->pph_amount, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td>Amount Received:</td>
            <td style="text-align:right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div style="margin-top: 50px;">
        <table style="width:100%">
            <tr>
                <td style="width:60%">
                    <p style="font-style: italic; color: #666; font-size:11px;">
                        * This receipt is a valid proof of payment generated automatically by the Rooterin Invoice system.
                    </p>
                </td>
                <td style="text-align:center; width:40%;">
                    <p>Finance Department</p>
                    <br><br><br>
                    <p><strong>Rooterin Plumbing</strong></p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
