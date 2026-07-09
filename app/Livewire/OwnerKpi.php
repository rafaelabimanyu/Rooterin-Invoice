<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\Client;
use App\Services\BusinessUnitReportingService;
use Carbon\Carbon;
class OwnerKpi extends Component
{
    public $minimal = false;
    public $activeCardType = null;
    public $activeId = null;

    public function openModal($cardType, $id = null)
    {
        // Reset component state properties to prevent stale state issues
        $this->reset(['activeCardType', 'activeId']);

        // 1. Dispatch immediate loading state to the slide-over
        $this->dispatch('slide-over-loading-start');

        // 2. Set active state properties
        $this->activeCardType = $cardType;
        $this->activeId = $id;

        // 3. Fetch the corresponding modal's data dynamically
        $data = $this->getModalData($cardType, $id);

        // 4. Compile/render the modal's specific blade view
        $html = view('livewire.owner-kpi-modals.' . $cardType, $data)->render();

        // 5. Dispatch the slide-over opening event with title and compiled HTML
        $title = $this->getModalTitle($cardType, $id, $data);
        $this->dispatch('open-slide-over', title: $title, content: $html);

        // 6. Fire event to re-initialize Lucide icons within the slide-over container
        $this->dispatch('init-lucide-icons');
    }

    private function getModalData($cardType, $id = null)
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();

