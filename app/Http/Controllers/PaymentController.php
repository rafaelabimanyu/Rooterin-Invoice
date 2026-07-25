<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function store(Request $request)
    {
        $invoice = Invoice::findOrFail($request->invoice_id);

        if (auth()->user()->role === 'staff') {
            \Illuminate\Support\Facades\Gate::authorize('update', $invoice);
        }

        $remaining = $invoice->amount_due;

        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1|max:' . $remaining,
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $payment = Payment::create($request->all());
            
            // Update Invoice Status
            $totalPaid = $invoice->payments()->sum('amount');
            
            if ($totalPaid >= $invoice->total) {
                if (!$invoice->receipt()->exists()) {
                    $this->invoiceService->markAsPaid($invoice);
                } else {
                    $invoice->update(['status' => 'paid']);
                }
            } elseif ($totalPaid > 0) {
                $invoice->update(['status' => 'dp']);
            }

            // Log activity
            \App\Models\ActivityLog::log(
                'record_payment',
                "Menginput pembayaran sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " untuk Invoice #{$invoice->invoice_number} ({$invoice->client->nama_client})",
                $payment
            );

            DB::commit();
            return back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Payment $payment)
    {
        abort_if(!in_array(auth()->user()->role, ['owner', 'admin']), 403, 'Unauthorized action.');

        try {
            DB::beginTransaction();

            $invoice = $payment->invoice;
            $amount = $payment->amount;
            $payment->delete();
            
            // Recalculate status
            $totalPaid = $invoice->payments()->sum('amount');
            if ($totalPaid == 0) {
                $invoice->update(['status' => 'sent']);
            } elseif ($totalPaid < $invoice->total) {
                $invoice->update(['status' => 'dp']);
            }

            // Log activity
            \App\Models\ActivityLog::log(
                'delete_payment',
                "Menghapus pembayaran sebesar Rp " . number_format($amount, 0, ',', '.') . " untuk Invoice #{$invoice->invoice_number} ({$invoice->client->nama_client})",
                $invoice
            );

            DB::commit();
            return back()->with('success', 'Payment deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
