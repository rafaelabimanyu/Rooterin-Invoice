<div class="table-container page-fade-in overflow-hidden h-full">
    <div class="px-10 py-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between bg-slate-50/30 gap-4">
        <div>
            <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">
                {{ __('dashboard.bu_performance') }}
            </h3>
            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                {{ __('dashboard.bu_performance_subtitle') }}
            </p>
        </div>
        <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600">
            <i data-lucide="layers" class="w-4.5 h-4.5"></i>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="overflow-x-auto w-full hidden md:block">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="table-header">
                    <th class="px-10 py-5">{{ __('dashboard.business_unit') }}</th>
                    <th class="px-10 py-5 text-center">{{ __('dashboard.total_orders') }}</th>
                    <th class="px-10 py-5 text-right">{{ __('dashboard.total_revenue') }}</th>
                    @if(auth()->user()->role !== 'staff')
                        <th class="px-10 py-5 text-right">{{ __('ui.actions') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($businessUnitSummary as $summary)
                    <tr class="table-row-premium group">
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600 group-hover:bg-gold-500 group-hover:text-slate-950 transition-colors duration-300">
                                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                                </div>
                                @if(auth()->user()->role !== 'staff')
                                    <a href="{{ route('business-units.show', $summary->id) }}" class="text-[14px] font-black text-slate-900 hover:text-gold-600 transition-colors">
                                        {{ $summary->name }}
                                    </a>
                                @else
                                    <span class="text-[14px] font-black text-slate-900">{{ $summary->name }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="text-[14px] font-bold text-slate-700 bg-slate-100/50 px-3 py-1 rounded-full border border-slate-200/20">{{ $summary->total_orders }}</span>
                        </td>
                        <td class="px-10 py-6 text-right">
                            <span class="text-[15px] font-black text-emerald-600">Rp {{ number_format($summary->total_revenue, 0, ',', '.') }}</span>
                        </td>
                        @if(auth()->user()->role !== 'staff')
                            <td class="px-10 py-6 text-right">
                                <a href="{{ route('business-units.show', $summary->id) }}" class="text-xs font-bold text-gold-650 hover:underline inline-flex items-center gap-1">
                                    {{ __('ui.view') }} <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                </a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role !== 'staff' ? 4 : 3 }}" class="px-10 py-8 text-center text-slate-400 italic text-sm">
                            {{ __('dashboard.no_records_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="block md:hidden divide-y divide-slate-100/70">
        @forelse($businessUnitSummary as $summary)
            <div class="p-6 hover:bg-slate-50 transition-colors duration-200">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600">
                            <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="flex flex-col">
                            @if(auth()->user()->role !== 'staff')
                                <a href="{{ route('business-units.show', $summary->id) }}" class="text-xs font-black text-slate-900 hover:text-gold-600">
                                    {{ $summary->name }}
                                </a>
                            @else
                                <span class="text-xs font-black text-slate-900">{{ $summary->name }}</span>
                            @endif
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $summary->total_orders }} {{ __('dashboard.orders') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="text-right">
                            <span class="text-[14px] font-black text-emerald-600">
                                Rp {{ number_format($summary->total_revenue, 0, ',', '.') }}
                            </span>
                        </div>
                        @if(auth()->user()->role !== 'staff')
                            <a href="{{ route('business-units.show', $summary->id) }}" class="text-slate-400 hover:text-gold-600">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-slate-400 italic text-xs">
                {{ __('dashboard.no_records_found') }}
            </div>
        @endforelse
    </div>
</div>
