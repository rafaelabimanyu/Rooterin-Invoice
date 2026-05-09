<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span>Enterprise</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600">Quotations / Offers</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">Quotations</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Manage price offers and service proposals for prospective clients.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('quotations.create') }}" class="btn-premium">
                <i data-lucide="plus" class="w-4 h-4 mr-2 inline"></i>New Quotation
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 bg-slate-50/50 dark:bg-slate-800/40">
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Quo Number</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Client Account</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Amount</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Expiry</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Status</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @forelse($quotations as $quotation)
                        <tr class="table-row-premium">
                            <td class="px-8 py-4.5">
                                <a href="{{ route('quotations.show', $quotation) }}" class="text-[13px] font-bold text-slate-900 dark:text-white hover:text-indigo-600 transition-colors">
                                    {{ $quotation->quotation_number }}
                                </a>
                            </td>
                            <td class="px-8 py-4.5">
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-800 dark:text-slate-200">{{ $quotation->client->nama_client }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $quotation->client->nama_perusahaan }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="text-[13px] font-black text-slate-900 dark:text-white">Rp {{ number_format($quotation->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="text-[12px] font-medium text-slate-600 dark:text-slate-400">{{ $quotation->expiry_date->format('M d, Y') }}</span>
                            </td>
                            <td class="px-8 py-4.5">
                                <x-badge :status="$quotation->status" />
                            </td>
                            <td class="px-8 py-4.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('quotations.show', $quotation) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    @if($quotation->status !== 'invoiced')
                                    <a href="{{ route('quotations.edit', $quotation) }}" class="p-2 text-slate-400 hover:text-amber-600 transition-colors">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    @endif
                                    <form action="{{ route('quotations.destroy', $quotation) }}" method="POST" onsubmit="return confirm('Delete this quotation?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-slate-400 hover:text-rose-600 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center max-w-xs mx-auto opacity-50">
                                    <i data-lucide="file-text" class="w-10 h-10 mb-4"></i>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">No Quotations Found</h4>
                                    <p class="text-xs text-slate-400 mt-1">Start by creating a service proposal for your prospective clients.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
