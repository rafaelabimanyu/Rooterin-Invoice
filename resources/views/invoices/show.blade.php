<x-app-layout>
    <div class="mb-8 md:mb-12 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 overflow-hidden">
                <a href="{{ route('invoices.index') }}" class="hover:text-indigo-600 transition-colors shrink-0">Invoices</a>
                <i data-lucide="chevron-right" class="w-3 h-3 shrink-0"></i>
                <span class="text-slate-900 truncate">{{ $invoice->invoice_number }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 font-outfit leading-tight truncate">Invoice Details</h1>
            <p class="text-sm text-slate-500 mt-1 truncate">Manage billing for {{ $invoice->client->nama_client }}.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 md:gap-4">
            <div class="flex items-center gap-2">
                <x-badge :status="$invoice->status" class="px-3 py-1.5 text-[10px] md:text-[11px]" />
                <div class="h-8 w-px bg-slate-200 mx-1 hidden sm:block"></div>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start">
                <div class="flex items-center gap-2">
                    <button title="Print" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 transition-all shadow-sm active:scale-95">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                    </button>
                    <a href="{{ route('invoices.pdf', $invoice) }}" title="Download PDF" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-indigo-600 transition-all shadow-sm active:scale-95">
                        <i data-lucide="download" class="w-4 h-4"></i>
                    </a>
                </div>
                <button class="flex-1 sm:flex-none px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2 active:scale-95">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span class="whitespace-nowrap">{{ __('ui.send_invoice') ?? 'Send' }}</span>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[24px] md:rounded-[32px] border border-slate-200/60 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] overflow-hidden max-w-5xl mx-auto mb-20 relative w-full">
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/30 rounded-full blur-3xl -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-slate-50 rounded-full blur-3xl -ml-32 -mb-32"></div>
        <!-- Professional Invoice Header -->
        <div class="p-6 sm:p-10 md:p-16 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-8 sm:gap-12">
                <div class="space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#0f172a] flex items-center justify-center text-white font-bold">R</div>
                        <span class="text-xl font-black text-slate-900 tracking-tighter uppercase">Rooterin<span class="text-indigo-500">.</span></span>
                    </div>
                    <div class="space-y-1 text-sm text-slate-500">
                        <p class="font-bold text-slate-900">Rooterin Technical Services</p>
                        <p>Sudirman Central Business District (SCBD)</p>
                        <p>Jakarta Selatan, 12190</p>
                        <p class="pt-2 font-medium">contact@rooterin.com</p>
                    </div>
                </div>
                
                <div class="text-right">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em] mb-6">Tax Invoice</h2>
                    <div class="space-y-1">
                        <p class="text-2xl font-black text-slate-900 font-outfit">{{ $invoice->invoice_number }}</p>
                        <p class="text-xs font-bold text-slate-500">Issued: {{ $invoice->tanggal_invoice->format('M d, Y') }}</p>
                        <div class="inline-block mt-4 px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded text-[10px] font-bold uppercase tracking-widest">
                            Due: {{ $invoice->due_date->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Relations -->
        <div class="grid grid-cols-1 sm:grid-cols-2 p-6 sm:p-10 md:p-16 gap-10 sm:gap-16 bg-slate-50/30 border-b border-slate-100">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Customer Account</p>
                <div class="space-y-2">
                    <p class="text-base md:text-lg font-black text-slate-900">{{ $invoice->client->nama_client }}</p>
                    <p class="text-xs md:text-sm font-bold text-indigo-600">{{ $invoice->client->nama_perusahaan }}</p>
                    <p class="text-xs md:text-sm text-slate-500 leading-relaxed max-w-xs">
                        {{ $invoice->client->alamat }}<br>
                        {{ $invoice->client->kota }}, {{ $invoice->client->provinsi }}
                    </p>
                </div>
            </div>
            
            <div class="text-left sm:text-right flex flex-col items-start sm:items-end justify-center">
                <div class="p-6 md:p-8 bg-white rounded-xl border border-slate-200 shadow-sm w-full sm:w-auto">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Payable</p>
                    <p class="text-2xl md:text-3xl font-black text-slate-900 font-outfit">Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Line Items -->
        <div class="p-0 md:p-16">
            <!-- Desktop Table -->
            <table class="hidden md:table w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase text-slate-400 tracking-widest border-b border-slate-200">
                        <th class="pb-4 px-16 md:px-0">Line Item Description</th>
                        <th class="pb-4 text-center w-24">Qty</th>
                        <th class="pb-4 text-right w-40">Rate</th>
                        <th class="pb-4 text-right w-40">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="py-6 px-16 md:px-0">
                                <p class="text-[13px] font-bold text-slate-900">{{ $item->deskripsi }}</p>
                            </td>
                            <td class="py-6 text-center text-[13px] font-medium text-slate-600">{{ number_format($item->qty, 0) }}</td>
                            <td class="py-6 text-right text-[13px] font-medium text-slate-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="py-6 text-right text-[13px] font-black text-slate-900">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Mobile Card Style Items -->
            <div class="md:hidden divide-y divide-slate-100">
                @foreach($invoice->items as $item)
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Item Description</p>
                            <p class="text-sm font-bold text-slate-900">{{ $item->deskripsi }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Qty / Rate</p>
                                <p class="text-[11px] font-medium text-slate-600">
                                    {{ number_format($item->qty, 0) }} &times; Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Subtotal</p>
                                <p class="text-[13px] font-black text-slate-900">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Calculation Summary -->
        <div class="p-6 sm:p-10 md:p-16 bg-slate-50/20 border-t border-slate-100">
            <div class="flex flex-col md:flex-row justify-between gap-10 md:gap-12">
                <div class="flex-1 space-y-8">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Terms & Conditions</p>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ $invoice->terms_condition }}</p>
                    </div>
                    
                    <!-- Payment History Section -->
                    @if($invoice->payments->count() > 0)
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Payment History</p>
                        <div class="space-y-3">
                            @foreach($invoice->payments as $payment)
                            <div class="flex items-center justify-between p-3 bg-white border border-slate-200/60 rounded-lg">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-slate-900">{{ $payment->payment_date->format('M d, Y') }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase">{{ $payment->payment_method }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-[12px] font-black text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Delete this payment record?')">
                                        @csrf @method('DELETE')
                                        <button class="text-slate-300 hover:text-rose-500 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="w-full md:w-80 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Subtotal</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">VAT ({{ $invoice->tax_percent }}%)</span>
                        <span class="font-bold text-slate-900">+ Rp {{ number_format($invoice->subtotal * ($invoice->tax_percent / 100), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Adjustment</span>
                        <span class="font-bold text-rose-500">- Rp {{ number_format($invoice->subtotal * ($invoice->discount_percent / 100), 0, ',', '.') }}</span>
                    </div>
                    
                    @if($invoice->status === 'dp')
                    <div class="flex justify-between items-center text-[12px] p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                        <span class="text-indigo-600 font-bold">Remaining Balance</span>
                        <span class="font-black text-indigo-700">Rp {{ number_format($invoice->amount_due, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
                        <span class="text-base md:text-lg font-black text-slate-900 uppercase tracking-tighter">Total Due</span>
                        <span class="text-xl md:text-3xl font-black text-indigo-600 font-outfit">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($invoice->status !== 'paid')
                    <button @click="$dispatch('open-modal', 'record-payment')" class="w-full py-4 bg-[#0f172a] text-white rounded-lg font-bold text-[12px] uppercase tracking-widest hover:bg-slate-800 transition-all mt-6 shadow-xl shadow-slate-900/10">
                        Record Payment
                    </button>
                    @endif
                </div>
            </div>
        </div>
                    
                    @if($invoice->status !== 'paid')
                    <button @click="$dispatch('open-modal', 'record-payment')" class="w-full py-4 bg-[#0f172a] text-white rounded-lg font-bold text-[12px] uppercase tracking-widest hover:bg-slate-800 transition-all mt-6 shadow-xl shadow-slate-900/10">
                        Record Payment
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <x-modal name="record-payment" :show="false">
            <div class="p-10">
                <h3 class="text-xl font-bold text-slate-900 font-outfit mb-2">Record Payment</h3>
                <p class="text-sm text-slate-500 mb-8">Enter the amount received for this invoice.</p>
                
                <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Amount Received (IDR)</label>
                        <input type="number" name="amount" value="{{ $invoice->amount_due }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-lg font-black text-indigo-600 outline-none">
                        <p class="text-[10px] text-slate-400 font-medium italic">Remaining balance: Rp {{ number_format($invoice->amount_due, 0, ',', '.') }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Payment Date</label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Method</label>
                            <select name="payment_method" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none">
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Cash">Cash</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="E-Wallet">E-Wallet</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Reference / Transaction ID</label>
                        <input type="text" name="reference_number" placeholder="e.g. TRX-123456" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none">
                    </div>

                    <div class="pt-6 flex items-center justify-end gap-3">
                        <button type="button" @click="$dispatch('close-modal', 'record-payment')" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800">Cancel</button>
                        <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-lg shadow-indigo-600/20">Save Payment</button>
                    </div>
                </form>
            </div>
        </x-modal>

        <!-- Footer -->
        <div class="px-6 md:px-16 py-10 bg-slate-50 border-t border-slate-100">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-8">
                <div class="text-[11px] text-slate-400 font-medium leading-relaxed max-w-lg text-center sm:text-left">
                    <p class="font-bold text-slate-500 uppercase tracking-widest mb-1">Payment Instructions</p>
                    <p>{{ $invoice->terms_condition }}</p>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Digitally Issued By</p>
                    <p class="text-xs font-black text-slate-900 uppercase">{{ $invoice->creator->name }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
