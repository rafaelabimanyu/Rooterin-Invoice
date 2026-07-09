<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $query = Receipt::with(['invoice.client', 'invoice.businessUnit']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('receipt_number', 'like', "%{$search}%")
                  ->orWhereHas('invoice.client', function($q) use ($search) {
                      $q->where('nama_client', 'like', "%{$search}%")
                        ->orWhere('nama_perusahaan', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('business_unit_id')) {
            $query->whereHas('invoice', function ($q) use ($request) {
                $q->where('business_unit_id', $request->business_unit_id);
            });
        }

        if (auth()->user()->role === 'staff') {
            $query->whereHas('invoice', function($q) {
                if (Schema::hasColumn('invoices', 'created_by')) {
                    $q->where('created_by', auth()->id());
                }
            })->where('created_at', '>=', now()->subHours(24));
        }

        $receipts = $query->latest()->paginate(10);
        $businessUnits = \App\Models\BusinessUnit::orderBy('name')->get();

        return view('receipts.index', compact('receipts', 'businessUnits'));
    }

    public function create()
    {
        return redirect()->route('invoices.index')->with('info', 'Kuitansi otomatis dibuat saat status invoice diubah menjadi Paid.');
    }

    public function store(Request $request)
    {
        return redirect()->route('receipts.index');
    }

    public function show(Receipt $receipt)
    {
        if (auth()->user()->role === 'staff') {
            $hasCreatedBy = false;
            $invoice = $receipt->invoice;
            if ($invoice && Schema::hasColumn('invoices', 'created_by')) {
                if ($invoice->created_by !== auth()->id() || $receipt->created_at < now()->subHours(24)) {
                    abort(403, 'Access restricted.');
                }
            }
        }
        $receipt->load(['invoice.client', 'invoice.items']);
        return view('receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt)
    {
        return redirect()->route('receipts.index')->with('info', 'Kuitansi otomatis tidak dapat diedit secara manual.');
    }

    public function update(Request $request, Receipt $receipt)
    {
        return redirect()->route('receipts.index');
    }

    public function convertToInvoice(Receipt $receipt)
    {
        return redirect()->route('receipts.index');
    }

    public function downloadPdf(Request $request, Receipt $receipt)
    {
        $locale = $request->get('lang', config('app.locale'));
        if (in_array($locale, ['en', 'id'])) {
            \Illuminate\Support\Facades\App::setLocale($locale);
        }

        \Illuminate\Support\Facades\Log::info("DEBUG RECEIPT PDF: Receipt ID={$receipt->id}, Number={$receipt->receipt_number}");
        if ($receipt->invoice) {
            \Illuminate\Support\Facades\Log::info("DEBUG RECEIPT PDF: Total invoice attachments in DB: " . $receipt->invoice->attachments()->count());
        }

        $receipt->load(['invoice.client', 'invoice.items']);
        if ($receipt->invoice) {
            $attachments = $receipt->invoice->attachments()->take(4)->get();
            $receipt->invoice->setRelation('attachments', $attachments);
        } else {
            $attachments = collect();
        }

        // Convert attachments to Base64
        if ($receipt->invoice && $receipt->invoice->attachments) {
            foreach ($receipt->invoice->attachments as $attachment) {
                $path = storage_path('app/public/' . $attachment->file_path);
                if (file_exists($path)) {
                    try {
                        $mime = mime_content_type($path) ?: 'image/jpeg';
                        $data = file_get_contents($path);
                        $attachment->base64_data = 'data:' . $mime . ';base64,' . base64_encode($data);
                    } catch (\Exception $e) {
                        $attachment->base64_data = null;
                    }
                } else {
                    $attachment->base64_data = null;
                }
            }
        }

        \Illuminate\Support\Facades\Log::info("DEBUG RECEIPT PDF: Loaded attachments count: " . $attachments->count());
        foreach ($attachments as $index => $att) {
            \Illuminate\Support\Facades\Log::info("DEBUG RECEIPT PDF: Attachment #{$index} ID={$att->id}, path={$att->file_path}, base64 length=" . ($att->base64_data ? strlen($att->base64_data) : 'NULL'));
        }

        // Convert logo to Base64
        $logoPath = public_path('img/logo-jnj.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Convert signature (ttd) to Base64
        $ttdPath = public_path('img/ttd.png');
        $ttdBase64 = null;
        if (file_exists($ttdPath)) {
            $ttdBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath));
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('receipts.pdf', compact('receipt', 'attachments', 'logoBase64', 'ttdBase64'));
        $numberSegments = explode('-', $receipt->receipt_number);
        $nomorPart = count($numberSegments) >= 2 
            ? $numberSegments[0] . '-' . $numberSegments[1] 
            : $receipt->receipt_number;

        $clientName = $receipt->client ? $receipt->client->nama_client : 'General';
        $cleanedClientName = $this->sanitizeFilenameString($clientName);

        $dateStr = ($receipt->tanggal_receipt ?: ($receipt->payment_date ?: ($receipt->created_at ?: now())))->format('d-m-Y');

        $filename = "Kwitansi-JNJ-{$nomorPart}-{$cleanedClientName}-{$dateStr}.pdf";
        return $pdf->download($filename);
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('receipts.index')->with('success', 'Receipt deleted successfully.');
    }

    private function sanitizeFilenameString($string)
    {
        $cleaned = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $string);
        $cleaned = preg_replace('/[\s_]+/', '-', $cleaned);
        $cleaned = preg_replace('/-+/', '-', $cleaned);
        return trim($cleaned, '-');
    }
}
