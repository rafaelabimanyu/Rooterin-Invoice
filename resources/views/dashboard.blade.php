<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">Financial Dashboard</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Enterprise billing and revenue management overview.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="btn-secondary">
                <i data-lucide="download" class="w-4 h-4 mr-2 inline"></i>Export Data
            </button>
            <a href="{{ route('invoices.create') }}" class="btn-premium">
                <i data-lucide="plus" class="w-4 h-4 mr-2 inline"></i>New Invoice
            </a>
        </div>
    </div>

    <!-- KPI Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="glass-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Revenue</p>
                <div class="p-2 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg text-indigo-600">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white font-outfit">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center gap-2">
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-500/10 px-1.5 py-0.5 rounded flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3 h-3 mr-1"></i>+8.2%
                </span>
                <span class="text-[10px] text-slate-400 font-medium">vs last period</span>
            </div>
        </div>

        <div class="glass-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Outstanding</p>
                <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg text-amber-600">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white font-outfit">Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</h3>
            <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center gap-2">
                <span class="text-[10px] text-slate-500 font-bold">{{ $pendingInvoicesCount }} Pending Invoices</span>
            </div>
        </div>

        <div class="glass-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Clients</p>
                <div class="p-2 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg text-emerald-600">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white font-outfit">{{ $totalClients }}</h3>
            <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center gap-2">
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-500/10 px-1.5 py-0.5 rounded">+2 New</span>
                <span class="text-[10px] text-slate-400 font-medium">this week</span>
            </div>
        </div>

        <div class="glass-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Paid Rate</p>
                <div class="p-2 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg text-indigo-600">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white font-outfit">{{ $totalInvoices > 0 ? round(($paidInvoicesCount / $totalInvoices) * 100) : 0 }}%</h3>
            <div class="mt-4">
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-indigo-600 h-full transition-all duration-1000" style="width: {{ $totalInvoices > 0 ? ($paidInvoicesCount / $totalInvoices) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="glass-card overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/30 dark:bg-slate-800/20">
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white font-outfit">Recent Billing Activity</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Latest 5 invoices across all accounts</p>
            </div>
            <a href="{{ route('invoices.index') }}" class="text-[12px] font-bold text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1.5">
                View Ledger <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 bg-slate-50/50 dark:bg-slate-800/40">
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Reference</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Customer Account</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Filing Date</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Total Amount</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @forelse($recentInvoices as $invoice)
                        <tr class="table-row-premium cursor-pointer" onclick="window.location='{{ route('invoices.show', $invoice) }}'">
                            <td class="px-8 py-4.5">
                                <span class="text-[13px] font-bold text-slate-900 dark:text-white">{{ $invoice->invoice_number }}</span>
                            </td>
                            <td class="px-8 py-4.5">
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-800 dark:text-slate-200">{{ $invoice->client->nama_client }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $invoice->client->nama_perusahaan }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="text-[12px] text-slate-500 font-medium">{{ $invoice->tanggal_invoice->format('M d, Y') }}</span>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="text-[13px] font-black text-slate-900 dark:text-white tracking-tight">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-8 py-4.5">
                                <x-badge :status="$invoice->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <i data-lucide="layers" class="w-8 h-8 mb-3"></i>
                                    <p class="text-sm font-medium">No recent activity detected.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
