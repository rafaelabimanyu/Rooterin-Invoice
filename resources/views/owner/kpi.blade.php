<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                </div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white font-outfit uppercase tracking-tight">Owner KPI Dashboard</h1>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Executive level business intelligence and performance metrics.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Real-time Data Active</span>
            </div>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Monthly Revenue -->
        <div class="glass-card p-6 group hover:border-indigo-500/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Revenue (This Month)</p>
                <div class="p-2 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg text-indigo-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white font-outfit">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</h3>
            <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                <span class="text-[10px] text-slate-400 font-bold uppercase">vs Last Month</span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black {{ $revenueChange >= 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' }}">
                    <i data-lucide="{{ $revenueChange >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                    {{ number_format(abs($revenueChange), 1) }}%
                </span>
            </div>
        </div>

        <!-- Unpaid Amount -->
        <div class="glass-card p-6 group hover:border-amber-500/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unpaid Amount</p>
                <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg text-amber-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white font-outfit text-amber-600">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</h3>
            <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800 grid grid-cols-2 gap-2">
                <div class="flex flex-col">
                    <span class="text-[9px] text-slate-400 font-black uppercase">Pending</span>
                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($pendingUnpaid, 0, ',', '.') }}</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[9px] text-slate-400 font-black uppercase">Overdue</span>
                    <span class="text-[11px] font-bold text-rose-600">Rp {{ number_format($overdueUnpaid, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Repeat Customer Rate -->
        <div class="glass-card p-6 group hover:border-emerald-500/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Repeat Rate</p>
                <div class="p-2 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg text-emerald-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white font-outfit">{{ number_format($repeatRate, 1) }}%</h3>
            <div class="mt-4">
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full transition-all duration-1000 shadow-[0_0_8px_rgba(16,185,129,0.5)]" style="width: {{ $repeatRate }}%"></div>
                </div>
                <p class="text-[10px] text-slate-400 font-medium mt-2">Loyalty metric across {{ $totalClients }} active clients</p>
            </div>
        </div>

        <!-- Top Client -->
        <div class="glass-card p-6 group hover:border-rose-500/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Top Client</p>
                <div class="p-2 bg-rose-50 dark:bg-rose-500/10 rounded-lg text-rose-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="crown" class="w-4 h-4"></i>
                </div>
            </div>
            @if($topClients->count() > 0)
                <h3 class="text-lg font-black text-slate-900 dark:text-white font-outfit truncate" title="{{ $topClients[0]->nama_client }}">{{ $topClients[0]->nama_client }}</h3>
                <p class="text-[11px] text-slate-500 font-medium truncate">{{ $topClients[0]->nama_perusahaan }}</p>
                <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 font-bold uppercase">Total LTV</span>
                    <span class="text-[12px] font-black text-slate-900 dark:text-white">Rp {{ number_format($topClients[0]->invoices_sum_total, 0, ',', '.') }}</span>
                </div>
            @else
                <p class="text-xs text-slate-400 italic">No data available</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Revenue Trend Chart -->
        <div class="lg:col-span-2 glass-card p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white font-outfit uppercase tracking-tight">Revenue Trend</h3>
                    <p class="text-[11px] text-slate-400 font-medium uppercase mt-1">Gross income over last 6 months</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 px-3 py-1 bg-indigo-50 dark:bg-indigo-500/10 rounded-full text-[10px] font-black text-indigo-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                        Gross Revenue
                    </div>
                </div>
            </div>
            <div id="revenueChart" class="min-h-[350px]"></div>
        </div>

        <!-- Performance Summary -->
        <div class="glass-card overflow-hidden flex flex-col">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20">
                <h3 class="font-black text-slate-900 dark:text-white font-outfit uppercase tracking-tight">Monthly Summary</h3>
                <p class="text-[11px] text-slate-400 font-medium uppercase mt-1">Activity for {{ now()->format('F Y') }}</p>
            </div>
            <div class="flex-1 p-8 flex flex-col justify-center space-y-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600">
                            <i data-lucide="file-plus" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Invoices Created</p>
                            <p class="text-lg font-black text-slate-900 dark:text-white font-outfit">{{ $monthlyPerformance['created'] }}</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Invoices Paid</p>
                            <p class="text-lg font-black text-slate-900 dark:text-white font-outfit">{{ $monthlyPerformance['paid'] }}</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Overdue Count</p>
                            <p class="text-lg font-black text-slate-900 dark:text-white font-outfit">{{ $monthlyPerformance['overdue'] }}</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
                </div>

                <div class="pt-8 mt-4 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center justify-between text-xs mb-2">
                        <span class="font-bold text-slate-500">Collection Rate</span>
                        <span class="font-black text-indigo-600">{{ $monthlyPerformance['created'] > 0 ? round(($monthlyPerformance['paid'] / $monthlyPerformance['created']) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-indigo-600 h-full" style="width: {{ $monthlyPerformance['created'] > 0 ? ($monthlyPerformance['paid'] / $monthlyPerformance['created']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top 5 Clients -->
        <div class="glass-card">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/30 dark:bg-slate-800/20">
                <h3 class="font-black text-slate-900 dark:text-white font-outfit uppercase tracking-tight">Top 5 Clients</h3>
                <i data-lucide="award" class="w-5 h-5 text-amber-500"></i>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-[0.1em] text-slate-400 bg-slate-50/50 dark:bg-slate-800/40">
                            <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Rank & Name</th>
                            <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Invoices</th>
                            <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800 text-right">Revenue (LTV)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @forelse($topClients as $index => $client)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-black text-slate-500">{{ $index + 1 }}</span>
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-bold text-slate-900 dark:text-white">{{ $client->nama_client }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium">{{ $client->nama_perusahaan }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="text-[12px] font-bold text-slate-600 dark:text-slate-400">{{ $client->invoices_count }} invoices</span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <span class="text-[13px] font-black text-slate-900 dark:text-white tracking-tight">Rp {{ number_format($client->invoices_sum_total, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-12 text-center">
                                    <x-empty-state icon="award" title="No Top Clients" description="Start billing your clients to see ranking here." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Large Payments -->
        <div class="glass-card">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/30 dark:bg-slate-800/20">
                <h3 class="font-black text-slate-900 dark:text-white font-outfit uppercase tracking-tight">Recent Large Payments</h3>
                <i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-500"></i>
            </div>
            <div class="p-4">
                <div class="space-y-4">
                    @forelse($recentLargePayments as $payment)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white dark:bg-slate-900 flex flex-col items-center justify-center border border-slate-100 dark:border-slate-800">
                                    <span class="text-[9px] font-black text-slate-400 uppercase leading-none">{{ $payment->payment_date->format('M') }}</span>
                                    <span class="text-sm font-black text-slate-900 dark:text-white leading-none mt-1">{{ $payment->payment_date->format('d') }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-900 dark:text-white">{{ $payment->invoice->client->nama_client }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $payment->invoice->invoice_number }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-[13px] font-black text-emerald-600 tracking-tight">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $payment->payment_method }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <x-empty-state icon="dollar-sign" title="No Payments" description="No recent large payments recorded." />
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
                    name: 'Revenue',
                    data: {!! json_encode($revenueTrend->pluck('revenue')) !!}
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#4f46e5'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: {!! json_encode($revenueTrend->pluck('month')) !!},
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '10px',
                            fontWeight: 700
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        },
                        style: {
                            colors: '#94a3b8',
                            fontSize: '10px',
                            fontWeight: 700
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    padding: {
                        left: 20
                    }
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
            
            // Re-render chart on theme change (if needed)
            window.addEventListener('dark-mode-toggled', function() {
                chart.updateOptions({
                    grid: {
                        borderColor: document.documentElement.classList.contains('dark') ? '#1e293b' : '#f1f5f9'
                    }
                });
            });
        });
    </script>
</x-app-layout>
