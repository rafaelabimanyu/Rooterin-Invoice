<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
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

        $invoices = $query->latest()->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $invoice_number = Invoice::generateNumber();
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        return view('invoices.create', compact('invoice_number', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'client_id' => 'required|exists:clients,id',
            'tanggal_invoice' => 'required|date',
            'due_date' => 'required|date|after_or_equal:tanggal_invoice',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['qty'] * $item['harga'];
            }

            $tax_amount = $subtotal * ($request->tax_percent / 100);
            $discount_amount = $subtotal * ($request->discount_percent / 100);
            $total = $subtotal + $tax_amount - $discount_amount;

            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'client_id' => $request->client_id,
                'tanggal_invoice' => $request->tanggal_invoice,
                'due_date' => $request->due_date,
                'warranty' => $request->warranty,
                'status' => 'sent',
                'subtotal' => $subtotal,
                'tax_percent' => $request->tax_percent ?? 0,
                'discount_percent' => $request->discount_percent ?? 0,
                'total' => $total,
                'notes_internal' => $request->notes_internal,
                'terms_condition' => $request->terms_condition,
                'bank_account_info' => $request->bank_account_info,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            // Handle Job Documentation
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('documentation', 'public');
                    $invoice->attachments()->create([
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();
            
            \App\Models\ActivityLog::log('created_invoice', "Issued new invoice #{$invoice->invoice_number} with documentation", $invoice);
            
            return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'items', 'creator', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        return view('invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'tanggal_invoice' => 'required|date',
            'due_date' => 'required|date|after_or_equal:tanggal_invoice',
            'status' => 'required|in:draft,sent,pending,dp,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['qty'] * $item['harga'];
            }

            $tax_amount = $subtotal * ($request->tax_percent / 100);
            $discount_amount = $subtotal * ($request->discount_percent / 100);
            $total = $subtotal + $tax_amount - $discount_amount;

            $invoice->update([
                'client_id' => $request->client_id,
                'tanggal_invoice' => $request->tanggal_invoice,
                'due_date' => $request->due_date,
                'warranty' => $request->warranty,
                'status' => $request->status,
                'subtotal' => $subtotal,
                'tax_percent' => $request->tax_percent ?? 0,
                'discount_percent' => $request->discount_percent ?? 0,
                'total' => $total,
                'notes_internal' => $request->notes_internal,
                'terms_condition' => $request->terms_condition,
                'bank_account_info' => $request->bank_account_info,
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

            // Handle New Attachments with Captions
            if ($request->hasFile('attachments')) {
                $captions = $request->input('captions', []);
                foreach ($request->file('attachments') as $index => $file) {
                    $path = $file->store('invoice_attachments', 'public');
                    $invoice->attachments()->create([
                        'file_path' => $path,
                        'caption' => $captions[$index] ?? null,
                    ]);
                }
            }

            DB::commit();
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

        $invoice->load(['client', 'items', 'payments', 'attachments']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
    }

    public function destroy(Invoice $invoice)
    {
        $num = $invoice->invoice_number;
        $invoice->delete();
        
        \App\Models\ActivityLog::log('deleted_invoice', "Deleted invoice #{$num}");
        
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