        switch ($cardType) {
            case 'total-revenue':
                $reportingService = app(BusinessUnitReportingService::class);
                $stats = $reportingService->getSummaryStats();
                $totalRevenue = $stats['total_revenue'];
                $paidInvoices = Invoice::with('client')
                    ->where('status', 'paid')
                    ->orderByDesc('updated_at')
                    ->take(10)
                    ->get();
                return compact('totalRevenue', 'paidInvoices');
 
            case 'collection-rate':
                $reportingService = app(BusinessUnitReportingService::class);
                $stats = $reportingService->getSummaryStats();
                $totalInvoicesCount = $stats['total_invoices_count'];
                $paidInvoicesCount = $stats['paid_invoices_count'];
                $collectionRate = $stats['collection_rate'];
                 
                $recentPaidInvoices = Invoice::with('client')
                     ->where('status', 'paid')
                     ->orderByDesc('updated_at')
                     ->take(5)
                     ->get();
                     
                $recentUnpaidInvoices = Invoice::with('client')
                     ->where('status', '!=', 'paid')
                     ->orderByDesc('due_date')
                     ->take(5)
                     ->get();
                 
                return compact('totalInvoicesCount', 'paidInvoicesCount', 'collectionRate', 'recentPaidInvoices', 'recentUnpaidInvoices');
 
            case 'revenue':
                $reportingService = app(BusinessUnitReportingService::class);
                $currentMonthStats = $reportingService->getSummaryStats([
                    'start_date' => $now->copy()->startOfMonth()->toDateString(),
                    'end_date' => $now->copy()->endOfMonth()->toDateString(),
                ]);
                $currentMonthRevenue = $currentMonthStats['total_revenue'];

                $lastMonthStats = $reportingService->getSummaryStats([
                    'start_date' => $lastMonth->copy()->startOfMonth()->toDateString(),
                    'end_date' => $lastMonth->copy()->endOfMonth()->toDateString(),
                ]);
                $lastMonthRevenue = $lastMonthStats['total_revenue'];

                $revenueChange = 0;
                if ($lastMonthRevenue > 0) {
                    $revenueChange = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
                } elseif ($currentMonthRevenue > 0) {
                    $revenueChange = 100;
                }
                $paidInvoices = Invoice::with('client')
                     ->whereMonth('created_at', $now->month)
                     ->whereYear('created_at', $now->year)
                     ->where('status', 'paid')
                     ->orderByDesc('updated_at')
                     ->take(10)
                     ->get();
 
                 return compact('currentMonthRevenue', 'lastMonthRevenue', 'revenueChange', 'paidInvoices');
 
             case 'risks':
                 $reportingService = app(BusinessUnitReportingService::class);
                 $stats = $reportingService->getSummaryStats();
                 $totalUnpaid = $stats['total_outstanding'];
                 $pendingUnpaid = $stats['pending_outstanding'];
                 $overdueUnpaid = $stats['overdue_outstanding'];
                 
                 $unpaidInvoices = Invoice::with('client')
                     ->where('status', '!=', 'paid')
                     ->orderByDesc('due_date')
                     ->take(10)
                     ->get();
 
                 return compact('totalUnpaid', 'pendingUnpaid', 'overdueUnpaid', 'unpaidInvoices');
 
             case 'loyalty':
                 $reportingService = app(BusinessUnitReportingService::class);
                 $totalClients = Client::count();
                 $repeatClients = Client::has('invoices', '>', 1)->count();
                 $repeatRate = $totalClients > 0 ? ($repeatClients / $totalClients) * 100 : 0;
                 $topClients = $reportingService->getTopClients([], 5);
 
                 return compact('repeatRate', 'totalClients', 'topClients');
 
             case 'prime-asset':
                 $reportingService = app(BusinessUnitReportingService::class);
                 $topClients = $reportingService->getTopClients([], 5)->map(function ($client) {
                     $lastInvoice = $client->invoices()->orderByDesc('created_at')->first();
                     $client->last_transaction = $lastInvoice ? $lastInvoice->created_at : null;
                     return $client;
                 });
 
                 return compact('topClients');
 
             case 'new-issuance':
                 $reportingService = app(BusinessUnitReportingService::class);
                 $currentMonthStats = $reportingService->getSummaryStats([
                     'start_date' => $now->copy()->startOfMonth()->toDateString(),
                     'end_date' => $now->copy()->endOfMonth()->toDateString(),
                 ]);
                 $monthlyPerformance = [
                     'created' => $currentMonthStats['total_invoices_count'],
                 ];
                 $newInvoices = Invoice::with('client')
                     ->whereMonth('created_at', $now->month)
                     ->whereYear('created_at', $now->year)
                     ->orderByDesc('created_at')
                     ->take(10)
                     ->get();
 
                 return compact('monthlyPerformance', 'newInvoices');
 
             case 'settled-assets':
                 $reportingService = app(BusinessUnitReportingService::class);
                 $currentMonthStats = $reportingService->getSummaryStats([
                     'start_date' => $now->copy()->startOfMonth()->toDateString(),
                     'end_date' => $now->copy()->endOfMonth()->toDateString(),
                 ]);
                 $monthlyPerformance = [
                     'paid' => $currentMonthStats['paid_invoices_count'],
                 ];
                 $paidInvoices = Invoice::with('client')
                     ->whereMonth('created_at', $now->month)
                     ->whereYear('created_at', $now->year)
                     ->where('status', 'paid')
                     ->orderByDesc('updated_at')
                     ->take(10)
                     ->get();
 
                 return compact('monthlyPerformance', 'paidInvoices');
 
             case 'stagnant-flow':
                 $monthlyPerformance = [
                     'overdue' => Invoice::where('due_date', '<', $now->toDateString())
                         ->where('status', '!=', 'paid')
                         ->count(),
                 ];
                 $overdueInvoices = Invoice::with('client')
                     ->where('due_date', '<', $now->toDateString())
                     ->where('status', '!=', 'paid')
                     ->orderBy('due_date')
                     ->take(10)
                     ->get();
 
                 return compact('monthlyPerformance', 'overdueInvoices');

            case 'client':
                $client = Client::withCount('invoices')
                    ->withSum('invoices', 'total')
                    ->find($id);

                if ($client) {
                    $lastInvoice = $client->invoices()->orderByDesc('created_at')->first();
                    $client->last_transaction = $lastInvoice ? $lastInvoice->created_at : null;
                }

                // Find index/rank of the client among top clients
                $index = Client::withSum('invoices', 'total')
                    ->orderByDesc('invoices_sum_total')
                    ->pluck('id')
                    ->search($id);

                if ($index === false) {
                    $index = 0;
                }

                return compact('client', 'index');

            case 'payment':
                $payment = Receipt::with('invoice.client')->find($id);
                return compact('payment');
        }

