<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Invoice;
use Carbon\Carbon;

class UpcomingBillingHorizon extends Component
{
    public function render()
    {
        $horizonDate = now()->addDays(7);
        
        $query = Invoice::with('client')
            ->where('status', '!=', 'paid')
            ->whereBetween('due_date', [now(), $horizonDate])
            ->orderBy('due_date');

        $upcomingInvoices = $query->get();

        $totalExpectedCashFlow = $upcomingInvoices->sum('total');

        return view('livewire.dashboard.upcoming-billing-horizon', [
            'upcomingInvoices' => $upcomingInvoices,
            'totalExpectedCashFlow' => $totalExpectedCashFlow,
        ]);
    }
}
