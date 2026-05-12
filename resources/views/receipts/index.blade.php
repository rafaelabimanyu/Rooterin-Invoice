<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span>Enterprise</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600">Receipts / Kwitansi</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 font-outfit">Receipts</h1>
            <p class="text-sm text-slate-500">Manage payment receipts for your clients.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('receipts.create') }}" class="btn-premium">
                <i data-lucide="plus" class="w-4 h-4 mr-2 inline"></i>New Receipt
            </a>
        </div>
    </div>

    <!-- Table / Mobile List -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 bg-slate-50/50">
                        <th class="px-8 py-4 border-b border-slate-100">Rec Number</th>
                        <th class="px-8 py-4 border-b border-slate-100">Client Account</th>
                        <th class="px-8 py-4 border-b border-slate-100">Amount</th>
                        <th class="px-8 py-4 border-b border-slate-100">Date</th>
                        <th class="px-8 py-4 border-b border-slate-100">Status</th>
                        <th class="px-8 py-4 border-b border-slate-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($receipts as $receipt)
                        <tr class="table-row-premium">
                            <td class="px-8 py-4.5">
                                <a href="{{ route('receipts.show', $receipt) }}" class="text-[13px] font-bold text-slate-900 hover:text-indigo-600 transition-colors">
                                    {{ $receipt->receipt_number }}
                                </a>
                            </td>
                            <td class="px-8 py-4.5">
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-800">{{ $receipt->client->nama_client }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $receipt->client->nama_perusahaan }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="text-[13px] font-black text-slate-900">Rp {{ number_format($receipt->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="text-[12px] font-medium text-slate-600">{{ $receipt->tanggal_receipt->format('M d, Y') }}</span>
                            </td>
                            <td class="px-8 py-4.5">
                                <x-badge :status="$receipt->status" />
                            </td>
                            <td class="px-8 py-4.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('receipts.show', $receipt) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('receipts.pdf', $receipt) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors" title="Download PDF">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                    </a>
                                    @if($receipt->status !== 'invoiced')
                                    <a href="{{ route('receipts.edit', $receipt) }}" class="p-2 text-slate-400 hover:text-amber-600 transition-colors">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    @endif
                                    <form action="{{ route('receipts.destroy', $receipt) }}" method="POST" onsubmit="return confirm('Delete this receipt?')">
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
                                @if(auth()->user()->role === 'staff')
                                    <x-empty-state icon="coffee" title="No activities today" description="Take a breath! You haven't created any receipts in the last 24 hours." />
                                @else
                                    <div class="flex flex-col items-center max-w-xs mx-auto opacity-50">
                                        <i data-lucide="file-text" class="w-10 h-10 mb-4"></i>
                                        <h4 class="text-sm font-bold text-slate-900">No Receipts Found</h4>
                                        <p class="text-xs text-slate-400 mt-1">Start by creating a payment receipt for your clients.</p>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile List View -->
        <div class="md:hidden divide-y divide-slate-100 px-4">
            @forelse($receipts as $receipt)
                <div class="py-6 space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <a href="{{ route('receipts.show', $receipt) }}" class="text-sm font-black text-slate-900 hover:text-indigo-600 transition-colors">
                                {{ $receipt->receipt_number }}
                            </a>
                            <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-tight mt-0.5">{{ $receipt->client->nama_client }}</p>
                        </div>
                        <x-badge :status="$receipt->status" class="scale-90 origin-right" />
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Amount</p>
                            <p class="text-sm font-black text-slate-900">Rp {{ number_format($receipt->total, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date</p>
                            <p class="text-xs font-bold text-slate-600">{{ $receipt->tanggal_receipt->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <p class="text-[10px] text-slate-400 font-medium">{{ $receipt->client->nama_perusahaan }}</p>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('receipts.show', $receipt) }}" class="text-[11px] font-black text-indigo-600 uppercase tracking-widest">View</a>
                            <a href="{{ route('receipts.pdf', $receipt) }}" class="text-[11px] font-black text-indigo-600 uppercase tracking-widest">PDF</a>
                            @if($receipt->status !== 'invoiced')
                                <a href="{{ route('receipts.edit', $receipt) }}" class="text-[11px] font-black text-amber-600 uppercase tracking-widest">Edit</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    @if(auth()->user()->role === 'staff')
                        <div class="flex flex-col items-center">
                            <i data-lucide="coffee" class="w-10 h-10 text-slate-300 mb-4"></i>
                            <p class="text-sm font-bold text-slate-900">No activities today</p>
                            <p class="text-[11px] text-slate-400 mt-1">You haven't created any receipts in the last 24 hours.</p>
                        </div>
                    @else
                        <p class="text-sm text-slate-500">No receipts detected.</p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
