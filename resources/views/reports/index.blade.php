<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span>Intelligence</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600">Business Reports</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">Financial Reports</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Analyze your revenue performance and client activity for {{ $year }}.</p>
        </div>
        <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2">
            <select name="year" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-4 py-2 text-sm font-bold outline-none">
                @for($i = date('Y'); $i >= 2024; $i--)
                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>Year {{ $i }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="glass-card p-6 border-l-4 border-l-indigo-500">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Total Billing</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white font-outfit">{{ $invoiceStats['total'] }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-1">Invoices issued</p>
        </div>
        <div class="glass-card p-6 border-l-4 border-l-emerald-500">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Paid Success</p>
            <p class="text-2xl font-black text-emerald-600 font-outfit">{{ $invoiceStats['paid'] }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-1">Full payment received</p>
        </div>
        <div class="glass-card p-6 border-l-4 border-l-amber-500">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Awaiting Payment</p>
            <p class="text-2xl font-black text-amber-600 font-outfit">{{ $invoiceStats['pending'] }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-1">Partial or sent status</p>
        </div>
        <div class="glass-card p-6 border-l-4 border-l-rose-500">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Overdue Rate</p>
            <p class="text-2xl font-black text-rose-600 font-outfit">{{ $invoiceStats['overdue'] }}</p>
            <p class="text-[10px] text-slate-400 font-medium mt-1">Action required</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Monthly Revenue Chart Placeholder -->
        <div class="lg:col-span-2 glass-card p-8">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-8 flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-4 h-4 text-indigo-500"></i>
                Revenue Streams (Monthly)
            </h3>
            
            <div class="h-64 flex items-end gap-2 px-4">
                @php
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    $max = $monthlyIncome->max('total') ?: 1;
                @endphp
                @foreach($months as $index => $name)
                    @php
                        $mNum = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                        $data = $monthlyIncome->where('month', $mNum)->first();
                        $val = $data ? $data->total : 0;
                        $height = ($val / $max) * 100;
                    @endphp
                    <div class="flex-1 flex flex-col items-center group relative">
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-t-sm transition-all group-hover:bg-indigo-500/20 relative overflow-hidden" style="height: 100%">
                            <div class="absolute bottom-0 left-0 right-0 bg-indigo-500 rounded-t-sm transition-all duration-1000" style="height: {{ $height }}%"></div>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 mt-2 uppercase">{{ $name }}</span>
                        
                        <!-- Tooltip -->
                        <div class="absolute -top-10 scale-0 group-hover:scale-100 transition-all bg-slate-900 text-white text-[10px] py-1 px-2 rounded whitespace-nowrap z-10 font-bold">
                            Rp {{ number_format($val, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Clients -->
        <div class="glass-card p-8">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-8 flex items-center gap-2">
                <i data-lucide="award" class="w-4 h-4 text-amber-500"></i>
                Top Performance Accounts
            </h3>
            
            <div class="space-y-6">
                @foreach($topClients as $client)
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[12px] font-bold text-slate-900 dark:text-white">{{ $client->nama_client }}</span>
                        <span class="text-[10px] text-slate-500 uppercase">{{ $client->invoices_count }} Invoices</span>
                    </div>
                    <div class="text-right">
                        <p class="text-[12px] font-black text-slate-900 dark:text-white">Rp {{ number_format($client->invoices_sum_total, 0, ',', '.') }}</p>
                        <p class="text-[9px] font-bold text-indigo-500 uppercase">Total Volume</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-10 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-800">
                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">
                    Based on total billing volume across all fiscal years. These clients represent your core business partnerships.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
