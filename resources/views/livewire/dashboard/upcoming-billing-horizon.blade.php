<div class="glass-card p-10 h-full flex flex-col shadow-2xl shadow-indigo-500/5 border-slate-100 page-fade-in stagger-4">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">Upcoming Billing Horizon</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Next 7 Days Projections</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
            <i data-lucide="calendar-clock" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="flex-1 space-y-6">
        @forelse($upcomingInvoices as $invoice)
            <div class="group flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-transparent hover:border-indigo-100 hover:bg-white transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-white shadow-sm border border-slate-100">
                        <span class="text-[9px] font-black uppercase text-slate-400 leading-none mb-1">{{ $invoice->due_date->format('M') }}</span>
                        <span class="text-lg font-black text-slate-900 leading-none">{{ $invoice->due_date->format('d') }}</span>
                    </div>
                    <div>
                        <p class="text-[13px] font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $invoice->invoice_number }}</p>
                        <p class="text-[11px] text-slate-500 font-medium">{{ $invoice->client->nama_client }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[14px] font-black text-slate-900">Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $invoice->due_date->isToday() ? 'text-rose-500' : 'text-slate-400' }}">
                        {{ $invoice->due_date->isToday() ? 'Due Today' : $invoice->due_date->diffForHumans() }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="sun" class="w-8 h-8 text-slate-200"></i>
                </div>
                <p class="text-xs font-bold text-slate-900 uppercase tracking-widest">Horizon is Clear</p>
                <p class="text-[11px] text-slate-400 mt-2">No invoices are due within the next 7 days.</p>
            </div>
        @endforelse
    </div>

    @if(!auth()->user()->hasRole('staff'))
    <div class="mt-10 pt-10 border-t border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Expected Cash Flow</p>
                <h4 class="text-2xl font-black text-slate-900 font-jakarta tracking-tighter">Rp {{ number_format($totalExpectedCashFlow, 0, ',', '.') }}</h4>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl">
                <i data-lucide="trending-up" class="w-5 h-5 text-emerald-600"></i>
            </div>
        </div>
    </div>
    @endif
</div>
