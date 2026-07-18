<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ProjectDetail;
use App\Models\Client;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['client', 'businessUnit', 'projectDetail']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('transaction_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nama_client', 'like', "%{$search}%")
                        ->orWhere('nama_perusahaan', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('mode')) {
            $query->where('mode', $request->mode);
        }

        $transactions = $query->latest()->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        $businessUnits = BusinessUnit::where('is_active', true)->orderBy('name')->get();
        return view('transactions.create', compact('clients', 'businessUnits'));
    }

    public function store(Request $request)
    {
        // 1. Dynamic Mode Validation
        $rules = [
            'mode' => 'required|in:invoice,receipt',
            'business_unit_id' => 'required|exists:business_units,id',
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'ppn_percentage' => 'nullable|numeric|min:0|max:100',
            'pph_percentage' => 'nullable|numeric|min:0|max:100',
            // Technical details fields
            'technician_names' => 'nullable|string',
            'cause_of_clog' => 'nullable|string',
            'warranty_info' => 'nullable|string',
            'documentation_links' => 'nullable|array',
            'documentation_links.*' => 'nullable|string', // asset paths or urls
            'notes' => 'nullable|string',
        ];

        // Conditional requirements depending on mode
        if ($request->input('mode') === 'invoice') {
            $rules['due_date'] = 'required|date|after_or_equal:today';
            $rules['status'] = 'required|in:draft,unpaid,paid';
        } else {
            // Receipt Mode
            $rules['payment_method'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // Fallback default values for technical fields to ensure data accuracy in business analytics
        $technicianNames = $request->input('technician_names') ?: 'Umum';
        $causeOfClog = $request->input('cause_of_clog') ?: '-';
        $warrantyInfo = $request->input('warranty_info') ?: 'Tidak Ada Garansi';

        try {
            // 2. Wrap database mutations in DB transaction for data integrity
            $transaction = DB::transaction(function () use ($request, $technicianNames, $causeOfClog, $warrantyInfo) {
                // Calculate financial totals
                $subtotal = 0;
                foreach ($request->items as $item) {
                    $subtotal += $item['qty'] * $item['price'];
                }

                $discount = (float)$request->input('discount', 0);
                $dpp = $subtotal - $discount;

                $ppnPercent = (float)$request->input('ppn_percentage', 0);
                $pphPercent = (float)$request->input('pph_percentage', 0);

                $ppnAmount = round($dpp * ($ppnPercent / 100), 2);
                $pphAmount = round($dpp * ($pphPercent / 100), 2);

                $total = round($dpp + $ppnAmount - $pphAmount, 2);

                $isReceipt = $request->input('mode') === 'receipt';
                $status = $isReceipt ? 'paid' : $request->input('status', 'unpaid');
                $dueDate = $isReceipt ? null : $request->input('due_date');
                $paymentDate = $isReceipt ? now()->toDateString() : ($status === 'paid' ? now()->toDateString() : null);
                $paymentMethod = $isReceipt ? $request->input('payment_method') : ($status === 'paid' ? ($request->input('payment_method') ?: 'Transfer') : null);

                // Auto-generate transaction number
                $prefix = $isReceipt ? 'REC' : 'INV';
                $dateSegment = date('Ymd');
                $randomSegment = strtoupper(bin2hex(random_bytes(3)));
                $transactionNumber = "{$prefix}-{$dateSegment}-{$randomSegment}";

                // Create the core transaction record
                $txn = Transaction::create([
                    'transaction_number' => $transactionNumber,
                    'mode' => $request->input('mode'),
                    'status' => $status,
                    'business_unit_id' => $request->business_unit_id,
                    'client_id' => $request->client_id,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total,
                    'due_date' => $dueDate,
                    'payment_date' => $paymentDate,
                    'payment_method' => $paymentMethod,
                    'notes' => $request->notes,
                    'created_by' => auth()->id(),
                ]);

                // Create associated project details containing technical values
                $txn->projectDetail()->create([
                    'technician_names' => $technicianNames,
                    'cause_of_clog' => $causeOfClog,
                    'warranty_info' => $warrantyInfo,
                    'documentation_links' => $request->input('documentation_links') ?: [],
                    'ppn_percentage' => $ppnPercent,
                    'pph_percentage' => $pphPercent,
                    'ppn_amount' => $ppnAmount,
                    'pph_amount' => $pphAmount,
                ]);

                // Create individual items
                foreach ($request->items as $item) {
                    $txn->items()->create([
                        'description' => $item['description'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'total' => $item['qty'] * $item['price'],
                    ]);
                }

                return $txn;
            });

            // 3. Conditional redirection or auto-download of PDF receipt
            if ($transaction->mode === 'receipt') {
                return $this->downloadReceiptPdf($transaction);
            }

            return redirect()
                ->route('transactions.show', $transaction->id)
                ->with('success', 'Invoice generated successfully.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to save transaction: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['projectDetail', 'items', 'client', 'businessUnit']);

        // Dynamically switch representation logic
        if ($transaction->mode === 'receipt') {
            return view('transactions.receipt_view', compact('transaction'));
        }

        return view('transactions.invoice_view', compact('transaction'));
    }

    public function downloadPdf(Transaction $transaction)
    {
        if ($transaction->mode === 'receipt') {
            return $this->downloadReceiptPdf($transaction);
        }

        return $this->downloadInvoicePdf($transaction);
    }

    private function downloadReceiptPdf(Transaction $transaction)
    {
        $transaction->load(['projectDetail', 'items', 'client', 'businessUnit']);

        // Technical details (technician, clog, warranty) are fully saved in DB, but omitted on receipt PDF as requested
        $pdf = Pdf::loadView('transactions.pdf_receipt', compact('transaction'))
            ->setPaper('a4')
            ->setOption([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download("Receipt-{$transaction->transaction_number}.pdf");
    }

    private function downloadInvoicePdf(Transaction $transaction)
    {
        $transaction->load(['projectDetail', 'items', 'client', 'businessUnit']);

        // Invoices include the technical details
        $pdf = Pdf::loadView('transactions.pdf_invoice', compact('transaction'))
            ->setPaper('a4')
            ->setOption([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download("Invoice-{$transaction->transaction_number}.pdf");
    }
}
