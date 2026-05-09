<x-app-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-outfit">Invoices</h1>
            <p class="text-slate-500 dark:text-slate-400">Manage billing and payments</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold transition-all shadow-lg shadow-indigo-600/20">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Create Invoice
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6">
        <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by invoice # or client name..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 rounded-xl transition-all outline-none text-sm text-slate-900 dark:text-white">
            </div>
            <select name="status" class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 rounded-xl transition-all outline-none text-sm text-slate-900 dark:text-white">
                <option value="">All Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-slate-900 dark:bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-800 dark:hover:bg-slate-700 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4 font-bold">Invoice Info</th>
                        <th class="px-6 py-4 font-bold">Client</th>
                        <th class="px-6 py-4 font-bold">Amount</th>
                        <th class="px-6 py-4 font-bold">Due Date</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-slate-500 font-medium">{{ $invoice->tanggal_invoice->format('d M Y') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $invoice->client->nama_client }}</p>
                                <p class="text-xs text-slate-500">{{ $invoice->client->nama_perusahaan ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-black text-slate-900 dark:text-white">Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 font-medium">
                                {{ $invoice->due_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <x-badge :status="$invoice->status" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-all">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-lg transition-all">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state-table />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
