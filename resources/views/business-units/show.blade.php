<x-app-layout :title="__('ui.business_units') . ': ' . $businessUnit->name">
    @php
        $locale = app()->getLocale();
        $isEn = $locale === 'en';
    @endphp

    <div class="animate-fade-in-up">
        <!-- Header Section -->
        <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <!-- Label & Breadcrumb -->
                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <span>{{ $isEn ? 'Enterprise' : 'Perusahaan' }}</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <a href="{{ route('business-units.index') }}" class="hover:text-gold-600 transition-colors">{{ __('ui.business_units') }}</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="text-gold-600 truncate">{{ $businessUnit->name }}</span>
                </div>
                <!-- Main Title (styled with gold/slate gradient in app.css) -->
                <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-outfit">{{ $businessUnit->name }}</h1>
                <p class="text-[15px] text-slate-400 font-medium">
                    {{ $businessUnit->description ?: ($isEn ? 'Performance metrics and transaction log.' : 'Metrik kinerja dan log transaksi.') }}
                </p>
            </div>
            <!-- Header Actions -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('business-units.pdf', $businessUnit) }}" class="btn-premium">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>{{ $isEn ? 'Print PDF' : 'Cetak Laporan' }}</span>
                </a>
                <a href="{{ route('business-units.edit', $businessUnit) }}" class="btn-secondary">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    <span>{{ __('ui.edit') }}</span>
                </a>
                <a href="{{ route('business-units.index') }}" class="btn-secondary">
                    {{ $isEn ? 'Back to List' : 'Kembali' }}
                </a>
            </div>
        </div>

        <!-- Stats Grid (6 KPI Metrics aligned with card-premium layout) -->
        @php
            $feePercentage = $businessUnit->fee_percentage ?? 0.00;
            $feeNominal = round(($stats['total_revenue'] * $feePercentage) / 100, 2);
            $netRevenue = round($stats['total_revenue'] - $feeNominal, 2);
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-12">
            <!-- 1. Total Billed -->
            <div class="card-premium">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-gold-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.total_billing') }}</p>
                    <i data-lucide="receipt" class="w-4 h-4 text-slate-300 group-hover:text-gold-500 transition-colors"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 font-outfit tracking-tight">
                    Rp {{ number_format($stats['total_billed'], 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wide">
                    {{ $stats['total_invoices_count'] }} {{ $isEn ? 'Issued' : 'Diterbitkan' }}
                </p>
            </div>

            <!-- 2. Gross Revenue (Omset Kotor) -->
            <div class="card-premium">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $isEn ? 'Gross Revenue' : 'Omset Kotor' }}</p>
                    <i data-lucide="trending-up" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 transition-colors"></i>
                </div>
                <h3 class="text-2xl font-bold text-emerald-600 font-outfit tracking-tight">
                    Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wide">
                    {{ $stats['paid_invoices_count'] }} {{ $isEn ? 'Paid' : 'Lunas' }}
                </p>
            </div>

            <!-- 3. Management Fee -->
            <div class="card-premium">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $isEn ? 'Management Fee' : 'Fee Manajemen' }}</p>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-amber-50 text-amber-700 border border-amber-100 shadow-sm shrink-0">
                        {{ number_format($feePercentage, 1, ',', '.') }}%
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 font-outfit tracking-tight">
                    Rp {{ number_format($feeNominal, 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wide">
                    {{ $isEn ? 'Fee Share Nominal' : 'Nominal Fee' }}
                </p>
            </div>

            <!-- 4. Net Revenue (Pendapatan Bersih) -->
            <div class="card-premium">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $isEn ? 'Net Revenue' : 'Pendapatan Bersih' }}</p>
                    <i data-lucide="wallet" class="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                </div>
                <h3 class="text-2xl font-bold text-blue-600 font-outfit tracking-tight">
                    Rp {{ number_format($netRevenue, 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wide">
                    {{ $isEn ? 'Gross minus fee' : 'Pendapatan setelah fee' }}
                </p>
            </div>

            <!-- 5. Outstanding Balance (Sisa Piutang) -->
            <div class="card-premium">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-rose-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.outstanding') }}</p>
                    <i data-lucide="clock" class="w-4 h-4 text-slate-300 group-hover:text-rose-500 transition-colors"></i>
                </div>
                <h3 class="text-2xl font-bold text-rose-600 font-outfit tracking-tight">
                    Rp {{ number_format($stats['total_outstanding'], 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wide">
                    {{ $isEn ? 'Receivables' : 'Piutang berjalan' }}
                </p>
            </div>

            <!-- 6. Collection Rate -->
            <div class="card-premium">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('ui.collection_rate') }}</p>
                    <i data-lucide="activity" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                </div>
                <h3 class="text-2xl font-bold text-indigo-600 font-outfit tracking-tight">
                    {{ number_format($stats['collection_rate'], 1, ',', '.') }}%
                </h3>
                <div class="w-full bg-slate-100 rounded-full h-1 mt-2.5 overflow-hidden">
                    <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $stats['collection_rate'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Middle Section: Trends & Client Contributions -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            <!-- 6-Month Cash Flow Trend -->
            <div class="glass-card p-6 lg:col-span-8 flex flex-col justify-between">
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-[#0F2A44] font-outfit">{{ $isEn ? 'Monthly Inflow Telemetry' : 'Telemetri Aliran Bulanan' }}</h3>
                            <p class="text-xs text-slate-450 font-medium">{{ $isEn ? 'Comparison of paid revenue versus outstanding balance.' : 'Perbandingan pendapatan terbayar vs piutang berjalan.' }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-sm bg-[#1FAF5A] block"></span>
                                <span>{{ $isEn ? 'Revenue' : 'Pendapatan' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-sm bg-[#0F2A44] block"></span>
                                <span>{{ $isEn ? 'Receivables' : 'Piutang' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trend Chart Container (Responsive & Styled) -->
                    <div class="overflow-x-auto w-full scrollbar-none pb-1 mt-6">
                        <div class="relative flex items-end justify-between h-52 w-full min-w-[450px] sm:min-w-0 border-b border-slate-100 pb-2">
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
                            @endphp

                            @forelse($monthlyTrend as $trend)
                                @php
                                    $revenueHeight = ($trend['revenue'] / $maxVal) * 100;
                                    $receivablesHeight = ($trend['receivables'] / $maxVal) * 100;
                                @endphp
                                <div class="flex flex-col items-center flex-1 h-full justify-end z-10 px-1">
                                    <div class="flex items-end gap-1.5 h-full w-full justify-center">
                                        <!-- Revenue Bar (Green accent #1FAF5A) -->
                                        <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-[#1FAF5A] to-[#2ecc71] hover:from-[#178a46] hover:to-[#27ae60] rounded-t-md transition-all duration-300 relative group/bar cursor-pointer" style="height: {{ $revenueHeight }}%">
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-[#0F2A44] text-white text-[10px] font-mono font-bold py-1.5 px-2.5 rounded-lg opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-xl z-30 border border-slate-700/30">
                                                <span class="text-[8px] text-slate-300 block uppercase font-sans tracking-wide">Revenue</span>
                                                Rp {{ number_format($trend['revenue'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <!-- Receivables Bar (Navy accent #0F2A44) -->
                                        <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-[#0F2A44] to-[#1a3d60] hover:from-[#091b2c] hover:to-[#0F2A44] rounded-t-md transition-all duration-300 relative group/bar cursor-pointer" style="height: {{ $receivablesHeight }}%">
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-[#0F2A44] text-white text-[10px] font-mono font-bold py-1.5 px-2.5 rounded-lg opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-xl z-30 border border-slate-700/30">
                                                <span class="text-[8px] text-slate-300 block uppercase font-sans tracking-wide">Receivables</span>
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
            <div class="glass-card p-6 lg:col-span-4 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#0F2A44] font-outfit mb-1">{{ $isEn ? 'Top Clients' : 'Klien Utama' }}</h3>
                    <p class="text-xs text-slate-400 font-medium mb-6">{{ $isEn ? 'Contributors by paid revenue in this unit.' : 'Kontributor berdasarkan nominal lunas di unit ini.' }}</p>

                    <div class="space-y-4">
                        @forelse($topClients as $index => $client)
                            <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/50 border border-slate-200/40 hover:bg-slate-100/50 hover:border-gold-500/20 transition-all duration-300">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-lg bg-gold-50 flex items-center justify-center text-[10px] font-black text-gold-600 font-outfit">
                                        #{{ $index + 1 }}
                                    </span>
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-slate-900 block truncate max-w-[140px]">{{ $client->nama_client }}</span>
                                        <span class="text-[9px] text-slate-400 font-bold block uppercase truncate max-w-[140px]">{{ $client->nama_perusahaan ?: '-' }}</span>
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

        <!-- Invoices Ledger Section -->
        <div class="glass-card p-6">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-slate-900 font-outfit mb-1">{{ $isEn ? 'Invoice Transactions Ledger' : 'Ledger Transaksi Invoice' }}</h3>
                <p class="text-sm text-slate-400 font-medium">{{ $isEn ? 'Listing of all invoice items assigned to this unit.' : 'Daftar semua entri invoice yang diunggah ke unit ini.' }}</p>
            </div>

            <!-- Desktop List View (Using row-floating from global Design System) -->
            <div class="hidden md:block space-y-4">
                <!-- Headers -->
                <div class="grid grid-cols-12 gap-8 px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-2xl mb-2">
                    <div class="col-span-2">{{ $isEn ? 'Invoice Number' : 'Nomor Invoice' }}</div>
                    <div class="col-span-3">{{ $isEn ? 'Customer Details' : 'Rincian Pelanggan' }}</div>
                    <div class="col-span-2">{{ $isEn ? 'Net Amount' : 'Nominal Bersih' }}</div>
                    <div class="col-span-2">{{ $isEn ? 'Due Date' : 'Jatuh Tempo' }}</div>
                    <div class="col-span-2 text-center">Status</div>
                    <div class="col-span-1 text-right">{{ $isEn ? 'Actions' : 'Aksi' }}</div>
                </div>

                <!-- Rows (adapted standard row-floating structure) -->
                @forelse($invoices as $invoice)
                    <div class="row-floating grid grid-cols-12 gap-8 items-center px-10 py-6 group transition-all duration-300">
                        <!-- Invoice Number -->
                        <div class="col-span-2 min-w-0">
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-[14px] font-bold text-slate-900 hover:text-gold-600 transition-colors duration-300 tracking-tight block truncate" title="{{ $invoice->invoice_number }}">
                                {{ $invoice->invoice_number }}
                            </a>
                        </div>

                        <!-- Customer details -->
                        <div class="col-span-3 min-w-0">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[14px] font-bold text-slate-800 truncate" title="{{ $invoice->client->nama_client }}">{{ $invoice->client->nama_client }}</span>
                                <span class="text-[12px] text-slate-400 font-medium truncate" title="{{ $invoice->client->nama_perusahaan }}">{{ $invoice->client->nama_perusahaan }}</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="col-span-2 min-w-0">
                            <span class="text-[15px] font-black text-slate-900 tracking-tight block truncate">
                                Rp {{ number_format($invoice->total, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Due Date -->
                        <div class="col-span-2 min-w-0">
                            @php
                                $isOverdue = $invoice->due_date && $invoice->due_date->isPast() && $invoice->status !== 'paid';
                            @endphp
                            <span class="text-[13px] font-bold {{ $isOverdue ? 'text-rose-500' : 'text-slate-500' }} truncate block" title="{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}">
                                {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}
                            </span>
                            @if($isOverdue)
                                <span class="text-[9px] font-black text-rose-500 uppercase tracking-tighter mt-0.5 truncate block">{{ $isEn ? 'OVERDUE' : 'TERLAMBAT' }}</span>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        <div class="col-span-2 flex justify-center">
                            <x-badge :status="$invoice->status" />
                        </div>

                        <!-- Actions -->
                        <div class="col-span-1">
                            <div class="flex items-center justify-end gap-3 opacity-40 group-hover:opacity-100 transition-all duration-300">
                                <a href="{{ route('invoices.show', $invoice) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors duration-300" title="{{ __('ui.view') }}">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="p-1 text-slate-400 hover:text-amber-600 transition-colors duration-300" title="{{ __('ui.edit') }}">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-dashed border-slate-200 rounded-[24px] p-24 text-center">
                        <div class="flex flex-col items-center max-w-sm mx-auto">
                            <div class="w-16 h-16 bg-slate-50 rounded-[20px] flex items-center justify-center mb-6">
                                <i data-lucide="file-text" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">{{ __('ui.empty_data') }}</h4>
                            <p class="text-sm text-slate-450 font-medium">{{ $isEn ? 'No invoices detected for this unit.' : 'Tidak ada invoice yang terdeteksi untuk unit ini.' }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Mobile List View (Spacious Card View with generous whitespace & large touch targets) -->
            <div class="md:hidden space-y-4">
                @forelse($invoices as $invoice)
                    <div 
                        onclick="window.location='{{ route('invoices.show', $invoice) }}'"
                        class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all active:scale-[0.98] cursor-pointer flex flex-col gap-3 duration-300"
                    >
                        <!-- Invoice Number & Status Badge -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono font-bold text-slate-400 tracking-tight">{{ $invoice->invoice_number }}</span>
                            <x-badge :status="$invoice->status" class="scale-75 origin-right shrink-0" />
                        </div>
                        <!-- Client name & details -->
                        <div class="min-w-0">
                            <h4 class="text-sm font-bold text-slate-900 truncate leading-tight">{{ $invoice->client->nama_client }}</h4>
                            @if($invoice->client->nama_perusahaan)
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5 truncate">{{ $invoice->client->nama_perusahaan }}</p>
                            @endif
                        </div>
                        <!-- Billed Amount & Due Date -->
                        <div class="flex items-center justify-between mt-1 pt-3 border-t border-slate-100">
                            <div>
                                <span class="text-[9px] text-slate-450 font-bold uppercase block tracking-wider">{{ $isEn ? 'Total Billed' : 'Total Tagihan' }}</span>
                                <span class="text-sm font-black text-slate-900 tracking-tight">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                            </div>
                            @if($invoice->due_date)
                                <div class="text-right">
                                    <span class="text-[9px] text-slate-450 font-bold uppercase block tracking-wider">{{ $isEn ? 'Due Date' : 'Jatuh Tempo' }}</span>
                                    @php
                                        $isOverdue = $invoice->due_date && $invoice->due_date->isPast() && $invoice->status !== 'paid';
                                    @endphp
                                    <span class="text-[11px] font-bold {{ $isOverdue ? 'text-rose-500' : 'text-slate-500' }}">
                                        {{ $invoice->due_date->format('M d, Y') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <!-- Action buttons with comfortable touch targets -->
                        <div class="flex items-center justify-end mt-2 pt-3 border-t border-slate-100 gap-3">
                            <a 
                                href="{{ route('invoices.show', $invoice) }}"
                                @click.stop=""
                                class="px-4 py-2.5 bg-slate-50 hover:bg-gold-50/50 text-slate-600 hover:text-gold-600 rounded-xl transition-all text-xs font-bold flex items-center gap-2 duration-300"
                            >
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                <span>{{ $isEn ? 'View' : 'Lihat' }}</span>
                            </a>
                            <a 
                                href="{{ route('invoices.edit', $invoice->id) }}"
                                @click.stop=""
                                class="px-4 py-2.5 bg-slate-50 hover:bg-gold-50/50 text-slate-600 hover:text-amber-600 rounded-xl transition-all text-xs font-bold flex items-center gap-2 duration-300"
                            >
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                <span>{{ $isEn ? 'Edit' : 'Ubah' }}</span>
                            </a>
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
                <div class="mt-8 flex justify-center">
                    <div class="bg-white/50 backdrop-blur-sm p-1.5 rounded-xl border border-slate-200/50 shadow-sm">
                        {{ $invoices->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
