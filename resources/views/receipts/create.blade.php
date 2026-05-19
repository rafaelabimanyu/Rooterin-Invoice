<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <a href="{{ route('receipts.index') }}" class="hover:text-indigo-600 transition-colors">{{ app()->getLocale() == 'en' ? 'Receipts' : 'Kuitansi' }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900">{{ app()->getLocale() == 'en' ? 'New Receipt' : 'Kuitansi Baru' }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 font-outfit">{{ app()->getLocale() == 'en' ? 'Create Receipt' : 'Buat Kuitansi' }}</h1>
            <p class="text-sm text-slate-500">{{ app()->getLocale() == 'en' ? 'Generate a professional payment receipt for your client.' : 'Buat kuitansi pembayaran profesional untuk klien Anda.' }}</p>
        </div>
    </div>

    <form action="{{ route('receipts.store') }}" method="POST" x-data="receiptForm()">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Side -->
            <div class="lg:col-span-8 space-y-8">
                <div class="glass-card p-10">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-8 pb-4 border-b border-slate-50">1. {{ app()->getLocale() == 'en' ? 'Client & Dates' : 'Klien & Tanggal' }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Client Account' : 'Akun Klien' }}</label>
                            <select name="client_id" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm text-slate-900 outline-none focus:ring-2 focus:ring-indigo-500/10 transition-all">
                                <option value="">{{ app()->getLocale() == 'en' ? 'Choose a client...' : 'Pilih klien...' }}</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nama_client }} ({{ $client->nama_perusahaan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Receipt Number' : 'Nomor Kuitansi' }}</label>
                            <input type="text" name="receipt_number" value="{{ $receipt_number }}" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200/60 rounded-lg text-sm text-slate-500 font-mono cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Receipt Date' : 'Tanggal Kuitansi' }}</label>
                            <input type="date" name="tanggal_receipt" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm text-slate-900 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Expiry Date' : 'Tanggal Kedaluwarsa' }}</label>
                            <input type="date" name="expiry_date" value="{{ date('Y-m-d', strtotime('+14 days')) }}" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm text-slate-900 outline-none">
                        </div>
                    </div>
                </div>

                <div class="glass-card overflow-hidden">
                    <div class="px-10 py-6 border-b border-slate-50 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">2. {{ app()->getLocale() == 'en' ? 'Service Items' : 'Item Layanan' }}</h3>
                        <button type="button" @click="addItem" class="text-[12px] font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5">
                            <i data-lucide="plus" class="w-4 h-4"></i> {{ app()->getLocale() == 'en' ? 'Append Item' : 'Tambah Item' }}
                        </button>
                    </div>
                    <div class="p-10 space-y-6">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="relative grid grid-cols-1 md:grid-cols-12 gap-6 pb-6 border-b border-slate-50 last:border-0 last:pb-0">
                                <div class="md:col-span-6 space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Description' : 'Deskripsi' }}</label>
                                    <input type="text" :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 font-semibold">
                                </div>
                                <div class="md:col-span-1 space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center block">{{ app()->getLocale() == 'en' ? 'Qty' : 'Jumlah' }}</label>
                                    <input type="number" step="0.01" :name="`items[${index}][qty]`" x-model="item.qty" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 font-semibold text-center">
                                </div>
                                <div class="md:col-span-2 space-y-2 text-right">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">{{ app()->getLocale() == 'en' ? 'Rate' : 'Harga' }}</label>
                                    <input type="number" :name="`items[${index}][harga]`" x-model="item.harga" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 font-semibold text-right">
                                </div>
                                <div class="md:col-span-3 space-y-2 text-right">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">{{ app()->getLocale() == 'en' ? 'Total' : 'Total' }}</label>
                                    <div class="text-[13px] font-black text-slate-900" x-text="formatCurrency(item.qty * item.harga)"></div>
                                </div>
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="absolute -right-4 top-1/2 -translate-y-1/2 p-1 text-rose-500 hover:bg-rose-50 rounded">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right Side (Calculations) -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-[#1e293b] text-white p-10 rounded-xl shadow-2xl space-y-8 sticky top-24">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">{{ app()->getLocale() == 'en' ? 'Receipt Summary' : 'Ringkasan Kuitansi' }}</h3>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400 font-medium">Subtotal</span>
                            <span class="font-bold font-mono" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex justify-between items-center gap-4">
                            <span class="text-slate-400 text-sm font-medium">{{ app()->getLocale() == 'en' ? 'Tax (%)' : 'Pajak (%)' }}</span>
                            <input type="number" name="tax_percent" x-model="tax_percent" @input="calculateTotal()" class="w-20 bg-slate-800 border-none rounded text-right text-sm font-bold text-indigo-400 p-1">
                        </div>
                        <div class="flex justify-between items-center gap-4">
                            <span class="text-slate-400 text-sm font-medium">{{ app()->getLocale() == 'en' ? 'Discount (%)' : 'Diskon (%)' }}</span>
                            <input type="number" name="discount_percent" x-model="discount_percent" @input="calculateTotal()" class="w-20 bg-slate-800 border-none rounded text-right text-sm font-bold text-rose-400 p-1">
                        </div>
                        <div class="pt-6 border-t border-slate-700 space-y-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Total Amount' : 'Jumlah Total' }}</p>
                            <h4 class="text-4xl font-black text-white font-outfit" x-text="formatCurrency(total)"></h4>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[13px] transition-all shadow-lg shadow-indigo-600/20 uppercase tracking-widest">
                        {{ app()->getLocale() == 'en' ? 'Save Receipt' : 'Simpan Kuitansi' }}
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        function receiptForm() {
            return {
                items: [{ deskripsi: '', qty: 1, harga: 0 }],
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
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                }
            }
        }
    </script>
</x-app-layout>
