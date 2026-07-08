<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $query = Receipt::with('invoice.client');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('receipt_number', 'like', "%{$search}%")
                  ->orWhereHas('invoice.client', function($q) use ($search) {
                      $q->where('nama_client', 'like', "%{$search}%")
                        ->orWhere('nama_perusahaan', 'like', "%{$search}%");
                  });
        }

        if (auth()->user()->role === 'staff') {
            $query->whereHas('invoice', function($q) {
                if (Schema::hasColumn('invoices', 'created_by')) {
                    $q->where('created_by', auth()->id());
                }
            })->where('created_at', '>=', now()->subHours(24));
        }

        $receipts = $query->latest()->paginate(10);

        return view('receipts.index', compact('receipts'));
    }

    public function create()
    {
        return redirect()->route('invoices.index')->with('info', 'Kuitansi otomatis dibuat saat status invoice diubah menjadi Paid.');
    }

    public function store(Request $request)
    {
        return redirect()->route('receipts.index');
    }

    public function show(Receipt $receipt)
    {
        if (auth()->user()->role === 'staff') {
            $hasCreatedBy = false;
            $invoice = $receipt->invoice;
            if ($invoice && Schema::hasColumn('invoices', 'created_by')) {
                if ($invoice->created_by !== auth()->id() || $receipt->created_at < now()->subHours(24)) {
                    abort(403, 'Access restricted.');
                }
            }
        }
        $receipt->load(['invoice.client', 'invoice.items']);
        return view('receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt)
    {
        return redirect()->route('receipts.index')->with('info', 'Kuitansi otomatis tidak dapat diedit secara manual.');
    }

    public function update(Request $request, Receipt $receipt)
    {
        return redirect()->route('receipts.index');
    }

    public function convertToInvoice(Receipt $receipt)
    {
        return redirect()->route('receipts.index');
    }

    public function downloadPdf(Request $request, Receipt $receipt)
    {
        $locale = $request->get('lang', config('app.locale'));
        if (in_array($locale, ['en', 'id'])) {
            \Illuminate\Support\Facades\App::setLocale($locale);
        }

        $receipt->load(['invoice.client', 'invoice.items']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('receipts.pdf', compact('receipt'));
        return $pdf->download("Receipt-{$receipt->receipt_number}.pdf");
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('receipts.index')->with('success', 'Receipt deleted successfully.');
    }
}
