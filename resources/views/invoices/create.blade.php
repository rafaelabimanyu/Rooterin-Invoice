<x-app-layout>
    <div class="mb-8">
        <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Invoices
        </a>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-outfit">Create Invoice</h1>
        <p class="text-slate-500 dark:text-slate-400">Generate a new professional invoice</p>
    </div>

    <form action="{{ route('invoices.store') }}" method="POST" x-data="invoiceForm()">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Main Info & Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header Info -->
                <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Client <span class="text-rose-500">*</span></label>
                            <select name="client_id" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-slate-900 dark:text-white">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nama_client }} ({{ $client->nama_perusahaan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <x-input label="Invoice Number" name="invoice_number" value="{{ $invoice_number }}" readonly class="bg-slate-50 dark:bg-slate-800/50" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="Invoice Date" name="tanggal_invoice" type="date" value="{{ date('Y-m-d') }}" required />
                        <x-input label="Due Date" name="due_date" type="date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required />
                    </div>
                </div>

                <!-- Items Section -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="list" class="w-5 h-5 text-indigo-600"></i>
                            Invoice Items
                        </h3>
                        <button type="button" @click="addItem" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 flex items-center gap-1.5">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            Add Item
                        </button>
                    </div>
                    
                    <div class="p-8 space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex flex-col md:flex-row gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800/60 relative group">
                                <div class="flex-1 space-y-1.5">
                                    <label class="text-[10px] uppercase font-bold text-slate-400">Description</label>
                                    <input type="text" :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" required placeholder="e.g. Plumbing Repair" class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm text-slate-900 dark:text-white font-medium">
                                </div>
                                <div class="w-full md:w-24 space-y-1.5">
                                    <label class="text-[10px] uppercase font-bold text-slate-400">Qty</label>
                                    <input type="number" step="0.01" :name="`items[${index}][qty]`" x-model="item.qty" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm text-slate-900 dark:text-white font-medium">
                                </div>
                                <div class="w-full md:w-40 space-y-1.5">
                                    <label class="text-[10px] uppercase font-bold text-slate-400">Unit Price</label>
                                    <input type="number" :name="`items[${index}][harga]`" x-model="item.harga" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm text-slate-900 dark:text-white font-medium">
                                </div>
                                <div class="w-full md:w-40 space-y-1.5">
                                    <label class="text-[10px] uppercase font-bold text-slate-400">Total</label>
                                    <div class="text-sm font-bold text-slate-900 dark:text-white py-0.5" x-text="formatCurrency(item.qty * item.harga)"></div>
                                </div>
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="absolute -right-3 -top-3 w-8 h-8 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-rose-500 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Notes & Terms -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <label class="block text-sm font-bold text-slate-900 dark:text-white">Internal Notes</label>
                        <textarea name="notes_internal" rows="4" placeholder="Only visible to admins..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 rounded-xl transition-all outline-none text-sm text-slate-900 dark:text-white"></textarea>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <label class="block text-sm font-bold text-slate-900 dark:text-white">Terms & Conditions</label>
                        <textarea name="terms_condition" rows="4" placeholder="Standard payment terms..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 rounded-xl transition-all outline-none text-sm text-slate-900 dark:text-white">Payment is due within 7 days. Thank you for your business!</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Side: Summary & Save -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Summary</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-bold text-slate-900 dark:text-white" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Tax (%)</span>
                                <input type="number" name="tax_percent" x-model="tax_percent" @input="calculateTotal()" class="w-16 text-right bg-slate-50 dark:bg-slate-800/50 border-none rounded-lg p-1 text-sm font-bold focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Discount (%)</span>
                                <input type="number" name="discount_percent" x-model="discount_percent" @input="calculateTotal()" class="w-16 text-right bg-slate-50 dark:bg-slate-800/50 border-none rounded-lg p-1 text-sm font-bold focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-base font-bold text-slate-900 dark:text-white">Total</span>
                            <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-slate-900 dark:bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-600/20">
                    <button type="submit" class="w-full py-4 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-2xl font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        Generate Invoice
                    </button>
                    <p class="text-[10px] text-center text-slate-400 dark:text-indigo-200 mt-4 uppercase tracking-widest font-bold">Rooterin Internal Invoice System</p>
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
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
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
