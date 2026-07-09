<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Generate dynamic and unique invoice number starting from 5003.
     */
    public function generateInvoiceNumber(): string
    {
        $lastInvoice = Invoice::where('invoice_number', 'LIKE', 'INV-%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 5003;
        $currentYear = date('Y');

        if ($lastInvoice) {
            if (preg_match('/^INV-(\d+)-(\d+)$/', $lastInvoice->invoice_number, $matches)) {
                $lastNumber = (int)$matches[1];
                $nextNumber = $lastNumber + 1;
            }
        }

        return "INV-{$nextNumber}-{$currentYear}";
    }

    /**
     * Calculate invoice total based on the exact formula:
     * Total = (Subtotal - Discount) + PPN - PPh
     */
    public function calculateTotal(float $subtotal, float $discount, float $ppn, float $pph): float
    {
        return round(($subtotal - $discount) + $ppn + $pph, 2);
    }

    /**
     * Transition invoice status to 'paid' and automatically generate a Receipt.
     * Wrapped in DB::transaction to ensure atomicity.
     */
    public function markAsPaid(Invoice $invoice): Receipt
    {
        if ($invoice->receipt()->exists()) {
            throw new \Exception("Kwitansi untuk invoice ini sudah pernah dibuat sebelumnya.");
        }

        return DB::transaction(function () use ($invoice) {
            // Update status invoice
            $invoice->update(['status' => 'paid']);

            // Generate receipt number (KWT-xxxx-yyyy) by replacing INV- with KWT-
            $receiptNumber = str_replace('INV-', 'KWT-', $invoice->invoice_number);

            // Create Receipt record
            return Receipt::create([
                'receipt_number' => $receiptNumber,
                'invoice_id' => $invoice->id,
                'amount_received' => $invoice->total,
                'payment_date' => now(),
            ]);
        });
    }
}
