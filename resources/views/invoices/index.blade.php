<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span>Enterprise</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600">Billing Ledger</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">Invoices</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Comprehensive list of all issued corporate invoices and payment statuses.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="btn-secondary">
                <i data-lucide="filter" class="w-4 h-4 mr-2 inline text-slate-400"></i>Advanced Filter
            </button>
            <a href="{{ route('invoices.create') }}" class="btn-premium">
                <i data-lucide="plus" class="w-4 h-4 mr-2 inline"></i>Create Invoice
            </a>
        </div>
    </div>

    <!-- Quick Stats Mini -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card px-6 py-4 border-l-4 border-l-indigo-500">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Total Issued</p>
            <p class="text-xl font-black text-slate-900 dark:text-white font-outfit">{{ $invoices->total() }} Invoices</p>
        </div>
        <div class="glass-card px-6 py-4 border-l-4 border-l-emerald-500">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Collection Rate</p>
            <p class="text-xl font-black text-emerald-600 font-outfit">84.2%</p>
        </div>
        <div class="glass-card px-6 py-4 border-l-4 border-l-amber-500">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Pending Amount</p>
            <p class="text-xl font-black text-slate-900 dark:text-white font-outfit">Rp 24.5M</p>
        </div>
        <div class="glass-card px-6 py-4 border-l-4 border-l-rose-500">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Overdue Count</p>
            <p class="text-xl font-black text-rose-600 font-outfit">12 Cases</p>
        </div>
    </div>

    <!-- Search & Control -->
    <div class="bg-white dark:bg-slate-900 p-3 rounded-xl border border-slate-200/60 dark:border-slate-800 shadow-sm mb-6 flex flex-col md:flex-row gap-3">
        <div class="flex-1 relative">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" placeholder="Search by invoice number, client, or date..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50/50 dark:bg-slate-800/30 border-none focus:ring-0 text-[13px] text-slate-900 dark:text-white rounded-lg">
        </div>
        <div class="flex items-center gap-2">
            <select class="bg-slate-50/50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2.5 text-[12px] font-semibold text-slate-600 dark:text-slate-300 focus:ring-1 focus:ring-indigo-500 outline-none">
                <option>Active Period</option>
                <option>Last 30 Days</option>
                <option>This Quarter</option>
            </select>
            <div class="w-px h-6 bg-slate-200 dark:bg-slate-800 mx-1"></div>
            <button class="p-2.5 bg-slate-50/50 dark:bg-slate-800 text-slate-400 hover:text-slate-900 transition-colors rounded-lg">
                <i data-lucide="settings-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 bg-slate-50/50 dark:bg-slate-800/40">
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Invoice Number</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Customer Details</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Net Amount</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Due Condition</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Status</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @forelse($invoices as $invoice)
                        <tr class="table-row-premium">
                            <td class="px-8 py-4.5">
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-[13px] font-bold text-slate-900 dark:text-white hover:text-indigo-600 transition-colors">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td class="px-8 py-4.5">
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-800 dark:text-slate-200">{{ $invoice->client->nama_client }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $invoice->client->nama_perusahaan }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="text-[13px] font-black text-slate-900 dark:text-white">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-8 py-4.5">
                                <div class="flex flex-col">
                                    <span class="text-[12px] font-semibold text-slate-600 dark:text-slate-400">{{ $invoice->due_date->format('M d, Y') }}</span>
                                    @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                        <span class="text-[9px] font-bold text-rose-500 uppercase tracking-tighter">Overdue by {{ $invoice->due_date->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-4.5">
                                <x-badge :status="$invoice->status" />
                            </td>
                            <td class="px-8 py-4.5 text-right">
                                <div class="flex items-center justify-end gap-1 opacity-40 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="p-2 text-slate-400 hover:text-amber-600 transition-colors">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <button class="p-2 text-slate-400 hover:text-slate-900 transition-colors">
                                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center max-w-xs mx-auto">
                                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                        <i data-lucide="file-text" class="w-8 h-8 text-slate-200"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">No Invoices Found</h4>
                                    <p class="text-xs text-slate-400 mt-1">Start by creating your first corporate invoice to begin tracking billing.</p>
                                    <a href="{{ route('invoices.create') }}" class="btn-premium mt-6">Create First Invoice</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
            <div class="px-8 py-4 bg-slate-50/50 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
