<x-app-layout>
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                <a href="{{ route('invoices.index') }}" class="hover:text-indigo-600 transition-colors">Invoices</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900 dark:text-white">{{ $invoice->invoice_number }}</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-outfit leading-tight">Invoice Details</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Review and manage billing for {{ $invoice->client->nama_client }}.</p>
        </div>
        <div class="flex items-center gap-3">
            <x-badge :status="$invoice->status" class="px-3 py-1 text-[11px]" />
            <div class="h-8 w-px bg-slate-200 dark:bg-slate-800 mx-2"></div>
            <button class="p-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-500 hover:text-slate-900 transition-all shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i>
            </button>
            <button class="p-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-500 hover:text-slate-900 transition-all shadow-sm">
                <i data-lucide="download" class="w-4 h-4"></i>
            </button>
            <button class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition-all shadow-sm flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                Send Invoice
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden max-w-5xl mx-auto">
        <!-- Professional Invoice Header -->
        <div class="p-16 border-b border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start gap-12">
                <div class="space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#0f172a] flex items-center justify-center text-white font-bold">R</div>
                        <span class="text-xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Rooterin<span class="text-indigo-500">.</span></span>
                    </div>
                    <div class="space-y-1 text-sm text-slate-500 dark:text-slate-400">
                        <p class="font-bold text-slate-900 dark:text-white">Rooterin Technical Services</p>
                        <p>Sudirman Central Business District (SCBD)</p>
                        <p>Jakarta Selatan, 12190</p>
                        <p class="pt-2 font-medium">contact@rooterin.com</p>
                    </div>
                </div>
                
                <div class="text-right">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em] mb-6">Tax Invoice</h2>
                    <div class="space-y-1">
                        <p class="text-2xl font-black text-slate-900 dark:text-white font-outfit">{{ $invoice->invoice_number }}</p>
                        <p class="text-xs font-bold text-slate-500">Issued: {{ $invoice->tanggal_invoice->format('M d, Y') }}</p>
                        <div class="inline-block mt-4 px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded text-[10px] font-bold uppercase tracking-widest">
                            Due: {{ $invoice->due_date->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Relations -->
        <div class="grid grid-cols-2 p-16 gap-16 bg-slate-50/30 dark:bg-slate-800/20 border-b border-slate-100 dark:border-slate-800">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Customer Account</p>
                <div class="space-y-2">
                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $invoice->client->nama_client }}</p>
                    <p class="text-sm font-bold text-indigo-600">{{ $invoice->client->nama_perusahaan }}</p>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-xs">
                        {{ $invoice->client->alamat }}<br>
                        {{ $invoice->client->kota }}, {{ $invoice->client->provinsi }}
                    </p>
                </div>
            </div>
            
            <div class="text-right flex flex-col items-end justify-center">
                <div class="p-8 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Payable</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white font-outfit">Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Line Items -->
        <div class="p-16">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase text-slate-400 tracking-widest border-b border-slate-200 dark:border-slate-800">
                        <th class="pb-4">Line Item Description</th>
                        <th class="pb-4 text-center w-24">Qty</th>
                        <th class="pb-4 text-right w-40">Rate</th>
                        <th class="pb-4 text-right w-40">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="py-6">
                                <p class="text-[13px] font-bold text-slate-900 dark:text-white">{{ $item->deskripsi }}</p>
                            </td>
                            <td class="py-6 text-center text-[13px] font-medium text-slate-600 dark:text-slate-400">{{ number_format($item->qty, 0) }}</td>
                            <td class="py-6 text-right text-[13px] font-medium text-slate-600 dark:text-slate-400">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="py-6 text-right text-[13px] font-black text-slate-900 dark:text-white">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Calculation Summary -->
            <div class="mt-12 flex justify-end">
                <div class="w-full md:w-80 space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-medium">Subtotal</span>
                        <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-medium">VAT ({{ $invoice->tax_percent }}%)</span>
                        <span class="font-bold text-slate-900 dark:text-white">+ Rp {{ number_format($invoice->subtotal * ($invoice->tax_percent / 100), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-medium">Adjustment</span>
                        <span class="font-bold text-rose-500">- Rp {{ number_format($invoice->subtotal * ($invoice->discount_percent / 100), 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter">Total Due</span>
                        <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400 font-outfit">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-16 py-10 bg-slate-50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-center gap-8">
                <div class="text-[11px] text-slate-400 font-medium leading-relaxed max-w-lg">
                    <p class="font-bold text-slate-500 uppercase tracking-widest mb-1">Payment Instructions</p>
                    <p>{{ $invoice->terms_condition }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Digitally Issued By</p>
                    <p class="text-xs font-black text-slate-900 dark:text-white uppercase">{{ $invoice->creator->name }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
