<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <a href="{{ route('quotations.index') }}" class="hover:text-indigo-600 transition-colors">Quotations</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900 dark:text-white">{{ $quotation->quotation_number }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">Proposal Details</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Review and approve price offer for {{ $quotation->client->nama_client }}.</p>
        </div>
        <div class="flex items-center gap-3">
            <x-badge :status="$quotation->status" />
            <div class="h-8 w-px bg-slate-200 dark:bg-slate-800 mx-2"></div>
            
            @if($quotation->status !== 'invoiced' && $quotation->status !== 'rejected')
                <form action="{{ route('quotations.convert', $quotation) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition-all flex items-center gap-2 shadow-lg shadow-indigo-600/20">
                        <i data-lucide="file-check" class="w-4 h-4"></i>
                        Approve & Create Invoice
                    </button>
                </form>
            @endif
            
            <button class="p-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-500 hover:text-slate-900 transition-all">
                <i data-lucide="printer" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <div class="glass-card overflow-hidden max-w-5xl mx-auto">
        <div class="p-16 border-b border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start gap-12">
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#0f172a] flex items-center justify-center text-white font-bold">R</div>
                        <span class="text-xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Rooterin<span class="text-indigo-500">.</span></span>
                    </div>
                    <div class="text-sm text-slate-500 space-y-1">
                        <p class="font-bold text-slate-900 dark:text-white">Rooterin Technical Services</p>
                        <p>Jakarta, Indonesia</p>
                    </div>
                </div>
                <div class="text-right space-y-2">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Quotation / Proposal</h2>
                    <p class="text-2xl font-black text-slate-900 dark:text-white font-outfit">{{ $quotation->quotation_number }}</p>
                    <p class="text-[11px] font-bold text-slate-500">Issued: {{ $quotation->tanggal_quotation->format('M d, Y') }}</p>
                    <p class="text-[11px] font-bold text-rose-500 uppercase tracking-wider">Expires: {{ $quotation->expiry_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="p-16 grid grid-cols-2 gap-16 bg-slate-50/20 dark:bg-slate-800/20 border-b border-slate-100 dark:border-slate-800">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Prospective Client</p>
                <div class="space-y-1">
                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $quotation->client->nama_client }}</p>
                    <p class="text-sm font-bold text-indigo-600">{{ $quotation->client->nama_perusahaan }}</p>
                    <p class="text-sm text-slate-500">{{ $quotation->client->alamat }}</p>
                </div>
            </div>
            <div class="text-right flex flex-col items-end justify-center">
                <div class="p-8 glass-card">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Estimated Total</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white font-outfit">Rp {{ number_format($quotation->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="p-16">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase text-slate-400 tracking-widest border-b border-slate-100 dark:border-slate-800">
                        <th class="pb-4">Proposed Service Description</th>
                        <th class="pb-4 text-center w-24">Qty</th>
                        <th class="pb-4 text-right w-40">Rate</th>
                        <th class="pb-4 text-right w-40">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @foreach($quotation->items as $item)
                        <tr>
                            <td class="py-6"><p class="text-[13px] font-bold text-slate-900 dark:text-white">{{ $item->deskripsi }}</p></td>
                            <td class="py-6 text-center text-[13px] text-slate-600 dark:text-slate-400">{{ number_format($item->qty, 0) }}</td>
                            <td class="py-6 text-right text-[13px] text-slate-600 dark:text-slate-400">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="py-6 text-right text-[13px] font-black text-slate-900 dark:text-white">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-12 flex justify-end">
                <div class="w-80 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-bold">Rp {{ number_format($quotation->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tax ({{ $quotation->tax_percent }}%)</span>
                        <span class="font-bold">+ Rp {{ number_format($quotation->subtotal * ($quotation->tax_percent/100), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Discount</span>
                        <span class="font-bold text-rose-500">- Rp {{ number_format($quotation->subtotal * ($quotation->discount_percent/100), 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-base font-black text-slate-900 dark:text-white uppercase">Grand Total</span>
                        <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400 font-outfit">Rp {{ number_format($quotation->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-16 py-8 bg-slate-50/50 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Terms & Conditions</p>
            <p class="text-[11px] text-slate-500 leading-relaxed">{{ $quotation->terms_condition }}</p>
        </div>
    </div>
</x-app-layout>
