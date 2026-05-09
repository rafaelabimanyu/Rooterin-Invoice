<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-outfit">Dashboard</h1>
        <p class="text-slate-500 dark:text-slate-400">Overview of your business performance</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stats-card title="Total Revenue" value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}" change="Monthly: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}" icon="dollar-sign" color="indigo" />
        <x-stats-card title="Pending Invoices" value="{{ $pendingInvoicesCount }}" change="Rp {{ number_format($pendingRevenue, 0, ',', '.') }}" icon="file-clock" color="amber" />
        <x-stats-card title="Active Clients" value="{{ $totalClients }}" change="Total: {{ $totalClients }}" icon="users" color="emerald" />
        <x-stats-card title="Total Invoices" value="{{ $totalInvoices }}" change="Paid: {{ $paidInvoicesCount }}" icon="file-text" color="indigo" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Invoices -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 dark:text-white font-outfit">Recent Invoices</h3>
                <a href="{{ route('invoices.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($recentInvoices as $invoice)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $invoice->invoice_number }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase">{{ $invoice->tanggal_invoice->format('d M Y') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $invoice->client->nama_client }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <x-badge :status="$invoice->status" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <x-empty-state-table />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions / Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-indigo-600 rounded-3xl p-6 text-white overflow-hidden relative group shadow-xl shadow-indigo-600/20">
                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-2">Create Invoice</h3>
                    <p class="text-indigo-100 text-sm mb-6">Send a professional invoice to your client in seconds.</p>
                    <a href="{{ route('invoices.create') }}" class="block w-full py-3 bg-white text-indigo-600 rounded-xl font-bold hover:bg-indigo-50 transition-colors text-center">
                        New Invoice
                    </a>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-indigo-500 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h3 class="font-bold text-slate-900 dark:text-white font-outfit mb-4">Business Insights</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Paid Invoices</span>
                        <span class="font-bold text-slate-900 dark:text-white">{{ $totalInvoices > 0 ? round(($paidInvoicesCount / $totalInvoices) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $totalInvoices > 0 ? ($paidInvoicesCount / $totalInvoices) * 100 : 0 }}%"></div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Active Status</span>
                        <span class="font-bold text-slate-900 dark:text-white">Live</span>
                    </div>
                    <div class="flex items-center gap-1">
                        @for($i = 0; $i < 7; $i++)
                            <div class="flex-1 h-8 rounded-md {{ $i < 5 ? 'bg-indigo-500/20' : 'bg-indigo-500' }}"></div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
