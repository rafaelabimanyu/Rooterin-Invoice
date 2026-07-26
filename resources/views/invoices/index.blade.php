<x-app-layout :title="app()->getLocale() == 'en' ? 'Billing & Invoice List' : 'Daftar Penagihan & Invoice'">
    <div class="animate-fade-in-up">
        <!-- Header Section -->
        <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <span>Enterprise</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <span class="text-gold-600 truncate">{{ app()->getLocale() == 'en' ? 'Billing Ledger' : 'Buku Besar Penagihan' }}</span>
                </div>
                <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-outfit">{{ __('ui.invoices') }}</h1>
                <p class="text-[15px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'Manage all issued corporate invoices and statuses.' : 'Kelola semua invoice perusahaan yang diterbitkan beserta statusnya.' }}</p>
            </div>
            <div class="flex items-center">
                <a href="{{ route('invoices.create') }}" class="btn-premium-glass group transition-all duration-300">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>{{ __('ui.create_invoice') }}</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card p-6 mb-10">
            <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-col md:flex-row md:flex-wrap items-end gap-4 w-full">
                <!-- Search Text -->
                <div class="space-y-2 flex-1 min-w-[280px] w-full">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Search Invoice' : 'Cari Invoice' }}</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ app()->getLocale() == 'en' ? 'Invoice number, client name...' : 'Nomor invoice, nama klien...' }}" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <div class="absolute left-3.5 top-3 text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div class="space-y-2 min-w-[180px] w-full md:w-auto">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <option value="">{{ app()->getLocale() == 'en' ? 'All Status' : 'Semua Status' }}</option>
                        @foreach(['paid', 'unpaid'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ strtoupper(__('ui.' . $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Business Unit Filter -->
                <div class="space-y-2 min-w-[200px] w-full md:w-auto">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Business Unit' : 'Unit Bisnis' }}</label>
                    <select name="business_unit_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <option value="">{{ app()->getLocale() == 'en' ? 'All Units' : 'Semua Unit' }}</option>
                        @foreach($businessUnits as $unit)
                            <option value="{{ $unit->id }}" {{ request('business_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-2 w-full md:w-auto min-w-[180px] shrink-0">
                    <button type="submit" class="flex-1 btn-premium py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-center transition-all duration-300">
                        Filter
                    </button>
                    <a href="{{ route('invoices.index') }}" class="btn-secondary py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center transition-all duration-300 shrink-0">Reset</a>
                </div>
            </form>
        </div>

        @if(auth()->user()->role !== 'staff')
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Card 1 -->
            <div class="card-premium group w-full transition-all duration-300">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-gold-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Total Issued' : 'Total Diterbitkan' }}</p>
                    <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600 group-hover:bg-gold-500 group-hover:text-white transition-all duration-300 shadow-sm animate-fade-in">
                        <i data-lucide="file-text" class="w-4 h-4 transition-transform duration-300 group-hover:scale-110"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-slate-900 font-outfit">{{ $invoices->total() }}</h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">{{ app()->getLocale() == 'en' ? 'Registered in system' : 'Terdaftar dalam sistem' }}</p>
            </div>
            
            <!-- Card 2 -->
            <div class="card-premium group w-full transition-all duration-300">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.total_collected') }}</p>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 shadow-sm animate-fade-in">
                        <i data-lucide="check-circle" class="w-4 h-4 transition-transform duration-300 group-hover:scale-110"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-emerald-600 font-outfit">Rp {{ number_format(\App\Models\Payment::sum('amount'), 0, ',', '.') }}</h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">{{ app()->getLocale() == 'en' ? 'Verified transactions' : 'Transaksi terverifikasi' }}</p>
            </div>

            <!-- Card 3 -->
            <div class="card-premium group w-full transition-all duration-300">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.amount_due') }}</p>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 shadow-sm animate-fade-in">
                        <i data-lucide="clock" class="w-4 h-4 transition-transform duration-300 group-hover:scale-110"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-slate-900 font-outfit">Rp {{ number_format(\App\Models\Invoice::where('status', 'unpaid')->sum('total'), 0, ',', '.') }}</h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">{{ app()->getLocale() == 'en' ? 'Outstanding receivables' : 'Piutang belum tertagih' }}</p>
            </div>

            <!-- Card 4 -->
            <div class="card-premium group w-full transition-all duration-300">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-rose-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Overdue Count' : 'Jumlah Menunggak' }}</p>
                    <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600 group-hover:bg-rose-500 group-hover:text-white transition-all duration-300 shadow-sm animate-fade-in">
                        <i data-lucide="alert-circle" class="w-4 h-4 transition-transform duration-300 group-hover:scale-110"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-rose-600 font-outfit">{{ \App\Models\Invoice::where('status', 'unpaid')->where('due_date', '<', \Carbon\Carbon::now()->toDateString())->count() }}</h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">{{ app()->getLocale() == 'en' ? 'Action required immediately' : 'Tindakan segera diperlukan' }}</p>
            </div>
        </div>
        @endif

        <!-- Desktop List View (Floating Rows) -->
        <div class="hidden md:block overflow-x-auto pb-4">
            <div class="min-w-[1100px] space-y-4 pr-4">
                <!-- List Header -->
                <div class="grid grid-cols-12 gap-6 px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-2xl mb-2">
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Invoice Number' : 'Nomor Invoice' }}</div>
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Customer Details' : 'Rincian Pelanggan' }}</div>
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Business Unit' : 'Unit Bisnis' }}</div>
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Net Amount' : 'Nominal Bersih' }}</div>
                    <div class="col-span-1 text-center">{{ app()->getLocale() == 'en' ? 'Due Date' : 'Jatuh Tempo' }}</div>
                    <div class="col-span-1 text-center">Status</div>
                    <div class="col-span-2 text-right pr-4">{{ app()->getLocale() == 'en' ? 'Actions' : 'Aksi' }}</div>
                </div>

                @forelse($invoices as $invoice)
                    <div class="row-floating grid grid-cols-12 gap-6 items-center px-10 py-6 group transition-all duration-300">
                        <!-- INVOICE NUMBER -->
                        <div class="col-span-2 min-w-0">
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-[14px] font-bold text-slate-900 hover:text-gold-600 transition-colors duration-300 tracking-tight block truncate" title="{{ $invoice->invoice_number }}">
                                {{ $invoice->invoice_number }}
                            </a>
                        </div>

                        <!-- CUSTOMER DETAILS -->
                        <div class="col-span-2 min-w-0">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[14px] font-bold text-slate-800 truncate" title="{{ $invoice->client->nama_client }}">{{ $invoice->client->nama_client }}</span>
                                <span class="text-[12px] text-slate-400 font-medium truncate" title="{{ $invoice->client->nama_perusahaan }}">{{ $invoice->client->nama_perusahaan }}</span>
                            </div>
                        </div>

                        <!-- BUSINESS UNIT -->
                        <div class="col-span-2 min-w-0">
                            <span class="text-[12px] font-black text-slate-700 bg-slate-100/80 px-2.5 py-1 rounded-lg border border-slate-200/40 uppercase tracking-wider truncate block" title="{{ $invoice->businessUnit ? $invoice->businessUnit->name : '-' }}">
                                {{ $invoice->businessUnit ? $invoice->businessUnit->name : '-' }}
                            </span>
                        </div>

                        <!-- NET AMOUNT -->
                        <div class="col-span-2 min-w-0">
                            <span class="text-[15px] font-black text-slate-900 tracking-tight block truncate">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                        </div>

                        <!-- DUE DATE -->
                        <div class="col-span-1 text-center min-w-0">
                            <div class="flex flex-col items-center min-w-0">
                                @php
                                    $isOverdue = $invoice->due_date && $invoice->due_date->isPast() && $invoice->status !== 'paid';
                                @endphp
                                <span class="text-[13px] font-bold {{ $isOverdue ? 'text-rose-500' : 'text-slate-500' }} truncate block" title="{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}">
                                    {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}
                                </span>
                                @if($isOverdue)
                                    <span class="text-[9px] font-black text-rose-500 uppercase tracking-tighter mt-0.5 truncate block">{{ app()->getLocale() == 'en' ? 'OVERDUE' : 'TERLAMBAT' }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- STATUS -->
                        <div class="col-span-1 flex justify-center whitespace-nowrap">
                            <x-badge :status="$invoice->status" />
                        </div>

                        <!-- ACTIONS -->
                        <div class="col-span-2">
                            <div class="flex items-center justify-end gap-3.5 opacity-40 group-hover:opacity-100 transition-all duration-300 pr-2">
                                <a href="{{ route('invoices.show', $invoice) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors duration-300" title="{{ __('ui.view') }}">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <button type="button" @click.prevent="$dispatch('download-pdf', { url: '{{ route('invoices.pdf', $invoice) }}', filename: 'Invoice-{{ $invoice->invoice_number }}.pdf' })" class="p-1 text-slate-400 hover:text-gold-600 transition-colors duration-300 focus:outline-none" title="{{ app()->getLocale() == 'en' ? 'Download PDF' : 'Unduh PDF' }}">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </button>
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="p-1 text-slate-400 hover:text-amber-600 transition-colors duration-300" title="{{ __('ui.edit') }}">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                @can('delete', $invoice)
                                <form id="delete-form-{{ $invoice->id }}" action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteInvoice('delete-form-{{ $invoice->id }}')" class="p-1 text-slate-400 hover:text-rose-600 transition-colors duration-300" title="{{ app()->getLocale() == 'en' ? 'Delete' : 'Hapus' }}">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-dashed border-slate-200 rounded-[32px] p-24 text-center">
                        <div class="flex flex-col items-center max-w-sm mx-auto">
                            <div class="w-20 h-20 bg-slate-50 rounded-[24px] flex items-center justify-center mb-6">
                                <i data-lucide="file-text" class="w-10 h-10 text-slate-300"></i>
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-2">{{ __('ui.empty_data') }}</h4>
                            <p class="text-[14px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'No invoices detected in the ledger. Start by issuing a new invoice.' : 'Tidak ada invoice yang terdeteksi di buku besar. Mulailah dengan menerbitkan invoice baru.' }}</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Mobile List View -->
        <div class="md:hidden space-y-3 px-4">
            @forelse($invoices as $invoice)
                <div 
                    onclick="window.location='{{ route('invoices.show', $invoice) }}'"
                    class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all active:scale-[0.98] cursor-pointer flex flex-col gap-2 duration-300"
                >
                    <!-- First Row: Invoice Number & Status -->
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 tracking-tight truncate">{{ $invoice->invoice_number }}</span>
                        <x-badge :status="$invoice->status" class="scale-75 origin-right shrink-0" />
                    </div>
                    <!-- Second Row: Client Name & Nominal -->
                    <div class="flex items-center justify-between">
                        <span class="text-[13px] font-black text-slate-900 truncate leading-tight">{{ $invoice->client->nama_client }}</span>
                        <span class="text-[14px] font-bold text-gold-600 tracking-tight shrink-0">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                    <!-- Third Row: Business Unit -->
                    <div class="flex items-center justify-between mt-1 pt-1.5 border-t border-slate-100">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'UNIT' : 'UNIT' }}</span>
                        <span class="text-[10px] font-bold text-slate-650 bg-slate-100 px-2 py-0.5 rounded uppercase tracking-wider">{{ $invoice->businessUnit ? $invoice->businessUnit->name : '-' }}</span>
                    </div>
                    <!-- Fourth Row: Actions -->
                    <div class="flex items-center justify-end mt-2 pt-2 border-t border-slate-100 gap-2">
                        <a 
                            href="{{ route('invoices.show', $invoice) }}"
                            @click.stop=""
                            class="px-3 py-1.5 bg-slate-50 hover:bg-gold-50/50 text-slate-500 hover:text-gold-600 rounded-xl transition-all text-xs font-bold flex items-center gap-1.5 duration-300"
                        >
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            <span>{{ app()->getLocale() == 'en' ? 'View' : 'Lihat' }}</span>
                        </a>
                        <button 
                            type="button"
                            @click.stop="$dispatch('download-pdf', { url: '{{ route('invoices.pdf', $invoice) }}', filename: 'Invoice-{{ $invoice->invoice_number }}.pdf' })"
                            class="px-3 py-1.5 bg-slate-50 hover:bg-gold-50/50 text-slate-500 hover:text-gold-600 rounded-xl transition-all text-xs font-bold flex items-center gap-1.5 duration-300"
                        >
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            <span>PDF</span>
                        </button>
                        <a 
                            href="{{ route('invoices.edit', $invoice->id) }}"
                            @click.stop=""
                            class="px-3 py-1.5 bg-slate-50 hover:bg-gold-50/50 text-slate-500 hover:text-amber-600 rounded-xl transition-all text-xs font-bold flex items-center gap-1.5 duration-300"
                        >
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            <span>{{ app()->getLocale() == 'en' ? 'Edit' : 'Ubah' }}</span>
                        </a>
                        @can('delete', $invoice)
                        <form 
                            id="delete-form-mobile-{{ $invoice->id }}"
                            action="{{ route('invoices.destroy', $invoice) }}" 
                            method="POST" 
                            class="inline"
                            @click.stop=""
                        >
                            @csrf
                            @method('DELETE')
                            <button 
                                type="button"
                                onclick="confirmDeleteInvoice('delete-form-mobile-{{ $invoice->id }}')"
                                class="px-3 py-1.5 bg-slate-50 hover:bg-rose-50 text-slate-500 hover:text-rose-600 rounded-xl transition-all text-xs font-bold flex items-center gap-1.5 duration-300"
                            >
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>{{ app()->getLocale() == 'en' ? 'Delete' : 'Hapus' }}</span>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-100 rounded-[24px] p-12 text-center">
                    <i data-lucide="file-text" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
                    <p class="text-sm font-bold text-slate-900">{{ __('ui.empty_data') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
            <div class="mt-12 flex justify-end">
                <div class="bg-white/50 backdrop-blur-sm p-2 rounded-2xl border border-slate-200/50 shadow-sm">
                    {{ $invoices->links() }}
                </div>
            </div>
        @endif

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

            function confirmDeleteInvoice(formId) {
                const isEnglish = {{ app()->getLocale() == 'en' ? 'true' : 'false' }};
                const title = isEnglish ? 'Select Deletion Reason' : 'Pilih Alasan Penghapusan';
                const text = isEnglish 
                    ? 'This invoice will be soft-deleted and moved to trash.'
                    : 'Invoice ini akan dihapus sementara dan dipindahkan ke tempat sampah.';
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
    </div>
</x-app-layout>
