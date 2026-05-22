<div class="space-y-6 animate-fade-in">
    <!-- Header Card -->
    <div class="p-6 bg-emerald-600 rounded-2xl text-white shadow-xl shadow-emerald-600/10 flex justify-between items-center">
        <div>
            <span class="px-2.5 py-0.5 bg-white/20 rounded-full text-[8px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Collection Rate' : 'Tingkat Koleksi Piutang' }}</span>
            <h3 class="text-2xl font-black mt-3 font-jakarta tracking-tight">{{ number_format($collectionRate, 1) }}%</h3>
            <p class="text-[9.5px] text-emerald-100 font-medium mt-2">{{ app()->getLocale() == 'en' ? 'Efficiency of invoice collection (Paid vs Total)' : 'Efisiensi penagihan invoice (Lunas vs Total)' }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
            <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-200"></i>
        </div>
    </div>

    <!-- Collection Progress Breakdown -->
    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Invoices Summary' : 'Ringkasan Invoice' }}</h4>
        
        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden shadow-inner">
            <div class="bg-indigo-600 h-full progress-bar-fill shadow-[0_0_12px_rgba(79,70,229,0.5)]" style="width: {{ $collectionRate }}%"></div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-2">
            <div class="flex flex-col">
                <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Settled Invoices' : 'Invoice Lunas' }}</span>
                <span class="text-base font-black text-slate-800">{{ $paidInvoicesCount }} / {{ $totalInvoicesCount }}</span>
            </div>
            <div class="flex flex-col text-right">
                <span class="text-[10px] text-rose-400 font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Unpaid / Outstanding' : 'Belum Lunas' }}</span>
                <span class="text-base font-black text-rose-600">{{ $totalInvoicesCount - $paidInvoicesCount }} Invoices</span>
            </div>
        </div>
    </div>

    <!-- Dual Lists -->
    <div class="space-y-4">
        <!-- Recent Paid -->
        <div class="space-y-2">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Latest Settled' : 'Invoice Baru Dilunasi' }}</h4>
            <div class="space-y-1.5">
                @forelse($recentPaidInvoices as $inv)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-black text-slate-800 block">{{ $inv->client?->nama_client ?? 'N/A' }}</span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">{{ $inv->invoice_number }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-emerald-600 block">Rp {{ number_format($inv->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic text-center py-2">{{ app()->getLocale() == 'en' ? 'No recently settled invoices.' : 'Belum ada invoice diselesaikan.' }}</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Unpaid -->
        <div class="space-y-2 pt-2">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Outstanding Payments' : 'Piutang Menunggu Pembayaran' }}</h4>
            <div class="space-y-1.5">
                @forelse($recentUnpaidInvoices as $inv)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-black text-slate-800 block">{{ $inv->client?->nama_client ?? 'N/A' }}</span>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $inv->invoice_number }}</span>
                                <span class="w-0.5 h-0.5 rounded-full bg-slate-300"></span>
                                <span class="text-[9px] text-rose-500 font-black uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Due' : 'Tempo' }}: {{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-slate-900 block">Rp {{ number_format($inv->total - $inv->payments->sum('amount'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic text-center py-2">{{ app()->getLocale() == 'en' ? 'No outstanding invoices.' : 'Tidak ada tagihan tertunggak.' }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
