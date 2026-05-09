<x-app-layout>
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="page-fade-in">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white font-jakarta tracking-tight mb-2 uppercase">Command Center</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium tracking-tight">Real-time enterprise intelligence and operational overview.</p>
        </div>
        <div class="flex items-center gap-4 page-fade-in" style="animation-delay: 100ms">
            <a href="{{ route('invoices.create') }}" class="btn-premium group">
                <i data-lucide="plus" class="w-4 h-4 transition-transform group-hover:rotate-90"></i>
                <span>{{ __('ui.create_invoice') }}</span>
            </a>
        </div>
    </div>

    <!-- KPI Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 page-fade-in" style="animation-delay: 200ms">
        <x-stats-card 
            :title="__('ui.total_billing')" 
            value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}" 
            change="+12.5%" 
            icon="bar-chart-3" 
            color="indigo" 
        />
        <x-stats-card 
            :title="__('ui.amount_due')" 
            value="Rp {{ number_format($pendingRevenue, 0, ',', '.') }}" 
            change="-5.2%" 
            icon="clock" 
            color="amber" 
        />
        <x-stats-card 
            :title="__('ui.clients')" 
            value="{{ $totalClients }}" 
            change="+3" 
            icon="users" 
            color="emerald" 
        />
        <div class="glass-card p-7 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-indigo-500/10">
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-indigo-500/5 text-indigo-600 flex items-center justify-center border border-indigo-500/10 shadow-sm group-hover:scale-110 transition-transform">
                    <i data-lucide="check-circle-2" class="w-7 h-7"></i>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400 shadow-sm">
                    Efficiency
                </span>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Collection Rate</p>
                <div class="flex items-end justify-between mb-2">
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white font-jakarta tracking-tight">{{ $totalInvoices > 0 ? round(($paidInvoicesCount / $totalInvoices) * 100) : 0 }}%</h3>
                </div>
                <div class="w-full bg-slate-100 dark:bg-white/5 h-2 rounded-full overflow-hidden shadow-inner">
                    <div class="bg-indigo-600 h-full transition-all duration-1000 shadow-[0_0_12px_rgba(79,70,229,0.5)]" style="width: {{ $totalInvoices > 0 ? ($paidInvoicesCount / $totalInvoices) * 100 : 0 }}%"></div>
                </div>
            </div>
            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="table-container page-fade-in" style="animation-delay: 300ms">
        <div class="px-10 py-8 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between bg-slate-50/30 dark:bg-white/[0.02] gap-4">
            <div>
                <h3 class="font-black text-slate-900 dark:text-white font-jakarta uppercase tracking-tight text-lg">Billing Operations</h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Latest system transactions</p>
            </div>
            <a href="{{ route('invoices.index') }}" class="btn-secondary group">
                <span>View All Invoices</span>
                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="table-header">
                        <th class="px-10 py-5">Reference</th>
                        <th class="px-10 py-5">Entity</th>
                        <th class="px-10 py-5">Timestamp</th>
                        <th class="px-10 py-5">Volume</th>
                        <th class="px-10 py-5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/[0.03]">
                    @forelse($recentInvoices as $invoice)
                        <tr class="table-row-premium cursor-pointer group" onclick="window.location='{{ route('invoices.show', $invoice) }}'">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center group-hover:bg-slate-900 dark:group-hover:bg-white transition-colors duration-300">
                                        <i data-lucide="hash" class="w-4 h-4 text-slate-400 group-hover:text-white dark:group-hover:text-slate-900"></i>
                                    </div>
                                    <span class="text-[13px] font-black text-slate-900 dark:text-white tracking-tight">{{ $invoice->invoice_number }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <div class="flex flex-col">
                                    <span class="text-[14px] font-bold text-slate-900 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $invoice->client->nama_client }}</span>
                                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $invoice->client->nama_perusahaan }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-300"></i>
                                    <span class="text-[12px] text-slate-500 dark:text-slate-400 font-bold uppercase">{{ $invoice->tanggal_invoice->format('M d, Y') }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <span class="text-[15px] font-black text-slate-900 dark:text-white tracking-tighter">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-10 py-6">
                                <x-badge :status="$invoice->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-20">
                                <x-empty-state icon="layers" title="Quiet Environment" description="No recent billing activity detected in the system." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
