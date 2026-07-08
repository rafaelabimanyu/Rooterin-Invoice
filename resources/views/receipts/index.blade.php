<x-app-layout :title="app()->getLocale() == 'en' ? 'Receipt & Payment Management' : 'Manajemen Kwitansi & Pembayaran'">
    <div class="animate-fade-in-up">
        <!-- Header Section -->
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
                <span>Enterprise</span>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gold-600">{{ app()->getLocale() == 'en' ? 'Receipts' : 'Kuitansi' }}</span>
            </div>
            <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-outfit">{{ app()->getLocale() == 'en' ? 'Receipts' : 'Kuitansi' }}</h1>
            <p class="text-[15px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'Manage payment receipts for your clients' : 'Kelola kuitansi pembayaran untuk klien Anda' }}</p>
        </div>
        <div class="flex items-center">
            <a href="{{ route('receipts.create') }}" class="btn-premium-glass group">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>{{ app()->getLocale() == 'en' ? 'New Receipt' : 'Kuitansi Baru' }}</span>
            </a>
        </div>
    </div>

    <!-- Desktop List View (Floating Rows) -->
    <div class="hidden md:block space-y-4">
        <!-- List Header -->
        <div class="grid grid-cols-12 gap-6 px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-2xl mb-2">
            <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Rec Number' : 'No. Kuitansi' }}</div>
            <div class="col-span-3">{{ app()->getLocale() == 'en' ? 'Client Account' : 'Akun Klien' }}</div>
            <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Amount' : 'Jumlah' }}</div>
            <div class="col-span-1 text-center">{{ app()->getLocale() == 'en' ? 'Date' : 'Tanggal' }}</div>
            <div class="col-span-2 text-center">Status</div>
            <div class="col-span-2 text-right">{{ app()->getLocale() == 'en' ? 'Actions' : 'Aksi' }}</div>
        </div>

        @forelse($receipts as $receipt)
            <div class="row-floating grid grid-cols-12 gap-6 items-center px-10 py-6 group">
                <!-- REC NUMBER -->
                <div class="col-span-2">
                    <a href="{{ route('receipts.show', $receipt) }}" class="text-[14px] font-bold text-slate-900 hover:text-gold-600 transition-colors tracking-tight">
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
                        <a href="{{ route('receipts.show', $receipt) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors" title="{{ app()->getLocale() == 'en' ? 'View Detail' : 'Lihat Detail' }}">
                            <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                        </a>
                        <a href="{{ route('receipts.pdf', $receipt) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors" title="{{ app()->getLocale() == 'en' ? 'Download PDF' : 'Unduh PDF' }}">
                            <i data-lucide="download" class="w-4.5 h-4.5"></i>
                        </a>
                        @if($receipt->status !== 'invoiced')
                        <a href="{{ route('receipts.edit', $receipt) }}" class="p-1 text-slate-400 hover:text-amber-600 transition-colors" title="{{ app()->getLocale() == 'en' ? 'Edit' : 'Ubah' }}">
                            <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                        </a>
                        @endif
                        <form action="{{ route('receipts.destroy', $receipt) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() == 'en' ? 'Delete this receipt?' : 'Hapus kuitansi ini?' }}')" class="inline">
                            @csrf @method('DELETE')
                            <button class="p-1 text-slate-400 hover:text-rose-600 transition-colors" title="{{ app()->getLocale() == 'en' ? 'Delete' : 'Hapus' }}">
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
                    <h4 class="text-xl font-bold text-slate-900 mb-2">{{ app()->getLocale() == 'en' ? 'No Receipts Found' : 'Kuitansi Tidak Ditemukan' }}</h4>
                    <p class="text-[14px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'Start by creating a payment receipt for your business clients to track transactions.' : 'Mulai dengan membuat kuitansi pembayaran untuk klien bisnis Anda guna melacak transaksi.' }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Mobile View -->
    <div class="md:hidden space-y-3 px-4">
        @forelse($receipts as $receipt)
            <div 
                onclick="window.location='{{ route('receipts.show', $receipt) }}'" 
                class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all active:scale-[0.98] cursor-pointer flex items-center justify-between gap-4"
            >
                <div class="flex-1 min-w-0">
                    <!-- First Row: Receipt Number & Status -->
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-slate-500 tracking-tight truncate">{{ $receipt->receipt_number }}</span>
                        <x-badge :status="$receipt->status" class="scale-75 origin-right shrink-0" />
                    </div>
                    <!-- Second Row: Client Name & Total Amount -->
                    <div class="flex items-center justify-between">
                        <span class="text-[13px] font-black text-slate-900 truncate leading-tight">{{ $receipt->client->nama_client }}</span>
                        <span class="text-[14px] font-black text-gold-650 tracking-tight shrink-0 pl-2">Rp {{ number_format($receipt->total, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <!-- Action: Download PDF -->
                <div class="shrink-0 flex items-center border-l border-slate-100 pl-3">
                    <button 
                        onclick="event.stopPropagation(); window.location='{{ route('receipts.pdf', $receipt) }}'"
                        class="p-2 bg-slate-50 hover:bg-gold-50/50 text-slate-400 hover:text-gold-600 rounded-xl transition-all active:scale-90"
                        title="{{ app()->getLocale() == 'en' ? 'Download PDF' : 'Unduh PDF' }}"
                    >
                        <i data-lucide="download" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-100 rounded-[24px] p-12 text-center">
                <i data-lucide="file-text" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
                <p class="text-sm font-bold text-slate-900">{{ app()->getLocale() == 'en' ? 'No records found.' : 'Tidak ada data ditemukan.' }}</p>
            </div>
        @endforelse
    </div>
    </div>
</x-app-layout>
