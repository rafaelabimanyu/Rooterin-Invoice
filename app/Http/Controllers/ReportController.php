<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $clientId = $request->get('client_id');

        // --- Invoice Reports ---
        $invoiceQuery = Invoice::whereBetween('tanggal_invoice', [$startDate, $endDate]);
        if ($clientId) {
            $invoiceQuery->where('client_id', $clientId);
        }

        $invoiceStats = [
            'total_count' => (clone $invoiceQuery)->count(),
            'total_value' => (clone $invoiceQuery)->sum('total'),
            'status_breakdown' => (clone $invoiceQuery)
                ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
                ->groupBy('status')
                ->get(),
        ];

        // --- Receipt (Payment) Reports ---
        $paymentQuery = Payment::whereBetween('payment_date', [$startDate, $endDate]);
        if ($clientId) {
            $paymentQuery->whereHas('invoice', function($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        $paymentStats = [
            'total_collected' => (clone $paymentQuery)->sum('amount'),
            'method_breakdown' => (clone $paymentQuery)
                ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
                ->groupBy('payment_method')
                ->get(),
            'recent_payments' => (clone $paymentQuery)->with(['invoice.client'])->latest('payment_date')->take(10)->get(),
        ];

        // --- Outstanding Balance ---
        $outstandingQuery = Invoice::whereIn('status', ['sent', 'dp', 'pending', 'overdue']);
        if ($clientId) {
            $outstandingQuery->where('client_id', $clientId);
        }
        $totalOutstanding = $outstandingQuery->sum(DB::raw('total - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.invoice_id = invoices.id), 0)'));

        $clients = Client::orderBy('nama_client')->get();

        return view('reports.index', compact(
            'startDate', 'endDate', 'clientId', 'clients',
            'invoiceStats', 'paymentStats', 'totalOutstanding'
        ));
    }
}
