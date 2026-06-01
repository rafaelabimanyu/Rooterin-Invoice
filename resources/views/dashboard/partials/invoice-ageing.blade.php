@if($cardless ?? false)
    <div class="flex items-center justify-between mb-6">
        <div>
            <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-xs">
                {{ app()->getLocale() == 'en' ? 'Accounts Receivable Ageing' : 'Umur Piutang (Ageing)' }}
            </h4>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                {{ app()->getLocale() == 'en' ? 'Unpaid invoices breakdown by due date' : 'Rincian piutang berdasarkan keterlambatan jatuh tempo' }}
            </p>
        </div>
        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
            <i data-lucide="clock" class="w-4.5 h-4.5"></i>
        </div>
    </div>

    @php
        $totalUnpaid = array_sum($invoiceAgeing);
        $maxAgeingVal = max($invoiceAgeing) ?: 1;
    @endphp

    <div class="space-y-4">
        <!-- Current -->
        <div class="space-y-1.5">
            <div class="flex justify-between text-xs font-bold text-slate-700">
                <span>{{ app()->getLocale() == 'en' ? 'Current (Not Due)' : 'Belum Jatuh Tempo' }}</span>
                <span class="font-black text-slate-900">Rp {{ number_format($invoiceAgeing['current'], 0, ',', '.') }}</span>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ ($invoiceAgeing['current'] / $maxAgeingVal) * 100 }}%"></div>
            </div>
        </div>

        <!-- 1-30 Days -->
        <div class="space-y-1.5">
            <div class="flex justify-between text-xs font-bold text-slate-700">
                <span>{{ app()->getLocale() == 'en' ? '1 - 30 Days Overdue' : 'Tunggakan 1 - 30 Hari' }}</span>
                <span class="font-black text-amber-600">Rp {{ number_format($invoiceAgeing['overdue_1_30'], 0, ',', '.') }}</span>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ ($invoiceAgeing['overdue_1_30'] / $maxAgeingVal) * 100 }}%"></div>
            </div>
        </div>

        <!-- 31-60 Days -->
        <div class="space-y-1.5">
            <div class="flex justify-between text-xs font-bold text-slate-700">
                <span>{{ app()->getLocale() == 'en' ? '31 - 60 Days Overdue' : 'Tunggakan 31 - 60 Hari' }}</span>
                <span class="font-black text-orange-500">Rp {{ number_format($invoiceAgeing['overdue_31_60'], 0, ',', '.') }}</span>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-orange-500 h-full rounded-full transition-all duration-500" style="width: {{ ($invoiceAgeing['overdue_31_60'] / $maxAgeingVal) * 100 }}%"></div>
            </div>
        </div>

        <!-- 60+ Days -->
        <div class="space-y-1.5">
            <div class="flex justify-between text-xs font-bold text-slate-700">
                <span>{{ app()->getLocale() == 'en' ? '60+ Days Overdue' : 'Tunggakan 60+ Hari' }}</span>
                <span class="font-black text-rose-600">Rp {{ number_format($invoiceAgeing['overdue_60_plus'], 0, ',', '.') }}</span>
            </div>
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-rose-500 h-full rounded-full transition-all duration-500" style="width: {{ ($invoiceAgeing['overdue_60_plus'] / $maxAgeingVal) * 100 }}%"></div>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-4 border-t border-slate-50 flex justify-between items-center">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Total Outstanding' : 'Total Piutang Aktif' }}</span>
        <span class="text-sm font-black text-indigo-600 font-jakarta">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</span>
    </div>
@else
    <div class="glass-card p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300">
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                        {{ app()->getLocale() == 'en' ? 'Accounts Receivable Ageing' : 'Umur Piutang (Ageing)' }}
                    </h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                        {{ app()->getLocale() == 'en' ? 'Unpaid invoices breakdown by due date' : 'Rincian piutang berdasarkan keterlambatan jatuh tempo' }}
                    </p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                    <i data-lucide="clock" class="w-4.5 h-4.5"></i>
                </div>
            </div>

            @php
                $totalUnpaid = array_sum($invoiceAgeing);
                $maxAgeingVal = max($invoiceAgeing) ?: 1;
            @endphp

            <div class="space-y-4">
                <!-- Current -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-slate-700">
                        <span>{{ app()->getLocale() == 'en' ? 'Current (Not Due)' : 'Belum Jatuh Tempo' }}</span>
                        <span class="font-black text-slate-900">Rp {{ number_format($invoiceAgeing['current'], 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ ($invoiceAgeing['current'] / $maxAgeingVal) * 100 }}%"></div>
                    </div>
                </div>

                <!-- 1-30 Days -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-slate-700">
                        <span>{{ app()->getLocale() == 'en' ? '1 - 30 Days Overdue' : 'Tunggakan 1 - 30 Hari' }}</span>
                        <span class="font-black text-amber-600">Rp {{ number_format($invoiceAgeing['overdue_1_30'], 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ ($invoiceAgeing['overdue_1_30'] / $maxAgeingVal) * 100 }}%"></div>
                    </div>
                </div>

                <!-- 31-60 Days -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-slate-700">
                        <span>{{ app()->getLocale() == 'en' ? '31 - 60 Days Overdue' : 'Tunggakan 31 - 60 Hari' }}</span>
                        <span class="font-black text-orange-500">Rp {{ number_format($invoiceAgeing['overdue_31_60'], 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-orange-500 h-full rounded-full transition-all duration-500" style="width: {{ ($invoiceAgeing['overdue_31_60'] / $maxAgeingVal) * 100 }}%"></div>
                    </div>
                </div>

                <!-- 60+ Days -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-slate-700">
                        <span>{{ app()->getLocale() == 'en' ? '60+ Days Overdue' : 'Tunggakan 60+ Hari' }}</span>
                        <span class="font-black text-rose-600">Rp {{ number_format($invoiceAgeing['overdue_60_plus'], 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-rose-500 h-full rounded-full transition-all duration-500" style="width: {{ ($invoiceAgeing['overdue_60_plus'] / $maxAgeingVal) * 100 }}%"></div>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-50 flex justify-between items-center">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Total Outstanding' : 'Total Piutang Aktif' }}</span>
                <span class="text-sm font-black text-indigo-600 font-jakarta">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
@endif
