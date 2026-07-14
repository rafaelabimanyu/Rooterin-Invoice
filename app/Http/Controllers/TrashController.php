<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrashController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Unauthorized action.');

        $invoices = Invoice::onlyTrashed()->with(['client', 'businessUnit'])->latest()->get();
        $receipts = Receipt::onlyTrashed()->with(['invoice.client'])->latest()->get();

        return view('trash.index', compact('invoices', 'receipts'));
    }

    public function restoreInvoice($id)
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Unauthorized action.');

        $invoice = Invoice::onlyTrashed()->findOrFail($id);
        $invoice->restore();

        \App\Models\ActivityLog::log('restored_invoice', "Restored invoice #{$invoice->invoice_number}");

        return redirect()->route('trash.index')->with('success', app()->getLocale() == 'en' 
            ? 'Invoice restored successfully.' 
            : 'Invoice berhasil dipulihkan.');
    }

    public function forceDeleteInvoice($id)
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Unauthorized action.');

        $invoice = Invoice::onlyTrashed()->findOrFail($id);

        // Clean up physical attachment files from disk
        foreach ($invoice->attachments as $attachment) {
            if ($attachment->file_path) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        // Delete attachments records
        $invoice->attachments()->delete();

        $num = $invoice->invoice_number;
        $invoice->forceDelete();

        \App\Models\ActivityLog::log('purged_invoice', "Permanently deleted invoice #{$num}");

        return redirect()->route('trash.index')->with('success', app()->getLocale() == 'en' 
            ? 'Invoice permanently deleted.' 
            : 'Invoice berhasil dihapus secara permanen.');
    }

    public function restoreReceipt($id)
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Unauthorized action.');

        $receipt = Receipt::onlyTrashed()->findOrFail($id);
        $receipt->restore();

        \App\Models\ActivityLog::log('restored_receipt', "Restored receipt #{$receipt->receipt_number}");

        return redirect()->route('trash.index')->with('success', app()->getLocale() == 'en' 
            ? 'Receipt restored successfully.' 
            : 'Kwitansi berhasil dipulihkan.');
    }

    public function forceDeleteReceipt($id)
    {
        abort_if(auth()->user()->role === 'staff', 403, 'Unauthorized action.');

        $receipt = Receipt::onlyTrashed()->findOrFail($id);

        $num = $receipt->receipt_number;
        $receipt->forceDelete();

        \App\Models\ActivityLog::log('purged_receipt', "Permanently deleted receipt #{$num}");

        return redirect()->route('trash.index')->with('success', app()->getLocale() == 'en' 
            ? 'Receipt permanently deleted.' 
            : 'Kwitansi berhasil dihapus secara permanen.');
    }
}
