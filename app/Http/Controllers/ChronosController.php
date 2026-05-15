<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChronosController extends Controller
{
    public function index()
    {
        return view('chronos.index');
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $query = Invoice::with(['client', 'creator'])
            ->whereBetween('due_date', [$start, $end]);

        // RBAC: Staff only see assigned invoices
        if (auth()->user()->hasRole('staff')) {
            $query->where('created_by', auth()->id());
        }

        $invoices = $query->get();

        $events = $invoices->map(function ($invoice) {
            $color = '#94a3b8'; // Default Slate
            if ($invoice->status === 'paid') $color = '#10b981'; // Emerald
            elseif ($invoice->status === 'overdue') $color = '#f43f5e'; // Rose
            elseif ($invoice->status === 'draft') $color = '#f59e0b'; // Amber

            return [
                'id' => $invoice->id,
                'title' => $invoice->invoice_number . ' - ' . $invoice->client->nama_client,
                'start' => $invoice->due_date->toIso8601String(),
                'color' => $color,
                'extendedProps' => [
                    'status' => $invoice->status,
                    'total' => 'Rp ' . number_format($invoice->total, 0, ',', '.'),
                    'client' => $invoice->client->nama_client,
                ],
            ];
        });

        return response()->json($events);
    }

    public function updateDate(Request $request, Invoice $invoice)
    {
        // RBAC: Only Admin/Owner can update due_date via drag-and-drop
        if (!auth()->user()->hasAnyRole(['owner', 'admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'due_date' => 'required|date',
        ]);

        $invoice->update([
            'due_date' => $request->due_date,
        ]);

        return response()->json(['success' => true]);
    }
}
