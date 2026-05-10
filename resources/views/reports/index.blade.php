<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-outfit tracking-tight">{{ __('ui.reports') }}</h1>
            <p class="text-sm text-slate-500">Comprehensive financial audit and performance analytics.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card p-6 mb-10">
        <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Client Account</label>
                <select name="client_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $clientId == $client->id ? 'selected' : '' }}>{{ $client->nama_client }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 btn-premium py-2.5">Apply Filter</button>
                <a href="{{ route('reports.index') }}" class="btn-secondary py-2.5">Reset</a>
            </div>
        </form>
    </div>

    <div x-data="{ tab: 'invoices' }">
        <!-- Tabs -->
        <div class="flex items-center gap-8 border-b border-slate-100 mb-10">
            <button @click="tab = 'invoices'" :class="tab === 'invoices' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent'" class="pb-4 text-sm font-bold border-b-2 transition-all">Invoice Performance</button>
            <button @click="tab = 'receipts'" :class="tab === 'receipts' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent'" class="pb-4 text-sm font-bold border-b-2 transition-all">Receipts & Payments</button>
        </div>

        <!-- Invoice Tab -->
        <div x-show="tab === 'invoices'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="glass-card p-8">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Invoices</p>
                    <h3 class="text-3xl font-black text-slate-900 font-outfit">{{ $invoiceStats['total_count'] }}</h3>
                </div>
                <div class="glass-card p-8">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Gross Billing</p>
                    <h3 class="text-3xl font-black text-indigo-600 font-outfit">Rp {{ number_format($invoiceStats['total_value'], 0, ',', '.') }}</h3>
                </div>
                <div class="glass-card p-8">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Outstanding</p>
                    <h3 class="text-3xl font-black text-rose-500 font-outfit">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div class="glass-card overflow-hidden">
                    <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                        <h4 class="font-bold text-slate-900">Status Breakdown</h4>
                    </div>
                    <div class="p-8 space-y-6">
                        @foreach($invoiceStats['status_breakdown'] as $stat)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <x-badge :status="$stat->status" />
                                    <span class="text-xs font-bold text-slate-500">{{ $stat->count }} Items</span>
                                </div>
                                <span class="text-sm font-black text-slate-900">Rp {{ number_format($stat->total, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Tab -->
        <div x-show="tab === 'receipts'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="glass-card p-8">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Collected</p>
                    <h3 class="text-3xl font-black text-emerald-600 font-outfit">Rp {{ number_format($paymentStats['total_collected'], 0, ',', '.') }}</h3>
                </div>
                <div class="glass-card p-8">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Collection Rate</p>
                    <h3 class="text-3xl font-black text-slate-900 font-outfit">
                        {{ $invoiceStats['total_value'] > 0 ? round(($paymentStats['total_collected'] / $invoiceStats['total_value']) * 100) : 0 }}%
                    </h3>
                </div>
            </div>

            <div class="glass-card overflow-hidden">
                <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                    <h4 class="font-bold text-slate-900">Recent Payments History</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50/50">
                                <th class="px-8 py-4">Date</th>
                                <th class="px-8 py-4">Client</th>
                                <th class="px-8 py-4">Invoice #</th>
                                <th class="px-8 py-4 text-right">Amount</th>
                                <th class="px-8 py-4">Method</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($paymentStats['recent_payments'] as $payment)
                                <tr>
                                    <td class="px-8 py-4 text-xs font-medium text-slate-500">{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="px-8 py-4">
                                        <span class="text-xs font-bold text-slate-900">{{ $payment->invoice->client->nama_client }}</span>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="text-xs font-bold text-indigo-600">{{ $payment->invoice->invoice_number }}</span>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <span class="text-xs font-black text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $payment->payment_method }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
