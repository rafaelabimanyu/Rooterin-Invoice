<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\User;
use App\Notifications\InvoiceChronosNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AutoReportService
{
    /**
     * Generate daily financial report/briefing.
     * Saves JSON to storage/app/daily_reports/morning_briefing.json
     *
     * @return void
     */
    public function generateDailyReport(): void
    {
        try {
            // 1. Total revenue (sum of paid invoices)
            $totalRevenue = Invoice::where('status', 'paid')->sum('total');

            // 2. Overdue invoices
            $overdueCount = Invoice::where('status', 'overdue')->count();
            $overdueAmount = Invoice::where('status', 'overdue')->sum('total');

            // 3. Pending/Sent invoices
            $pendingCount = Invoice::whereIn('status', ['sent', 'pending', 'dp'])->count();
            $pendingAmount = Invoice::whereIn('status', ['sent', 'pending', 'dp'])->sum('total');

            // 4. Trend Analysis via DataAggregatorService
            $trendService = app(DataAggregatorService::class);
            $trendId = $trendService->getRevenueTrend('id');
            $trendEn = $trendService->getRevenueTrend('en');

            // Construct briefings
            $briefingTextId = "Selamat pagi! Berikut ringkasan performa finansial pagi ini:\n\n" .
                "* **Total Pendapatan Terkumpul:** Rp " . number_format($totalRevenue, 0, ',', '.') . "\n" .
                "* **Tagihan Menunggak (Overdue):** {$overdueCount} Invoice (Total: Rp " . number_format($overdueAmount, 0, ',', '.') . ")\n" .
                "* **Tagihan Pending:** {$pendingCount} Invoice (Total: Rp " . number_format($pendingAmount, 0, ',', '.') . ")\n\n" .
                "**Analisis Tren:** {$trendId['insight']}\n\n" .
                "Harap segera menindaklanjuti tagihan overdue untuk menjaga kesehatan arus kas.";

            $briefingTextEn = "Good morning! Here is this morning's financial performance summary:\n\n" .
                "* **Total Revenue Collected:** Rp " . number_format($totalRevenue, 0, ',', '.') . "\n" .
                "* **Overdue Invoices:** {$overdueCount} Invoices (Total: Rp " . number_format($overdueAmount, 0, ',', '.') . ")\n" .
                "* **Pending Invoices:** {$pendingCount} Invoices (Total: Rp " . number_format($pendingAmount, 0, ',', '.') . ")\n\n" .
                "**Trend Analysis:** {$trendEn['insight']}\n\n" .
                "Please follow up on overdue invoices promptly to secure cash flow.";

            $data = [
                'generated_at' => Carbon::now()->toDateTimeString(),
                'total_revenue' => (float)$totalRevenue,
                'overdue_count' => $overdueCount,
                'overdue_amount' => (float)$overdueAmount,
                'pending_count' => $pendingCount,
                'pending_amount' => (float)$pendingAmount,
                'briefing_text_id' => $briefingTextId,
                'briefing_text_en' => $briefingTextEn,
            ];

            $directory = storage_path('app/daily_reports');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            File::put($directory . '/morning_briefing.json', json_encode($data, JSON_PRETTY_PRINT));
            Log::info("Daily morning briefing report generated successfully.");
        } catch (\Throwable $e) {
            Log::error("Failed to generate daily morning briefing: " . $e->getMessage());
        }
    }

    /**
     * Get the latest generated report.
     *
     * @param string $locale
     * @return array|null
     */
    public function getLatestReport(string $locale = 'id'): ?array
    {
        $filePath = storage_path('app/daily_reports/morning_briefing.json');
        if (!File::exists($filePath)) {
            $this->generateDailyReport();
        }

        if (File::exists($filePath)) {
            $data = json_decode(File::get($filePath), true);
            if ($data) {
                $data['text'] = ($locale === 'en') ? $data['briefing_text_en'] : $data['briefing_text_id'];
                return $data;
            }
        }

        return null;
    }

    /**
     * Get the last time the report was generated.
     *
     * @return string|null
     */
    public function getLastGeneratedTime(): ?string
    {
        $filePath = storage_path('app/daily_reports/morning_briefing.json');
        if (File::exists($filePath)) {
            $data = json_decode(File::get($filePath), true);
            return $data['generated_at'] ?? null;
        }
        return null;
    }

    /**
     * Scan overdue invoices above Rp 10.000.000, write alerts to log, and notify Admin/Owner.
     *
     * @return void
     */
    public function checkUrgentOverdueInvoices(): void
    {
        try {
            $urgentInvoices = Invoice::with('client')
                ->where('status', 'overdue')
                ->where('total', '>', 10000000)
                ->get();

            if ($urgentInvoices->isEmpty()) {
                return;
            }

            $logPath = storage_path('logs/urgent_alerts.log');
            $adminsAndOwners = User::whereIn('role', ['admin', 'owner'])->get();

            foreach ($urgentInvoices as $invoice) {
                $timestamp = Carbon::now()->toDateTimeString();
                $formattedAmount = "Rp " . number_format($invoice->total, 0, ',', '.');
                $clientName = $invoice->client ? $invoice->client->nama_client : 'Unknown Client';
                
                $logMessage = "[{$timestamp}] URGENT OVERDUE INVOICE: Invoice #{$invoice->invoice_number} by {$clientName} is overdue. Total amount: {$formattedAmount}.\n";
                
                // Write to urgent_alerts.log
                File::append($logPath, $logMessage);

                // Create a notification for Admin & Owner
                $notificationMsg = "[HIGH PRIORITY] Invoice #{$invoice->invoice_number} ({$clientName}) is OVERDUE with nominal {$formattedAmount}. Urgent action required!";
                
                foreach ($adminsAndOwners as $user) {
                    $exists = $user->unreadNotifications()
                        ->where('data->invoice_id', $invoice->id)
                        ->where('data->type', 'overdue')
                        ->exists();

                    if (!$exists) {
                        $user->notify(new InvoiceChronosNotification($invoice, 'overdue', $notificationMsg));
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error("Failed to run checkUrgentOverdueInvoices: " . $e->getMessage());
        }
    }
}