        return [];
    }

    private function getModalTitle($cardType, $id, $data)
    {
        $isEn = app()->getLocale() == 'en';
        switch ($cardType) {
            case 'total-revenue':
                return $isEn ? 'Total Billing Detail' : 'Detail Total Penagihan';
            case 'collection-rate':
                return $isEn ? 'Collection Rate Detail' : 'Detail Tingkat Koleksi';
            case 'revenue':
                return $isEn ? 'Revenue Detail' : 'Detail Pendapatan';
            case 'risks':
                return $isEn ? 'Capital at Risk' : 'Modal dalam Risiko';
            case 'loyalty':
                return $isEn ? 'Loyalty Pulse' : 'Indeks Loyalitas';
            case 'prime-asset':
                return $isEn ? 'Prime Asset' : 'Aset Utama';
            case 'new-issuance':
                return $isEn ? 'New Issuance Detail' : 'Detail Penerbitan Baru';
            case 'settled-assets':
                return $isEn ? 'Settled Assets Detail' : 'Detail Aset Diselesaikan';
            case 'stagnant-flow':
                return $isEn ? 'Stagnant Flow Detail' : 'Detail Aliran Stagnan';
            case 'client':
                return $isEn ? 'Priority Entities' : 'Entitas Prioritas';
            case 'payment':
                return $isEn ? 'Inflow Telemetry' : 'Telemetri Aliran Masuk';
        }
        return 'Details';
    }

    public function render(BusinessUnitReportingService $reportingService)
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();

        // Total Lifetime Metrics for Dashboard
        $globalStats = $reportingService->getSummaryStats();
        $totalRevenue = $globalStats['total_revenue'];
        $pendingRevenue = $globalStats['total_outstanding'];
        $totalClientsCount = Client::where('status', 'aktif')->count();
        $totalInvoicesCount = $globalStats['total_invoices_count'];
        $paidInvoicesCount = $globalStats['paid_invoices_count'];
        $collectionRate = $globalStats['collection_rate'];

        // 1. Monthly Revenue & Mom Comparison
        $currentMonthStats = $reportingService->getSummaryStats([
            'start_date' => $now->copy()->startOfMonth()->toDateString(),
            'end_date' => $now->copy()->endOfMonth()->toDateString(),
        ]);
        $currentMonthRevenue = $currentMonthStats['total_revenue'];

        $lastMonthStats = $reportingService->getSummaryStats([
            'start_date' => $lastMonth->copy()->startOfMonth()->toDateString(),
            'end_date' => $lastMonth->copy()->endOfMonth()->toDateString(),
        ]);
        $lastMonthRevenue = $lastMonthStats['total_revenue'];

        $revenueChange = 0;
        if ($lastMonthRevenue > 0) {
            $revenueChange = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } elseif ($currentMonthRevenue > 0) {
            $revenueChange = 100;
        }

        // 2. Unpaid Amount Breakdown
        $totalUnpaid = $globalStats['total_outstanding'];
        $pendingUnpaid = $globalStats['pending_outstanding'];
        $overdueUnpaid = $globalStats['overdue_outstanding'];

        // 3. Repeat Customer Rate
        $totalClients = Client::count();
        $repeatClients = Client::has('invoices', '>', 1)->count();
        $repeatRate = $totalClients > 0 ? ($repeatClients / $totalClients) * 100 : 0;

        // 4. Top 5 Clients
        $topClients = $reportingService->getTopClients([], 5)->map(function ($client) {
            $lastInvoice = $client->invoices()->orderByDesc('created_at')->first();
            $client->last_transaction = $lastInvoice ? $lastInvoice->created_at : null;
            return $client;
        });

        // 5. Revenue Trend (Last 6 Months)
        $monthlyTrend = $reportingService->getMonthlyTrend([], 6);
        $revenueTrend = collect($monthlyTrend)->map(function ($item) {
            return [
                'month' => $item['month_label'],
                'revenue' => (float) $item['revenue']
            ];
        });

        // 6. Recent Large Payments
        $recentLargePayments = Receipt::with('invoice.client')
            ->orderByDesc('amount_received')
            ->take(5)
            ->get();

        // 7. Monthly Performance Summary
        $monthlyPerformance = [
            'created' => $currentMonthStats['total_invoices_count'],
            'paid' => $currentMonthStats['paid_invoices_count'],
            'overdue' => Invoice::where('due_date', '<', $now->toDateString())
                ->where('status', '!=', 'paid')
                ->count(),
        ];

        $businessUnitsSummary = $reportingService->getBusinessUnitsSummary();

        return view('livewire.owner-kpi', compact(
            'currentMonthRevenue',
            'lastMonthRevenue',
            'revenueChange',
            'totalUnpaid',
            'pendingUnpaid',
            'overdueUnpaid',
            'repeatRate',
            'topClients',
            'revenueTrend',
            'recentLargePayments',
            'monthlyPerformance',
            'totalClients',
            'totalRevenue',
            'pendingRevenue',
            'totalClientsCount',
            'collectionRate',
            'totalInvoicesCount',
            'paidInvoicesCount',
            'businessUnitsSummary'
        ));
    }
}
