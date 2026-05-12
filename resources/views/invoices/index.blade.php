<x-app-layout>
    <div class="mb-8 md:mb-10 flex flex-col sm:flex-row sm:items-end justify-between gap-6">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span class="shrink-0">Enterprise</span>
                <i data-lucide="chevron-right" class="w-3 h-3 shrink-0"></i>
                <span class="text-indigo-600 truncate">Billing Ledger</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 font-outfit tracking-tight truncate">{{ __('ui.invoices') }}</h1>
            <p class="text-xs md:text-sm text-slate-500 truncate">Manage all issued corporate invoices and statuses.</p>
        </div>
        <div class="flex items-center">
            <a href="{{ route('invoices.create') }}" class="btn-premium w-full sm:w-auto py-3 sm:py-2.5">
                <i data-lucide="plus" class="w-4 h-4 mr-2 inline"></i>{{ __('ui.create_invoice') }}
            </a>
        </div>
    </div>

    <!-- Quick Stats Mini -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card px-6 py-4 border-l-4 border-l-indigo-500">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Total Issued</p>
            <p class="text-xl font-black text-slate-900 font-outfit">{{ $invoices->total() }}</p>
        </div>
        <div class="glass-card px-6 py-4 border-l-4 border-l-emerald-500">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ __('ui.total_collected') }}</p>
            <p class="text-xl font-black text-emerald-600 font-outfit">Rp {{ number_format(\App\Models\Payment::sum('amount'), 0, ',', '.') }}</p>
        </div>
        <div class="glass-card px-6 py-4 border-l-4 border-l-amber-500">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ __('ui.amount_due') }}</p>
            <p class="text-xl font-black text-slate-900 font-outfit">Rp {{ number_format(\App\Models\Invoice::whereIn('status', ['sent', 'dp', 'pending', 'overdue'])->sum('total'), 0, ',', '.') }}</p>
        </div>
        <div class="glass-card px-6 py-4 border-l-4 border-l-rose-500">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Overdue Count</p>
            <p class="text-xl font-black text-rose-600 font-outfit">{{ \App\Models\Invoice::where('status', 'overdue')->count() }}</p>
        </div>
    </div>

    <!-- Table / Mobile List -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 bg-slate-50/50">
                        <th class="px-8 py-4 border-b border-slate-100">Invoice Number</th>
                        <th class="px-8 py-4 border-b border-slate-100">Customer Details</th>
                        <th class="px-8 py-4 border-b border-slate-100">Net Amount</th>
                        <th class="px-8 py-4 border-b border-slate-100">Due Date</th>
                        <th class="px-8 py-4 border-b border-slate-100">{{ __('ui.status') }}</th>
                        <th class="px-8 py-4 border-b border-slate-100 text-right">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($invoices as $invoice)
                        <tr class="table-row-premium">
                            <td class="px-8 py-4.5">
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-[13px] font-bold text-slate-900 hover:text-indigo-600 transition-colors">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td class="px-8 py-4.5">
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-800">{{ $invoice->client->nama_client }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $invoice->client->nama_perusahaan }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="text-[13px] font-black text-slate-900">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-8 py-4.5">
                                <div class="flex flex-col">
                                    <span class="text-[12px] font-semibold text-slate-600">{{ $invoice->due_date->format('M d, Y') }}</span>
                                    @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                        <span class="text-[9px] font-bold text-rose-500 uppercase tracking-tighter">{{ __('ui.overdue') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-4.5">
                                <x-badge :status="$invoice->status" />
                            </td>
                            <td class="px-8 py-4.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors" title="{{ __('ui.view') }}">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="p-2 text-slate-400 hover:text-amber-600 transition-colors" title="{{ __('ui.edit') }}">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <x-empty-state icon="file-text" :title="__('ui.empty_data')" description="No invoices detected in the ledger." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile List View -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($invoices as $invoice)
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-sm font-black text-slate-900 hover:text-indigo-600 transition-colors">
                                {{ $invoice->invoice_number }}
                            </a>
                            <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-tight mt-0.5">{{ $invoice->client->nama_client }}</p>
                        </div>
                        <x-badge :status="$invoice->status" class="scale-90 origin-right" />
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Net Amount</p>
                            <p class="text-sm font-black text-slate-900">Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Due Date</p>
                            <p class="text-xs font-bold text-slate-600">{{ $invoice->due_date->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <p class="text-[10px] text-slate-400 font-medium">{{ $invoice->client->nama_perusahaan }}</p>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-[11px] font-black text-indigo-600 uppercase tracking-widest">View</a>
                            <a href="{{ route('invoices.edit', $invoice) }}" class="text-[11px] font-black text-amber-600 uppercase tracking-widest">Edit</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <p class="text-sm text-slate-500">{{ __('ui.empty_data') }}</p>
                </div>
            @endforelse
        </div>
        @if($invoices->hasPages())
            <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
