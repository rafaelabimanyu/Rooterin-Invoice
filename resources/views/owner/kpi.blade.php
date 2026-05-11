<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6 page-fade-in">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-xl shadow-slate-900/20 animate-float">
                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight uppercase">Executive Intelligence</h1>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-[0.1em]">Business performance & growth analytics</p>
                </div>
            </div>
        </div>
        <div class="hidden md:flex items-center gap-4">
            <div class="px-4 py-2 glass-panel rounded-xl shadow-sm flex items-center gap-3 border-slate-200/50">
                <div class="flex flex-col">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">Telemetry</span>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-700 uppercase">Live Feed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 page-fade-in" style="animation-delay: 100ms">
        <!-- Monthly Revenue -->
        <div class="glass-card p-6 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-indigo-500/10 cursor-pointer"
             @click="$dispatch('open-slide-over', { title: 'Revenue Detail', content: `<div class=\'space-y-6\'>
                <div class=\'p-5 bg-indigo-50 rounded-2xl border border-indigo-100\'>
                    <p class=\'text-xs font-black text-indigo-400 uppercase tracking-widest mb-4\'>MoM Analysis</p>
                    <div class=\'flex items-center justify-between mb-2\'>
                        <span class=\'text-sm font-bold text-slate-600\'>Current Month</span>
                        <span class=\'text-sm font-black text-slate-900\'>Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</span>
                    </div>
                    <div class=\'flex items-center justify-between\'>
                        <span class=\'text-sm font-bold text-slate-600\'>Last Month</span>
                        <span class=\'text-sm font-black text-slate-900\'>Rp {{ number_format($lastMonthRevenue ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
             </div>` })">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Revenue (MTD)</p>
                <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600 group-hover:rotate-12 transition-transform duration-500">
                    <i data-lucide="banknote" class="w-5 h-5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 font-jakarta tracking-tight mb-4">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</h3>
            <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Growth</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $revenueChange >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                    <i data-lucide="{{ $revenueChange >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                    {{ number_format(abs($revenueChange), 1) }}%
                </span>
            </div>
            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>

        <!-- Unpaid Amount -->
        <div class="glass-card p-6 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-amber-500/10 cursor-pointer"
             @click="$dispatch('open-slide-over', { title: 'Capital Risks', content: 'Detailed risk assessment content...' })">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Capital at Risk</p>
                <div class="p-2.5 bg-amber-50 rounded-xl text-amber-600 group-hover:rotate-12 transition-transform duration-500">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-amber-600 font-jakarta tracking-tight mb-4">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</h3>
            <div class="pt-4 border-t border-slate-50 grid grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest">Floating</span>
                    <span class="text-[11px] font-black text-slate-700">Rp {{ number_format($pendingUnpaid, 0, ',', '.') }}</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[8px] text-rose-400 font-black uppercase tracking-widest">Critical</span>
                    <span class="text-[11px] font-black text-rose-600">Rp {{ number_format($overdueUnpaid, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>

        <!-- Repeat Customer Rate -->
        <div class="glass-card p-6 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-emerald-500/10 cursor-pointer"
             @click="$dispatch('open-slide-over', { title: 'Loyalty Metrics', content: 'Detailed loyalty metrics...' })">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Loyalty Pulse</p>
                <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600 group-hover:rotate-12 transition-transform duration-500">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 font-jakarta tracking-tight mb-4">{{ number_format($repeatRate, 1) }}%</h3>
            <div class="space-y-2.5 relative z-10">
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full shadow-[0_0_8px_rgba(16,185,129,0.5)]" style="width: {{ $repeatRate }}%"></div>
                </div>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Across {{ $totalClients }} entities</p>
            </div>
            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>

        <!-- Top Client -->
        <div class="glass-card p-6 group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-rose-500/10 cursor-pointer"
             @click="$dispatch('open-slide-over', { title: 'Prime Asset', content: 'Top client detail report...' })">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Prime Asset</p>
                <div class="p-2.5 bg-rose-50 rounded-xl text-rose-600 group-hover:rotate-12 transition-transform duration-500">
                    <i data-lucide="crown" class="w-5 h-5"></i>
                </div>
            </div>
            @if($topClients->count() > 0)
                <h3 class="text-lg font-black text-slate-900 font-jakarta tracking-tight truncate mb-0.5">{{ $topClients[0]->nama_client }}</h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider truncate">{{ $topClients[0]->nama_perusahaan }}</p>
                <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Valuation</span>
                    <span class="text-[12px] font-black text-slate-900 tracking-tighter">Rp {{ number_format($topClients[0]->invoices_sum_total, 0, ',', '.') }}</span>
                </div>
            @else
                <p class="text-[10px] text-slate-400 italic font-medium">Insufficient data</p>
            @endif
            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 page-fade-in" style="animation-delay: 200ms">
        <!-- Revenue Trend Chart -->
        <div class="lg:col-span-2 glass-card p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">Revenue Vectors</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em] mt-1">6-Month historical analysis</p>
                </div>
                <div class="hidden sm:flex items-center gap-3">
                    <div class="flex items-center gap-2 px-3 py-1 bg-indigo-50 rounded-full text-[9px] font-black text-indigo-600 uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                        Gross Intake
                    </div>
                </div>
            </div>
            <div id="revenueChart" class="min-h-[320px]"></div>
        </div>

        <!-- Performance Summary -->
        <div class="glass-card overflow-hidden flex flex-col group">
            <div class="p-6 border-b border-slate-100 bg-slate-50/30">
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">Operational Summary</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em] mt-1">Status: {{ now()->format('M Y') }}</p>
            </div>
            <div class="flex-1 p-6 flex flex-col justify-center space-y-6 relative">
                <div class="flex items-center justify-between group/item cursor-pointer hover:translate-x-1 transition-transform"
                     @click="$dispatch('open-slide-over', { title: 'New Issuance Detail', content: 'Detailed list of new invoices...' })">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover/item:bg-blue-600 group-hover/item:text-white transition-all duration-300">
                            <i data-lucide="file-plus" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] mb-0.5">New Issuance</p>
                            <p class="text-xl font-black text-slate-900 font-jakarta">{{ $monthlyPerformance['created'] }}</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 opacity-0 group-hover/item:opacity-100 transition-opacity"></i>
                </div>

                <div class="flex items-center justify-between group/item cursor-pointer hover:translate-x-1 transition-transform"
                     @click="$dispatch('open-slide-over', { title: 'Settled Assets Detail', content: 'Detailed list of settled invoices...' })">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-all duration-300">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] mb-0.5">Settled Assets</p>
                            <p class="text-xl font-black text-slate-900 font-jakarta">{{ $monthlyPerformance['paid'] }}</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 opacity-0 group-hover/item:opacity-100 transition-opacity"></i>
                </div>

                <div class="flex items-center justify-between group/item cursor-pointer hover:translate-x-1 transition-transform"
                     @click="$dispatch('open-slide-over', { title: 'Stagnant Flow Detail', content: 'Detailed list of overdue invoices...' })">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover/item:bg-rose-600 group-hover/item:text-white transition-all duration-300">
                            <i data-lucide="clock-alert" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] mb-0.5">Stagnant Flow</p>
                            <p class="text-xl font-black text-slate-900 font-jakarta">{{ $monthlyPerformance['overdue'] }}</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 opacity-0 group-hover/item:opacity-100 transition-opacity"></i>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest mb-2">
                        <span class="text-slate-500">Collection Velocity</span>
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
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">Priority Entities</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Enterprise valuation (LTV)</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 border border-amber-500/10">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="table-header">
                            <th class="px-8 py-4 text-[10px]">Rank & Identity</th>
                            <th class="px-8 py-4 text-[10px]">Volume</th>
                            <th class="px-8 py-4 text-[10px] text-right">Valuation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($topClients as $index => $client)
                            <tr class="table-row-premium cursor-pointer group"
                                @click="$dispatch('open-slide-over', { title: '{{ $client->nama_client }} Analysis', content: 'Detailed client LTV report...' })">
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
                                    No data available
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
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">Inflow Telemetry</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Major capital movements</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-500/10">
                    <i data-lucide="zap" class="w-5 h-5 fill-current"></i>
                </div>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @forelse($recentLargePayments as $payment)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50/50 hover:bg-white hover:shadow-xl hover:-translate-x-1 transition-all duration-300 border border-transparent hover:border-slate-200/50 group cursor-pointer"
                             @click="$dispatch('open-slide-over', { title: 'Payment Intelligence', content: 'Detailed payment tracking...' })">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-white flex flex-col items-center justify-center border border-slate-100 shadow-sm group-hover:bg-slate-900 transition-colors duration-500">
                                    <span class="text-[8px] font-black text-slate-400 uppercase leading-none group-hover:text-slate-500">{{ $payment->payment_date->format('M') }}</span>
                                    <span class="text-sm font-black text-slate-900 leading-none mt-0.5 group-hover:text-white">{{ $payment->payment_date->format('d') }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $payment->invoice->client->nama_client }}</span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $payment->invoice->invoice_number }}</span>
                                        <span class="w-0.5 h-0.5 rounded-full bg-slate-300"></span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $payment->payment_method }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-[14px] font-black text-emerald-600 tracking-tighter">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Inflow</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 italic text-sm">
                            No recent inflows
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
