<x-app-layout :title="__('ui.ledger_title')">
    <div class="animate-fade-in-up">
        <!-- Header Section -->
        <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <span>Enterprise</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <span class="text-gold-600 truncate">{{ __('ui.ledger_title') }}</span>
                </div>
                <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-outfit">{{ __('ui.ledger_title') }}</h1>
                <p class="text-[15px] text-slate-400 font-medium">{{ __('ui.ledger_subtitle') }}</p>
            </div>
            <div class="flex items-center">
                <div class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 border border-amber-200/60 rounded-xl text-amber-700 text-xs font-bold uppercase tracking-wider shadow-sm">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <span>{{ app()->getLocale() == 'en' ? 'Read Only' : 'Hanya Baca' }}</span>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card p-6 mb-10">
            <form action="{{ route('ledger.index') }}" method="GET" class="flex flex-col md:flex-row md:flex-wrap items-end gap-4 w-full">
                <!-- Search Text -->
                <div class="space-y-2 flex-1 min-w-[280px] w-full">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Search Ledger' : 'Cari Buku Besar' }}</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ app()->getLocale() == 'en' ? 'Search invoice number or client...' : 'Cari nomor invoice atau klien...' }}" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
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
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('ui.draft') }}</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>{{ __('ui.unpaid') }}</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('ui.paid') }}</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>{{ __('ui.overdue') }}</option>
                    </select>
                </div>
                
                <!-- Business Unit Filter -->
                <div class="space-y-2 min-w-[200px] w-full md:w-auto">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Business Unit' : 'Unit Bisnis' }}</label>
                    <select name="business_unit_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <option value="">{{ app()->getLocale() == 'en' ? 'All Units' : 'Semua Unit' }}</option>
                        @foreach($businessUnits as $bu)
                            <option value="{{ $bu->id }}" {{ request('business_unit_id') == $bu->id ? 'selected' : '' }}>{{ $bu->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Document Type Filter -->
                <div class="space-y-2 min-w-[200px] w-full md:w-auto">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Document Type' : 'Jenis Dokumen' }}</label>
                    <select name="doc_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <option value="">{{ app()->getLocale() == 'en' ? 'All Types' : 'Semua Jenis' }}</option>
                        <option value="invoice" {{ request('doc_type') == 'invoice' ? 'selected' : '' }}>{{ __('ui.invoice_doc') }} {{ app()->getLocale() == 'en' ? 'Only' : 'Saja' }}</option>
                        <option value="receipt" {{ request('doc_type') == 'receipt' ? 'selected' : '' }}>{{ __('ui.receipt_doc') }} {{ app()->getLocale() == 'en' ? 'Linked' : 'Terhubung' }}</option>
                    </select>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-2 w-full md:w-auto min-w-[180px] shrink-0">
                    <button type="submit" class="flex-1 btn-premium py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-center transition-all duration-300">
                        Filter
                    </button>
                    <a href="{{ route('ledger.index') }}" class="btn-secondary py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center transition-all duration-300 shrink-0">Reset</a>
                </div>
            </form>
        </div>

        <!-- Desktop List View (Floating Rows) -->
        <div class="hidden md:block overflow-x-auto pb-4">
            <div class="min-w-[1100px] space-y-4 pr-4">
                <!-- List Header -->
                <div class="grid grid-cols-12 gap-6 px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-2xl mb-2">
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Doc Type' : 'Jenis Dokumen' }}</div>
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'References' : 'Nomor Dokumen' }}</div>
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Customer Details' : 'Rincian Pelanggan' }}</div>
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Business Unit' : 'Unit Bisnis' }}</div>
                    <div class="col-span-2">{{ app()->getLocale() == 'en' ? 'Amount / Date' : 'Nominal / Tanggal' }}</div>
                    <div class="col-span-1 text-center">Status</div>
                    <div class="col-span-1 text-right pr-4">{{ app()->getLocale() == 'en' ? 'Actions' : 'Aksi' }}</div>
                </div>

                @forelse($invoices as $invoice)
                    <div class="row-floating grid grid-cols-12 gap-6 items-center px-10 py-6 group transition-all duration-300">
                        <!-- DOCUMENT TYPE -->
                        <div class="col-span-2 min-w-0">
                            @if($invoice->receipt)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/60 rounded-lg text-[10px] font-bold uppercase tracking-wide">
                                    <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                                    <span>{{ __('ui.receipt_doc') }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200/60 rounded-lg text-[10px] font-bold uppercase tracking-wide">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                    <span>{{ __('ui.invoice_doc') }}</span>
                                </span>
                            @endif
                        </div>

                        <!-- REFERENCES -->
                        <div class="col-span-2 min-w-0">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[14px] font-bold text-slate-900 truncate" title="{{ $invoice->invoice_number }}">
                                    {{ $invoice->invoice_number }}
                                </span>
                                @if($invoice->receipt)
                                    <span class="text-[12px] text-emerald-700 font-semibold truncate" title="{{ $invoice->receipt->receipt_number }}">
                                        {{ $invoice->receipt->receipt_number }}
                                    </span>
                                @else
                                    <span class="text-[12px] text-slate-400 italic truncate">
                                        {{ __('ui.no_linked_receipt') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- CUSTOMER DETAILS -->
                        <div class="col-span-2 min-w-0">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[14px] font-bold text-slate-800 truncate" title="{{ optional($invoice->client)->nama_client ?? '-' }}">
                                    {{ optional($invoice->client)->nama_client ?? '-' }}
                                </span>
                                @if(optional($invoice->client)->nama_perusahaan)
                                    <span class="text-[12px] text-slate-400 font-medium truncate" title="{{ $invoice->client->nama_perusahaan }}">
                                        {{ $invoice->client->nama_perusahaan }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- BUSINESS UNIT -->
                        <div class="col-span-2 min-w-0">
                            <span class="text-[12px] font-black text-slate-700 bg-slate-100/80 px-2.5 py-1 rounded-lg border border-slate-200/40 uppercase tracking-wider truncate block" title="{{ optional($invoice->businessUnit)->name ?? '-' }}">
                                {{ optional($invoice->businessUnit)->name ?? '-' }}
                            </span>
                        </div>

                        <!-- AMOUNT / DATE -->
                        <div class="col-span-2 min-w-0">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[15px] font-black text-slate-900 tracking-tight truncate">
                                    {{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($invoice->total, 0, ',', '.') }}
                                </span>
                                <span class="text-[12px] text-slate-400 font-medium truncate">
                                    {{ $invoice->due_date?->format(\App\Models\Setting::get('date_format', 'd M Y')) ?? $invoice->created_at?->format(\App\Models\Setting::get('date_format', 'd M Y')) ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- STATUS -->
                        <div class="col-span-1 flex justify-center whitespace-nowrap">
                            <x-badge :status="$invoice->status" />
                        </div>

                        <!-- ACTIONS -->
                        <div class="col-span-1">
                            <div class="flex items-center justify-end gap-3.5 opacity-40 group-hover:opacity-100 transition-all duration-300 pr-2">
                                <a href="{{ route('invoices.show', $invoice) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors duration-300" title="{{ __('ui.view_invoice') }}">
                                    <i data-lucide="file-text" class="w-4.5 h-4.5"></i>
                                </a>
                                @if($invoice->receipt)
                                    <a href="{{ route('receipts.show', $invoice->receipt) }}" class="p-1 text-slate-400 hover:text-emerald-600 transition-colors duration-300" title="{{ __('ui.view_receipt') }}">
                                        <i data-lucide="receipt" class="w-4.5 h-4.5"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-dashed border-slate-200 rounded-[32px] p-24 text-center">
                        <div class="flex flex-col items-center max-w-sm mx-auto">
                            <div class="w-20 h-20 bg-slate-50 rounded-[24px] flex items-center justify-center mb-6">
                                <i data-lucide="book-open" class="w-10 h-10 text-slate-300"></i>
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-2">{{ __('ui.empty_data') }}</h4>
                            <p class="text-[14px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'No transactions detected in the ledger.' : 'Tidak ada transaksi yang terdeteksi di buku besar.' }}</p>
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
                    <!-- First Row: Doc Type & Status -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            @if($invoice->receipt)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200/40 rounded text-[9px] font-bold uppercase tracking-wide">
                                    <i data-lucide="file-check" class="w-2.5 h-2.5"></i>
                                    <span>{{ __('ui.receipt_doc') }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200/40 rounded text-[9px] font-bold uppercase tracking-wide">
                                    <i data-lucide="file-text" class="w-2.5 h-2.5"></i>
                                    <span>{{ __('ui.invoice_doc') }}</span>
                                </span>
                            @endif
                        </div>
                        <x-badge :status="$invoice->status" class="scale-75 origin-right shrink-0" />
                    </div>

                    <!-- Second Row: Document Number & Date -->
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 tracking-tight truncate">
                            {{ $invoice->invoice_number }} 
                            @if($invoice->receipt)
                                <span class="text-slate-300 mx-1">|</span> <span class="text-emerald-700 font-semibold">{{ $invoice->receipt->receipt_number }}</span>
                            @endif
                        </span>
                        <span class="text-[11px] text-slate-400 font-medium shrink-0">
                            {{ $invoice->due_date?->format(\App\Models\Setting::get('date_format', 'd M Y')) ?? $invoice->created_at?->format(\App\Models\Setting::get('date_format', 'd M Y')) ?? '-' }}
                        </span>
                    </div>

                    <!-- Third Row: Client Details & Total -->
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col min-w-0">
                            <span class="text-[13px] font-black text-slate-900 truncate leading-tight">{{ optional($invoice->client)->nama_client ?? '-' }}</span>
                            @if(optional($invoice->client)->nama_perusahaan)
                                <span class="text-[10px] text-slate-400 truncate mt-0.5">{{ $invoice->client->nama_perusahaan }}</span>
                            @endif
                        </div>
                        <span class="text-[14px] font-bold text-slate-900 tracking-tight shrink-0 pl-2">
                            Rp {{ number_format($invoice->total, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Fourth Row: Business Unit -->
                    <div class="flex items-center justify-between mt-1 pt-1.5 border-t border-slate-100">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'UNIT' : 'UNIT' }}</span>
                        <span class="text-[10px] font-bold text-slate-650 bg-slate-100 px-2 py-0.5 rounded uppercase tracking-wider">{{ optional($invoice->businessUnit)->name ?? '-' }}</span>
                    </div>

                    <!-- Fifth Row: Mobile Actions -->
                    <div class="flex items-center justify-end mt-2 pt-2 border-t border-slate-100 gap-2">
                        <a 
                            href="{{ route('invoices.show', $invoice) }}"
                            @click.stop=""
                            class="px-3 py-1.5 bg-slate-50 hover:bg-gold-50/50 text-slate-500 hover:text-gold-600 rounded-xl transition-all text-xs font-bold flex items-center gap-1.5 duration-300"
                        >
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                            <span>{{ app()->getLocale() == 'en' ? 'Invoice' : 'Invoice' }}</span>
                        </a>
                        @if($invoice->receipt)
                        <a 
                            href="{{ route('receipts.show', $invoice->receipt) }}"
                            @click.stop=""
                            class="px-3 py-1.5 bg-slate-50 hover:bg-gold-50/50 text-slate-500 hover:text-emerald-600 rounded-xl transition-all text-xs font-bold flex items-center gap-1.5 duration-300"
                        >
                            <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                            <span>{{ app()->getLocale() == 'en' ? 'Receipt' : 'Kwitansi' }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-100 rounded-[24px] p-12 text-center">
                    <i data-lucide="book-open" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
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

        <!-- Summary Footer -->
        <div class="mt-4 text-xs text-slate-400 text-right pr-4">
            {{ app()->getLocale() == 'en' ? 'Showing' : 'Menampilkan' }}
            {{ $invoices->firstItem() ?? 0 }}–{{ $invoices->lastItem() ?? 0 }}
            {{ app()->getLocale() == 'en' ? 'of' : 'dari' }}
            {{ $invoices->total() }}
            {{ app()->getLocale() == 'en' ? 'records' : 'data' }}
        </div>
    </div>
</x-app-layout>
