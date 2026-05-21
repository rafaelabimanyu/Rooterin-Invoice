<div class="space-y-6 animate-fade-in">
    <div class="p-5 bg-rose-600 rounded-2xl text-white shadow-xl shadow-rose-600/10 flex justify-between items-center">
        <div>
            <span class="px-2.5 py-0.5 bg-white/20 rounded-full text-[8px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Overdue Debt Risk' : 'Risiko Utang Jatuh Tempo' }}</span>
            <h3 class="text-lg font-black mt-3 font-jakarta tracking-tight">{{ app()->getLocale() == 'en' ? 'Stagnant Flow' : 'Aliran Stagnan' }}</h3>
            <p class="text-2xl font-black mt-1 font-jakarta">{{ $monthlyPerformance['overdue'] }} Invoices</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
            <i data-lucide="clock-alert" class="w-6 h-6 text-white"></i>
        </div>
    </div>

    <div class="space-y-3">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Overdue Invoices' : 'Faktur Jatuh Tempo' }}</h4>
        <div class="space-y-2">
            @forelse($overdueInvoices as $inv)
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-slate-800 tracking-tight leading-none block mb-1">{{ $inv->client?->nama_client ?? 'N/A' }}</span>
                        <span class="text-[9px] text-rose-500 font-bold uppercase tracking-wider block">{{ app()->getLocale() == 'en' ? 'Due' : 'Tempo' }}: {{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-rose-600 block">Rp {{ number_format($inv->total - $inv->payments->sum('amount'), 0, ',', '.') }}</span>
                        <span class="px-2 py-0.5 bg-rose-50 text-rose-600 rounded text-[8px] font-black uppercase tracking-wider inline-block mt-1">{{ app()->getLocale() == 'en' ? 'Overdue' : 'Terlambat' }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 italic text-center py-12">{{ app()->getLocale() == 'en' ? 'No overdue invoices.' : 'Tidak ada faktur jatuh tempo.' }}</p>
            @endforelse
        </div>
    </div>
</div>
