<x-app-layout :title="__('ui.business_units') . ': ' . $businessUnit->name">
    @php
        $locale = app()->getLocale();
        $isEn = $locale === 'en';
    @endphp

    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 font-jakarta">
                    <span>{{ $isEn ? 'Administration' : 'Administrasi' }}</span>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <a href="{{ route('business-units.index') }}" class="hover:text-gold-600 transition-colors">{{ __('ui.business_units') }}</a>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <span class="text-gold-600">{{ $businessUnit->name }}</span>
                </div>
                <h1 class="text-4xl font-extrabold text-slate-900 font-outfit tracking-tight">{{ $businessUnit->name }}</h1>
                <p class="text-sm text-slate-500 font-medium mt-1">
                    {{ $businessUnit->description ?: ($isEn ? 'Performance metrics and transaction log.' : 'Metrik kinerja dan log transaksi.') }}
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('business-units.pdf', $businessUnit) }}" class="btn-secondary py-3 px-5 rounded-2xl text-xs uppercase tracking-wider font-bold inline-flex items-center gap-2 bg-emerald-50 hover:bg-emerald-100/80 text-emerald-700 border-emerald-200/60">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    {{ $isEn ? 'Print PDF' : 'Cetak Laporan' }}
                </a>
                <a href="{{ route('business-units.edit', $businessUnit) }}" class="btn-secondary py-3 px-5 rounded-2xl text-xs uppercase tracking-wider font-bold inline-flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    {{ __('ui.edit') }}
                </a>
                <a href="{{ route('business-units.index') }}" class="btn-secondary py-3 px-5 rounded-2xl text-xs uppercase tracking-wider font-bold inline-flex items-center justify-center">
                    {{ $isEn ? 'Back to List' : 'Kembali' }}
                </a>
            </div>
        </div>

        <!-- Stats Grid (Glassmorphism Metric Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- 1. Total Billed -->
            <div class="glass-card relative overflow-hidden p-6 border-slate-200/60 transition-all duration-300 hover:shadow-lg">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-gold-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.total_billing') }}</p>
                    <div class="w-8 h-8 rounded-xl bg-gold-50 flex items-center justify-center text-gold-650">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-900 font-outfit tracking-tight">
                    Rp {{ number_format($stats['total_billed'], 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-slate-450 font-bold mt-2 uppercase tracking-wide">
                    {{ $stats['total_invoices_count'] }} {{ $isEn ? 'Invoices Issued' : 'Invoice Diterbitkan' }}
                </p>
            </div>

            <!-- 2. Realized Revenue -->
            <div class="glass-card relative overflow-hidden p-6 border-slate-200/60 transition-all duration-300 hover:shadow-lg">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.total_collected') }}</p>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-650">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-emerald-650 font-outfit tracking-tight">
                    Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-slate-450 font-bold mt-2 uppercase tracking-wide">
                    {{ $stats['paid_invoices_count'] }} {{ $isEn ? 'Settled Invoices' : 'Faktur Selesai' }}
                </p>
            </div>

            <!-- 3. Outstanding Balance -->
            <div class="glass-card relative overflow-hidden p-6 border-slate-200/60 transition-all duration-300 hover:shadow-lg">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.outstanding') }}</p>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-650">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-900 font-outfit tracking-tight">
                    Rp {{ number_format($stats['total_outstanding'], 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-3 mt-2 text-[9px] text-slate-400 font-black uppercase">
                    <span class="text-amber-600">Pending: Rp {{ number_format($stats['pending_outstanding'], 0, ',', '.') }}</span>
                    <span class="text-rose-500">Overdue: Rp {{ number_format($stats['overdue_outstanding'], 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- 4. Collection Rate -->
            <div class="glass-card relative overflow-hidden p-6 border-slate-200/60 transition-all duration-300 hover:shadow-lg">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.collection_rate') }}</p>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-650">
                        <i data-lucide="percent" class="w-4 h-4"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-indigo-600 font-outfit tracking-tight">
                    {{ number_format($stats['collection_rate'], 1, ',', '.') }}%
                </h3>
                <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $stats['collection_rate'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Middle Section: Trends & Client Contributions -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
            <!-- 6-Month Cash Flow Trend -->
            <div class="glass-card border-slate-200/60 p-6 lg:col-span-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 font-outfit">{{ $isEn ? 'Monthly Inflow Telemetry' : 'Telemetri Aliran Bulanan' }}</h3>
                            <p class="text-xs text-slate-450 font-medium">{{ $isEn ? 'Comparison of paid revenue versus outstanding balance.' : 'Perbandingan pendapatan terbayar vs piutang berjalan.' }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-sm bg-gold-500 block"></span>
                                <span>{{ $isEn ? 'Revenue' : 'Pendapatan' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-sm bg-amber-500 block"></span>
                                <span>{{ $isEn ? 'Receivables' : 'Piutang' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trend Chart Container -->
                    <div class="overflow-x-auto w-full scrollbar-thin pb-2">
                        <div class="relative flex items-end justify-between h-48 w-full min-w-[500px] md:min-w-0 border-b border-slate-100 pb-2 mt-8">
                            <!-- Y-Axis Gridlines -->
                            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-2">
                                <div class="w-full border-t border-slate-100/70"></div>
                                <div class="w-full border-t border-slate-100/70"></div>
                                <div class="w-full border-t border-slate-100/70"></div>
                                <div class="w-full border-t border-slate-100/70"></div>
                            </div>

                            @php
                                $maxVal = collect($monthlyTrend)->max(fn($t) => max($t['revenue'], $t['receivables'])) ?: 1;
                                $maxVal = $maxVal <= 0 ? 1 : $maxVal;

                                function formatChartShortAmount($amount) {
                                    if ($amount >= 1000000000) {
                                        return 'Rp ' . number_format($amount / 1000000000, 1, ',', '.') . 'B';
                                    } elseif ($amount >= 1000000) {
                                        return 'Rp ' . number_format($amount / 1000000, 1, ',', '.') . 'M';
                                    } elseif ($amount >= 1000) {
                                        return 'Rp ' . number_format($amount / 1000, 1, ',', '.') . 'K';
                                    } else {
                                        return 'Rp ' . number_format($amount, 0, ',', '.');
                                    }
                                }
                            @endphp

                            @forelse($monthlyTrend as $trend)
                                @php
                                    $revenueHeight = ($trend['revenue'] / $maxVal) * 100;
                                    $receivablesHeight = ($trend['receivables'] / $maxVal) * 100;
                                @endphp
                                <div class="flex flex-col items-center flex-1 h-full justify-end z-10">
                                    <div class="flex items-end gap-1.5 h-full w-full justify-center px-1">
                                        <!-- Revenue Bar -->
                                        <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-gold-600 to-gold-500 hover:from-gold-700 hover:to-gold-600 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: {{ $revenueHeight }}%">
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                                Rp {{ number_format($trend['revenue'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <!-- Receivables Bar -->
                                        <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-amber-500 to-amber-450 hover:from-amber-600 hover:to-amber-500 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: {{ $receivablesHeight }}%">
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                                Rp {{ number_format($trend['receivables'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-black text-slate-400 mt-2 tracking-wider uppercase">{{ $trend['month_label'] }}</span>
                                </div>
                            @empty
                                <div class="flex items-center justify-center w-full h-full text-slate-400 text-xs italic">
                                    {{ $isEn ? 'No metrics available' : 'Tidak ada metrik tersedia' }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Clients Contribution -->
            <div class="glass-card border-slate-200/60 p-6 lg:col-span-4 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 font-outfit mb-1">{{ $isEn ? 'Top Clients' : 'Klien Utama' }}</h3>
                    <p class="text-xs text-slate-450 font-medium mb-6">{{ $isEn ? 'Contributors by paid revenue in this unit.' : 'Kontributor berdasarkan nominal lunas di unit ini.' }}</p>

                    <div class="space-y-4">
                        @forelse($topClients as $index => $client)
                            <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/40 hover:bg-slate-100/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-lg bg-gold-50 flex items-center justify-center text-[10px] font-black text-gold-600 font-outfit">
                                        #{{ $index + 1 }}
                                    </span>
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 block truncate max-w-[120px]">{{ $client->nama_client }}</span>
                                        <span class="text-[9px] text-slate-400 font-bold block uppercase truncate max-w-[120px]">{{ $client->nama_perusahaan ?: '-' }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-black text-slate-800 block">
                                        Rp {{ number_format($client->invoices_sum_total ?? 0, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[9px] text-slate-400 font-medium block">
                                        {{ $isEn ? 'Paid' : 'Lunas' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center text-slate-400 text-xs italic">
                                {{ $isEn ? 'No client contributors' : 'Tidak ada kontributor klien' }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices List Card -->
        <div class="glass-card border-slate-200/60 p-6">
            <h3 class="text-lg font-bold text-slate-900 font-outfit mb-1">{{ $isEn ? 'Invoice Transactions Ledger' : 'Ledger Transaksi Invoice' }}</h3>
            <p class="text-xs text-slate-450 font-medium mb-6">{{ $isEn ? 'Listing of all invoice items assigned to this unit.' : 'Daftar semua entri invoice yang diunggah ke unit ini.' }}</p>

            <!-- Desktop List View -->
            <div class="hidden md:block space-y-3">
                <!-- Headers -->
                <div class="grid grid-cols-12 gap-8 px-6 py-3 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-xl mb-1">
                    <div class="col-span-2">{{ $isEn ? 'Invoice Number' : 'Nomor Invoice' }}</div>
                    <div class="col-span-3">{{ $isEn ? 'Customer Details' : 'Rincian Pelanggan' }}</div>
                    <div class="col-span-2">{{ $isEn ? 'Total Amount' : 'Nominal Total' }}</div>
                    <div class="col-span-2">{{ $isEn ? 'Due Date' : 'Jatuh Tempo' }}</div>
                    <div class="col-span-2 text-center">Status</div>
                    <div class="col-span-1 text-right">{{ $isEn ? 'Actions' : 'Aksi' }}</div>
                </div>

                <!-- Rows -->
                @forelse($invoices as $invoice)
                    <div class="row-floating grid grid-cols-12 gap-8 items-center px-6 py-4.5 group hover:bg-slate-50/40">
                        <!-- Invoice Number -->
                        <div class="col-span-2">
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-[13px] font-bold text-slate-900 hover:text-gold-600 transition-colors tracking-tight">
                                {{ $invoice->invoice_number }}
                            </a>
                        </div>

                        <!-- Customer details -->
                        <div class="col-span-3">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold text-slate-800">{{ $invoice->client->nama_client }}</span>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $invoice->client->nama_perusahaan ?: '-' }}</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="col-span-2">
                            <span class="text-[14px] font-black text-slate-900 tracking-tight">
                                Rp {{ number_format($invoice->total, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Due Date -->
                        <div class="col-span-2">
                            @php
                                $isOverdue = $invoice->due_date && $invoice->due_date->isPast() && $invoice->status !== 'paid';
                            @endphp
                            <span class="text-[12px] font-bold {{ $isOverdue ? 'text-rose-500' : 'text-slate-500' }}">
                                {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}
                            </span>
                        </div>

                        <!-- Status Badge -->
                        <div class="col-span-2 flex justify-center">
                            <x-badge :status="$invoice->status" />
                        </div>

                        <!-- Actions -->
                        <div class="col-span-1">
                            <div class="flex items-center justify-end gap-3 opacity-40 group-hover:opacity-100 transition-all duration-300">
                                <a href="{{ route('invoices.show', $invoice) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors" title="{{ __('ui.view') }}">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="p-1 text-slate-400 hover:text-amber-600 transition-colors" title="{{ __('ui.edit') }}">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-slate-50/50 rounded-2xl py-12 text-center text-slate-400 text-sm font-medium border border-dashed border-slate-200">
                        {{ $isEn ? 'No transaction records found' : 'Tidak ditemukan rekaman transaksi' }}
                    </div>
                @endforelse
            </div>

            <!-- Mobile List View -->
            <div class="md:hidden space-y-3">
                @forelse($invoices as $invoice)
                    <div onclick="window.location='{{ route('invoices.show', $invoice) }}'" class="bg-white border border-slate-200/60 rounded-xl p-4 shadow-sm active:scale-[0.98] transition-all cursor-pointer space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500">{{ $invoice->invoice_number }}</span>
                            <x-badge :status="$invoice->status" class="scale-75 origin-right" />
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] font-black text-slate-900 truncate max-w-[150px]">{{ $invoice->client->nama_client }}</span>
                            <span class="text-[13px] font-bold text-gold-600">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="bg-slate-50 rounded-xl py-8 text-center text-slate-400 text-xs italic">
                        {{ $isEn ? 'No transactions' : 'Tidak ada transaksi' }}
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($invoices->hasPages())
                <div class="mt-6 flex justify-center">
                    <div class="bg-white/50 backdrop-blur-sm p-1.5 rounded-xl border border-slate-200/50 shadow-sm">
                        {{ $invoices->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
