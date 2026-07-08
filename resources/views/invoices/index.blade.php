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
            <a href="{{ route('invoices.create') }}" class="btn-premium-glass group">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>{{ __('ui.create_invoice') }}</span>
            </a>
        </div>
    </div>

    @if(auth()->user()->role !== 'staff')
    <!-- Summary Cards -->
    <div class="flex overflow-x-auto snap-x gap-3 pb-3 no-scrollbar md:grid md:grid-cols-4 md:gap-8 md:pb-0 mb-12">
        <div class="card-premium group min-w-[85%] snap-center md:min-w-0">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-gold-500/80"></div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Total Issued' : 'Total Diterbitkan' }}</p>
                <i data-lucide="file-text" class="w-4 h-4 text-slate-300 group-hover:text-gold-500 transition-colors"></i>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 font-outfit">{{ $invoices->total() }}</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">{{ app()->getLocale() == 'en' ? 'Registered in system' : 'Terdaftar dalam sistem' }}</p>
        </div>
        
        <div class="card-premium group min-w-[85%] snap-center md:min-w-0">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500/80"></div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.total_collected') }}</p>
                <i data-lucide="check-circle" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 transition-colors"></i>
            </div>
            <h3 class="text-3xl font-bold text-emerald-600 font-outfit">Rp {{ number_format(\App\Models\Payment::sum('amount'), 0, ',', '.') }}</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">{{ app()->getLocale() == 'en' ? 'Verified transactions' : 'Transaksi terverifikasi' }}</p>
        </div>

        <div class="card-premium group min-w-[85%] snap-center md:min-w-0">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500/80"></div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.amount_due') }}</p>
                <i data-lucide="clock" class="w-4 h-4 text-slate-300 group-hover:text-amber-500 transition-colors"></i>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 font-outfit">Rp {{ number_format(\App\Models\Invoice::whereIn('status', ['sent', 'dp', 'pending', 'overdue'])->sum('total'), 0, ',', '.') }}</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">{{ app()->getLocale() == 'en' ? 'Outstanding receivables' : 'Piutang belum tertagih' }}</p>
        </div>

        <div class="card-premium group min-w-[85%] snap-center md:min-w-0">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-rose-500/80"></div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Overdue Count' : 'Jumlah Menunggak' }}</p>
                <i data-lucide="alert-circle" class="w-4 h-4 text-slate-300 group-hover:text-rose-500 transition-colors"></i>
            </div>
            <h3 class="text-3xl font-bold text-rose-600 font-outfit">{{ \App\Models\Invoice::where('status', 'overdue')->count() }}</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">{{ app()->getLocale() == 'en' ? 'Action required immediately' : 'Tindakan segera diperlukan' }}</p>
        </div>
    </div>
    @endif

    <!-- Desktop List View (Floating Rows) -->
    <div class="hidden md:block space-y-4">
        <!-- List Header -->
        <div class="grid grid-cols-12 gap-8 px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-2xl mb-2">
            <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Invoice Number' : 'Nomor Invoice' }}</div>
            <div class="col-span-3">{{ app()->getLocale() == 'en' ? 'Customer Details' : 'Rincian Pelanggan' }}</div>
            <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Net Amount' : 'Nominal Bersih' }}</div>
            <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Due Date' : 'Jatuh Tempo' }}</div>
            <div class="col-span-1 text-center">Status</div>
            <div class="col-span-2 text-right">{{ app()->getLocale() == 'en' ? 'Actions' : 'Aksi' }}</div>
        </div>

        @forelse($invoices as $invoice)
            <div class="row-floating grid grid-cols-12 gap-8 items-center px-10 py-6 group">
                <!-- INVOICE NUMBER -->
                <div class="col-span-2">
                    <a href="{{ route('invoices.show', $invoice) }}" class="text-[14px] font-bold text-slate-900 hover:text-gold-600 transition-colors tracking-tight">
                        {{ $invoice->invoice_number }}
                    </a>
                </div>

                <!-- CUSTOMER DETAILS -->
                <div class="col-span-3">
                    <div class="flex flex-col">
                        <span class="text-[14px] font-bold text-slate-800">{{ $invoice->client->nama_client }}</span>
                        <span class="text-[12px] text-slate-400 font-medium">{{ $invoice->client->nama_perusahaan }}</span>
                    </div>
                </div>

                <!-- NET AMOUNT -->
                <div class="col-span-2">
                    <span class="text-[15px] font-black text-slate-900 tracking-tight">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>

                <!-- DUE DATE -->
                <div class="col-span-2">
                    <div class="flex flex-col">
                        @php
                            $isOverdue = $invoice->due_date->isPast() && $invoice->status !== 'paid';
                        @endphp
                        <span class="text-[13px] font-bold {{ $isOverdue ? 'text-rose-500' : 'text-slate-500' }}">
                            {{ $invoice->due_date->format('M d, Y') }}
                        </span>
                        @if($isOverdue)
                            <span class="text-[9px] font-black text-rose-500 uppercase tracking-tighter mt-0.5">{{ app()->getLocale() == 'en' ? 'OVERDUE' : 'TERLAMBAT' }}</span>
                        @endif
                    </div>
                </div>

                <!-- STATUS -->
                <div class="col-span-1 flex justify-center">
                    <x-badge :status="$invoice->status" />
                </div>

                <!-- ACTIONS -->
                <div class="col-span-2">
                    <div class="flex items-center justify-end gap-4 opacity-40 group-hover:opacity-100 transition-all duration-300">
                        <a href="{{ route('invoices.show', $invoice) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors" title="{{ __('ui.view') }}">
                            <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                        </a>
                        <a href="{{ route('invoices.edit', $invoice) }}" class="p-1 text-slate-400 hover:text-amber-600 transition-colors" title="{{ __('ui.edit') }}">
                            <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                        </a>
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

    <!-- Mobile List View -->
    <div class="md:hidden space-y-3 px-4">
        @forelse($invoices as $invoice)
            <div 
                onclick="window.location='{{ route('invoices.show', $invoice) }}'"
                class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all active:scale-[0.98] cursor-pointer flex flex-col gap-2"
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
        <div class="mt-12 flex justify-center">
            <div class="bg-white/50 backdrop-blur-sm p-2 rounded-2xl border border-slate-200/50 shadow-sm">
                {{ $invoices->links() }}
            </div>
        </div>
    @endif
    </div>
</x-app-layout>
