<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $payment = Payment::create($request->all());
            
            // Update Invoice Status
            $invoice = Invoice::find($request->invoice_id);
            $totalPaid = $invoice->payments()->sum('amount');
            
            if ($totalPaid >= $invoice->total) {
                $invoice->update(['status' => 'paid']);
            } elseif ($totalPaid > 0) {
                $invoice->update(['status' => 'dp']); // Or 'pending'
            }

            DB::commit();
            return back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;
        $payment->delete();
        
        // Recalculate status
        $totalPaid = $invoice->payments()->sum('amount');
        if ($totalPaid == 0) {
            $invoice->update(['status' => 'sent']);
        } elseif ($totalPaid < $invoice->total) {
            $invoice->update(['status' => 'dp']);
        }
        
        return back()->with('success', 'Payment deleted.');
    }
}
