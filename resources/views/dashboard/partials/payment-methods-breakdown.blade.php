<div class="table-container page-fade-in overflow-hidden h-full flex flex-col justify-between">
    <div>
        <div class="px-10 py-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between bg-slate-50/30 gap-4">
            <div>
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">
                    {{ __('dashboard.payment_methods_title') }}
                </h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                    {{ __('dashboard.payment_methods_subtitle') }}
                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600">
                <i data-lucide="pie-chart" class="w-4.5 h-4.5"></i>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="overflow-x-auto w-full hidden md:block">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="table-header">
                        <th class="px-10 py-5">{{ __('dashboard.payment_channel') }}</th>
                        <th class="px-10 py-5 text-center">{{ __('dashboard.transaction_count') }}</th>
                        <th class="px-10 py-5 w-[150px]">{{ __('ui.progress' ?? 'Persentase') }}</th>
                        <th class="px-10 py-5 text-right">{{ __('dashboard.total_collected') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($paymentMethodsBreakdown as $method)
                        @php
                            $percentage = $totalPaymentsAmount > 0 ? ($method->total_amount / $totalPaymentsAmount * 100) : 0;
                        @endphp
                        <tr class="table-row-premium group">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600 group-hover:bg-gold-500 group-hover:text-slate-950 transition-colors duration-300">
                                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-[14px] font-black text-slate-900">{{ $method->payment_method ?: 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6 text-center">
                                <span class="text-[14px] font-bold text-slate-700 bg-slate-100/50 px-3 py-1 rounded-full border border-slate-200/20">{{ $method->count }}</span>
                            </td>
                            <td class="px-10 py-6">
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gold-500 h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-[10px] text-slate-400 font-bold mt-1 block">{{ number_format($percentage, 1) }}%</span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <span class="text-[15px] font-black text-emerald-600">Rp {{ number_format($method->total_amount, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-10 py-8 text-center text-slate-400 italic text-sm">
                                {{ __('dashboard.no_records_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-slate-100/70">
            @forelse($paymentMethodsBreakdown as $method)
                @php
                    $percentage = $totalPaymentsAmount > 0 ? ($method->total_amount / $totalPaymentsAmount * 100) : 0;
                @endphp
                <div class="p-6 hover:bg-slate-50 transition-colors duration-200">
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600">
                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-900">{{ $method->payment_method ?: 'N/A' }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $method->count }} {{ __('dashboard.transaction_count') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[14px] font-black text-emerald-600">
                                Rp {{ number_format($method->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-gold-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-[9px] text-slate-400 font-bold">{{ number_format($percentage, 1) }}%</span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-slate-400 italic text-xs">
                    {{ __('dashboard.no_records_found') }}
                </div>
            @endforelse
        </div>
    </div>

    <!-- Bottom Stat Footer -->
    @if($totalPaymentsAmount > 0)
        <div class="px-10 py-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between gap-4">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('dashboard.avg_payment_size') }}</span>
            <span class="text-base font-black text-slate-950 font-jakarta">Rp {{ number_format($averagePaymentAmount, 0, ',', '.') }}</span>
        </div>
    @endif
</div>
