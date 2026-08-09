<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;


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



        $receipts = $query->latest()->paginate(10);
        $businessUnits = \App\Models\BusinessUnit::orderBy('name')->get();

        return view('receipts.index', compact('receipts', 'businessUnits'));
    }

    public function create()
    {
        return redirect()->route('invoices.index')->with('info', 'Kwitansi otomatis dibuat saat status invoice diubah menjadi Paid.');
    }

    public function createInstant()
    {
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        $businessUnits = \App\Models\BusinessUnit::where('is_active', true)->orderBy('name')->get();
        
        $invoiceService = new \App\Services\InvoiceService();
        $invoice_number = $invoiceService->generateInvoiceNumber();

        return view('receipts.create_instant_receipt', compact('invoice_number', 'clients', 'businessUnits'));
    }

    public function storeInstant(Request $request)
    {
        $request->validate([
            'business_unit_id' => 'required|exists:business_units,id',
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'pph' => 'nullable|numeric|min:0',
            'cause_of_problem' => 'nullable|string',
            'notes' => 'nullable|string',
            'technician_names' => 'nullable|string',
            'warranty_value' => 'nullable|numeric|min:1',
            'warranty_unit' => 'nullable|string|in:Hari,Bulan,Tahun,Days,Months,Years',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['qty'] * $item['harga'];
            }

            $discountInput = (float) $request->input('discount', 0);
            $ppnInput = (float) $request->input('ppn', 0);
            $pphInput = (float) $request->input('pph', 0);

            $discountNominal = round($discountInput > 100 ? $discountInput : ($subtotal * ($discountInput / 100)), 2);
            $dpp = round($subtotal - $discountNominal, 2);
            $ppnNominal = round($ppnInput > 100 ? $ppnInput : ($dpp * ($ppnInput / 100)), 2);
            $pphNominal = round($pphInput > 100 ? $pphInput : ($dpp * ($pphInput / 100)), 2);

            $invoiceService = new \App\Services\InvoiceService();
            $total = round($invoiceService->calculateTotal($subtotal, $discountNominal, $ppnNominal, $pphNominal), 2);
            $invoiceNumber = $invoiceService->generateInvoiceNumber();

            $warranty = null;
            if ($request->filled('warranty_value')) {
                $warranty = $request->warranty_value . ' ' . $request->input('warranty_unit', 'Bulan');
            }

            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'business_unit_id' => $request->business_unit_id,
                'client_id' => $request->client_id,
                'subtotal' => $subtotal,
                'discount' => $discountNominal,
                'ppn' => $ppnNominal,
                'pph' => $pphNominal,
                'total' => $total,
                'status' => 'paid',
                'due_date' => now()->toDateString(),
                'cause_of_problem' => $request->cause_of_problem,
                'notes' => $request->notes ?: null,
                'technician_names' => $request->technician_names ?: null,
                'warranty' => $warranty ?: null,
            ];

            if (Schema::hasColumn('invoices', 'created_by')) {
                $invoiceData['created_by'] = auth()->id();
            }

            $invoice = Invoice::create($invoiceData);

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments', 'public');
                    $invoice->attachments()->create([
                        'file_path' => $path,
                    ]);
                }
            }

            $receipt = $invoiceService->markAsPaid($invoice);

            \Illuminate\Support\Facades\DB::commit();

            \App\Models\ActivityLog::log('created_invoice', "Issued new invoice #{$invoice->invoice_number} (Instant Receipt)", $invoice);

            $roleLabel = auth()->user()->role;
            $userName = auth()->user()->name;
            $activityText = ucfirst($roleLabel) . " {$userName} generated instant receipt #{$receipt->receipt_number} and invoice #{$invoice->invoice_number}.";
            
            \App\Models\SecurityLog::create([
                'user_id' => auth()->id(),
                'activity' => $activityText,
                'details' => [],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_suspicious' => false,
            ]);

            $usersToNotify = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
            foreach ($usersToNotify as $u) {
                $u->notify(new \App\Notifications\SystemActivityNotification(
                    'Instant Receipt Generated',
                    "{$userName} ({$roleLabel}) generated instant receipt #{$receipt->receipt_number}.",
                    'security',
                    route('receipts.show', $receipt)
                ));
            }

            return redirect()->route('receipts.index')->with('success', app()->getLocale() == 'en' ? 'Instant receipt generated successfully.' : 'Kwitansi instan berhasil dibuat.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->withInput()->with('error', (app()->getLocale() == 'en' ? 'Failed to generate instant receipt: ' : 'Gagal menyimpan kwitansi instan: ') . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        return redirect()->route('receipts.index');
    }

    public function show(Receipt $receipt)
    {

        $receipt->load(['invoice.client', 'invoice.items', 'invoice.attachments']);
        return view('receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt)
    {
        $receipt->load(['invoice.items', 'invoice.client', 'invoice.businessUnit', 'invoice.attachments']);
        $businessUnits = \App\Models\BusinessUnit::orderBy('name')->get();
        $clients = \App\Models\Client::orderBy('nama_client')->get();
        return view('receipts.edit', compact('receipt', 'businessUnits', 'clients'));
    }


    public function update(Request $request, Receipt $receipt)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $receipt);

        $request->validate([
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'technician_names' => 'nullable|string|max:500',
            'cause_of_problem' => 'nullable|string|max:1000',
            'warranty_value' => 'nullable|integer|min:1',
            'warranty_unit' => 'nullable|string|in:Hari,Bulan,Tahun,Days,Months,Years',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'ppn' => 'nullable|numeric|min:0|max:100',
            'pph' => 'nullable|numeric|min:0|max:100',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'delete_attachments' => 'nullable|array',
            'delete_attachments.*' => 'nullable|integer',
        ]);


        $invoice = $receipt->invoice;
        if (!$invoice) {
            return redirect()->route('receipts.index')->with('error', 'Kwitansi tidak memiliki invoice terkait.');
        }

        // 1. Capture the initial state before any database changes
        $oldReceiptState = [
            'amount_received' => (float) $receipt->amount_received,
            'payment_date'    => $receipt->payment_date ? $receipt->payment_date->toIso8601String() : null,
        ];

        $oldInvoiceState = [
            'subtotal' => (float) $invoice->subtotal,
            'discount' => (float) $invoice->discount,
            'ppn'      => (float) $invoice->ppn,
            'pph'      => (float) $invoice->pph,
            'total'    => (float) $invoice->total,
            'notes'    => $invoice->notes,
            'technician_names' => $invoice->technician_names,
            'cause_of_problem' => $invoice->cause_of_problem,
            'warranty' => $invoice->warranty,
        ];


        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Recalculate Invoice totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['qty'] * $item['harga'];
            }

            $discountInput = (float) $request->input('discount', 0);
            $ppnInput = (float) $request->input('ppn', 0);
            $pphInput = (float) $request->input('pph', 0);

            $discountNominal = round($discountInput > 100 ? $discountInput : ($subtotal * ($discountInput / 100)), 2);
            $dpp = round($subtotal - $discountNominal, 2);
            $ppnNominal = round($ppnInput > 100 ? $ppnInput : ($dpp * ($ppnInput / 100)), 2);
            $pphNominal = round($pphInput > 100 ? $pphInput : ($dpp * ($pphInput / 100)), 2);

            $invoiceService = new \App\Services\InvoiceService();
            $total = round($invoiceService->calculateTotal($subtotal, $discountNominal, $ppnNominal, $pphNominal), 2);

            // Build warranty string
            $warranty = null;
            if ($request->filled('warranty_value')) {
                $warranty = $request->warranty_value . ' ' . $request->input('warranty_unit', 'Bulan');
            }

            // Update Invoice fields (financial + technical)
            $invoice->update([
                'subtotal' => $subtotal,
                'discount' => $discountNominal,
                'ppn' => $ppnNominal,
                'pph' => $pphNominal,
                'total' => $total,
                'notes' => $request->notes,
                'technician_names' => $request->technician_names,
                'cause_of_problem' => $request->cause_of_problem,
                'warranty' => $warranty,
            ]);


            // Sync items (delete old ones and recreate)
            $invoice->items()->delete();
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            // Sync Receipt
            $receipt->update([
                'amount_received' => $total,
                'payment_date' => $request->payment_date,
            ]);

            // Handle attachment deletions
            if ($request->filled('delete_attachments')) {
                foreach ($request->delete_attachments as $attachmentId) {
                    $attachment = \App\Models\InvoiceAttachment::where('id', $attachmentId)
                        ->where('invoice_id', $invoice->id)
                        ->first();
                    if ($attachment) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file_path);
                        $attachment->delete();
                    }
                }
            }

            // Handle new attachment uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments', 'public');
                    $invoice->attachments()->create([
                        'file_path' => $path,
                    ]);
                }
            }


            // Sync Payments (fully paid balance)
            $payment = $invoice->payments()->first();
            if ($payment) {
                $payment->update([
                    'amount' => $total,
                    'payment_date' => $request->payment_date,
                ]);
            } else {
                \App\Models\Payment::create([
                    'invoice_id' => $invoice->id,
                    'payment_date' => $request->payment_date,
                    'amount' => $total,
                    'payment_method' => 'Transfer Bank',
                    'reference_number' => 'AUTO-GENERATED',
                    'notes' => 'Automatic payment entry on sync',
                ]);
            }

            // Reload state from database
            $receipt->refresh();
            $invoice->refresh();

            $newReceiptState = [
                'amount_received' => (float) $receipt->amount_received,
                'payment_date'    => $receipt->payment_date ? $receipt->payment_date->toIso8601String() : null,
            ];

            $newInvoiceState = [
                'subtotal' => (float) $invoice->subtotal,
                'discount' => (float) $invoice->discount,
                'ppn'      => (float) $invoice->ppn,
                'pph'      => (float) $invoice->pph,
                'total'    => (float) $invoice->total,
                'notes'    => $invoice->notes,
                'technician_names' => $invoice->technician_names,
                'cause_of_problem' => $invoice->cause_of_problem,
                'warranty' => $invoice->warranty,
            ];


            // Compare and compile the diff array
            $diffs = [];
            
            // Map Receipt values
            foreach ($oldReceiptState as $field => $oldVal) {
                $newVal = $newReceiptState[$field];
                if ($oldVal !== $newVal) {
                    $diffs[] = [
                        'field' => 'receipt_' . $field,
                        'before' => $oldVal,
                        'after' => $newVal,
                    ];
                }
            }

            // Map Invoice values
            foreach ($oldInvoiceState as $field => $oldVal) {
                $newVal = $newInvoiceState[$field];
                if ($oldVal !== $newVal) {
                    $diffs[] = [
                        'field' => 'invoice_' . $field,
                        'before' => $oldVal,
                        'after' => $newVal,
                    ];
                }
            }

            // Log change in SecurityLog
            $roleLabel = auth()->user()->role;
            $userName = auth()->user()->name;
            $activityText = ucfirst($roleLabel) . " {$userName} updated receipt #{$receipt->receipt_number} and synchronized connected Invoice #{$invoice->invoice_number}.";
            
            \App\Models\SecurityLog::create([
                'user_id' => auth()->id(),
                'activity' => $activityText,
                'details' => $diffs,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_suspicious' => false,
            ]);

            \App\Models\ActivityLog::log('updated_receipt', "Updated receipt #{$receipt->receipt_number} and synchronized with Invoice #{$invoice->invoice_number}");

            \Illuminate\Support\Facades\DB::commit();

            // Send notification to Owner & Admin
            $usersToNotify = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
            foreach ($usersToNotify as $u) {
                $u->notify(new \App\Notifications\SystemActivityNotification(
                    'Receipt & Invoice Synced',
                    "{$userName} ({$roleLabel}) updated receipt #{$receipt->receipt_number}. Invoice values synced automatically.",
                    'security',
                    route('receipts.show', $receipt)
                ));
            }

            return redirect()->route('receipts.show', $receipt)->with('receipt_updated_sync', true);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyinkronkan data: ' . $e->getMessage());
        }
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('receipts.pdf', compact('receipt', 'attachments', 'logoBase64', 'ttdBase64'))
            ->setPaper('a4')
            ->setOption([
                'isRemoteEnabled' => true, 
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'sans-serif',
                'enable_php' => true
            ]);
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

    public function destroy(Request $request, Receipt $receipt)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $receipt);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $num = $receipt->receipt_number;
            $invoice = $receipt->invoice;

            $receipt->update([
                'deleted_by' => auth()->id(),
                'deletion_reason' => $request->input('deletion_reason')
            ]);

            $receipt->delete();

            if ($invoice) {
                $invoice->update(['status' => 'unpaid']);
                $invoice->payments()->delete();
            }

            \App\Models\ActivityLog::log('deleted_receipt', "Soft deleted receipt #{$num}");

            if (auth()->user()->role === 'staff') {
                $reason = $request->input('deletion_reason') ?: '-';
                \App\Models\SecurityLog::create([
                    'user_id' => auth()->id(),
                    'activity' => "Staff " . auth()->user()->name . " soft-deleted receipt #{$num} (Reason: {$reason})",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $usersToNotify = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
                foreach ($usersToNotify as $u) {
                    $u->notify(new \App\Notifications\SystemActivityNotification(
                        'Receipt Deleted by Staff',
                        "Staff " . auth()->user()->name . " soft-deleted receipt #{$num}. Reason: {$reason}",
                        'security',
                        route('trash.index')
                    ));
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->back()->with('success', app()->getLocale() == 'en' 
                ? 'Receipt moved to trash successfully.' 
                : 'Kwitansi berhasil dipindahkan ke tempat sampah.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', app()->getLocale() == 'en'
                ? 'Failed to delete receipt: ' . $e->getMessage()
                : 'Gagal menghapus kwitansi: ' . $e->getMessage());
        }
    }

    private function sanitizeFilenameString($string)
    {
        $cleaned = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $string);
        $cleaned = preg_replace('/[\s_]+/', '-', $cleaned);
        $cleaned = preg_replace('/-+/', '-', $cleaned);
        return trim($cleaned, '-');
    }
}
