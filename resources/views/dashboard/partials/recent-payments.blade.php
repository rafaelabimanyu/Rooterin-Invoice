<div class="table-container page-fade-in overflow-hidden h-full flex flex-col justify-between">
    <div>
        <div class="px-10 py-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between bg-slate-50/30 gap-4">
            <div>
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">
                    {{ __('dashboard.recent_payments_title') }}
                </h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                    {{ __('dashboard.recent_payments_subtitle') }}
                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600">
                <i data-lucide="arrow-down-left" class="w-4.5 h-4.5"></i>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="overflow-x-auto w-full hidden md:block">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="table-header">
                        <th class="px-10 py-5">{{ __('ui.date' ?? 'Tanggal') }}</th>
                        <th class="px-10 py-5">{{ __('ui.client' ?? 'Klien') }}</th>
                        <th class="px-10 py-5">{{ __('dashboard.payment_channel') }}</th>
                        <th class="px-10 py-5">{{ __('dashboard.reference_no') }}</th>
                        <th class="px-10 py-5 text-right">{{ __('ui.amount' ?? 'Nominal') }}</th>
                        <th class="px-10 py-5 text-right">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentPayments as $payment)
                        <tr class="table-row-premium group">
                            <td class="px-10 py-6">
                                <span class="text-xs font-bold text-slate-500">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : 'N/A' }}</span>
                            </td>
                            <td class="px-10 py-6">
                                <div class="flex flex-col">
                                    <span class="text-[14px] font-black text-slate-900">{{ $payment->invoice?->client?->nama_client ?? 'N/A' }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $payment->invoice?->client?->nama_perusahaan ?? '-' }}</span>
                                    @if($payment->invoice?->kategori_invoice === 'kemitraan')
                                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100 shadow-sm w-fit">
                                            <i data-lucide="handshake" class="w-2.5 h-2.5"></i>
                                            Kemitraan ({{ $payment->invoice->periode_kontrak }})
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <span class="px-2.5 py-0.5 bg-slate-100 rounded-full text-xs font-bold text-slate-700">{{ $payment->payment_method ?: 'N/A' }}</span>
                            </td>
                            <td class="px-10 py-6">
                                <span class="text-xs font-mono font-bold text-slate-600">{{ $payment->reference_number ?: '-' }}</span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <span class="text-[15px] font-black text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                @if($payment->invoice)
                                    @php
                                        $showRoute = $payment->invoice->kategori_invoice === 'kemitraan' 
                                            ? route('contract-invoices.show', $payment->invoice_id) 
                                            : route('invoices.show', $payment->invoice_id);
                                    @endphp
                                    <a href="{{ $showRoute }}" class="text-xs font-bold text-gold-650 hover:underline inline-flex items-center gap-1">
                                        {{ __('ui.view') }} <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                    </a>
                                @else
                                    <span class="text-xs font-bold text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-10 py-8 text-center text-slate-400 italic text-sm">
                                {{ __('dashboard.no_records_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-slate-100/70">
            @forelse($recentPayments as $payment)
                <div class="p-6 hover:bg-slate-50 transition-colors duration-200">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i data-lucide="arrow-down-left" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-900">{{ $payment->invoice?->client?->nama_client ?? 'N/A' }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">
                                    {{ $payment->payment_date ? $payment->payment_date->format('d M Y') : 'N/A' }} • {{ $payment->payment_method }}
                                </span>
                                @if($payment->invoice?->kategori_invoice === 'kemitraan')
                                    <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100 shadow-sm w-fit">
                                        <i data-lucide="handshake" class="w-2.5 h-2.5"></i>
                                        Kemitraan ({{ $payment->invoice->periode_kontrak }})
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="text-right">
                                <span class="text-[14px] font-black text-emerald-600">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </span>
                            </div>
                            @if($payment->invoice)
                                @php
                                    $showRoute = $payment->invoice->kategori_invoice === 'kemitraan' 
                                        ? route('contract-invoices.show', $payment->invoice_id) 
                                        : route('invoices.show', $payment->invoice_id);
                                @endphp
                                <a href="{{ $showRoute }}" class="text-slate-400 hover:text-gold-600">
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
</div>
