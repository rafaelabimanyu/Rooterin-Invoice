<div class="space-y-6 animate-fade-in">
    <div class="p-6 bg-indigo-600 rounded-2xl text-white shadow-xl shadow-indigo-600/10 flex justify-between items-center">
        <div>
            <span class="px-2.5 py-0.5 bg-white/20 rounded-full text-[8px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Revenue (MTD)' : 'Pendapatan (Bulan Berjalan)' }}</span>
            <h3 class="text-2xl font-black mt-3 font-jakarta tracking-tight">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</h3>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black {{ $revenueChange >= 0 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                    <i data-lucide="{{ $revenueChange >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                    {{ number_format(abs($revenueChange), 1) }}%
                </span>
                <span class="text-[9.5px] text-indigo-200 font-medium">{{ app()->getLocale() == 'en' ? 'vs Last Month' : 'vs Bulan Lalu' }}</span>
            </div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
            <i data-lucide="banknote" class="w-6 h-6 text-indigo-200"></i>
        </div>
    </div>

    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Comparison Metrics' : 'Metrik Perbandingan' }}</h4>
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-600">{{ app()->getLocale() == 'en' ? 'Current Month' : 'Bulan Ini' }}</span>
            <span class="text-sm font-bold text-slate-800">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
            <span class="text-xs font-semibold text-slate-600">{{ app()->getLocale() == 'en' ? 'Last Month' : 'Bulan Lalu' }}</span>
            <span class="text-sm font-bold text-slate-800">Rp {{ number_format($lastMonthRevenue, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="space-y-3">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Recent Revenue Inflows' : 'Aliran Masuk Terkini' }}</h4>
        <div class="space-y-2">
            @forelse($paidInvoices as $inv)
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-slate-800 tracking-tight leading-none block mb-1">{{ $inv->client->nama_client }}</span>
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">{{ $inv->invoice_number }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-emerald-600 block">Rp {{ number_format($inv->total, 0, ',', '.') }}</span>
                        <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest block mt-0.5">{{ $inv->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 italic text-center py-6">{{ app()->getLocale() == 'en' ? 'No revenue inflows recorded this month.' : 'Belum ada aliran pendapatan tercatat bulan ini.' }}</p>
            @endforelse
        </div>
    </div>
</div>
