<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\BusinessUnit;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $query = Invoice::with('client');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nama_client', 'like', "%{$search}%")
                        ->orWhere('nama_perusahaan', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if (auth()->user()->role === 'staff') {
            if (Schema::hasColumn('invoices', 'created_by')) {
                $query->where('created_by', auth()->id())
                      ->where('created_at', '>=', now()->subHours(24));
            }
        }

        $invoices = $query->latest()->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $invoice_number = $this->invoiceService->generateInvoiceNumber();
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        $businessUnits = BusinessUnit::orderBy('name')->get();
        return view('invoices.create', compact('invoice_number', 'clients', 'businessUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_unit_id' => 'required|exists:business_units,id',
            'client_id' => 'required|exists:clients,id',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:draft,sent,pending,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'pph' => 'nullable|numeric|min:0',
            'cause_of_problem' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['qty'] * $item['harga'];
            }

            $discount = (float) $request->input('discount', 0);
            $ppn = (float) $request->input('ppn', 0);
            $pph = (float) $request->input('pph', 0);

            $total = $this->invoiceService->calculateTotal($subtotal, $discount, $ppn, $pph);
            $invoiceNumber = $this->invoiceService->generateInvoiceNumber();

            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'business_unit_id' => $request->business_unit_id,
                'client_id' => $request->client_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'ppn' => $ppn,
                'pph' => $pph,
                'total' => $total,
                'status' => $request->input('status', 'draft'),
                'due_date' => $request->due_date,
                'cause_of_problem' => $request->cause_of_problem,
                'notes' => $request->notes,
            ];

            if (Schema::hasColumn('invoices', 'created_by')) {
                $invoiceData['created_by'] = auth()->id();
            }

            $invoice = Invoice::create($invoiceData);

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            // If status is paid, trigger automatic receipt generation
            if ($invoice->status === 'paid') {
                $this->invoiceService->markAsPaid($invoice);
            }

            DB::commit();

            \App\Models\ActivityLog::log('created_invoice', "Issued new invoice #{$invoice->invoice_number}", $invoice);

            return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        \Illuminate\Support\Facades\Gate::authorize('view', $invoice);

        $invoice->load(['client', 'items', 'receipt']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $invoice);

        $invoice->load('items');
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        $businessUnits = BusinessUnit::orderBy('name')->get();
        return view('invoices.edit', compact('invoice', 'clients', 'businessUnits'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $invoice);

        $request->validate([
            'business_unit_id' => 'required|exists:business_units,id',
            'client_id' => 'required|exists:clients,id',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:draft,sent,pending,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'pph' => 'nullable|numeric|min:0',
            'cause_of_problem' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Security check: status 'paid' requires vital fields to be completed
        if ($request->status === 'paid') {
            $request->validate([
                'due_date' => 'required|date',
                'business_unit_id' => 'required',
                'client_id' => 'required',
            ]);
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['qty'] * $item['harga'];
            }

            $discount = (float) $request->input('discount', 0);
            $ppn = (float) $request->input('ppn', 0);
            $pph = (float) $request->input('pph', 0);

            $total = $this->invoiceService->calculateTotal($subtotal, $discount, $ppn, $pph);

            $invoice->update([
                'business_unit_id' => $request->business_unit_id,
                'client_id' => $request->client_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'ppn' => $ppn,
                'pph' => $pph,
                'total' => $total,
                'due_date' => $request->due_date,
                'cause_of_problem' => $request->cause_of_problem,
                'notes' => $request->notes,
            ]);

            $invoice->items()->delete();
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            // Logika pelunasan otomatis kwitansi
            if ($request->status === 'paid' && $invoice->getOriginal('status') !== 'paid') {
                $this->invoiceService->markAsPaid($invoice);
            } else {
                // Update status normally if it is not a new paid transition
                $invoice->update(['status' => $request->status]);
            }

            DB::commit();

            \App\Models\ActivityLog::log('updated_invoice', "Updated invoice #{$invoice->invoice_number}", $invoice);

            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function downloadPdf(Request $request, Invoice $invoice)
    {
        $locale = $request->get('lang', config('app.locale'));
        if (in_array($locale, ['en', 'id'])) {
            \Illuminate\Support\Facades\App::setLocale($locale);
        }

        $invoice->load(['client', 'items', 'receipt', 'attachments']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4')
            ->setOption([
                'isRemoteEnabled' => true, 
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);
        return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
    }

    public function destroy(Invoice $invoice)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $invoice);

        $num = $invoice->invoice_number;
        $invoice->delete();
        
        \App\Models\ActivityLog::log('deleted_invoice', "Deleted invoice #{$num}");
        
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
