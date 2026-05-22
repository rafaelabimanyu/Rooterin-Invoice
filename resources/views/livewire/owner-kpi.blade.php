<div>
    @if($minimal)
        <!-- Refreshed Dashboard Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 animate-fade-in-up">
            <!-- Card 1: Pendapatan Kotor -->
            <div class="glass-card p-6 group hover:-translate-y-1 hover:shadow-lg hover:border-indigo-500/20 transition-all duration-300 ease-out cursor-pointer relative overflow-hidden border border-slate-200/50"
                 @click="$dispatch('slide-over-loading-start')"
                 wire:click="openModal('total-revenue')">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/5 blur-3xl group-hover:bg-indigo-500/15 transition-colors duration-500 rounded-full"></div>
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-indigo-500/5 text-indigo-600 flex items-center justify-center border border-indigo-500/10 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                        <i data-lucide="bar-chart-3" class="w-7 h-7"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-600 shadow-sm">
                        {{ app()->getLocale() == 'en' ? 'Lifetime' : 'Selamanya' }}
                    </span>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] md:text-[11px] font-black text-slate-500 uppercase tracking-[0.25em] mb-2">
                        {{ __('ui.total_billing') }}
                    </p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 font-jakarta tracking-tight group-hover:translate-x-1 transition-transform duration-500">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            </div>

            <!-- Card 2: Invois Tertunggak -->
            <div class="glass-card p-6 group hover:-translate-y-1 hover:shadow-lg hover:border-amber-500/20 transition-all duration-300 ease-out cursor-pointer relative overflow-hidden border border-slate-200/50"
                 @click="$dispatch('slide-over-loading-start')"
                 wire:click="openModal('risks')">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-amber-500/5 blur-3xl group-hover:bg-amber-500/15 transition-colors duration-500 rounded-full"></div>
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500/10 to-amber-500/5 text-amber-600 flex items-center justify-center border border-amber-500/10 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                        <i data-lucide="clock" class="w-7 h-7"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-600 shadow-sm">
                        {{ app()->getLocale() == 'en' ? 'Receivable' : 'Piutang' }}
                    </span>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] md:text-[11px] font-black text-slate-500 uppercase tracking-[0.25em] mb-2">
                        {{ __('ui.amount_due') }}
                    </p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 font-jakarta tracking-tight group-hover:translate-x-1 transition-transform duration-500">
                        Rp {{ number_format($pendingRevenue, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            </div>

            <!-- Card 3: Klien -->
            <div class="glass-card p-6 group hover:-translate-y-1 hover:shadow-lg hover:border-emerald-500/20 transition-all duration-300 ease-out cursor-pointer relative overflow-hidden border border-slate-200/50"
                 @click="$dispatch('slide-over-loading-start')"
                 wire:click="openModal('loyalty')">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500/5 blur-3xl group-hover:bg-emerald-500/15 transition-colors duration-500 rounded-full"></div>
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500/10 to-emerald-500/5 text-emerald-600 flex items-center justify-center border border-emerald-500/10 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                        <i data-lucide="users" class="w-7 h-7"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-600 shadow-sm">
                        {{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}
                    </span>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] md:text-[11px] font-black text-slate-500 uppercase tracking-[0.25em] mb-2">
                        {{ __('ui.clients') }}
                    </p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 font-jakarta tracking-tight group-hover:translate-x-1 transition-transform duration-500">
                        {{ $totalClientsCount }}
                    </h3>
                </div>
                <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            </div>

            <!-- Card 4: Rasio Pengumpulan -->
            <div class="glass-card p-6 group hover:-translate-y-1 hover:shadow-lg hover:border-indigo-500/20 transition-all duration-300 ease-out cursor-pointer relative overflow-hidden border border-slate-200/50"
                 @click="$dispatch('slide-over-loading-start')"
                 wire:click="openModal('collection-rate')">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/5 blur-3xl group-hover:bg-indigo-500/15 transition-colors duration-500 rounded-full"></div>
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-indigo-500/5 text-indigo-600 flex items-center justify-center border border-indigo-500/10 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                        <i data-lucide="check-circle-2" class="w-7 h-7"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-600 shadow-sm">
                        {{ __('ui.efficiency') }}
                    </span>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] md:text-[11px] font-black text-slate-500 uppercase tracking-[0.25em] mb-2">
                        {{ __('ui.collection_rate') }}
                    </p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 font-jakarta tracking-tight group-hover:translate-x-1 transition-transform duration-500">
                        {{ round($collectionRate) }}%
                    </h3>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden shadow-inner mt-4">
                        <div class="bg-indigo-600 h-full progress-bar-fill shadow-[0_0_12px_rgba(79,70,229,0.5)]"
                            style="width: {{ $collectionRate }}%">
                        </div>
                    </div>
                </div>
                <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            </div>
        </div>
    @else
        <div class="animate-fade-in-up">
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6 page-fade-in">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-xl shadow-slate-900/20 animate-float">
                        <i data-lucide="trending-up" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight uppercase">{{ app()->getLocale() == 'en' ? 'Executive Intelligence' : 'Kecerdasan Eksekutif' }}</h1>
                        <p class="text-[11px] text-slate-500 font-bold uppercase tracking-[0.1em]">{{ app()->getLocale() == 'en' ? 'Business performance & growth analytics' : 'Analisis kinerja bisnis & pertumbuhan' }}</p>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-4">
                <div class="px-4 py-2 glass-panel rounded-xl shadow-sm flex items-center gap-3 border-slate-200/50">
                    <div class="flex flex-col">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ app()->getLocale() == 'en' ? 'Telemetry' : 'Telemetri' }}</span>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-slate-700 uppercase">{{ app()->getLocale() == 'en' ? 'Live Feed' : 'Aktivitas Terkini' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 page-fade-in" style="animation-delay: 100ms">
            <!-- Monthly Revenue -->
            <div class="glass-card p-6 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-indigo-500/10 cursor-pointer"
                 @click="$dispatch('slide-over-loading-start')"
                 wire:click="openModal('revenue')">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Revenue (MTD)' : 'Pendapatan (Bulan Berjalan)' }}</p>
                    <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600 group-hover:rotate-12 transition-transform duration-500">
                        <i data-lucide="banknote" class="w-5 h-5"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-900 font-jakarta tracking-tight mb-4">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</h3>
                <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Growth' : 'Pertumbuhan' }}</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $revenueChange >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                        <i data-lucide="{{ $revenueChange >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                        {{ number_format(abs($revenueChange), 1) }}%
                    </span>
                </div>
                <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            </div>

            <!-- Unpaid Amount -->
            <div class="glass-card p-6 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-amber-500/10 cursor-pointer"
                 @click="$dispatch('slide-over-loading-start')"
                 wire:click="openModal('risks')">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Capital at Risk' : 'Modal dalam Risiko' }}</p>
                    <div class="p-2.5 bg-amber-50 rounded-xl text-amber-600 group-hover:rotate-12 transition-transform duration-500">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-amber-600 font-jakarta tracking-tight mb-4">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</h3>
                <div class="pt-4 border-t border-slate-50 grid grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Floating' : 'Berjalan' }}</span>
                        <span class="text-[11px] font-black text-slate-700">Rp {{ number_format($pendingUnpaid, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col text-right">
                        <span class="text-[8px] text-rose-400 font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Critical' : 'Kritis' }}</span>
                        <span class="text-[11px] font-black text-rose-600">Rp {{ number_format($overdueUnpaid, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            </div>

            <!-- Repeat Customer Rate -->
            <div class="glass-card p-6 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-emerald-500/10 cursor-pointer"
                 @click="$dispatch('slide-over-loading-start')"
                 wire:click="openModal('loyalty')">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Loyalty Pulse' : 'Indeks Loyalitas' }}</p>
                    <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600 group-hover:rotate-12 transition-transform duration-500">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-900 font-jakarta tracking-tight mb-4">{{ number_format($repeatRate, 1) }}%</h3>
                <div class="space-y-2.5 relative z-10">
                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full shadow-[0_0_8px_rgba(16,185,129,0.5)]" style="width: {{ $repeatRate }}%"></div>
                    </div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Across ' : 'Dari ' }}{{ $totalClients }}{{ app()->getLocale() == 'en' ? ' entities' : ' entitas' }}</p>
                </div>
                <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            </div>

            <!-- Top Client -->
            <div class="glass-card p-6 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-rose-500/10 cursor-pointer"
                 @click="$dispatch('slide-over-loading-start')"
                 wire:click="openModal('prime-asset')">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Prime Asset' : 'Aset Utama' }}</p>
                    <div class="p-2.5 bg-rose-50 rounded-xl text-rose-600 group-hover:rotate-12 transition-transform duration-500">
                        <i data-lucide="crown" class="w-5 h-5"></i>
                    </div>
                </div>
                @if($topClients->count() > 0)
                    <h3 class="text-lg font-black text-slate-900 font-jakarta tracking-tight truncate mb-0.5">{{ $topClients[0]->nama_client }}</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider truncate">{{ $topClients[0]->nama_perusahaan }}</p>
                    <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Valuation' : 'Valuasi' }}</span>
                        <span class="text-[12px] font-black text-slate-900 tracking-tighter">Rp {{ number_format($topClients[0]->invoices_sum_total, 0, ',', '.') }}</span>
                    </div>
                @else
                    <p class="text-[10px] text-slate-400 italic font-medium">{{ app()->getLocale() == 'en' ? 'Insufficient data' : 'Data tidak mencukupi' }}</p>
                @endif
                <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 page-fade-in" style="animation-delay: 200ms">
            <!-- Revenue Trend Chart -->
            <div class="lg:col-span-2 glass-card p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">{{ app()->getLocale() == 'en' ? 'Revenue Vectors' : 'Vektor Pendapatan' }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em] mt-1">{{ app()->getLocale() == 'en' ? '6-Month historical analysis' : 'Analisis riwayat 6 bulan' }}</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-3">
                        <div class="flex items-center gap-2 px-3 py-1 bg-indigo-50 rounded-full text-[9px] font-black text-indigo-600 uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                            {{ app()->getLocale() == 'en' ? 'Gross Intake' : 'Pemasukan Kotor' }}
                        </div>
                    </div>
                </div>
                <!-- Fixed Height Container with wire:ignore -->
                <div wire:ignore class="relative h-[300px] w-full">
                    <div id="revenueChart" class="absolute inset-0"></div>
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="glass-card overflow-hidden flex flex-col group">
                <div class="p-6 border-b border-slate-100 bg-slate-50/30">
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">{{ app()->getLocale() == 'en' ? 'Operational Summary' : 'Ringkasan Operasional' }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em] mt-1">{{ app()->getLocale() == 'en' ? 'Status' : 'Status' }}: {{ now()->format('M Y') }}</p>
                </div>
                <div class="flex-1 p-6 flex flex-col justify-center space-y-6 relative">
                    <div class="flex items-center justify-between group/item cursor-pointer hover:translate-x-1 transition-transform"
                         @click="$dispatch('slide-over-loading-start')"
                         wire:click="openModal('new-issuance')">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover/item:bg-blue-600 group-hover/item:text-white transition-all duration-300">
                                <i data-lucide="file-plus" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] mb-0.5">{{ app()->getLocale() == 'en' ? 'New Issuance' : 'Penerbitan Baru' }}</p>
                                <p class="text-xl font-black text-slate-900 font-jakarta">{{ $monthlyPerformance['created'] }}</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 opacity-0 group-hover/item:opacity-100 transition-opacity"></i>
                    </div>

                    <div class="flex items-center justify-between group/item cursor-pointer hover:translate-x-1 transition-transform"
                         @click="$dispatch('slide-over-loading-start')"
                         wire:click="openModal('settled-assets')">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-all duration-300">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] mb-0.5">{{ app()->getLocale() == 'en' ? 'Settled Assets' : 'Aset Diselesaikan' }}</p>
                                <p class="text-xl font-black text-slate-900 font-jakarta">{{ $monthlyPerformance['paid'] }}</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 opacity-0 group-hover/item:opacity-100 transition-opacity"></i>
                    </div>

                    <div class="flex items-center justify-between group/item cursor-pointer hover:translate-x-1 transition-transform"
                         @click="$dispatch('slide-over-loading-start')"
                         wire:click="openModal('stagnant-flow')">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover/item:bg-rose-600 group-hover/item:text-white transition-all duration-300">
                                <i data-lucide="clock-alert" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] mb-0.5">{{ app()->getLocale() == 'en' ? 'Stagnant Flow' : 'Aliran Stagnan' }}</p>
                                <p class="text-xl font-black text-slate-900 font-jakarta">{{ $monthlyPerformance['overdue'] }}</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 opacity-0 group-hover/item:opacity-100 transition-opacity"></i>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest mb-2">
                            <span class="text-slate-500">{{ app()->getLocale() == 'en' ? 'Collection Velocity' : 'Kecepatan Pengumpulan' }}</span>
                            <span class="text-indigo-600">{{ $monthlyPerformance['created'] > 0 ? round(($monthlyPerformance['paid'] / $monthlyPerformance['created']) * 100) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-indigo-600 h-full" style="width: {{ $monthlyPerformance['created'] > 0 ? ($monthlyPerformance['paid'] / $monthlyPerformance['created']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 page-fade-in" style="animation-delay: 300ms">
            <!-- Top 5 Clients -->
            <div class="table-container">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">{{ app()->getLocale() == 'en' ? 'Priority Entities' : 'Entitas Prioritas' }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Enterprise valuation (LTV)' : 'Valuasi perusahaan (LTV)' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 border border-amber-500/10">
                        <i data-lucide="award" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="table-header">
                                <th class="px-8 py-4 text-[10px]">{{ app()->getLocale() == 'en' ? 'Rank & Identity' : 'Peringkat & Identitas' }}</th>
                                <th class="px-8 py-4 text-[10px]">{{ app()->getLocale() == 'en' ? 'Volume' : 'Volume' }}</th>
                                <th class="px-8 py-4 text-[10px] text-right">{{ app()->getLocale() == 'en' ? 'Valuation' : 'Valuasi' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($topClients as $index => $client)
                                <tr class="table-row-premium cursor-pointer group"
                                    @click="$dispatch('slide-over-loading-start')"
                                    wire:click="openModal('client', {{ $client->id }})">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-4">
                                            <span class="w-7 h-7 rounded-lg bg-slate-900 flex items-center justify-center text-[9px] font-black text-white">{{ $index + 1 }}</span>
                                            <div class="flex flex-col">
                                                <span class="text-[13px] font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $client->nama_client }}</span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $client->nama_perusahaan }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-full text-[10px] font-bold text-slate-600">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                            {{ $client->invoices_count }} Unit
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <span class="text-[13px] font-black text-slate-900 tracking-tighter">Rp {{ number_format($client->invoices_sum_total, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-12 text-center text-slate-400 italic text-sm">
                                        {{ app()->getLocale() == 'en' ? 'No data available' : 'Tidak ada data tersedia' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Large Payments -->
            <div class="glass-card flex flex-col">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">{{ app()->getLocale() == 'en' ? 'Inflow Telemetry' : 'Telemetri Aliran Masuk' }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Major capital movements' : 'Pergerakan modal besar' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-500/10">
                        <i data-lucide="zap" class="w-5 h-5 fill-current"></i>
                    </div>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        @forelse($recentLargePayments as $payment)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50/50 hover:bg-white hover:shadow-xl hover:-translate-x-1 transition-all duration-300 border border-transparent hover:border-slate-200/50 group cursor-pointer"
                                 @click="$dispatch('slide-over-loading-start')"
                                 wire:click="openModal('payment', {{ $payment->id }})">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-white flex flex-col items-center justify-center border border-slate-100 shadow-sm group-hover:bg-slate-900 transition-colors duration-500">
                                        <span class="text-[8px] font-black text-slate-400 uppercase leading-none group-hover:text-slate-500">{{ $payment->payment_date->format('M') }}</span>
                                        <span class="text-sm font-black text-slate-900 leading-none mt-0.5 group-hover:text-white">{{ $payment->payment_date->format('d') }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[13px] font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $payment->invoice?->client?->nama_client ?? 'N/A' }}</span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $payment->invoice?->invoice_number ?? 'N/A' }}</span>
                                            <span class="w-0.5 h-0.5 rounded-full bg-slate-300"></span>
                                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $payment->payment_method }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col text-right">
                                    <span class="text-[14px] font-black text-emerald-600 tracking-tighter">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest mt-0.5">{{ app()->getLocale() == 'en' ? 'Inflow' : 'Masuk' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-slate-400 italic text-sm">
                                {{ app()->getLocale() == 'en' ? 'No recent inflows' : 'Tidak ada aliran masuk baru' }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Chart Scripts & Event Listeners -->
    @if(!$minimal)
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            (function() {
                window.initChart = function() {
                    if (typeof ApexCharts === 'undefined') {
                        setTimeout(window.initChart, 50);
                        return;
                    }

                    const chartEl = document.querySelector("#revenueChart");
                    if (!chartEl) return;

                    const categories = {!! json_encode($revenueTrend->pluck('month')) !!};
                    const data = {!! json_encode($revenueTrend->pluck('revenue')) !!};
                    const categoriesStr = JSON.stringify(categories);
                    const dataStr = JSON.stringify(data);

                    // Prevent destroying and recreating the chart if it already exists and data has not changed
                    if (window.ownerRevenueChart && window.lastChartCategories === categoriesStr && window.lastChartData === dataStr) {
                        return;
                    }

                    window.lastChartCategories = categoriesStr;
                    window.lastChartData = dataStr;
                    
                    if (window.ownerRevenueChart) {
                        try {
                            window.ownerRevenueChart.destroy();
                        } catch(e) {}
                    }
                    
                    const options = {
                        series: [{
                            name: '{{ app()->getLocale() == 'en' ? "Enterprise Revenue" : "Pendapatan Perusahaan" }}',
                            data: data
                        }],
                        chart: {
                            type: 'area',
                            height: '100%',
                            width: '100%',
                            toolbar: { show: false },
                            zoom: { enabled: false },
                            fontFamily: 'Plus Jakarta Sans, sans-serif'
                        },
                        colors: ['#6366f1'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.5,
                                opacityTo: 0.05,
                                stops: [0, 90, 100]
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: {
                            curve: 'smooth',
                            width: 4,
                            lineCap: 'round'
                        },
                        xaxis: {
                            categories: categories,
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: {
                                style: {
                                    colors: '#94a3b8',
                                    fontSize: '11px',
                                    tracking: 800
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(val) {
                                    return 'Rp ' + (val/1000000).toFixed(1) + 'M';
                                },
                                style: {
                                    colors: '#94a3b8',
                                    fontSize: '11px',
                                    tracking: 800
                                }
                            }
                        },
                        grid: {
                            borderColor: document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.05)' : '#f1f5f9',
                            strokeDashArray: 6,
                            padding: { left: 20, right: 20 }
                        },
                        markers: {
                            size: 0,
                            hover: { size: 6 }
                        },
                        tooltip: {
                            theme: 'dark',
                            y: {
                                formatter: function(val) {
                                    return 'Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    height: 300
                                }
                            }
                        }],
                        responsive_enabled: true,
                        maintainAspectRatio: false
                    };

                    window.ownerRevenueChart = new ApexCharts(chartEl, options);
                    window.ownerRevenueChart.render();
                };

                // Call immediately if DOM is ready
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    window.initChart();
                }

                // Guard listener registration to prevent registering duplicate listeners during morph updates
                if (window.ownerChartListenersRegistered) {
                    return;
                }
                window.ownerChartListenersRegistered = true;

                // Initial and reactive boots
                document.addEventListener('DOMContentLoaded', () => { window.initChart(); });
                document.addEventListener('livewire:navigated', () => { window.initChart(); });
                
                // Re-init chart when Livewire component is updated
                window.addEventListener('init-chart', () => { window.initChart(); });

                window.addEventListener('dark-mode-toggled', function() {
                    if (window.ownerRevenueChart) {
                        window.ownerRevenueChart.updateOptions({
                            grid: {
                                borderColor: document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.05)' : '#f1f5f9'
                            }
                        });
                    }
                });

                // Register Livewire hook to re-initialize on component update morphs
                const registerChartHook = () => {
                    if (typeof Livewire !== 'undefined') {
                        Livewire.hook('morph.updated', ({ el, component }) => {
                            if (component && (component.name === 'owner-kpi' || component.name === 'OwnerKpi')) {
                                window.initChart();
                            }
                        });
                    }
                };
                if (window.Livewire) {
                    registerChartHook();
                } else {
                    document.addEventListener('livewire:init', () => {
                        registerChartHook();
                    });
                }
            })();
        </script>
    @endif

    <script>
        // Lucide reinitialization event listener
        window.addEventListener('init-lucide-icons', () => {
            setTimeout(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 50);
        });
    </script>
</div>
