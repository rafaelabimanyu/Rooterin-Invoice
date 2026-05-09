<x-app-layout>
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8 page-fade-in">
        <div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-2xl bg-slate-900 dark:bg-white flex items-center justify-center text-white dark:text-slate-950 shadow-2xl shadow-slate-900/20 dark:shadow-white/5 animate-float">
                    <i data-lucide="trending-up" class="w-8 h-8"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-black text-slate-900 dark:text-white font-jakarta tracking-tight uppercase">Executive Intelligence</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-[0.1em] mt-1">High-level business performance & growth analytics</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="px-5 py-2.5 glass-panel rounded-2xl shadow-sm flex items-center gap-4 border-slate-200/50">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Telemetry</span>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] animate-pulse"></span>
                        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Live Database Feed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 page-fade-in" style="animation-delay: 100ms">
        <!-- Monthly Revenue -->
        <div class="glass-card p-8 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-indigo-500/10">
            <div class="flex items-center justify-between mb-8">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em]">Revenue (MTD)</p>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl text-indigo-600 group-hover:rotate-12 transition-transform duration-500">
                    <i data-lucide="banknote" class="w-6 h-6"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white font-jakarta tracking-tighter mb-4">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</h3>
            <div class="pt-6 border-t border-slate-50 dark:border-white/5 flex items-center justify-between">
                <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Growth vs Last Month</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black {{ $revenueChange >= 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' }} shadow-sm">
                    <i data-lucide="{{ $revenueChange >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3.5 h-3.5"></i>
                    {{ number_format(abs($revenueChange), 1) }}%
                </span>
            </div>
            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>

        <!-- Unpaid Amount -->
        <div class="glass-card p-8 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-amber-500/10">
            <div class="flex items-center justify-between mb-8">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em]">Capital at Risk</p>
                <div class="p-3 bg-amber-50 dark:bg-amber-500/10 rounded-2xl text-amber-600 group-hover:rotate-12 transition-transform duration-500">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-amber-600 font-jakarta tracking-tighter mb-4">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</h3>
            <div class="pt-6 border-t border-slate-50 dark:border-white/5 grid grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Floating</span>
                    <span class="text-[12px] font-black text-slate-700 dark:text-slate-300">Rp {{ number_format($pendingUnpaid, 0, ',', '.') }}</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[9px] text-rose-400 font-black uppercase tracking-widest">Critical</span>
                    <span class="text-[12px] font-black text-rose-600">Rp {{ number_format($overdueUnpaid, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>

        <!-- Repeat Customer Rate -->
        <div class="glass-card p-8 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-emerald-500/10">
            <div class="flex items-center justify-between mb-8">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em]">Loyalty Pulse</p>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl text-emerald-600 group-hover:rotate-12 transition-transform duration-500">
                    <i data-lucide="refresh-cw" class="w-6 h-6"></i>
                </div>
            </div>
            <h3 class="text-4xl font-black text-slate-900 dark:text-white font-jakarta tracking-tighter mb-4">{{ number_format($repeatRate, 1) }}%</h3>
            <div class="space-y-3 relative z-10">
                <div class="w-full bg-slate-100 dark:bg-white/5 h-2.5 rounded-full overflow-hidden shadow-inner">
                    <div class="bg-emerald-500 h-full transition-all duration-1000 shadow-[0_0_12px_rgba(16,185,129,0.5)]" style="width: {{ $repeatRate }}%"></div>
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Across {{ $totalClients }} active entities</p>
            </div>
            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>

        <!-- Top Client -->
        <div class="glass-card p-8 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-rose-500/10">
            <div class="flex items-center justify-between mb-8">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em]">Prime Asset</p>
                <div class="p-3 bg-rose-50 dark:bg-rose-500/10 rounded-2xl text-rose-600 group-hover:rotate-12 transition-transform duration-500">
                    <i data-lucide="crown" class="w-6 h-6"></i>
                </div>
            </div>
            @if($topClients->count() > 0)
                <h3 class="text-xl font-black text-slate-900 dark:text-white font-jakarta tracking-tight truncate mb-1" title="{{ $topClients[0]->nama_client }}">{{ $topClients[0]->nama_client }}</h3>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider truncate">{{ $topClients[0]->nama_perusahaan }}</p>
                <div class="mt-8 pt-6 border-t border-slate-50 dark:border-white/5 flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Total Valuation</span>
                    <span class="text-[14px] font-black text-slate-900 dark:text-white tracking-tighter">Rp {{ number_format($topClients[0]->invoices_sum_total, 0, ',', '.') }}</span>
                </div>
            @else
                <p class="text-xs text-slate-400 italic font-medium">Insufficient telemetry data</p>
            @endif
            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12 page-fade-in" style="animation-delay: 200ms">
        <!-- Revenue Trend Chart -->
        <div class="lg:col-span-2 glass-card p-10">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white font-jakarta uppercase tracking-tight text-xl">Revenue Vectors</h3>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">6-Month historical performance analysis</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-4 py-1.5 bg-indigo-50 dark:bg-indigo-500/10 rounded-full text-[10px] font-black text-indigo-600 uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 shadow-[0_0_8px_rgba(79,70,229,0.5)]"></span>
                        Gross Intake
                    </div>
                </div>
            </div>
            <div id="revenueChart" class="min-h-[400px]"></div>
        </div>

        <!-- Performance Summary -->
        <div class="glass-card overflow-hidden flex flex-col group">
            <div class="p-10 border-b border-slate-100 dark:border-white/5 bg-slate-50/30 dark:bg-white/[0.02]">
                <h3 class="font-black text-slate-900 dark:text-white font-jakarta uppercase tracking-tight text-xl">Operational Summary</h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">Performance for {{ now()->format('F Y') }}</p>
            </div>
            <div class="flex-1 p-10 flex flex-col justify-center space-y-10 relative">
                <div class="flex items-center justify-between group/item">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover/item:scale-110 transition-transform">
                            <i data-lucide="file-plus" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">New Issuance</p>
                            <p class="text-2xl font-black text-slate-900 dark:text-white font-jakarta">{{ $monthlyPerformance['created'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between group/item">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover/item:scale-110 transition-transform">
                            <i data-lucide="check-circle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Settled Assets</p>
                            <p class="text-2xl font-black text-slate-900 dark:text-white font-jakarta">{{ $monthlyPerformance['paid'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between group/item">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 group-hover/item:scale-110 transition-transform">
                            <i data-lucide="clock-alert" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Stagnant Flow</p>
                            <p class="text-2xl font-black text-slate-900 dark:text-white font-jakarta">{{ $monthlyPerformance['overdue'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="pt-10 border-t border-slate-100 dark:border-white/5">
                    <div class="flex items-center justify-between text-[11px] font-black uppercase tracking-widest mb-3">
                        <span class="text-slate-500">Collection Velocity</span>
                        <span class="text-indigo-600 dark:text-indigo-400">{{ $monthlyPerformance['created'] > 0 ? round(($monthlyPerformance['paid'] / $monthlyPerformance['created']) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-white/5 h-2.5 rounded-full overflow-hidden shadow-inner">
                        <div class="bg-indigo-600 h-full shadow-[0_0_12px_rgba(79,70,229,0.5)]" style="width: {{ $monthlyPerformance['created'] > 0 ? ($monthlyPerformance['paid'] / $monthlyPerformance['created']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 page-fade-in" style="animation-delay: 300ms">
        <!-- Top 5 Clients -->
        <div class="table-container">
            <div class="px-10 py-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/30 dark:bg-white/[0.02]">
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white font-jakarta uppercase tracking-tight text-lg">Priority Entities</h3>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Ranking by enterprise valuation (LTV)</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-500 border border-amber-500/10">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="table-header">
                            <th class="px-10 py-5">Rank & Identity</th>
                            <th class="px-10 py-5">Volume</th>
                            <th class="px-10 py-5 text-right">Market Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-white/[0.03]">
                        @forelse($topClients as $index => $client)
                            <tr class="table-row-premium">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-5">
                                        <span class="w-8 h-8 rounded-lg bg-slate-900 dark:bg-white flex items-center justify-center text-[10px] font-black text-white dark:text-slate-950 shadow-lg">{{ $index + 1 }}</span>
                                        <div class="flex flex-col">
                                            <span class="text-[14px] font-black text-slate-900 dark:text-white tracking-tight">{{ $client->nama_client }}</span>
                                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $client->nama_perusahaan }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 dark:bg-white/5 rounded-full text-[11px] font-bold text-slate-600 dark:text-slate-400">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                        {{ $client->invoices_count }} Units
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <span class="text-[15px] font-black text-slate-900 dark:text-white tracking-tighter">Rp {{ number_format($client->invoices_sum_total, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-10 py-16">
                                    <x-empty-state icon="award" title="No Priority Entities" description="Enterprise valuation will appear here once billing commences." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Large Payments -->
        <div class="glass-card flex flex-col">
            <div class="px-10 py-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/30 dark:bg-white/[0.02]">
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white font-jakarta uppercase tracking-tight text-lg">Inflow Telemetry</h3>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">Significant capital movements</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500 border border-emerald-500/10">
                    <i data-lucide="zap" class="w-5 h-5 fill-current"></i>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentLargePayments as $payment)
                        <div class="flex items-center justify-between p-5 rounded-2xl bg-slate-50/50 dark:bg-white/[0.02] hover:bg-white dark:hover:bg-white/[0.05] hover:shadow-xl hover:-translate-x-1 transition-all duration-300 border border-transparent hover:border-slate-200/50 dark:hover:border-white/10 group">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-premium-800 flex flex-col items-center justify-center border border-slate-100 dark:border-white/5 shadow-sm group-hover:bg-slate-900 dark:group-hover:bg-white transition-colors duration-500">
                                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase leading-none group-hover:text-slate-500 dark:group-hover:text-slate-400">{{ $payment->payment_date->format('M') }}</span>
                                    <span class="text-lg font-black text-slate-900 dark:text-white leading-none mt-1 group-hover:text-white dark:group-hover:text-slate-900">{{ $payment->payment_date->format('d') }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[14px] font-black text-slate-900 dark:text-white tracking-tight group-hover:text-indigo-600 transition-colors">{{ $payment->invoice->client->nama_client }}</span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $payment->invoice->invoice_number }}</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $payment->payment_method }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-[16px] font-black text-emerald-600 dark:text-emerald-400 tracking-tighter">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">Confirmed Inflow</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center">
                            <x-empty-state icon="dollar-sign" title="Static Capital" description="No major financial inflows detected in the recent telemetry cycle." />
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const options = {
                series: [{
                    name: 'Enterprise Revenue',
                    data: {!! json_encode($revenueTrend->pluck('revenue')) !!}
                }],
                chart: {
                    type: 'area',
                    height: 400,
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
                    categories: {!! json_encode($revenueTrend->pluck('month')) !!},
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '11px',
                            fontWeight: 800
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
                            fontWeight: 800
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
                }
            };

            const chart = new ApexCharts(document.querySelector("#revenueChart"), options);
            chart.render();
            
            window.addEventListener('dark-mode-toggled', function() {
                chart.updateOptions({
                    grid: {
                        borderColor: document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.05)' : '#f1f5f9'
                    }
                });
            });
        });
    </script>
</x-app-layout>
