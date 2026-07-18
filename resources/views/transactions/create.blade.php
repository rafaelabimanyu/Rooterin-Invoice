<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Transaction | Rooterin</title>
    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --success: #10b981;
            --danger: #ef4444;
            --bg-gray: #f8fafc;
            --border-color: #cbd5e1;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-gray);
            color: var(--text-dark);
            padding: 2rem 1rem;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            border: 1px solid #e2e8f0;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2rem;
            border-left: 5px solid var(--primary);
            padding-left: 0.75rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .input-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            color: var(--text-dark);
            transition: all 0.2s ease;
            background: #ffffff;
        }

        .input-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .mode-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .mode-card {
            flex: 1;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            cursor: pointer;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }

        .mode-card:hover {
            border-color: var(--primary);
            background-color: rgba(37, 99, 235, 0.02);
        }

        .mode-card.active {
            border-color: var(--primary);
            background-color: rgba(37, 99, 235, 0.05);
        }

        .mode-card input[type="radio"] {
            margin-top: 0.25rem;
            accent-color: var(--primary);
            width: 1.2rem;
            height: 1.2rem;
        }

        .mode-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .mode-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.3;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        @media (min-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr 1fr;
            }
        }

        .section-header {
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin: 2rem 0 1rem 0;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 0.5rem;
        }

        .hidden {
            display: none !important;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .items-table th {
            text-align: left;
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            border-bottom: 2px solid #e2e8f0;
        }

        .items-table td {
            padding: 0.75rem 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .btn-icon {
            background: none;
            border: none;
            color: var(--danger);
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0.25rem;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .btn-icon:hover {
            background: rgba(239, 68, 68, 0.08);
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: var(--text-dark);
            padding: 0.75rem 1.25rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        /* Calculations Box */
        .calc-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            max-width: 400px;
            margin-left: auto;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .calc-row.total {
            border-top: 2px solid #e2e8f0;
            padding-top: 0.75rem;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary);
        }

        /* Documentation links UI */
        .doc-link-row {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
        }

        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #047857;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Create Adaptive Transaction</h1>

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
        @csrf

        <!-- Document Type Selector -->
        <label>Document Presentation Mode</label>
        <div class="mode-container">
            <div class="mode-card active" onclick="setMode('invoice')" id="modeInvoiceCard">
                <input type="radio" name="mode" value="invoice" id="modeInvoice" checked>
                <div>
                    <div class="mode-title">Invoice (Tagihan)</div>
                    <div class="mode-desc">Requires a future due date. Generates an unpaid statement to send to client.</div>
                </div>
            </div>
            <div class="mode-card" onclick="setMode('receipt')" id="modeReceiptCard">
                <input type="radio" name="mode" value="receipt" id="modeReceipt">
                <div>
                    <div class="mode-title">Instant Receipt (Kwitansi Lunas)</div>
                    <div class="mode-desc">No due date. Sets payment date to today and status to PAID. Auto-downloads receipt PDF immediately.</div>
                </div>
            </div>
        </div>

        <!-- General Info Section -->
        <div class="section-header">Billing & Entity Info</div>
        <div class="grid-2">
            <div class="form-group">
                <label for="business_unit_id">Business Unit</label>
                <select name="business_unit_id" id="business_unit_id" required class="input-control">
                    <option value="">Select Business Unit</option>
                    @foreach($businessUnits as $bu)
                        <option value="{{ $bu->id }}" {{ old('business_unit_id') == $bu->id ? 'selected' : '' }}>{{ $bu->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="client_id">Client</label>
                <select name="client_id" id="client_id" required class="input-control">
                    <option value="">Select Client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->nama_client }} @if($client->nama_perusahaan)- {{ $client->nama_perusahaan }}@endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Dynamic Fields Container -->
        <div class="grid-2">
            <!-- Invoice Mode Elements -->
            <div class="form-group" id="invoiceDueDateGroup">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date" required min="{{ date('Y-m-d') }}" class="input-control" value="{{ old('due_date') }}">
            </div>
            <div class="form-group" id="invoiceStatusGroup">
                <label for="status">Invoice Status</label>
                <select name="status" id="status" class="input-control">
                    <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>

            <!-- Receipt Mode Elements -->
            <div class="form-group hidden" id="receiptPaymentMethodGroup">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" class="input-control">
                    <option value="Transfer" {{ old('payment_method') == 'Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            <div class="form-group hidden" id="receiptDateGroup">
                <label>Payment Date</label>
                <input type="text" readonly value="{{ date('d F Y') }} (Today)" class="input-control" style="background: #f1f5f9; cursor: not-allowed; color: var(--text-muted);">
            </div>
        </div>

        <!-- Technical Details Section -->
        <div class="section-header">Technical Project Details (Analytics & Archiving)</div>
        <div class="alert alert-success" style="padding: 0.75rem 1rem; border-color: #d1fae5; margin-bottom: 1rem; font-size: 0.85rem;">
            💡 <strong>Note:</strong> All technical fields will be saved securely for backend reports regardless of presentation mode. Default fallbacks are applied if omitted.
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label for="technician_names">Technician Name(s)</label>
                <input type="text" name="technician_names" id="technician_names" placeholder="e.g. John Doe, Alex (Default: Umum)" class="input-control" value="{{ old('technician_names') }}">
            </div>
            <div class="form-group">
                <label for="warranty_info">Warranty Value</label>
                <input type="text" name="warranty_info" id="warranty_info" placeholder="e.g. 30 Hari, 3 Bulan (Default: Tidak Ada Garansi)" class="input-control" value="{{ old('warranty_info') }}">
            </div>
        </div>
        <div class="form-group">
            <label for="cause_of_clog">Cause of Clog / Technical Problem Description</label>
            <textarea name="cause_of_clog" id="cause_of_clog" rows="3" placeholder="Describe the technical issue solved (e.g. grease buildup, root penetration. Default: -)" class="input-control">{{ old('cause_of_clog') }}</textarea>
        </div>

        <!-- Documentation Links Section -->
        <div class="form-group">
            <label>Documentation Asset Links (Existing Asset Paths / URLs)</label>
            <div id="documentationLinksContainer">
                <div class="doc-link-row">
                    <input type="text" name="documentation_links[]" placeholder="e.g. docs/img-01.jpg or asset link" class="input-control">
                    <button type="button" class="btn-icon" onclick="removeDocLink(this)">✖</button>
                </div>
            </div>
            <button type="button" class="btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; margin-top: 0.5rem;" onclick="addDocLink()">
                ➕ Add Asset Link
            </button>
        </div>

        <!-- Line Items Section -->
        <div class="section-header">Line Items</div>
        <table class="items-table" id="itemsTable">
            <thead>
                <tr>
                    <th style="width: 50%;">Description</th>
                    <th style="width: 15%;">Qty</th>
                    <th style="width: 25%;">Unit Price (Rp)</th>
                    <th style="width: 10%; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" name="items[0][description]" required placeholder="Service/item description" class="input-control">
                    </td>
                    <td>
                        <input type="number" name="items[0][qty]" required step="0.01" min="0.01" class="input-control qty-input" value="1" oninput="calculateTotals()">
                    </td>
                    <td>
                        <input type="number" name="items[0][price]" required step="1" min="0" class="input-control price-input" value="0" oninput="calculateTotals()">
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn-icon" onclick="removeItemRow(this)">✖</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn-secondary" onclick="addItemRow()">
            ➕ Add Line Item
        </button>

        <!-- Taxes and Discounts Section -->
        <div class="section-header">Discounts & Taxes</div>
        <div class="grid-2">
            <div class="form-group">
                <label for="discount">Discount Amount (Nominal Rp)</label>
                <input type="number" name="discount" id="discount" step="1" min="0" value="0" class="input-control" oninput="calculateTotals()">
            </div>
            <div class="form-group" style="display: flex; gap: 1rem;">
                <div style="flex: 1;">
                    <label for="ppn_percentage">PPN (%)</label>
                    <input type="number" name="ppn_percentage" id="ppn_percentage" step="0.01" min="0" max="100" value="0" class="input-control" oninput="calculateTotals()">
                </div>
                <div style="flex: 1;">
                    <label for="pph_percentage">PPh (%)</label>
                    <input type="number" name="pph_percentage" id="pph_percentage" step="0.01" min="0" max="100" value="0" class="input-control" oninput="calculateTotals()">
                </div>
            </div>
        </div>

        <!-- Calculations Card -->
        <div class="calc-box">
            <div class="calc-row">
                <span>Subtotal:</span>
                <span id="labelSubtotal">Rp 0</span>
            </div>
            <div class="calc-row text-danger">
                <span>Discount:</span>
                <span id="labelDiscount">- Rp 0</span>
            </div>
            <div class="calc-row">
                <span>PPN:</span>
                <span id="labelPpn">Rp 0</span>
            </div>
            <div class="calc-row" style="color: #b91c1c;">
                <span>PPh Deduction:</span>
                <span id="labelPph">- Rp 0</span>
            </div>
            <div class="calc-row total">
                <span>Total Amount:</span>
                <span id="labelTotal">Rp 0</span>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
            <button type="reset" class="btn-secondary" style="background: #ffffff;">Reset Form</button>
            <button type="submit" class="btn-primary">Generate & Print</button>
        </div>
    </form>
</div>

<script>
    let itemIndex = 1;

    function setMode(mode) {
        const modeInvoice = document.getElementById('modeInvoice');
        const modeReceipt = document.getElementById('modeReceipt');
        const modeInvoiceCard = document.getElementById('modeInvoiceCard');
        const modeReceiptCard = document.getElementById('modeReceiptCard');

        const invoiceDueDateGroup = document.getElementById('invoiceDueDateGroup');
        const invoiceStatusGroup = document.getElementById('invoiceStatusGroup');
        const receiptPaymentMethodGroup = document.getElementById('receiptPaymentMethodGroup');
        const receiptDateGroup = document.getElementById('receiptDateGroup');

        const dueDateInput = document.getElementById('due_date');
        const paymentMethodSelect = document.getElementById('payment_method');

        if (mode === 'invoice') {
            modeInvoice.checked = true;
            modeInvoiceCard.classList.add('active');
            modeReceiptCard.classList.remove('active');

            invoiceDueDateGroup.classList.remove('hidden');
            invoiceStatusGroup.classList.remove('hidden');
            receiptPaymentMethodGroup.classList.add('hidden');
            receiptDateGroup.classList.add('hidden');

            dueDateInput.setAttribute('required', 'required');
            paymentMethodSelect.removeAttribute('required');
        } else {
            modeReceipt.checked = true;
            modeReceiptCard.classList.add('active');
            modeInvoiceCard.classList.remove('active');

            invoiceDueDateGroup.classList.add('hidden');
            invoiceStatusGroup.classList.add('hidden');
            receiptPaymentMethodGroup.classList.remove('hidden');
            receiptDateGroup.classList.remove('hidden');

            dueDateInput.removeAttribute('required');
            paymentMethodSelect.setAttribute('required', 'required');
        }
    }

    function addDocLink() {
        const container = document.getElementById('documentationLinksContainer');
        const div = document.createElement('div');
        div.className = 'doc-link-row';
        div.innerHTML = `
            <input type="text" name="documentation_links[]" placeholder="e.g. docs/img-02.jpg or asset link" class="input-control">
            <button type="button" class="btn-icon" onclick="removeDocLink(this)">✖</button>
        `;
        container.appendChild(div);
    }

    function removeDocLink(btn) {
        const rows = document.querySelectorAll('.doc-link-row');
        if (rows.length > 1) {
            btn.parentElement.remove();
        } else {
            btn.parentElement.querySelector('input').value = '';
        }
    }

    function addItemRow() {
        const tableBody = document.querySelector('#itemsTable tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <input type="text" name="items[${itemIndex}][description]" required placeholder="Service/item description" class="input-control">
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][qty]" required step="0.01" min="0.01" class="input-control qty-input" value="1" oninput="calculateTotals()">
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][price]" required step="1" min="0" class="input-control price-input" value="0" oninput="calculateTotals()">
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn-icon" onclick="removeItemRow(this)">✖</button>
            </td>
        `;
        tableBody.appendChild(tr);
        itemIndex++;
        calculateTotals();
    }

    function removeItemRow(btn) {
        const rows = document.querySelectorAll('#itemsTable tbody tr');
        if (rows.length > 1) {
            btn.closest('tr').remove();
            calculateTotals();
        }
    }

    function calculateTotals() {
        const qtyInputs = document.querySelectorAll('.qty-input');
        const priceInputs = document.querySelectorAll('.price-input');
        let subtotal = 0;

        qtyInputs.forEach((input, index) => {
            const qty = parseFloat(input.value) || 0;
            const price = parseFloat(priceInputs[index].value) || 0;
            subtotal += qty * price;
        });

        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const dpp = Math.max(0, subtotal - discount);

        const ppnPercent = parseFloat(document.getElementById('ppn_percentage').value) || 0;
        const pphPercent = parseFloat(document.getElementById('pph_percentage').value) || 0;

        const ppnAmount = Math.round(dpp * (ppnPercent / 100));
        const pphAmount = Math.round(dpp * (pphPercent / 100));
        const total = Math.max(0, dpp + ppnAmount - pphAmount);

        // Format Currency Helper
        const formatIDR = (num) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
        };

        document.getElementById('labelSubtotal').innerText = formatIDR(subtotal);
        document.getElementById('labelDiscount').innerText = '- ' + formatIDR(discount);
        document.getElementById('labelPpn').innerText = formatIDR(ppnAmount);
        document.getElementById('labelPph').innerText = '- ' + formatIDR(pphAmount);
        document.getElementById('labelTotal').innerText = formatIDR(total);
    }

    // Run calculation once initially
    calculateTotals();
</script>
</body>
</html>
