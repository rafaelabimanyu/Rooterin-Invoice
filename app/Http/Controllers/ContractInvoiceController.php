<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\BusinessUnit;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContractInvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $query = Invoice::where('kategori_invoice', 'kemitraan')->with(['client', 'businessUnit']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('nama_client', 'like', "%{$search}%")
                        ->orWhere('nama_perusahaan', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('business_unit_id')) {
            $query->where('business_unit_id', $request->business_unit_id);
        }

        $invoices = $query->latest()->paginate(10);
        $businessUnits = BusinessUnit::orderBy('name')->get();

        return view('contract_invoices.index', compact('invoices', 'businessUnits'));
    }

    public function create()
    {
        $invoice_number = $this->invoiceService->generateInvoiceNumber();
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        $businessUnits = BusinessUnit::where('is_active', true)->orderBy('name')->get();
        return view('contract_invoices.create', compact('invoice_number', 'clients', 'businessUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_unit_id' => 'required|exists:business_units,id',
            'client_id' => 'required|exists:clients,id',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:draft,sent,pending,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'pph' => 'nullable|numeric|min:0',
            'cause_of_problem' => 'nullable|string',
            'notes' => 'nullable|string',
            'technician_names' => 'nullable|string',
            'warranty_value' => 'nullable|integer|min:1',
            'warranty_unit' => 'nullable|string|in:Hari,Bulan,Tahun,Days,Months,Years',
            'periode_kontrak' => 'nullable|string|max:255',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        try {
            DB::beginTransaction();

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

            $total = round($this->invoiceService->calculateTotal($subtotal, $discountNominal, $ppnNominal, $pphNominal), 2);
            $invoiceNumber = $this->invoiceService->generateInvoiceNumber();

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
                'status' => $request->input('status', 'draft'),
                'kategori_invoice' => 'kemitraan',
                'periode_kontrak' => $request->periode_kontrak,
                'due_date' => $request->due_date,
                'cause_of_problem' => $request->cause_of_problem,
                'notes' => $request->notes,
                'technician_names' => $request->technician_names,
                'warranty' => $warranty,
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

            // If status is paid, trigger automatic receipt generation
            if ($invoice->status === 'paid') {
                $this->invoiceService->markAsPaid($invoice);
            }

            DB::commit();

            \App\Models\ActivityLog::log('created_invoice', "Issued new partnership invoice #{$invoice->invoice_number}", $invoice);

            return redirect()->route('contract-invoices.index')->with('success', 'Partnership Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        // Check if this belongs to partnership category
        if ($invoice->kategori_invoice !== 'kemitraan') {
            abort(404);
        }

        \Illuminate\Support\Facades\Gate::authorize('view', $invoice);

        $invoice->load(['client', 'items', 'receipt']);
        return view('contract_invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->kategori_invoice !== 'kemitraan') {
            abort(404);
        }

        \Illuminate\Support\Facades\Gate::authorize('update', $invoice);

        $invoice->load('items');
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        $businessUnits = BusinessUnit::where('is_active', true)
            ->orWhere('id', $invoice->business_unit_id)
            ->orderBy('name')
            ->get();
        return view('contract_invoices.edit', compact('invoice', 'clients', 'businessUnits'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->kategori_invoice !== 'kemitraan') {
            abort(404);
        }

        \Illuminate\Support\Facades\Gate::authorize('update', $invoice);

        $request->validate([
            'business_unit_id' => 'required|exists:business_units,id',
            'client_id' => 'required|exists:clients,id',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:draft,sent,pending,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'pph' => 'nullable|numeric|min:0',
            'cause_of_problem' => 'nullable|string',
            'notes' => 'nullable|string',
            'technician_names' => 'nullable|string',
            'warranty_value' => 'nullable|integer|min:1',
            'warranty_unit' => 'nullable|string|in:Hari,Bulan,Tahun,Days,Months,Years',
            'periode_kontrak' => 'nullable|string|max:255',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->status === 'paid') {
            $request->validate([
                'due_date' => 'required|date',
                'business_unit_id' => 'required',
                'client_id' => 'required',
            ]);
        }

        try {
            DB::beginTransaction();

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

            $total = round($this->invoiceService->calculateTotal($subtotal, $discountNominal, $ppnNominal, $pphNominal), 2);

            $warranty = null;
            if ($request->filled('warranty_value')) {
                $warranty = $request->warranty_value . ' ' . $request->input('warranty_unit', 'Bulan');
            }

            $invoice->update([
                'business_unit_id' => $request->business_unit_id,
                'client_id' => $request->client_id,
                'subtotal' => $subtotal,
                'discount' => $discountNominal,
                'ppn' => $ppnNominal,
                'pph' => $pphNominal,
                'total' => $total,
                'periode_kontrak' => $request->periode_kontrak,
                'due_date' => $request->due_date,
                'cause_of_problem' => $request->cause_of_problem,
                'notes' => $request->notes,
                'technician_names' => $request->technician_names,
                'warranty' => $warranty,
            ]);

            $invoice->items()->delete();
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            if ($request->has('deleted_attachments') && is_array($request->deleted_attachments)) {
                $attachmentsToDelete = $invoice->attachments()->whereIn('id', $request->deleted_attachments)->get();
                foreach ($attachmentsToDelete as $att) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($att->file_path);
                    $att->delete();
                }
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments', 'public');
                    $invoice->attachments()->create([
                        'file_path' => $path,
                    ]);
                }
            }

            if ($request->status === 'paid' && $invoice->getOriginal('status') !== 'paid') {
                $this->invoiceService->markAsPaid($invoice);
            } else {
                $invoice->update(['status' => $request->status]);
            }

            DB::commit();

            \App\Models\ActivityLog::log('updated_invoice', "Updated partnership invoice #{$invoice->invoice_number}", $invoice);

            if (auth()->user()->role === 'staff') {
                \App\Models\SecurityLog::create([
                    'user_id' => auth()->id(),
                    'activity' => "Staff " . auth()->user()->name . " edited partnership invoice #{$invoice->invoice_number}",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $usersToNotify = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
                foreach ($usersToNotify as $u) {
                    $u->notify(new \App\Notifications\SystemActivityNotification(
                        'Partnership Invoice Edited by Staff',
                        "Staff " . auth()->user()->name . " edited partnership invoice #{$invoice->invoice_number}",
                        'security',
                        route('contract-invoices.show', $invoice)
                    ));
                }
            }

            return redirect()->route('contract-invoices.index')->with('success', 'Partnership Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function downloadPdf(Request $request, Invoice $invoice)
    {
        if ($invoice->kategori_invoice !== 'kemitraan') {
            abort(404);
        }

        $locale = $request->get('lang', config('app.locale'));
        if (in_array($locale, ['en', 'id'])) {
            \Illuminate\Support\Facades\App::setLocale($locale);
        }

        $invoice->load(['client', 'items', 'receipt']);
        // For partnership, retrieve up to 12 attachments for a complete Appendix section
        $attachments = $invoice->attachments()->take(12)->get();
        $invoice->setRelation('attachments', $attachments);

        // Convert attachments to Base64
        foreach ($invoice->attachments as $attachment) {
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('contract_invoices.pdf', compact('invoice', 'attachments', 'logoBase64', 'ttdBase64'))
            ->setPaper('a4')
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'sans-serif',
                'enable_php' => true
            ]);

        $numberSegments = explode('-', $invoice->invoice_number);
        $nomorPart = count($numberSegments) >= 2
            ? $numberSegments[0] . '-' . $numberSegments[1]
            : $invoice->invoice_number;

        $clientName = $invoice->client ? $invoice->client->nama_client : 'General';
        $cleanedClientName = $this->sanitizeFilenameString($clientName);

        $dateStr = ($invoice->tanggal_invoice ?: ($invoice->created_at ?: now()))->format('d-m-Y');

        $filename = "Invoice-Kemitraan-JNJ-{$nomorPart}-{$cleanedClientName}-{$dateStr}.pdf";
        return $pdf->download($filename);
    }

    public function destroy(Request $request, Invoice $invoice)
    {
        if ($invoice->kategori_invoice !== 'kemitraan') {
            abort(404);
        }

        \Illuminate\Support\Facades\Gate::authorize('delete', $invoice);

        if ($invoice->receipt()->exists()) {
            return redirect()->route('contract-invoices.index')->with('error', app()->getLocale() == 'en'
                ? 'This invoice cannot be deleted because it has an active receipt. Please delete the receipt first.'
                : 'Invoice tidak dapat dihapus karena memiliki kwitansi yang aktif. Silakan hapus kwitansi terlebih dahulu.');
        }

        try {
            $num = $invoice->invoice_number;

            $invoice->update([
                'deleted_by' => auth()->id(),
                'deletion_reason' => $request->input('deletion_reason')
            ]);

            $invoice->delete();

            \App\Models\ActivityLog::log('deleted_invoice', "Soft deleted partnership invoice #{$num}");

            if (auth()->user()->role === 'staff') {
                $reason = $request->input('deletion_reason') ?: '-';
                \App\Models\SecurityLog::create([
                    'user_id' => auth()->id(),
                    'activity' => "Staff " . auth()->user()->name . " soft-deleted partnership invoice #{$num} (Reason: {$reason})",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $usersToNotify = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
                foreach ($usersToNotify as $u) {
                    $u->notify(new \App\Notifications\SystemActivityNotification(
                        'Partnership Invoice Deleted by Staff',
                        "Staff " . auth()->user()->name . " soft-deleted partnership invoice #{$num}. Reason: {$reason}",
                        'security',
                        route('trash.index')
                    ));
                }
            }

            return redirect()->route('contract-invoices.index')->with('success', app()->getLocale() == 'en'
                ? 'Partnership Invoice moved to trash successfully.'
                : 'Invoice kemitraan berhasil dipindahkan ke tempat sampah.');
        } catch (\Exception $e) {
            return redirect()->route('contract-invoices.index')->with('error', app()->getLocale() == 'en'
                ? 'Failed to move invoice to trash: ' . $e->getMessage()
                : 'Gagal memindahkan invoice ke tempat sampah: ' . $e->getMessage());
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
