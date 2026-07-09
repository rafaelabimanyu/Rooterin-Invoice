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

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'businessUnit']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nama_client', 'like', "%{$search}%")
                        ->orWhere('nama_perusahaan', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('business_unit_id')) {
            $query->where('business_unit_id', $request->business_unit_id);
        }

        if (auth()->user()->role === 'staff') {
            if (Schema::hasColumn('invoices', 'created_by')) {
                $query->where('created_by', auth()->id())
                      ->where('created_at', '>=', now()->subHours(24));
            }
        }

        $invoices = $query->latest()->paginate(10);
        $businessUnits = BusinessUnit::orderBy('name')->get();

        return view('invoices.index', compact('invoices', 'businessUnits'));
    }

    public function create()
    {
        $invoice_number = $this->invoiceService->generateInvoiceNumber();
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        $businessUnits = BusinessUnit::where('is_active', true)->orderBy('name')->get();
        return view('invoices.create', compact('invoice_number', 'clients', 'businessUnits'));
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
                'due_date' => $request->due_date,
                'cause_of_problem' => $request->cause_of_problem,
                'notes' => $request->notes,
                'technician_names' => $request->technician_names,
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

            \App\Models\ActivityLog::log('created_invoice', "Issued new invoice #{$invoice->invoice_number}", $invoice);

            return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        \Illuminate\Support\Facades\Gate::authorize('view', $invoice);

        $invoice->load(['client', 'items', 'receipt']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $invoice);

        $invoice->load('items');
        $clients = Client::where('status', 'aktif')->orderBy('nama_client')->get();
        $businessUnits = BusinessUnit::where('is_active', true)
            ->orWhere('id', $invoice->business_unit_id)
            ->orderBy('name')
            ->get();
        return view('invoices.edit', compact('invoice', 'clients', 'businessUnits'));
    }

    public function update(Request $request, Invoice $invoice)
    {
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
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        // Security check: status 'paid' requires vital fields to be completed
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

            $invoice->update([
                'business_unit_id' => $request->business_unit_id,
                'client_id' => $request->client_id,
                'subtotal' => $subtotal,
                'discount' => $discountNominal,
                'ppn' => $ppnNominal,
                'pph' => $pphNominal,
                'total' => $total,
                'due_date' => $request->due_date,
                'cause_of_problem' => $request->cause_of_problem,
                'notes' => $request->notes,
                'technician_names' => $request->technician_names,
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

            // Logika pelunasan otomatis kwitansi
            if ($request->status === 'paid' && $invoice->getOriginal('status') !== 'paid') {
                $this->invoiceService->markAsPaid($invoice);
            } else {
                // Update status normally if it is not a new paid transition
                $invoice->update(['status' => $request->status]);
            }

            DB::commit();

            \App\Models\ActivityLog::log('updated_invoice', "Updated invoice #{$invoice->invoice_number}", $invoice);

            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function downloadPdf(Request $request, Invoice $invoice)
    {
        $locale = $request->get('lang', config('app.locale'));
        if (in_array($locale, ['en', 'id'])) {
            \Illuminate\Support\Facades\App::setLocale($locale);
        }

        \Illuminate\Support\Facades\Log::info("DEBUG INVOICE PDF: Invoice ID={$invoice->id}, Number={$invoice->invoice_number}");
        \Illuminate\Support\Facades\Log::info("DEBUG INVOICE PDF: Total attachments in DB: " . $invoice->attachments()->count());

        $invoice->load(['client', 'items', 'receipt']);
        $attachments = $invoice->attachments()->take(4)->get();
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

        \Illuminate\Support\Facades\Log::info("DEBUG INVOICE PDF: Loaded attachments count: " . $attachments->count());
        foreach ($attachments as $index => $att) {
            \Illuminate\Support\Facades\Log::info("DEBUG INVOICE PDF: Attachment #{$index} ID={$att->id}, path={$att->file_path}, base64 length=" . ($att->base64_data ? strlen($att->base64_data) : 'NULL'));
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice', 'attachments', 'logoBase64', 'ttdBase64'))
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

        $filename = "Invoice-JNJ-{$nomorPart}-{$cleanedClientName}-{$dateStr}.pdf";
        return $pdf->download($filename);
    }

    public function destroy(Invoice $invoice)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $invoice);

        $num = $invoice->invoice_number;
        $invoice->delete();
        
        \App\Models\ActivityLog::log('deleted_invoice', "Deleted invoice #{$num}");
        
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    private function sanitizeFilenameString($string)
    {
        $cleaned = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $string);
        $cleaned = preg_replace('/[\s_]+/', '-', $cleaned);
        $cleaned = preg_replace('/-+/', '-', $cleaned);
        return trim($cleaned, '-');
    }
}
