<x-app-layout :title="app()->getLocale() == 'en' ? 'Audit Reports & Analytics' : 'Laporan Audit & Analisis'">
    <div class="page-fade-in py-8 px-6 lg:px-8" x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'invoices' }">
        <div class="max-w-full mx-auto space-y-10">
            <!-- Header Block -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 font-jakarta uppercase tracking-tight">{{ __('ui.reports') }}</h1>
                    <p class="text-sm text-slate-500 mt-1">{{ app()->getLocale() == 'en' ? 'Comprehensive financial audit, realtime cashflow monitoring, and performance analytics.' : 'Audit keuangan komprehensif, pemantauan arus kas waktu nyata, dan analisis kinerja.' }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="glass-card p-6">
                <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <input type="hidden" name="tab" :value="tab">
                    <div class="space-y-2 md:col-span-3">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Start Date' : 'Tanggal Mulai' }}</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 focus:bg-white transition-colors font-medium">
                    </div>
                    <div class="space-y-2 md:col-span-3">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'End Date' : 'Tanggal Selesai' }}</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 focus:bg-white transition-colors font-medium">
                    </div>
                    <div class="space-y-2 md:col-span-3">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Client Account' : 'Akun Klien' }}</label>
                        <select name="client_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 focus:bg-white transition-colors font-medium">
                            <option value="">{{ app()->getLocale() == 'en' ? 'All Clients' : 'Semua Klien' }}</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $clientId == $client->id ? 'selected' : '' }}>{{ $client->nama_client }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-wrap gap-2 md:col-span-3">
                        <button type="submit" class="flex-1 btn-premium py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Apply Filter' : 'Terapkan Filter' }}</button>
                        <button type="submit" formaction="{{ route('reports.export') }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white transition-colors duration-300 py-2.5 px-4 rounded-xl font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span>{{ app()->getLocale() == 'en' ? 'Export Excel' : 'Ekspor Excel' }}</span>
                        </button>
                        <a href="{{ route('reports.index') }}" class="btn-secondary py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center">Reset</a>
                    </div>
                </form>
            </div>

            <div>
                <!-- Tabs -->
                <div class="flex flex-wrap items-center gap-4 sm:gap-8 border-b border-slate-100 mb-10">
                    <button @click="tab = 'invoices'" :class="tab === 'invoices' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent'" class="pb-4 text-xs font-black border-b-2 transition-all uppercase tracking-widest">
                        {{ app()->getLocale() == 'en' ? 'Invoice Performance' : 'Kinerja Faktur' }}
                    </button>
                    <button @click="tab = 'receipts'" :class="tab === 'receipts' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent'" class="pb-4 text-xs font-black border-b-2 transition-all uppercase tracking-widest">
                        {{ app()->getLocale() == 'en' ? 'Receipts & Payments' : 'Kuitansi & Pembayaran' }}
                    </button>
                    <button @click="tab = 'clients'" :class="tab === 'clients' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent'" class="pb-4 text-xs font-black border-b-2 transition-all uppercase tracking-widest">
                        {{ app()->getLocale() == 'en' ? 'Client Analytics & Trends' : 'Analisis Klien & Tren' }}
                    </button>
                </div>

                <!-- Invoice Tab -->
                <div x-show="tab === 'invoices'" x-transition>
                    <!-- Metric Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <!-- Total Faktur -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/5 blur-xl group-hover:bg-indigo-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ app()->getLocale() == 'en' ? 'Total Invoices' : 'Total Faktur' }}</p>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight">{{ $invoiceStats['total_count'] }}</h3>
                            <div class="mt-4 flex items-center gap-1.5">
                                @if($invoiceStats['count_growth'] > 0)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 shadow-sm">
                                        <i data-lucide="trending-up" class="w-2.5 h-2.5"></i>
                                        +{{ number_format($invoiceStats['count_growth'], 1) }}%
                                    </span>
                                @elseif($invoiceStats['count_growth'] < 0)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-600 shadow-sm">
                                        <i data-lucide="trending-down" class="w-2.5 h-2.5"></i>
                                        {{ number_format($invoiceStats['count_growth'], 1) }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-500 shadow-sm">
                                        <i data-lucide="minus" class="w-2.5 h-2.5"></i>
                                        0.0%
                                    </span>
                                @endif
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'vs last period' : 'dibanding periode lalu' }}</span>
                            </div>
                        </div>

                        <!-- Total Tagihan Kotor -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/5 blur-xl group-hover:bg-indigo-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ app()->getLocale() == 'en' ? 'Gross Billing' : 'Total Tagihan Kotor' }}</p>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="banknote" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight">Rp {{ number_format($invoiceStats['total_value'], 0, ',', '.') }}</h3>
                            <div class="mt-4 flex items-center gap-1.5">
                                @if($invoiceStats['value_growth'] > 0)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 shadow-sm">
                                        <i data-lucide="trending-up" class="w-2.5 h-2.5"></i>
                                        +{{ number_format($invoiceStats['value_growth'], 1) }}%
                                    </span>
                                @elseif($invoiceStats['value_growth'] < 0)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-600 shadow-sm">
                                        <i data-lucide="trending-down" class="w-2.5 h-2.5"></i>
                                        {{ number_format($invoiceStats['value_growth'], 1) }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-500 shadow-sm">
                                        <i data-lucide="minus" class="w-2.5 h-2.5"></i>
                                        0.0%
                                    </span>
                                @endif
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'vs last period' : 'dibanding periode lalu' }}</span>
                            </div>
                        </div>

                        <!-- Total Tunggakan -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-500/5 blur-xl group-hover:bg-rose-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ app()->getLocale() == 'en' ? 'Total Outstanding' : 'Total Tunggakan' }}</p>
                                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-rose-600 font-jakarta tracking-tight">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</h3>
                            <div class="mt-4 flex items-center gap-1.5">
                                @if($outstandingGrowth > 0)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-600 shadow-sm">
                                        <i data-lucide="trending-up" class="w-2.5 h-2.5"></i>
                                        +{{ number_format($outstandingGrowth, 1) }}%
                                    </span>
                                @elseif($outstandingGrowth < 0)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 shadow-sm">
                                        <i data-lucide="trending-down" class="w-2.5 h-2.5"></i>
                                        {{ number_format($outstandingGrowth, 1) }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-500 shadow-sm">
                                        <i data-lucide="minus" class="w-2.5 h-2.5"></i>
                                        0.0%
                                    </span>
                                @endif
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'vs last period' : 'dibanding periode lalu' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Status Breakdown Card (Left) -->
                        <div class="glass-card overflow-hidden flex flex-col justify-between">
                            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                                <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">{{ app()->getLocale() == 'en' ? 'Status Breakdown' : 'Rincian Status' }}</h4>
                            </div>
                            <div class="p-8 space-y-6">
                                @forelse($invoiceStats['status_breakdown'] as $stat)
                                    <div class="flex items-center justify-between hover:translate-x-1 transition-transform">
                                        <div class="flex items-center gap-3">
                                            <x-badge :status="$stat->status" />
                                            <span class="text-xs font-bold text-slate-500">{{ $stat->count }} {{ app()->getLocale() == 'en' ? 'Items' : 'Item' }}</span>
                                        </div>
                                        <span class="text-sm font-black text-slate-900 font-jakarta">Rp {{ number_format($stat->total, 0, ',', '.') }}</span>
                                    </div>
                                @empty
                                    <div class="py-12 text-center text-slate-400 italic text-sm">
                                        {{ app()->getLocale() == 'en' ? 'No status data' : 'Tidak ada data status' }}
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Revenue vs. Receivables Trend Chart (Right) -->
                        <div class="glass-card p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">{{ app()->getLocale() == 'en' ? 'Revenue & Receivables Trend' : 'Tren Pendapatan & Piutang' }}</h4>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Monthly financial trends comparison' : 'Perbandingan tren keuangan bulanan' }}</p>
                                </div>
                            </div>
                            <!-- Responsive Chart Container -->
                            <div class="relative h-[250px] w-full">
                                <div id="trendChart" class="absolute inset-0"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transaction Logs Table -->
                    <div class="glass-card overflow-hidden mt-10">
                        <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">{{ app()->getLocale() == 'en' ? 'Recent Transaction Logs' : 'Aktivitas Transaksi Terakhir' }}</h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Realtime invoice audit log' : 'Log audit faktur secara realtime' }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                <i data-lucide="history" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-50/30">
                                        <th class="px-8 py-4">{{ app()->getLocale() == 'en' ? 'Invoice Number' : 'No. Faktur' }}</th>
                                        <th class="px-8 py-4">{{ app()->getLocale() == 'en' ? 'Client' : 'Klien' }}</th>
                                        <th class="px-8 py-4">{{ app()->getLocale() == 'en' ? 'Modified Date' : 'Tanggal Diubah' }}</th>
                                        <th class="px-8 py-4 text-right">{{ app()->getLocale() == 'en' ? 'Amount' : 'Jumlah' }}</th>
                                        <th class="px-8 py-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($recentInvoices as $invoice)
                                        <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                            <td class="px-8 py-4">
                                                <span class="text-xs font-black text-indigo-600">{{ $invoice->invoice_number }}</span>
                                            </td>
                                            <td class="px-8 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-slate-900">{{ $invoice->client->nama_client }}</span>
                                                    <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">{{ $invoice->client->nama_perusahaan }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-4 text-xs font-medium text-slate-500">
                                                {{ $invoice->updated_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <span class="text-xs font-black text-slate-900">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="px-8 py-4 text-center">
                                                <x-badge :status="$invoice->status" />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic text-sm">
                                                {{ app()->getLocale() == 'en' ? 'No recent activity' : 'Tidak ada aktivitas terbaru' }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Receipt Tab -->
                <div x-show="tab === 'receipts'" x-transition>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <!-- Total Terkumpul -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/5 blur-xl group-hover:bg-emerald-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ app()->getLocale() == 'en' ? 'Total Collected' : 'Total Terkumpul' }}</p>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-emerald-600 font-jakarta tracking-tight">Rp {{ number_format($paymentStats['total_collected'], 0, ',', '.') }}</h3>
                            <div class="mt-4 flex items-center gap-1.5">
                                @if($paymentStats['collected_growth'] > 0)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 shadow-sm">
                                        <i data-lucide="trending-up" class="w-2.5 h-2.5"></i>
                                        +{{ number_format($paymentStats['collected_growth'], 1) }}%
                                    </span>
                                @elseif($paymentStats['collected_growth'] < 0)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-600 shadow-sm">
                                        <i data-lucide="trending-down" class="w-2.5 h-2.5"></i>
                                        {{ number_format($paymentStats['collected_growth'], 1) }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-500 shadow-sm">
                                        <i data-lucide="minus" class="w-2.5 h-2.5"></i>
                                        0.0%
                                    </span>
                                @endif
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'vs last period' : 'dibanding periode lalu' }}</span>
                            </div>
                        </div>

                        <!-- Tingkat Pengumpulan -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/5 blur-xl group-hover:bg-indigo-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ app()->getLocale() == 'en' ? 'Collection Rate' : 'Tingkat Pengumpulan' }}</p>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="percent" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight">
                                {{ $invoiceStats['total_value'] > 0 ? round(($paymentStats['total_collected'] / $invoiceStats['total_value']) * 100) : 0 }}%
                            </h3>
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-4 shadow-inner">
                                <div class="bg-indigo-600 h-full" style="width: {{ $invoiceStats['total_value'] > 0 ? ($paymentStats['total_collected'] / $invoiceStats['total_value']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card overflow-hidden">
                        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">{{ app()->getLocale() == 'en' ? 'Recent Payments History' : 'Riwayat Pembayaran Terbaru' }}</h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Audit payment ledger' : 'Buku besar audit pembayaran' }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                                <i data-lucide="receipt" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-50/30">
                                        <th class="px-8 py-4">{{ app()->getLocale() == 'en' ? 'Date' : 'Tanggal' }}</th>
                                        <th class="px-8 py-4">{{ app()->getLocale() == 'en' ? 'Client' : 'Klien' }}</th>
                                        <th class="px-8 py-4">{{ app()->getLocale() == 'en' ? 'Invoice #' : 'No. Faktur' }}</th>
                                        <th class="px-8 py-4 text-right">{{ app()->getLocale() == 'en' ? 'Amount' : 'Jumlah' }}</th>
                                        <th class="px-8 py-4">{{ app()->getLocale() == 'en' ? 'Method' : 'Metode' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($paymentStats['recent_payments'] as $payment)
                                        <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                            <td class="px-8 py-4 text-xs font-medium text-slate-500">{{ $payment->payment_date->format('M d, Y') }}</td>
                                            <td class="px-8 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-slate-900">{{ $payment->invoice->client->nama_client }}</span>
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $payment->invoice->client->nama_perusahaan }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-4">
                                                <span class="text-xs font-bold text-indigo-600">{{ $payment->invoice->invoice_number }}</span>
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <span class="text-xs font-black text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="px-8 py-4">
                                                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg">{{ $payment->payment_method }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic text-sm">
                                                {{ app()->getLocale() == 'en' ? 'No recent payments' : 'Tidak ada pembayaran terbaru' }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Client Analytics & Trends Tab -->
                <div x-show="tab === 'clients'" x-transition>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Top Revenue Drivers -->
                        <div class="glass-card overflow-hidden flex flex-col justify-between">
                            <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                                <div>
                                    <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">{{ app()->getLocale() == 'en' ? 'Top Revenue Drivers' : 'Pendorong Pendapatan Utama' }}</h4>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Highest lifetime value client accounts' : 'Akun klien dengan kontribusi pendapatan tertinggi' }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                                    <i data-lucide="crown" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <div class="p-8 space-y-6">
                                @forelse($topClientRevenue as $client)
                                    <div class="flex items-center justify-between hover:translate-x-1 transition-transform duration-300">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs">
                                                LTV
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-900">{{ $client->nama_client }}</span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $client->nama_perusahaan }}</span>
                                            </div>
                                        </div>
                                        <span class="text-sm font-black text-slate-900 font-jakarta">Rp {{ number_format($client->total_revenue, 0, ',', '.') }}</span>
                                    </div>
                                @empty
                                    <div class="py-12 text-center text-slate-400 italic text-sm">
                                        {{ app()->getLocale() == 'en' ? 'No revenue data in this period' : 'Tidak ada data pendapatan dalam periode ini' }}
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Outstanding Payment Delays -->
                        <div class="glass-card overflow-hidden flex flex-col justify-between">
                            <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                                <div>
                                    <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">{{ app()->getLocale() == 'en' ? 'Outstanding Delays' : 'Tunggakan & Hambatan Pembayaran' }}</h4>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Accounts with highest outstanding balance' : 'Akun dengan saldo tunggakan tertinggi' }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shadow-sm">
                                    <i data-lucide="clock-alert" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <div class="p-8 space-y-6">
                                @forelse($topClientOutstanding as $client)
                                    <div class="flex items-center justify-between hover:translate-x-1 transition-transform duration-300">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center font-black text-xs">
                                                DEBT
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-900">{{ $client->nama_client }}</span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $client->nama_perusahaan }}</span>
                                            </div>
                                        </div>
                                        <span class="text-sm font-black text-rose-600 font-jakarta">Rp {{ number_format($client->total_outstanding, 0, ',', '.') }}</span>
                                    </div>
                                @empty
                                    <div class="py-12 text-center text-slate-400 italic text-sm">
                                        {{ app()->getLocale() == 'en' ? 'No outstanding balance in this period' : 'Tidak ada tunggakan pembayaran dalam periode ini' }}
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categories = {!! json_encode($trendMonths) !!};
            const revenueData = {!! json_encode($trendRevenue) !!};
            const receivablesData = {!! json_encode($trendReceivables) !!};
            
            const options = {
                series: [
                    {
                        name: '{{ app()->getLocale() == "en" ? "Revenue" : "Pendapatan" }}',
                        data: revenueData
                    },
                    {
                        name: '{{ app()->getLocale() == "en" ? "Receivables" : "Piutang" }}',
                        data: receivablesData
                    }
                ],
                chart: {
                    type: 'area',
                    height: '100%',
                    width: '100%',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                colors: ['#10b981', '#f43f5e'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    lineCap: 'round'
                },
                xaxis: {
                    categories: categories,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '10px'
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
                            fontSize: '10px'
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 6,
                    padding: { left: 10, right: 10 }
                },
                markers: {
                    size: 0,
                    hover: { size: 5 }
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

            const chart = new ApexCharts(document.querySelector("#trendChart"), options);
            chart.render();
        });
    </script>
</x-app-layout>
