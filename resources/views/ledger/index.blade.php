<x-app-layout :title="__('ui.ledger_title')">
    {{-- Page Header --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                <span class="text-slate-900">{{ __('ui.ledger_title') }}</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 font-outfit leading-tight">{{ __('ui.ledger_title') }}</h1>
            <p class="text-slate-500 mt-1 text-sm">{{ __('ui.ledger_subtitle') }}</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-xs font-bold uppercase tracking-wider">
            <i data-lucide="lock" class="w-3.5 h-3.5"></i>
            {{ app()->getLocale() == 'en' ? 'Read Only' : 'Hanya Baca' }}
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('ledger.index') }}" class="mb-6 bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ app()->getLocale() == 'en' ? 'Search invoice number or client...' : 'Cari nomor invoice atau klien...' }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 outline-none focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
                    <option value="">{{ __('All') }}</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('ui.draft') }}</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>{{ __('ui.unpaid') }}</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('ui.paid') }}</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>{{ __('ui.overdue') }}</option>
                </select>
            </div>
            <div>
                <select name="business_unit_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
                    <option value="">{{ __('ui.all_units') }}</option>
                    @foreach($businessUnits as $bu)
                        <option value="{{ $bu->id }}" {{ request('business_unit_id') == $bu->id ? 'selected' : '' }}>{{ $bu->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="doc_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
                    <option value="">{{ __('ui.all_types') }}</option>
                    <option value="invoice" {{ request('doc_type') == 'invoice' ? 'selected' : '' }}>{{ __('ui.invoice_doc') }} {{ app()->getLocale() == 'en' ? 'Only' : 'Saja' }}</option>
                    <option value="receipt" {{ request('doc_type') == 'receipt' ? 'selected' : '' }}>{{ __('ui.receipt_doc') }} {{ app()->getLocale() == 'en' ? 'Linked' : 'Terhubung' }}</option>
                </select>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-4">
            <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-800 transition-all flex items-center gap-2">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                {{ __('ui.filter') }}
            </button>
            <a href="{{ route('ledger.index') }}" class="px-5 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-200 transition-all">
                {{ __('ui.reset') }}
            </a>
        </div>
    </form>

    {{-- Ledger Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="text-left px-6 py-4 text-[11px] font-black uppercase tracking-widest">{{ __('ui.document_type') }}</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Invoice #' : 'No. Invoice' }}</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Receipt #' : 'No. Kwitansi' }}</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Client' : 'Klien' }}</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Unit' : 'Unit Bisnis' }}</th>
                        <th class="text-right px-6 py-4 text-[11px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Total' : 'Total' }}</th>
                        <th class="text-center px-6 py-4 text-[11px] font-black uppercase tracking-widest">{{ __('ui.status') }}</th>
                        <th class="text-left px-6 py-4 text-[11px] font-black uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Date' : 'Tanggal' }}</th>
                        <th class="text-center px-6 py-4 text-[11px] font-black uppercase tracking-widest">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        {{-- Type Badge --}}
                        <td class="px-6 py-4">
                            @if($invoice->receipt)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-bold uppercase tracking-wide">
                                    <i data-lucide="file-check" class="w-3 h-3"></i>
                                    {{ __('ui.receipt_doc') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[10px] font-bold uppercase tracking-wide">
                                    <i data-lucide="file-text" class="w-3 h-3"></i>
                                    {{ __('ui.invoice_doc') }}
                                </span>
                            @endif
                        </td>

                        {{-- Invoice Number --}}
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-slate-900 text-xs">{{ $invoice->invoice_number }}</span>
                        </td>

                        {{-- Receipt Number --}}
                        <td class="px-6 py-4">
                            @if($invoice->receipt)
                                <span class="font-mono text-xs text-emerald-700 font-semibold">{{ $invoice->receipt->receipt_number }}</span>
                            @else
                                <span class="text-xs text-slate-400 italic">{{ __('ui.no_linked_receipt') }}</span>
                            @endif
                        </td>

                        {{-- Client --}}
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900 text-xs">{{ optional($invoice->client)->nama_client ?? '-' }}</div>
                            @if(optional($invoice->client)->nama_perusahaan)
                                <div class="text-[11px] text-slate-400">{{ $invoice->client->nama_perusahaan }}</div>
                            @endif
                        </td>

                        {{-- Business Unit --}}
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-600 font-medium">{{ optional($invoice->businessUnit)->name ?? '-' }}</span>
                        </td>

                        {{-- Total --}}
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-slate-900 text-sm font-mono">
                                {{ \App\Models\Setting::get('currency_symbol', 'Rp') }} {{ number_format($invoice->total, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusMap = [
                                    'paid'    => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'check-circle'],
                                    'unpaid'  => ['bg-rose-50 text-rose-700 border-rose-200', 'clock'],
                                    'overdue' => ['bg-orange-50 text-orange-700 border-orange-200', 'alert-circle'],
                                    'draft'   => ['bg-slate-100 text-slate-600 border-slate-200', 'file'],
                                ];
                                [$cls, $icon] = $statusMap[$invoice->status] ?? ['bg-slate-100 text-slate-500 border-slate-200', 'minus'];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border text-[10px] font-bold uppercase tracking-wide {{ $cls }}">
                                <i data-lucide="{{ $icon }}" class="w-3 h-3"></i>
                                {{ __('ui.' . $invoice->status) ?? $invoice->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                            {{ $invoice->due_date?->format(\App\Models\Setting::get('date_format', 'd M Y')) ?? $invoice->created_at?->format(\App\Models\Setting::get('date_format', 'd M Y')) ?? '-' }}
                        </td>


                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('invoices.show', $invoice) }}"
                                   title="{{ __('ui.view_invoice') }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-700 text-white rounded-lg text-[10px] font-bold uppercase tracking-wide transition-all">
                                    <i data-lucide="file-text" class="w-3 h-3"></i>
                                    {{ __('ui.view_invoice') }}
                                </a>
                                @if($invoice->receipt)
                                <a href="{{ route('receipts.show', $invoice->receipt) }}"
                                   title="{{ __('ui.view_receipt') }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold uppercase tracking-wide transition-all">
                                    <i data-lucide="file-check" class="w-3 h-3"></i>
                                    {{ __('ui.view_receipt') }}
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-20 text-center">
                            <i data-lucide="book-open" class="w-12 h-12 text-slate-200 mx-auto mb-3"></i>
                            <p class="text-slate-400 font-semibold text-sm">{{ __('ui.empty_data') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>

    {{-- Summary footer --}}
    <div class="mt-4 text-xs text-slate-400 text-right">
        {{ app()->getLocale() == 'en' ? 'Showing' : 'Menampilkan' }}
        {{ $invoices->firstItem() ?? 0 }}–{{ $invoices->lastItem() ?? 0 }}
        {{ app()->getLocale() == 'en' ? 'of' : 'dari' }}
        {{ $invoices->total() }}
        {{ app()->getLocale() == 'en' ? 'records' : 'data' }}
    </div>
</x-app-layout>
