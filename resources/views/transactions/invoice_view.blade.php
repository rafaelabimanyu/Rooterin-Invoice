<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $transaction->transaction_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f8fafc; padding: 2rem; }
        .card { max-width: 800px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); padding: 2rem; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .title { font-size: 1.5rem; font-weight: 700; color: #2563eb; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        th { text-align: left; background: #f8fafc; padding: 0.75rem; border-bottom: 2px solid #e2e8f0; }
        td { padding: 0.75rem; border-bottom: 1px solid #e2e8f0; }
        .totals { float: right; width: 300px; margin-top: 1.5rem; }
        .totals div { display: flex; justify-content: space-between; padding: 0.5rem 0; }
        .grand-total { font-weight: 700; font-size: 1.2rem; color: #2563eb; border-top: 2px solid #e2e8f0; padding-top: 0.5rem; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <div>
            <div class="title">INVOICE</div>
            <div>Transaction Number: {{ $transaction->transaction_number }}</div>
            <div>Status: <span style="font-weight: 700; color: {{ $transaction->status === 'paid' ? '#10b981' : '#f59e0b' }}">{{ strtoupper($transaction->status) }}</span></div>
        </div>
        <div style="text-align: right">
            <a href="{{ route('transactions.pdf', $transaction->id) }}" class="btn">⬇ Download PDF</a>
        </div>
    </div>
    
    <div>
        <strong>Client:</strong> {{ $transaction->client->nama_client }}<br>
        <strong>Business Unit:</strong> {{ $transaction->businessUnit->name }}<br>
        <strong>Due Date:</strong> {{ $transaction->due_date ? $transaction->due_date->format('d M Y') : '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ number_format($item->qty, 2) }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div><span>Subtotal:</span> <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span></div>
        @if($transaction->discount > 0)
            <div><span>Discount:</span> <span style="color:#ef4444;">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span></div>
        @endif
        @if($transaction->projectDetail)
            <div><span>PPN ({{ $transaction->projectDetail->ppn_percentage }}%):</span> <span>Rp {{ number_format($transaction->projectDetail->ppn_amount, 0, ',', '.') }}</span></div>
            <div><span>PPh Deduction ({{ $transaction->projectDetail->pph_percentage }}%):</span> <span style="color:#ef4444;">- Rp {{ number_format($transaction->projectDetail->pph_amount, 0, ',', '.') }}</span></div>
        @endif
        <div class="grand-total"><span>Total:</span> <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span></div>
    </div>

    <div style="clear: both; margin-top: 2rem; background: #f8fafc; padding: 1rem; border-radius: 8px;">
        <h3>Technical Details</h3>
        @if($transaction->projectDetail)
            <strong>Technicians:</strong> {{ $transaction->projectDetail->technician_names }}<br>
            <strong>Warranty:</strong> {{ $transaction->projectDetail->warranty_info }}<br>
            <strong>Problem Cause:</strong> {{ $transaction->projectDetail->cause_of_clog }}<br>
        @else
            No technical details recorded.
        @endif
    </div>
</div>
</body>
</html>
