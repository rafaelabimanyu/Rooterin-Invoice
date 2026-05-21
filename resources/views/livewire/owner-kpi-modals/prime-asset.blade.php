<div class="space-y-6 animate-fade-in">
    @if($topClients->count() > 0)
        @php $top = $topClients[0]; @endphp
        <div class="p-6 bg-rose-500 rounded-2xl text-white shadow-xl shadow-rose-500/10 flex justify-between items-center">
            <div>
                <span class="px-2.5 py-0.5 bg-white/20 rounded-full text-[8px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Prime Asset' : 'Aset Utama' }}</span>
                <h3 class="text-xl font-black mt-3 font-jakarta tracking-tight leading-tight">{{ $top->nama_client }}</h3>
                <p class="text-[10px] text-rose-100 font-bold uppercase tracking-wider mt-1">{{ app()->getLocale() == 'en' ? 'Highest Valuation Partner' : 'Mitra Valuasi Tertinggi' }} | {{ $top->nama_perusahaan }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                <i data-lucide="crown" class="w-6 h-6 text-rose-200"></i>
            </div>
        </div>

        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Valuation & Metrics' : 'Valuasi & Metrik' }}</h4>
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-600">{{ app()->getLocale() == 'en' ? 'Total Life Time Value' : 'Total Nilai Kontrak (LTV)' }}</span>
                <span class="text-sm font-black text-rose-600">Rp {{ number_format($top->invoices_sum_total, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <span class="text-xs font-semibold text-slate-600">{{ app()->getLocale() == 'en' ? 'Total Volume' : 'Total Volume Transaksi' }}</span>
                <span class="text-sm font-bold text-slate-800">{{ $top->invoices_count }} {{ app()->getLocale() == 'en' ? 'Invoices' : 'Faktur' }}</span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <span class="text-xs font-semibold text-slate-600">{{ app()->getLocale() == 'en' ? 'Last Transaction' : 'Transaksi Terakhir' }}</span>
                <span class="text-xs font-bold text-slate-800">{{ $top->last_transaction ? \Carbon\Carbon::parse($top->last_transaction)->format('d M Y') : '-' }}</span>
            </div>
        </div>

        <div class="space-y-2">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Client Specifications' : 'Spesifikasi Klien' }}</h4>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3">
                <div class="flex items-center gap-3">
                    <i data-lucide="tag" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider">CODE: {{ $top->kode_client }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="briefcase" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-xs font-semibold text-slate-700">{{ $top->client_type_label }} | {{ $top->industry_sector_label }}</span>
                </div>
                @if($top->npwp)
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-check-2" class="w-4 h-4 text-slate-400"></i>
                        <span class="text-xs font-semibold text-slate-700">NPWP: {{ $top->npwp }}</span>
                    </div>
                @endif
            </div>
        </div>
    @else
        <p class="text-xs text-slate-400 italic text-center py-12">{{ app()->getLocale() == 'en' ? 'Insufficient client data.' : 'Data klien tidak mencukupi.' }}</p>
    @endif
</div>
