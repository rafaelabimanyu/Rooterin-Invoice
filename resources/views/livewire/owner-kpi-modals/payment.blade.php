<div class="space-y-6 animate-fade-in">
    @if($payment)
        <div class="p-5 bg-emerald-500 rounded-2xl text-white shadow-xl shadow-emerald-500/10 flex justify-between items-center">
            <div>
                <span class="px-2.5 py-0.5 bg-white/20 rounded-full text-[8px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Major Capital Movements' : 'Pergerakan modal besar' }}</span>
                <h3 class="text-2xl font-black mt-3 font-jakarta tracking-tight">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-white/80 font-bold uppercase tracking-wider mt-0.5">{{ $payment->payment_date->format('d M Y') }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                <i data-lucide="arrow-down-left" class="w-6 h-6 text-white"></i>
            </div>
        </div>

        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Origin of Funds' : 'Asal Aliran Dana' }}</h4>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white shrink-0">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-black text-slate-900 tracking-tight">{{ $payment->invoice?->client?->nama_client ?? 'N/A' }}</span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $payment->invoice?->client?->nama_perusahaan ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Transaction Details' : 'Kredensial Transaksi' }}</h4>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Invoice Ref' : 'No. Faktur' }}</span>
                    <span class="text-xs font-black text-slate-800">{{ $payment->invoice?->invoice_number ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Method' : 'Metode Pembayaran' }}</span>
                    <span class="px-2.5 py-0.5 bg-slate-100 rounded-full text-xs font-bold text-slate-700">{{ $payment->payment_method }}</span>
                </div>
                @if($payment->reference_number)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Reference' : 'Nomor Referensi' }}</span>
                        <span class="text-xs font-bold text-slate-800">{{ $payment->reference_number }}</span>
                    </div>
                @endif
                @if($payment->notes)
                    <div class="pt-3 border-t border-slate-100/50 flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Memo / Notes' : 'Catatan / Memo' }}</span>
                        <span class="text-xs font-semibold text-slate-600 leading-relaxed italic">"{{ $payment->notes }}"</span>
                    </div>
                @endif
            </div>
        </div>
    @else
        <p class="text-xs text-slate-400 italic text-center py-12">{{ app()->getLocale() == 'en' ? 'Payment details not found.' : 'Detail pembayaran tidak ditemukan.' }}</p>
    @endif
</div>
