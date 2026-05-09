<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with('client');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('quotation_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nama_client', 'like', "%{$search}%")
                        ->orWhere('nama_perusahaan', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotations = $query->latest()->paginate(10);

        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $quotation_number = Quotation::generateNumber();
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        return view('quotations.create', compact('quotation_number', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quotation_number' => 'required|unique:quotations,quotation_number',
            'client_id' => 'required|exists:clients,id',
            'tanggal_quotation' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:tanggal_quotation',
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

            $quotation = Quotation::create([
                'quotation_number' => $request->quotation_number,
                'client_id' => $request->client_id,
                'tanggal_quotation' => $request->tanggal_quotation,
                'expiry_date' => $request->expiry_date,
                'status' => 'draft',
                'subtotal' => $subtotal,
                'tax_percent' => $request->tax_percent ?? 0,
                'discount_percent' => $request->discount_percent ?? 0,
                'total' => $total,
                'notes_internal' => $request->notes_internal,
                'terms_condition' => $request->terms_condition,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $quotation->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            DB::commit();
            return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['client', 'items', 'creator']);
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load('items');
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        return view('quotations.edit', compact('quotation', 'clients'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'tanggal_quotation' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:tanggal_quotation',
            'status' => 'required|in:draft,sent,approved,rejected,invoiced',
            'items' => 'required|array|min:1',
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

            $quotation->update([
                'client_id' => $request->client_id,
                'tanggal_quotation' => $request->tanggal_quotation,
                'expiry_date' => $request->expiry_date,
                'status' => $request->status,
                'subtotal' => $subtotal,
                'tax_percent' => $request->tax_percent ?? 0,
                'discount_percent' => $request->discount_percent ?? 0,
                'total' => $total,
                'notes_internal' => $request->notes_internal,
                'terms_condition' => $request->terms_condition,
            ]);

            $quotation->items()->delete();
            foreach ($request->items as $item) {
                $quotation->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            DB::commit();
            return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function convertToInvoice(Quotation $quotation)
    {
        try {
            DB::beginTransaction();

            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateNumber(),
                'client_id' => $quotation->client_id,
                'tanggal_invoice' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'draft',
                'subtotal' => $quotation->subtotal,
                'tax_percent' => $quotation->tax_percent,
                'discount_percent' => $quotation->discount_percent,
                'total' => $quotation->total,
                'notes_internal' => "Generated from Quotation #" . $quotation->quotation_number . ". " . $quotation->notes_internal,
                'terms_condition' => $quotation->terms_condition,
                'created_by' => auth()->id(),
            ]);

            foreach ($quotation->items as $item) {
                $invoice->items()->create([
                    'deskripsi' => $item->deskripsi,
                    'qty' => $item->qty,
                    'harga' => $item->harga,
                    'total' => $item->total,
                ]);
            }

            $quotation->update(['status' => 'invoiced']);

            DB::commit();
            return redirect()->route('invoices.show', $invoice)->with('success', 'Quotation converted to Invoice successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }
}
