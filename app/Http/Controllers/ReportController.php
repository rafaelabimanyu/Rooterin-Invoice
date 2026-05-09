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
        $year = $request->get('year', date('Y'));
        
        // Monthly Income (Payments)
        $monthlyIncome = Payment::select(
            DB::raw('sum(amount) as total'),
            DB::raw("strftime('%m', payment_date) as month")
        )
        ->whereYear('payment_date', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Top Clients
        $topClients = Client::withCount('invoices')
            ->withSum('invoices', 'total')
            ->orderBy('invoices_sum_total', 'desc')
            ->take(5)
            ->get();

        // Invoice Performance
        $invoiceStats = [
            'total' => Invoice::count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'pending' => Invoice::whereIn('status', ['sent', 'dp', 'pending'])->count(),
            'overdue' => Invoice::where('status', 'overdue')->count(),
        ];

        return view('reports.index', compact('monthlyIncome', 'topClients', 'invoiceStats', 'year'));
    }
}
