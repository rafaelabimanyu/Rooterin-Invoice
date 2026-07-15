<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mendapatkan semua invoice berstatus 'paid'
        $paidInvoices = Invoice::where('status', 'paid')->get();

        foreach ($paidInvoices as $invoice) {
            // Jumlah pembayaran yang sudah diinput
            $totalPaid = $invoice->payments()->sum('amount');
            $remaining = $invoice->total - $totalPaid;

            if ($remaining > 0) {
                // Tentukan tanggal pembayaran dari receipt jika ada, atau fallback ke updated_at / now
                $paymentDate = $invoice->receipt ? $invoice->receipt->payment_date : ($invoice->updated_at ?: now());

                Payment::create([
                    'invoice_id' => $invoice->id,
                    'payment_date' => $paymentDate,
                    'amount' => $remaining,
                    'payment_method' => 'Transfer Bank',
                    'reference_number' => 'SYS-SYNC',
                    'notes' => 'Synchronized missing payment entry for paid invoice',
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus rekaman pembayaran yang di-generate dari sinkronisasi ini
        Payment::where('reference_number', 'SYS-SYNC')->delete();
    }
};
