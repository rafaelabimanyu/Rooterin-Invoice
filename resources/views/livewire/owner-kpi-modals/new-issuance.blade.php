<div class="space-y-6 animate-fade-in">
    <div class="p-5 bg-gold-500 rounded-2xl text-slate-950 shadow-xl shadow-gold-500/10 flex justify-between items-center font-bold">
        <div>
            <span class="px-2.5 py-0.5 bg-slate-950/10 rounded-full text-[8px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Monthly Operational Feed' : 'Aliran Operasional Bulanan' }}</span>
            <h3 class="text-lg font-black mt-3 font-jakarta tracking-tight">{{ app()->getLocale() == 'en' ? 'New Issuance (MTD)' : 'Penerbitan Baru (Bulan Berjalan)' }}</h3>
            <p class="text-2xl font-black mt-1 font-jakarta">{{ $monthlyPerformance['created'] }} Invoices</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-950/10 flex items-center justify-center">
            <i data-lucide="file-plus" class="w-6 h-6 text-slate-800"></i>
        </div>
    </div>

    <div class="space-y-3">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Recently Created Invoices' : 'Faktur yang Baru Dibuat' }}</h4>
        <div class="space-y-2">
            @forelse($newInvoices as $inv)
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-slate-800 tracking-tight leading-none block mb-1">{{ $inv->client?->nama_client ?? 'N/A' }}</span>
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">{{ $inv->invoice_number }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-slate-900 block">Rp {{ number_format($inv->total, 0, ',', '.') }}</span>
                        <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider inline-block mt-1
                            @if($inv->status == 'paid') bg-emerald-50 text-emerald-600
                            @elseif($inv->status == 'sent') bg-gold-50 text-gold-600
                            @else bg-slate-100 text-slate-600
                            @endif">
                            {{ $inv->status }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 italic text-center py-12">{{ app()->getLocale() == 'en' ? 'No invoices created this month.' : 'Tidak ada faktur dibuat bulan ini.' }}</p>
            @endforelse
        </div>
    </div>
</div>
