<x-app-layout>
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8 page-fade-in">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight mb-2 uppercase">
                {{ __('ui.command_center') }}</h1>
            <p class="text-sm text-slate-500 font-medium tracking-tight">{{ __('ui.operational_overview') }}</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('invoices.create') }}" class="btn-premium group">
                <i data-lucide="plus" class="w-4 h-4 transition-transform group-hover:rotate-90"></i>
                <span>{{ __('ui.create_invoice') }}</span>
            </a>
        </div>
    </div>

    <!-- KPI Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <div class="page-fade-in stagger-1">
            <x-stats-card :title="__('ui.total_billing')" value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}"
                change="+12.5%" icon="bar-chart-3" color="indigo" detail="<div class='space-y-6'>
                    <div class='p-6 bg-slate-50 rounded-2xl border border-slate-100'>
                        <p class='text-xs font-black text-slate-400 uppercase tracking-widest mb-4'>Revenue Distribution</p>
                        <div class='space-y-4'>
                            <div class='flex items-center justify-between'>
                                <span class='text-[13px] font-bold text-slate-600'>Product Sales</span>
                                <span class='text-[13px] font-black text-slate-900'>Rp 12.500.000</span>
                            </div>
                            <div class='w-full bg-slate-200 h-1.5 rounded-full overflow-hidden'>
                                <div class='bg-indigo-500 h-full w-[70%]'></div>
                            </div>
                            <div class='flex items-center justify-between'>
                                <span class='text-[13px] font-bold text-slate-600'>Service Fees</span>
                                <span class='text-[13px] font-black text-slate-900'>Rp 5.200.000</span>
                            </div>
                            <div class='w-full bg-slate-200 h-1.5 rounded-full overflow-hidden'>
                                <div class='bg-emerald-500 h-full w-[30%]'></div>
                            </div>
                        </div>
                    </div>
                    <div class='grid grid-cols-2 gap-4'>
                        <div class='p-4 bg-indigo-50 rounded-xl border border-indigo-100'>
                            <p class='text-[10px] font-black text-indigo-400 uppercase tracking-widest'>MTD Growth</p>
                            <p class='text-xl font-black text-indigo-700 mt-1'>+18.4%</p>
                        </div>
                        <div class='p-4 bg-emerald-50 rounded-xl border border-emerald-100'>
                            <p class='text-[10px] font-black text-emerald-400 uppercase tracking-widest'>Projected</p>
                            <p class='text-xl font-black text-emerald-700 mt-1'>Rp 25M</p>
                        </div>
                    </div>
                </div>" />
        </div>
        <div class="page-fade-in stagger-2">
            <x-stats-card :title="__('ui.amount_due')" value="Rp {{ number_format($pendingRevenue, 0, ',', '.') }}"
                change="-5.2%" icon="clock" color="amber" detail="<div class='space-y-6'>
                    <div class='flex items-center justify-between p-4 bg-amber-50 rounded-xl border border-amber-100'>
                        <div class='flex items-center gap-3'>
                            <div class='p-2 bg-amber-500 rounded-lg text-white'>
                                <i data-lucide='alert-triangle' class='w-5 h-5'></i>
                            </div>
                            <div>
                                <p class='text-[13px] font-black text-amber-900'>High Risk Arrears</p>
                                <p class='text-[11px] text-amber-600 font-bold'>Overdue by 30+ Days</p>
                            </div>
                        </div>
                        <p class='text-lg font-black text-amber-900'>Rp 2.4M</p>
                    </div>
                </div>" />
        </div>
        <div class="page-fade-in stagger-3">
            <x-stats-card :title="__('ui.clients')" value="{{ $totalClients }}" change="+3" icon="users" color="emerald"
                detail="<div class='space-y-4'>
                    <p class='text-xs font-black text-slate-400 uppercase tracking-widest'>Top Accounts</p>
                    <div class='divide-y divide-slate-100'>
                        <div class='py-3 flex items-center justify-between'>
                            <span class='text-[13px] font-bold text-slate-700'>PT. Maju Bersama</span>
                            <span class='text-[11px] font-black bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded'>VIP</span>
                        </div>
                        <div class='py-3 flex items-center justify-between'>
                            <span class='text-[13px] font-bold text-slate-700'>Global Tech Inc</span>
                            <span class='text-[11px] font-black bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded'>RETAINER</span>
                        </div>
                    </div>
                </div>" />
        </div>
        <div class="page-fade-in stagger-4 glass-card p-7 group hover:-translate-y-3 hover:shadow-[0_20px_50px_rgba(79,70,229,0.15)] hover:border-indigo-500/30 transition-all duration-500 relative overflow-hidden cursor-pointer"
            @click="$dispatch('open-slide-over', { title: 'Collection Efficiency', content: 'Detailed efficiency report content goes here...' })">
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-indigo-500/5 text-indigo-600 flex items-center justify-center border border-indigo-500/10 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                    <i data-lucide="check-circle-2" class="w-7 h-7"></i>
                </div>
                <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-600 shadow-sm">
                    {{ __('ui.efficiency') }}
                </span>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] mb-2">
                    {{ __('ui.collection_rate') }}</p>
                <div class="flex items-end justify-between mb-2">
                    <h3 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight">
                        {{ $totalInvoices > 0 ? round(($paidInvoicesCount / $totalInvoices) * 100) : 0 }}%</h3>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden shadow-inner">
                    <div class="bg-indigo-600 h-full progress-bar-fill shadow-[0_0_12px_rgba(79,70,229,0.5)]"
                        style="width: {{ $totalInvoices > 0 ? ($paidInvoicesCount / $totalInvoices) * 100 : 0 }}%">
                    </div>
                </div>
            </div>
            <div
                class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none">
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="table-container page-fade-in stagger-5">
        <div
            class="px-10 py-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between bg-slate-50/30 gap-4">
            <div>
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">
                    {{ __('ui.billing_operations') }}</h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                    {{ __('ui.latest_transactions') }}</p>
            </div>
            <a href="{{ route('invoices.index') }}" class="btn-secondary group">
                <span>{{ __('ui.view_all_invoices') }}</span>
                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="table-header">
                        <th class="px-10 py-5">{{ __('ui.reference') }}</th>
                        <th class="px-10 py-5">{{ __('ui.entity') }}</th>
                        <th class="px-10 py-5">{{ __('ui.timestamp') }}</th>
                        <th class="px-10 py-5">{{ __('ui.volume') }}</th>
                        <th class="px-10 py-5">{{ __('ui.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentInvoices as $invoice)
                        <tr class="table-row-premium cursor-pointer group"
                            onclick="window.location='{{ route('invoices.show', $invoice) }}'">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center group-hover:bg-slate-900 transition-colors duration-300">
                                        <i data-lucide="hash" class="w-4 h-4 text-slate-400 group-hover:text-white"></i>
                                    </div>
                                    <span
                                        class="text-[13px] font-black text-slate-900 tracking-tight">{{ $invoice->invoice_number }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <div class="flex flex-col">
                                    <span
                                        class="text-[14px] font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $invoice->client->nama_client }}</span>
                                    <span
                                        class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $invoice->client->nama_perusahaan }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-300"></i>
                                    <span
                                        class="text-[12px] text-slate-500 font-bold uppercase">{{ $invoice->tanggal_invoice->translatedFormat('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <span class="text-[15px] font-black text-slate-900 tracking-tighter">Rp
                                    {{ number_format($invoice->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-10 py-6">
                                <x-badge :status="$invoice->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-20">
                                <x-empty-state icon="layers" :title="__('ui.quiet_environment')"
                                    :description="__('ui.no_recent_activity')" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>