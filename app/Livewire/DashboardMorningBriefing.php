<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AutoReportService;
use App\Models\Invoice;

class DashboardMorningBriefing extends Component
{
    public $briefing = null;
    public $lastGenerated = null;
    public $urgentAlerts = [];

    public function mount()
    {
        $this->loadBriefing();
    }

    public function loadBriefing()
    {
        $service = app(AutoReportService::class);
        $this->briefing = $service->getLatestReport(app()->getLocale());
        $this->lastGenerated = $service->getLastGeneratedTime();
        
        // Load overdue invoices with total > 10,000,000 for high-priority alerts
        $this->urgentAlerts = Invoice::with('client')
            ->where('status', 'overdue')
            ->where('total', '>', 10000000)
            ->get()
            ->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'invoice_number' => $inv->invoice_number,
                    'client_name' => $inv->client ? $inv->client->nama_client : 'Unknown Client',
                    'total' => $inv->total,
                    'formatted_total' => 'Rp ' . number_format($inv->total, 0, ',', '.'),
                    'due_date' => $inv->due_date ? $inv->due_date->format('d M Y') : 'N/A'
                ];
            })
            ->toArray();
    }

    public function refreshBriefing()
    {
        $service = app(AutoReportService::class);
        
        // Regenerate briefing report and scan for urgent notifications
        $service->generateDailyReport();
        $service->checkUrgentOverdueInvoices();
        
        $this->loadBriefing();
        $this->dispatch('notify', ['message' => 'Morning briefing updated successfully.', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.dashboard-morning-briefing');
    }
}
