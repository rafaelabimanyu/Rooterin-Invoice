<div class="table-container page-fade-in stagger-6 overflow-hidden mt-8">
    <div class="px-10 py-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between bg-slate-50/30 gap-4">
        <div>
            <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">
                {{ app()->getLocale() == 'en' ? 'Business Unit Performance' : 'Kinerja Unit Bisnis' }}
            </h3>
            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                {{ app()->getLocale() == 'en' ? 'Summary of orders and total revenue per unit' : 'Ringkasan orderan dan total pendapatan per unit bisnis' }}
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
                    <th class="px-10 py-5">{{ app()->getLocale() == 'en' ? 'Business Unit' : 'Unit Bisnis' }}</th>
                    <th class="px-10 py-5 text-center">{{ app()->getLocale() == 'en' ? 'Total Orders' : 'Total Orderan' }}</th>
                    <th class="px-10 py-5 text-right">{{ app()->getLocale() == 'en' ? 'Total Revenue' : 'Total Pendapatan' }}</th>
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
                                <span class="text-[14px] font-black text-slate-900">{{ $summary->name }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="text-[14px] font-bold text-slate-700 bg-slate-100/50 px-3 py-1 rounded-full border border-slate-200/20">{{ $summary->total_orders }}</span>
                        </td>
                        <td class="px-10 py-6 text-right">
                            <span class="text-[15px] font-black text-emerald-600">Rp {{ number_format($summary->total_revenue, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-10 py-8 text-center text-slate-400 italic text-sm">
                            {{ app()->getLocale() == 'en' ? 'No records found.' : 'Tidak ada data ditemukan.' }}
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
                            <span class="text-xs font-black text-slate-900">{{ $summary->name }}</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $summary->total_orders }} {{ app()->getLocale() == 'en' ? 'Orders' : 'Orderan' }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[14px] font-black text-emerald-600">
                            Rp {{ number_format($summary->total_revenue, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-slate-400 italic text-xs">
                {{ app()->getLocale() == 'en' ? 'No records found.' : 'Tidak ada data ditemukan.' }}
            </div>
        @endforelse
    </div>
</div>
