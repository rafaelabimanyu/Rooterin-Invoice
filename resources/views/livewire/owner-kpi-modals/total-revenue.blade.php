<div class="space-y-6 animate-fade-in">
    <!-- Header Card -->
    <div class="p-6 bg-gold-500 rounded-2xl text-slate-950 shadow-xl shadow-gold-500/10 flex justify-between items-center font-bold">
        <div>
            <span class="px-2.5 py-0.5 bg-slate-950/10 rounded-full text-[8px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Lifetime Revenue' : 'Total Pendapatan Terkumpul' }}</span>
            <h3 class="text-2xl font-black mt-3 font-jakarta tracking-tight">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="text-[9.5px] text-slate-800 font-bold mt-2">{{ app()->getLocale() == 'en' ? 'Gross revenue from paid invoices' : 'Pendapatan kotor dari seluruh invoice yang lunas' }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-950/10 flex items-center justify-center">
            <i data-lucide="banknote" class="w-6 h-6 text-slate-800"></i>
        </div>
    </div>

    <!-- Recent Payments List -->
    <div class="space-y-3">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Recent Settled Inflow' : 'Aliran Dana Masuk Terakhir' }}</h4>
        <div class="space-y-2">
            @forelse($paidInvoices as $inv)
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between hover:bg-white hover:border-slate-200 transition-colors duration-200">
                    <div>
                        <span class="text-xs font-black text-slate-800 tracking-tight block mb-1">{{ $inv->client?->nama_client ?? 'N/A' }}</span>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $inv->invoice_number }}</span>
                            <span class="w-0.5 h-0.5 rounded-full bg-slate-300"></span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $inv->client?->nama_perusahaan ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-emerald-600 block">Rp {{ number_format($inv->total, 0, ',', '.') }}</span>
                        <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest block mt-0.5">{{ $inv->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 italic text-center py-6">{{ app()->getLocale() == 'en' ? 'No paid invoices found.' : 'Tidak ada invoice lunas ditemukan.' }}</p>
            @endforelse
        </div>
    </div>
</div>
