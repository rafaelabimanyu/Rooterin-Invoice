<x-app-layout>
    <!-- Header Section -->
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
                <span>Enterprise</span>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-blue-600">Receipts / Kwitansi</span>
            </div>
            <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-outfit">Receipts</h1>
            <p class="text-[15px] text-slate-400 font-medium">Manage payment receipts for your clients</p>
        </div>
        <div class="flex items-center">
            <a href="{{ route('receipts.create') }}" class="btn-premium-glass group">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>New Receipt</span>
            </a>
        </div>
    </div>

    <!-- Desktop List View (Floating Rows) -->
    <div class="hidden md:block space-y-4">
        <!-- List Header -->
        <div class="grid grid-cols-12 gap-6 px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-2xl mb-2">
            <div class="col-span-2">Rec Number</div>
            <div class="col-span-3">Client Account</div>
            <div class="col-span-2">Amount</div>
            <div class="col-span-1 text-center">Date</div>
            <div class="col-span-2 text-center">Status</div>
            <div class="col-span-2 text-right">Actions</div>
        </div>

        @forelse($receipts as $receipt)
            <div class="row-floating grid grid-cols-12 gap-6 items-center px-10 py-6 group">
                <!-- REC NUMBER -->
                <div class="col-span-2">
                    <a href="{{ route('receipts.show', $receipt) }}" class="text-[14px] font-bold text-slate-900 hover:text-blue-600 transition-colors tracking-tight">
                        {{ $receipt->receipt_number }}
                    </a>
                </div>

                <!-- CLIENT ACCOUNT -->
                <div class="col-span-3">
                    <div class="flex flex-col">
                        <span class="text-[14px] font-bold text-slate-800">{{ $receipt->client->nama_client }}</span>
                        <span class="text-[12px] text-slate-400 font-medium">{{ $receipt->client->nama_perusahaan }}</span>
                    </div>
                </div>

                <!-- AMOUNT -->
                <div class="col-span-2">
                    <span class="text-[15px] font-black text-slate-900 tracking-tight">Rp {{ number_format($receipt->total, 0, ',', '.') }}</span>
                </div>

                <!-- DATE -->
                <div class="col-span-1 text-center">
                    <span class="text-[13px] font-bold text-slate-500">{{ $receipt->tanggal_receipt->format('M d, Y') }}</span>
                </div>

                <!-- STATUS -->
                <div class="col-span-2 flex justify-center">
                    <x-badge :status="$receipt->status" />
                </div>

                <!-- ACTIONS -->
                <div class="col-span-2">
                    <div class="flex items-center justify-end gap-4 opacity-40 group-hover:opacity-100 transition-all duration-300">
                        <a href="{{ route('receipts.show', $receipt) }}" class="p-1 text-slate-400 hover:text-blue-600 transition-colors" title="View Detail">
                            <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                        </a>
                        <a href="{{ route('receipts.pdf', $receipt) }}" class="p-1 text-slate-400 hover:text-indigo-600 transition-colors" title="Download PDF">
                            <i data-lucide="download" class="w-4.5 h-4.5"></i>
                        </a>
                        @if($receipt->status !== 'invoiced')
                        <a href="{{ route('receipts.edit', $receipt) }}" class="p-1 text-slate-400 hover:text-amber-600 transition-colors" title="Edit">
                            <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                        </a>
                        @endif
                        <form action="{{ route('receipts.destroy', $receipt) }}" method="POST" onsubmit="return confirm('Delete this receipt?')" class="inline">
                            @csrf @method('DELETE')
                            <button class="p-1 text-slate-400 hover:text-rose-600 transition-colors" title="Delete">
                                <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-slate-200 rounded-[32px] p-24 text-center">
                <div class="flex flex-col items-center max-w-sm mx-auto">
                    <div class="w-20 h-20 bg-slate-50 rounded-[24px] flex items-center justify-center mb-6">
                        <i data-lucide="file-text" class="w-10 h-10 text-slate-300"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-2">No Receipts Found</h4>
                    <p class="text-[14px] text-slate-400 font-medium">Start by creating a payment receipt for your business clients to track transactions.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Mobile View -->
    <div class="md:hidden space-y-4">
        @forelse($receipts as $receipt)
            <div class="bg-white border border-slate-100 rounded-[24px] p-6 shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $receipt->receipt_number }}</h3>
                        <p class="text-[13px] font-bold text-blue-600 uppercase tracking-tight mt-0.5">{{ $receipt->client->nama_client }}</p>
                    </div>
                    <x-badge :status="$receipt->status" class="scale-90 origin-right" />
                </div>
                
                <div class="grid grid-cols-2 gap-6 py-4 border-t border-slate-50">
                    <div>
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Amount</p>
                        <p class="text-lg font-black text-slate-900">Rp {{ number_format($receipt->total, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Date</p>
                        <p class="text-[14px] font-bold text-slate-600">{{ $receipt->tanggal_receipt->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                    <p class="text-[12px] text-slate-400 font-medium truncate max-w-[150px]">{{ $receipt->client->nama_perusahaan }}</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('receipts.show', $receipt) }}" class="text-[12px] font-black text-blue-600 uppercase tracking-widest">View</a>
                        <a href="{{ route('receipts.pdf', $receipt) }}" class="text-[12px] font-black text-indigo-600 uppercase tracking-widest">PDF</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-100 rounded-[24px] p-12 text-center">
                <i data-lucide="file-text" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
                <p class="text-sm font-bold text-slate-900">No records found.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
