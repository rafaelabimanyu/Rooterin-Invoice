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

        // RBAC: Staff only see assigned
        if (auth()->user()->hasRole('staff')) {
            $query->where('created_by', auth()->id());
        }

        $upcomingInvoices = $query->get();

        $totalExpectedCashFlow = 0;
        if (!auth()->user()->hasRole('staff')) {
            $totalExpectedCashFlow = $upcomingInvoices->sum('total');
        }

        return view('livewire.dashboard.upcoming-billing-horizon', [
            'upcomingInvoices' => $upcomingInvoices,
            'totalExpectedCashFlow' => $totalExpectedCashFlow,
        ]);
    }
}
