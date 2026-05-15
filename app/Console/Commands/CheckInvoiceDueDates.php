<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\User;
use App\Notifications\InvoiceChronosNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckInvoiceDueDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chronos:check-due-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check invoice due dates and send proactive notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Chronos Check...');

        // 1. H-3 Reminder
        $h3Invoices = Invoice::where('status', '!=', 'paid')
            ->whereDate('due_date', now()->addDays(3)->toDateString())
            ->get();

        foreach ($h3Invoices as $invoice) {
            $message = "Invoice {$invoice->invoice_number} is due in 3 days.";
            $this->notifyStakeholders($invoice, 'reminder', $message);
        }

        // 2. Day H (Due Today)
        $todayInvoices = Invoice::where('status', '!=', 'paid')
            ->whereDate('due_date', now()->toDateString())
            ->get();

        foreach ($todayInvoices as $invoice) {
            $message = "Invoice {$invoice->invoice_number} is due today!";
            $this->notifyStakeholders($invoice, 'due_today', $message);
        }

        // 3. Overdue (H+1 and beyond, if status not paid/overdue)
        // Automatically mark as overdue if due_date passed
        $overdueInvoices = Invoice::where('status', '!=', 'paid')
            ->where('status', '!=', 'overdue')
            ->whereDate('due_date', '<', now()->toDateString())
            ->get();

        foreach ($overdueInvoices as $invoice) {
            $invoice->update(['status' => 'overdue']);
            $message = "Invoice {$invoice->invoice_number} is now OVERDUE. Urgent action required.";
            $this->notifyStakeholders($invoice, 'overdue', $message);
        }

        $this->info('Chronos Check Completed.');
    }

    protected function notifyStakeholders(Invoice $invoice, string $type, string $message)
    {
        // Staff (Creator)
        $creator = $invoice->creator;
        if ($creator) {
            $creator->notify(new InvoiceChronosNotification($invoice, $type, $message));
        }

        // Admins & Owners (for Overdue or all?)
        // Requirement says: 
        // H-3: Staff & Admin
        // Hari H: (UI doesn't specify, but usually stakeholders)
        // Overdue: Admin & Owner

        $adminsAndOwners = User::whereIn('role', ['admin', 'owner'])->get();
        foreach ($adminsAndOwners as $user) {
            // Avoid duplicate notification if the creator is also an admin/owner
            if ($creator && $user->id === $creator->id) continue;
            
            if ($type === 'overdue' || $type === 'reminder' || $type === 'due_today') {
                $user->notify(new InvoiceChronosNotification($invoice, $type, $message));
            }
        }
    }
}
