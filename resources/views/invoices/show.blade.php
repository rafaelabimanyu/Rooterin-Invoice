<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Invoices
            </a>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-outfit">Invoice {{ $invoice->invoice_number }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <x-badge :status="$invoice->status" class="px-4 py-2 text-sm" />
            <button class="p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-600 dark:text-slate-400 hover:text-indigo-600 transition-all shadow-sm">
                <i data-lucide="printer" class="w-5 h-5"></i>
            </button>
            <button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold transition-all shadow-lg shadow-indigo-600/20">
                Send to Client
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
        <!-- Invoice Header -->
        <div class="p-12 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/10">
            <div class="flex flex-col md:flex-row justify-between gap-12">
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-2xl font-black">R</div>
                        <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Rooterin</span>
                    </div>
                    <div class="text-sm text-slate-500 dark:text-slate-400 space-y-1">
                        <p class="font-bold text-slate-900 dark:text-white">Rooterin Services Ltd.</p>
                        <p>Jl. Jendral Sudirman No. 123</p>
                        <p>Jakarta Selatan, Indonesia</p>
                        <p>contact@rooterin.com</p>
                    </div>
                </div>
                
                <div class="text-right space-y-1">
                    <h2 class="text-4xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-4">Invoice</h2>
                    <p class="text-sm font-bold text-slate-900 dark:text-white">#{{ $invoice->invoice_number }}</p>
                    <p class="text-sm text-slate-500">Issued on {{ $invoice->tanggal_invoice->format('M d, Y') }}</p>
                    <p class="text-sm text-rose-500 font-bold">Due on {{ $invoice->due_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Client & Info Grid -->
        <div class="p-12 grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Billed To</p>
                <div class="space-y-1">
                    <p class="text-xl font-black text-slate-900 dark:text-white">{{ $invoice->client->nama_client }}</p>
                    <p class="text-base font-bold text-indigo-600 dark:text-indigo-400">{{ $invoice->client->nama_perusahaan }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed max-w-xs">
                        {{ $invoice->client->alamat }}<br>
                        {{ $invoice->client->kota }}, {{ $invoice->client->provinsi }}
                    </p>
                </div>
            </div>
            
            <div class="md:text-right flex flex-col md:items-end justify-center">
                <div class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-3xl border border-slate-100 dark:border-slate-800 inline-block">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Amount Due</p>
                    <p class="text-4xl font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="px-12 pb-12">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-slate-100 dark:border-slate-800 text-xs font-black uppercase text-slate-400 tracking-widest">
                            <th class="py-4 text-left">Description</th>
                            <th class="py-4 text-center">Qty</th>
                            <th class="py-4 text-right">Unit Price</th>
                            <th class="py-4 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach($invoice->items as $item)
                            <tr>
                                <td class="py-6">
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $item->deskripsi }}</p>
                                </td>
                                <td class="py-6 text-center text-slate-600 dark:text-slate-400 font-medium">{{ number_format($item->qty, 0) }}</td>
                                <td class="py-6 text-right text-slate-600 dark:text-slate-400 font-medium">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="py-6 text-right font-bold text-slate-900 dark:text-white">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer / Totals -->
        <div class="p-12 bg-slate-50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800">
            <div class="flex flex-col md:flex-row justify-between gap-12">
                <div class="max-w-md space-y-6">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Terms & Conditions</p>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $invoice->terms_condition }}</p>
                    </div>
                    @if($invoice->notes_internal)
                        <div class="p-4 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-2xl">
                            <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase mb-1">Internal Notes</p>
                            <p class="text-xs text-amber-600 dark:text-amber-300 italic">{{ $invoice->notes_internal }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="w-full md:w-80 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-medium">Subtotal</span>
                        <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-medium">Tax ({{ $invoice->tax_percent }}%)</span>
                        <span class="font-bold text-slate-900 dark:text-white">+ Rp {{ number_format($invoice->subtotal * ($invoice->tax_percent / 100), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-medium">Discount ({{ $invoice->discount_percent }}%)</span>
                        <span class="font-bold text-rose-500">- Rp {{ number_format($invoice->subtotal * ($invoice->discount_percent / 100), 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-4 border-t-2 border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <span class="text-lg font-black text-slate-900 dark:text-white">Grand Total</span>
                        <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
