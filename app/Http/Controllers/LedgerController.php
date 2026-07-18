<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'businessUnit', 'receipt'])
            ->latest(); // orders by created_at desc


        // Filter: search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', fn($c) => $c
                      ->where('nama_client', 'like', "%{$search}%")
                      ->orWhere('nama_perusahaan', 'like', "%{$search}%"));
            });
        }

        // Filter: status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: business unit
        if ($request->filled('business_unit_id')) {
            $query->where('business_unit_id', $request->business_unit_id);
        }

        // Filter: type (invoice only vs has receipt)
        if ($request->filled('doc_type')) {
            if ($request->doc_type === 'receipt') {
                $query->whereHas('receipt');
            } elseif ($request->doc_type === 'invoice') {
                $query->doesntHave('receipt');
            }
        }

        $invoices = $query->paginate(15)->withQueryString();
        $businessUnits = BusinessUnit::orderBy('name')->get();

        return view('ledger.index', compact('invoices', 'businessUnits'));
    }
}
