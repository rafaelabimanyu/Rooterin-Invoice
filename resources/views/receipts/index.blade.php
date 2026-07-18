<x-app-layout :title="app()->getLocale() == 'en' ? 'Receipt & Payment Management' : 'Manajemen Kwitansi & Pembayaran'">
    <div class="animate-fade-in-up">
        <!-- Header Section -->
        <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <span>Enterprise</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <span class="text-gold-600">{{ app()->getLocale() == 'en' ? 'Receipts' : 'Kwitansi' }}</span>
                </div>
                <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-outfit">{{ app()->getLocale() == 'en' ? 'Receipts' : 'Kwitansi' }}</h1>
                <p class="text-[15px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'Manage payment receipts for your clients' : 'Kelola kwitansi pembayaran untuk klien Anda' }}</p>
            </div>
            <div class="flex items-center">
                <a href="{{ route('receipts.create') }}" class="btn-premium-glass group transition-all duration-300">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>{{ app()->getLocale() == 'en' ? 'New Receipt' : 'Kwitansi Baru' }}</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card p-6 mb-10">
            <form action="{{ route('receipts.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 lg:gap-6 items-end">
                <!-- Search Text -->
                <div class="space-y-2 md:col-span-2 lg:col-span-6">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Search Receipt' : 'Cari Kwitansi' }}</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ app()->getLocale() == 'en' ? 'Receipt number, client name...' : 'Nomor kwitansi, nama klien...' }}" class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <div class="absolute left-3.5 top-2.5 text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Business Unit Filter -->
                <div class="space-y-2 md:col-span-1 lg:col-span-4">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Business Unit' : 'Unit Bisnis' }}</label>
                    <select name="business_unit_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <option value="">{{ app()->getLocale() == 'en' ? 'All Units' : 'Semua Unit' }}</option>
                        @foreach($businessUnits as $unit)
                            <option value="{{ $unit->id }}" {{ request('business_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-2 md:col-span-1 lg:col-span-2 w-full">
                    <button type="submit" class="flex-1 btn-premium py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-center transition-all duration-300 font-medium">
                        Filter
                    </button>
                    <a href="{{ route('receipts.index') }}" class="btn-secondary py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center transition-all duration-300 font-medium shrink-0">Reset</a>
                </div>
            </form>
        </div>

        <!-- Desktop List View (Floating Rows) -->
        <div class="hidden md:block overflow-x-auto pb-4">
            <div class="min-w-[1000px] space-y-4 pr-4">
                <!-- List Header -->
                <div class="grid grid-cols-12 gap-6 px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-2xl mb-2">
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Rec Number' : 'No. Kwitansi' }}</div>
                    <div class="col-span-3">{{ app()->getLocale() == 'en' ? 'Client Account' : 'Akun Klien' }}</div>
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Business Unit' : 'Unit Bisnis' }}</div>
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Amount' : 'Jumlah' }}</div>
                    <div class="col-span-1 text-center">{{ app()->getLocale() == 'en' ? 'Date' : 'Tanggal' }}</div>
                    <div class="col-span-1 text-center">Status</div>
                    <div class="col-span-1 text-right">{{ app()->getLocale() == 'en' ? 'Actions' : 'Aksi' }}</div>
                </div>

                @forelse($receipts as $receipt)
                    <div class="row-floating grid grid-cols-12 gap-6 items-center px-10 py-6 group transition-all duration-300">
                        <!-- REC NUMBER -->
                        <div class="col-span-2 min-w-0">
                            <a href="{{ route('receipts.show', $receipt) }}" class="text-[14px] font-bold text-slate-900 hover:text-gold-600 transition-colors duration-300 tracking-tight block truncate" title="{{ $receipt->receipt_number }}">
                                {{ $receipt->receipt_number }}
                            </a>
                        </div>

                        <!-- CLIENT ACCOUNT -->
                        <div class="col-span-3 min-w-0">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[14px] font-bold text-slate-800 truncate" title="{{ optional($receipt->client)->nama_client ?? 'Klien Tidak Ditemukan' }}">{{ optional($receipt->client)->nama_client ?? 'Klien Tidak Ditemukan' }}</span>
                                <span class="text-[12px] text-slate-400 font-medium truncate" title="{{ optional($receipt->client)->nama_perusahaan ?? '-' }}">{{ optional($receipt->client)->nama_perusahaan ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- BUSINESS UNIT -->
                        <div class="col-span-2 min-w-0">
                            <span class="text-[12px] font-black text-slate-700 bg-slate-100/80 px-2.5 py-1 rounded-lg border border-slate-200/40 uppercase tracking-wider truncate block" title="{{ $receipt->invoice && $receipt->invoice->businessUnit ? $receipt->invoice->businessUnit->name : '-' }}">
                                {{ $receipt->invoice && $receipt->invoice->businessUnit ? $receipt->invoice->businessUnit->name : '-' }}
                            </span>
                        </div>

                        <!-- AMOUNT -->
                        <div class="col-span-2 min-w-0">
                            <span class="text-[15px] font-black text-slate-900 tracking-tight block truncate">Rp {{ number_format($receipt->total, 0, ',', '.') }}</span>
                        </div>

                        <!-- DATE -->
                        <div class="col-span-1 text-center min-w-0">
                            <span class="text-[13px] font-bold text-slate-500 block truncate" title="{{ $receipt->tanggal_receipt->format('M d, Y') }}">{{ $receipt->tanggal_receipt->format('M d, Y') }}</span>
                        </div>

                        <!-- STATUS -->
                        <div class="col-span-1 flex justify-center">
                            <x-badge :status="$receipt->status" />
                        </div>

                        <!-- ACTIONS -->
                        <div class="col-span-1">
                            <div class="flex items-center justify-end gap-3 opacity-40 group-hover:opacity-100 transition-all duration-300">
                                <a href="{{ route('receipts.show', $receipt) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors duration-300" title="{{ app()->getLocale() == 'en' ? 'View Detail' : 'Lihat Detail' }}">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <button 
                                    type="button" 
                                    @click.prevent="$dispatch('download-pdf', { url: '{{ route('receipts.pdf', $receipt) }}', filename: 'Receipt-{{ $receipt->receipt_number }}.pdf' })" 
                                    class="p-1 text-slate-400 hover:text-gold-600 transition-colors duration-300 focus:outline-none" 
                                    title="{{ app()->getLocale() == 'en' ? 'Download PDF' : 'Unduh PDF' }}"
                                >
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </button>
                                @if($receipt->status !== 'invoiced')
                                <a href="{{ route('receipts.edit', $receipt->id) }}" class="p-1 text-slate-400 hover:text-amber-600 transition-colors duration-300" title="{{ app()->getLocale() == 'en' ? 'Edit' : 'Ubah' }}">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                @endif
                                <form id="delete-form-{{ $receipt->id }}" action="{{ route('receipts.destroy', $receipt) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDeleteReceipt('delete-form-{{ $receipt->id }}')" class="p-1 text-slate-400 hover:text-rose-600 transition-colors duration-300" title="{{ app()->getLocale() == 'en' ? 'Delete' : 'Hapus' }}">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
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
                            <h4 class="text-xl font-bold text-slate-900 mb-2">{{ app()->getLocale() == 'en' ? 'No Receipts Found' : 'Kwitansi Tidak Ditemukan' }}</h4>
                            <p class="text-[14px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'Start by creating a payment receipt for your business clients to track transactions.' : 'Mulai dengan membuat kwitansi pembayaran untuk klien bisnis Anda guna melacak transaksi.' }}</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Mobile View -->
        <div class="md:hidden space-y-3 px-4">
            @forelse($receipts as $receipt)
                <div 
                    onclick="window.location='{{ route('receipts.show', $receipt) }}'" 
                    class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all active:scale-[0.98] cursor-pointer flex flex-col gap-2 duration-300"
                >
                    <div class="flex-grow min-w-0">
                        <!-- First Row: Receipt Number & Status -->
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold text-slate-500 tracking-tight truncate">{{ $receipt->receipt_number }}</span>
                            <x-badge :status="$receipt->status" class="scale-75 origin-right shrink-0" />
                        </div>
                        <!-- Second Row: Client Name & Total Amount -->
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] font-black text-slate-900 truncate leading-tight">{{ optional($receipt->client)->nama_client ?? 'Klien Tidak Ditemukan' }}</span>
                            <span class="text-[14px] font-bold text-gold-600 tracking-tight shrink-0 pl-2">Rp {{ number_format($receipt->total, 0, ',', '.') }}</span>
                        </div>
                        <!-- Third Row: Business Unit -->
                        <div class="flex items-center justify-between mt-1 pt-1.5 border-t border-slate-100">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'UNIT' : 'UNIT' }}</span>
                            <span class="text-[10px] font-bold text-slate-650 bg-slate-100 px-2 py-0.5 rounded uppercase tracking-wider">{{ $receipt->invoice && $receipt->invoice->businessUnit ? $receipt->invoice->businessUnit->name : '-' }}</span>
                        </div>
                    </div>
                    
                    <!-- Action: Download PDF -->
                    <div class="flex items-center justify-end mt-2 pt-2 border-t border-slate-100 gap-2">
                        <button 
                            type="button"
                            @click.stop="$dispatch('download-pdf', { url: '{{ route('receipts.pdf', $receipt) }}', filename: 'Receipt-{{ $receipt->receipt_number }}.pdf' })"
                            class="px-3 py-1.5 bg-slate-50 hover:bg-gold-50/50 text-slate-500 hover:text-gold-600 rounded-xl transition-all text-xs font-bold flex items-center gap-1.5 duration-300"
                        >
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            <span>PDF</span>
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

    @push('scripts')
    <script>
        function submitFormWithReason(formId, reason) {
            const form = document.getElementById(formId);
            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'deletion_reason';
            reasonInput.value = reason;
            form.appendChild(reasonInput);
            form.submit();
        }

        function confirmDeleteReceipt(formId) {
            const isEnglish = {{ app()->getLocale() == 'en' ? 'true' : 'false' }};
            const title = isEnglish ? 'Select Deletion Reason' : 'Pilih Alasan Penghapusan';
            const text = isEnglish 
                ? 'This receipt will be soft-deleted and moved to trash.'
                : 'Kwitansi ini akan dihapus sementara dan dipindahkan ke tempat sampah.';
            const confirmButtonText = isEnglish ? 'Yes, delete it!' : 'Ya, hapus!';
            const cancelButtonText = isEnglish ? 'Cancel' : 'Batal';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    input: 'select',
                    inputOptions: {
                        'Salah Input': isEnglish ? 'Wrong Input' : 'Salah Input',
                        'Dibatalkan Klien': isEnglish ? 'Cancelled by Client' : 'Dibatalkan Klien',
                        'Duplikat': isEnglish ? 'Duplicate' : 'Duplikat',
                        'Lainnya': isEnglish ? 'Other' : 'Lainnya'
                    },
                    inputPlaceholder: isEnglish ? 'Select reason...' : 'Pilih alasan...',
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: cancelButtonText,
                    inputValidator: (value) => {
                        if (!value) {
                            return isEnglish ? 'You need to select a reason!' : 'Anda harus memilih alasan!';
                        }
                    },
                    customClass: {
                        confirmButton: 'px-5 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold text-xs uppercase tracking-wider text-center mr-2 transition-all duration-300',
                        cancelButton: 'px-5 py-2.5 bg-slate-500 hover:bg-slate-650 text-white rounded-xl font-bold text-xs uppercase tracking-wider text-center transition-all duration-300',
                        input: 'rounded-xl border-slate-200 text-sm font-medium mt-3 focus:border-gold-500 focus:ring-gold-500'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        let reason = result.value;
                        if (reason === 'Lainnya') {
                            Swal.fire({
                                title: isEnglish ? 'Custom Deletion Reason' : 'Alasan Penghapusan Lainnya',
                                input: 'text',
                                inputPlaceholder: isEnglish ? 'Type reason here...' : 'Tulis alasan di sini...',
                                showCancelButton: true,
                                confirmButtonText: isEnglish ? 'Confirm Delete' : 'Konfirmasi Hapus',
                                cancelButtonText: cancelButtonText,
                                inputValidator: (val) => {
                                    if (!val) {
                                        return isEnglish ? 'Reason cannot be empty!' : 'Alasan tidak boleh kosong!';
                                    }
                                },
                                customClass: {
                                    confirmButton: 'px-5 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold text-xs uppercase tracking-wider text-center mr-2 transition-all duration-300',
                                    cancelButton: 'px-5 py-2.5 bg-slate-500 hover:bg-slate-650 text-white rounded-xl font-bold text-xs uppercase tracking-wider text-center transition-all duration-300',
                                    input: 'rounded-xl border-slate-200 text-sm font-medium mt-3 focus:border-gold-500 focus:ring-gold-500'
                                },
                                buttonsStyling: false
                            }).then((textResult) => {
                                if (textResult.isConfirmed) {
                                    submitFormWithReason(formId, textResult.value);
                                }
                            });
                        } else {
                            submitFormWithReason(formId, reason);
                        }
                    }
                });
            } else {
                const reason = prompt(isEnglish ? 'Enter deletion reason:' : 'Masukkan alasan penghapusan:');
                if (reason) {
                    submitFormWithReason(formId, reason);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
