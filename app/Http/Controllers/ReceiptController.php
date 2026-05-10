<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $query = Receipt::with('client');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('receipt_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nama_client', 'like', "%{$search}%")
                        ->orWhere('nama_perusahaan', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $receipts = $query->latest()->paginate(10);

        return view('receipts.index', compact('receipts'));
    }

    public function create()
    {
        $receipt_number = Receipt::generateNumber();
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        return view('receipts.create', compact('receipt_number', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receipt_number' => 'required|unique:receipts,receipt_number',
            'client_id' => 'required|exists:clients,id',
            'tanggal_receipt' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:tanggal_receipt',
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

            $receipt = Receipt::create([
                'receipt_number' => $request->receipt_number,
                'client_id' => $request->client_id,
                'tanggal_receipt' => $request->tanggal_receipt,
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
                $receipt->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            DB::commit();
            return redirect()->route('receipts.index')->with('success', 'Receipt created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(Receipt $receipt)
    {
        $receipt->load(['client', 'items', 'creator']);
        return view('receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt)
    {
        $receipt->load('items');
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        return view('receipts.edit', compact('receipt', 'clients'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'tanggal_receipt' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:tanggal_receipt',
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

            $receipt->update([
                'client_id' => $request->client_id,
                'tanggal_receipt' => $request->tanggal_receipt,
                'expiry_date' => $request->expiry_date,
                'status' => $request->status,
                'subtotal' => $subtotal,
                'tax_percent' => $request->tax_percent ?? 0,
                'discount_percent' => $request->discount_percent ?? 0,
                'total' => $total,
                'notes_internal' => $request->notes_internal,
                'terms_condition' => $request->terms_condition,
            ]);

            $receipt->items()->delete();
            foreach ($request->items as $item) {
                $receipt->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            DB::commit();
            return redirect()->route('receipts.index')->with('success', 'Receipt updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function convertToInvoice(Receipt $receipt)
    {
        try {
            DB::beginTransaction();

            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateNumber(),
                'client_id' => $receipt->client_id,
                'tanggal_invoice' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'draft',
                'subtotal' => $receipt->subtotal,
                'tax_percent' => $receipt->tax_percent,
                'discount_percent' => $receipt->discount_percent,
                'total' => $receipt->total,
                'notes_internal' => "Generated from Receipt #" . $receipt->receipt_number . ". " . $receipt->notes_internal,
                'terms_condition' => $receipt->terms_condition,
                'created_by' => auth()->id(),
            ]);

            foreach ($receipt->items as $item) {
                $invoice->items()->create([
                    'deskripsi' => $item->deskripsi,
                    'qty' => $item->qty,
                    'harga' => $item->harga,
                    'total' => $item->total,
                ]);
            }

            $receipt->update(['status' => 'invoiced']);

            DB::commit();
            return redirect()->route('invoices.show', $invoice)->with('success', 'Receipt converted to Invoice successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('receipts.index')->with('success', 'Receipt deleted successfully.');
    }
}
