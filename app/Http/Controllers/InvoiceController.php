<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Traits\CalculatesTotals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    use CalculatesTotals;

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
            $query->where('created_by', auth()->id())
                  ->where('created_at', '>=', now()->subHours(24));
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
            'warranty_value' => 'nullable|integer|min:1',
            'warranty_unit' => 'nullable|string|in:Hari,Bulan,Tahun,Days,Months,Years',
        ]);

        try {
            DB::beginTransaction();

            $warranty = null;
            if ($request->filled('warranty_value')) {
                $warranty = $request->warranty_value . ' ' . ($request->warranty_unit ?? 'Bulan');
            }

            $financials = $this->calculateFinancials(
                $request->items,
                $request->tax_percent,
                $request->discount_percent
            );

            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'client_id' => $request->client_id,
                'tanggal_invoice' => $request->tanggal_invoice,
                'due_date' => $request->due_date,
                'warranty' => $warranty,
                'status' => 'sent',
                'subtotal' => $financials['subtotal'],
                'tax_percent' => $financials['tax_percent'],
                'discount_percent' => $financials['discount_percent'],
                'total' => $financials['total'],
                'notes_internal' => null,
                'terms_condition' => $request->terms_condition,
                'bank_account_info' => "Bank: Bank Central Asia (BCA)\nAcc No: 6281873404\nName: Wibowo Pratikno",
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
        \Illuminate\Support\Facades\Gate::authorize('view', $invoice);

        $invoice->load(['client', 'items', 'creator', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $invoice);

        $invoice->load('items');
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        return view('invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $invoice);

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'tanggal_invoice' => 'required|date',
            'due_date' => 'required|date|after_or_equal:tanggal_invoice',
            'status' => 'required|in:draft,sent,pending,dp,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'warranty_value' => 'nullable|integer|min:1',
            'warranty_unit' => 'nullable|string|in:Hari,Bulan,Tahun,Days,Months,Years',
        ]);

        try {
            DB::beginTransaction();

            $warranty = null;
            if ($request->filled('warranty_value')) {
                $warranty = $request->warranty_value . ' ' . ($request->warranty_unit ?? 'Bulan');
            }

            $financials = $this->calculateFinancials(
                $request->items,
                $request->tax_percent,
                $request->discount_percent
            );

            $invoice->update([
                'client_id' => $request->client_id,
                'tanggal_invoice' => $request->tanggal_invoice,
                'due_date' => $request->due_date,
                'warranty' => $warranty,
                'status' => $request->status,
                'subtotal' => $financials['subtotal'],
                'tax_percent' => $financials['tax_percent'],
                'discount_percent' => $financials['discount_percent'],
                'total' => $financials['total'],
                'notes_internal' => null,
                'terms_condition' => $request->terms_condition,
                'bank_account_info' => "Bank: Bank Central Asia (BCA)\nAcc No: 6281873404\nName: Wibowo Pratikno",
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
