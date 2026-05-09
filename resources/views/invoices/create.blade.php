<x-app-layout>
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                <a href="{{ route('invoices.index') }}" class="hover:text-indigo-600 transition-colors">Invoices</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900 dark:text-white">New Invoice</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-outfit leading-tight">Create Invoice</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Configure billing details and items for your client.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('invoices.index') }}" class="px-5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 transition-all">
                Discard
            </a>
        </div>
    </div>

    <form action="{{ route('invoices.store') }}" method="POST" x-data="invoiceForm()">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Side: Main Form -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Customer Selection -->
                <div class="bg-white dark:bg-slate-900 p-10 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-8 pb-4 border-b border-slate-100 dark:border-slate-800">1. Customer Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Select Client Account</label>
                            <select name="client_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none text-sm text-slate-900 dark:text-white transition-all">
                                <option value="">Choose a client...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nama_client }} — {{ $client->nama_perusahaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Invoice Identifier</label>
                            <input type="text" name="invoice_number" value="{{ $invoice_number }}" readonly class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-500 font-mono cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Issuance Date</label>
                            <input type="date" name="tanggal_invoice" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none text-sm text-slate-900 dark:text-white transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Due Date</label>
                            <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none text-sm text-slate-900 dark:text-white transition-all">
                        </div>
                    </div>
                </div>

                <!-- Billing Items -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-10 py-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest">2. Billing Items</h3>
                        <button type="button" @click="addItem" class="text-[12px] font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 transition-colors">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Append Line Item
                        </button>
                    </div>
                    
                    <div class="p-10 space-y-6">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="relative grid grid-cols-1 md:grid-cols-12 gap-6 pb-6 border-b border-slate-50 dark:border-slate-800 last:border-0 last:pb-0 group">
                                <div class="md:col-span-6 space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Description</label>
                                    <input type="text" :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" required placeholder="Service or product description..." class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 dark:text-white font-semibold">
                                </div>
                                <div class="md:col-span-1 space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center block">Qty</label>
                                    <input type="number" step="0.01" :name="`items[${index}][qty]`" x-model="item.qty" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 dark:text-white font-semibold text-center">
                                </div>
                                <div class="md:col-span-2 space-y-2 text-right">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Rate</label>
                                    <input type="number" :name="`items[${index}][harga]`" x-model="item.harga" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 dark:text-white font-semibold text-right">
                                </div>
                                <div class="md:col-span-3 space-y-2 text-right">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Line Total</label>
                                    <div class="text-[13px] font-black text-slate-900 dark:text-white py-0" x-text="formatCurrency(item.qty * item.harga)"></div>
                                </div>
                                
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="absolute -right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all p-1 text-rose-500 hover:bg-rose-50 rounded">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Terms & Memo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Internal Memo</label>
                        <textarea name="notes_internal" rows="3" placeholder="Private notes for the team..." class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-700 dark:text-slate-300"></textarea>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Client Terms</label>
                        <textarea name="terms_condition" rows="3" class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-700 dark:text-slate-300">Net 30. Please remit payment via bank transfer.</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Side: Calculations -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-[#1e293b] text-white p-10 rounded-xl shadow-2xl space-y-8 sticky top-24">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Financial Summary</h3>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400 font-medium">Net Subtotal</span>
                            <span class="font-bold font-mono" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        
                        <div class="flex justify-between items-center gap-4">
                            <span class="text-slate-400 text-sm font-medium">Tax Rate (%)</span>
                            <input type="number" name="tax_percent" x-model="tax_percent" @input="calculateTotal()" class="w-20 bg-slate-800 border-none rounded text-right text-sm font-bold text-indigo-400 p-1 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div class="flex justify-between items-center gap-4">
                            <span class="text-slate-400 text-sm font-medium">Adjustment (%)</span>
                            <input type="number" name="discount_percent" x-model="discount_percent" @input="calculateTotal()" class="w-20 bg-slate-800 border-none rounded text-right text-sm font-bold text-rose-400 p-1 focus:ring-1 focus:ring-rose-500">
                        </div>

                        <div class="pt-6 border-t border-slate-700 space-y-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Grand Total Due</p>
                            <h4 class="text-4xl font-black text-white font-outfit" x-text="formatCurrency(total)"></h4>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[13px] transition-all shadow-lg shadow-indigo-600/20 uppercase tracking-widest flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Confirm & Issue
                    </button>
                    
                    <p class="text-[10px] text-center text-slate-500 font-bold uppercase tracking-widest">Enterprise Billing System v2.0</p>
                </div>
            </div>
        </div>
    </form>

    <script>
        function invoiceForm() {
            return {
                items: [
                    { deskripsi: '', qty: 1, harga: 0 }
                ],
                subtotal: 0,
                tax_percent: 0,
                discount_percent: 0,
                total: 0,
                
                addItem() {
                    this.items.push({ deskripsi: '', qty: 1, harga: 0 });
                    this.$nextTick(() => lucide.createIcons());
                },
                
                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateTotal();
                },
                
                calculateTotal() {
                    this.subtotal = this.items.reduce((acc, item) => acc + (item.qty * Math.max(0, item.harga)), 0);
                    let taxAmount = this.subtotal * (this.tax_percent / 100);
                    let discountAmount = this.subtotal * (this.discount_percent / 100);
                    this.total = this.subtotal + taxAmount - discountAmount;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value);
                }
            }
        }
    </script>
</x-app-layout>
