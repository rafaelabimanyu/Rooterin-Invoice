<x-app-layout :title="app()->getLocale() == 'en' ? 'Edit Invoice' : 'Ubah Invoice'">
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                <a href="{{ route('invoices.index') }}" class="hover:text-gold-600 transition-colors">{{ app()->getLocale() == 'en' ? 'Invoices' : 'Invoice' }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900">{{ __('Edit Invoice') }}</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 font-outfit leading-tight">{{ __('Edit Invoice') }}</h1>
            <p class="text-slate-500 mt-1">{{ __('Update billing details, status, and items for this invoice.') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('invoices.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all">
                {{ __('Discard') }}
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('invoices.update', $invoice) }}" method="POST" x-data="invoiceForm()" enctype="multipart/form-data" class="pb-24 md:pb-0">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Side -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white p-10 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">1. {{ __('Client & Business Unit') }}</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Business Unit') }}</label>
                            <select name="business_unit_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
                                <option value="">{{ __('Choose Business Unit...') }}</option>
                                @foreach($businessUnits as $bu)
                                    <option value="{{ $bu->id }}" {{ $invoice->business_unit_id == $bu->id ? 'selected' : '' }}>{{ $bu->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Client Account') }}</label>
                            <select name="client_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
                                <option value="">{{ __('Choose a client...') }}</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>{{ $client->nama_client }} ({{ $client->nama_perusahaan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Invoice Identifier') }}</label>
                            <input type="text" value="{{ $invoice->invoice_number }}" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-500 font-mono cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Due Date') }}</label>
                            <input type="date" name="due_date" value="{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none text-sm text-slate-900 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</label>
                            <select name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
                                @foreach(['draft', 'sent', 'pending', 'paid', 'overdue', 'cancelled'] as $statusOption)
                                    <option value="{{ $statusOption }}" {{ $invoice->status == $statusOption ? 'selected' : '' }}>{{ strtoupper($statusOption) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Billing Items -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-10 py-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">2. {{ __('Billing Items') }}</h3>
                        <button type="button" @click="addItem" class="text-[12px] font-bold text-gold-600 hover:text-gold-700 flex items-center gap-1.5 transition-colors">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            {{ __('Append Line Item') }}
                        </button>
                    </div>
                    
                    <div class="p-10 space-y-6">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="relative grid grid-cols-1 md:grid-cols-12 gap-6 pb-6 border-b border-slate-50 last:border-0 last:pb-0 group">
                                <div class="md:col-span-6 space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Description') }}</label>
                                    <input type="text" :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" required placeholder="{{ __('Service or product description...') }}" class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 font-semibold">
                                </div>
                                <div class="md:col-span-1 space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center block">{{ __('Qty') }}</label>
                                    <input type="number" step="0.01" :name="`items[${index}][qty]`" x-model="item.qty" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 font-semibold text-center">
                                </div>
                                <div class="md:col-span-2 space-y-2 text-right">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('Rate') }}</label>
                                    <input type="number" :name="`items[${index}][harga]`" x-model="item.harga" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 font-semibold text-right">
                                </div>
                                <div class="md:col-span-3 space-y-2 text-right">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('Line Total') }}</label>
                                    <div class="text-[13px] font-black text-slate-900 py-0" x-text="formatCurrency(item.qty * item.harga)"></div>
                                </div>
                                
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="absolute -right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all p-1 text-rose-500 hover:bg-rose-50 rounded">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                        
                        <!-- Mobile Financial Summary inputs (only visible on mobile) -->
                        <div class="mt-8 pt-8 border-t border-slate-100 space-y-4 md:hidden">
                            <div class="flex justify-between items-center text-sm font-semibold text-slate-600">
                                <span>Subtotal</span>
                                <span x-text="formatCurrency(subtotal)"></span>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Discount') }}</label>
                                    <input type="number" step="0.01" min="0" x-model.number="discount" @input="calculateTotal()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-rose-500 outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">PPN</label>
                                    <input type="number" step="0.01" min="0" x-model.number="ppn" @input="calculateTotal()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-gold-600 outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">PPh</label>
                                    <input type="number" step="0.01" min="0" x-model.number="pph" @input="calculateTotal()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-cyan-500 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accordion Container: Informasi Tambahan & Dokumen -->
                <div x-data="{ isOpen: window.innerWidth >= 768 }" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
                    <!-- Accordion Header -->
                    <button 
                        type="button" 
                        @click="isOpen = !isOpen" 
                        class="w-full px-8 py-5 flex items-center justify-between bg-slate-50/50 hover:bg-slate-50 transition-colors border-b border-slate-100"
                    >
                        <span class="text-sm font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="folder-plus" class="w-4 h-4 text-gold-500"></i>
                            {{ app()->getLocale() == 'en' ? 'Additional Info & Documents' : 'Informasi Tambahan & Dokumen' }}
                        </span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="isOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <!-- Accordion Content -->
                    <div x-show="isOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="p-6 md:p-8 space-y-8">
                        <!-- Documentation Section -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <i data-lucide="image" class="w-4 h-4 text-gold-500"></i>
                                {{ __('Job Documentation') }}
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-4">
                                @foreach($invoice->attachments as $attachment)
                                    <div class="relative aspect-square rounded-lg overflow-hidden border border-slate-200 shadow-sm group">
                                        <img src="{{ asset('storage/' . $attachment->file_path) }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-slate-500">{{ __('Upload additional work evidence or job site documentation. Support multiple files.') }}</p>
                            <div class="relative group cursor-pointer border-2 border-dashed border-slate-200 rounded-xl p-8 hover:border-gold-500 transition-all flex flex-col items-center justify-center bg-slate-50/50">
                                <input type="file" name="attachments[]" multiple @change="handleFiles" class="absolute inset-0 opacity-0 cursor-pointer">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 group-hover:text-gold-500 mb-2"></i>
                                <p class="text-[11px] font-bold text-slate-400 group-hover:text-gold-500 uppercase tracking-widest">{{ __('Select Images') }}</p>
                            </div>

                            <!-- Image Preview Grid -->
                            <div x-show="previews.length > 0" class="mt-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4" x-cloak>
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative aspect-square rounded-lg overflow-hidden border border-slate-200 shadow-sm group">
                                        <img :src="preview" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                            <i data-lucide="image" class="w-5 h-5 text-white"></i>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Cause of Problem & Notes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-slate-50/50 p-6 rounded-xl border border-slate-200/50 space-y-3 md:col-span-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ app()->getLocale() == 'en' ? 'Field Technicians' : 'Teknisi Lapangan' }}</label>
                                <input type="text" name="technician_names" value="{{ $invoice->technician_names }}" placeholder="Contoh: Budi, Andi" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none">
                            </div>
                            <div class="bg-slate-50/50 p-6 rounded-xl border border-slate-200/50 space-y-3 md:col-span-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('Penyebab Mampet') }}</label>
                                <input type="text" name="cause_of_problem" value="{{ $invoice->cause_of_problem }}" placeholder="Contoh: Penyebab Mampet: Pasir dan Batu" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none">
                            </div>
                            <div class="bg-slate-50/50 p-6 rounded-xl border border-slate-200/50 space-y-3 md:col-span-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('Catatan') }}</label>
                                <textarea name="notes" rows="3" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none">{{ $invoice->notes }}</textarea>
                            </div>
                            <div class="bg-slate-50/50 p-6 rounded-xl border border-slate-200/50 space-y-3">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ app()->getLocale() == 'en' ? 'Bank Account Details' : 'Rincian Rekening Bank' }}</label>
                                <div class="text-[13px] text-slate-700 font-semibold space-y-1">
                                    <p>Bank: <span class="text-slate-900">Bank Central Asia (BCA)</span></p>
                                    <p>Acc No: <span class="text-slate-900">6281873404</span></p>
                                    <p>Name: <span class="text-slate-900">Wibowo Pratikno</span></p>
                                </div>
                            </div>
                            <div class="bg-slate-50/50 p-6 rounded-xl border border-slate-200/50 space-y-3">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ app()->getLocale() == 'en' ? 'Company Brand & Contacts' : 'Identitas Perusahaan & Kontak' }}</label>
                                <div class="text-[13px] text-slate-700 space-y-2">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">J&J GROUP PLUMBING SERVICES</p>
                                        <p class="text-[11px] text-slate-500 font-medium italic">"SOLUSI PINTAR, SALURAN LANCAR, TANPA BONGKAR*"</p>
                                    </div>
                                    <div class="pt-1">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Contact Numbers</p>
                                        <div class="flex flex-col gap-1 mt-1 font-semibold">
                                            <span class="text-gold-600 flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gold-600 animate-pulse"></span>
                                                0812-40000-759 <span class="text-[9px] bg-gold-50 text-gold-700 px-1.5 py-0.5 rounded uppercase font-bold tracking-wider">Primary</span>
                                            </span>
                                            <span class="text-slate-700 pl-3">0812-40000-749</span>
                                            <span class="text-slate-700 pl-3">0812-83-300-900</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warranty -->
                        <div class="bg-slate-50/50 p-6 rounded-xl border border-slate-200/50 space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('Warranty Period') }}</label>
                            <div class="flex items-center gap-3">
                                @php
                                    $warrantyValue = '';
                                    $warrantyUnit = 'Bulan';
                                    if ($invoice->warranty) {
                                        $parts = explode(' ', $invoice->warranty);
                                        $warrantyValue = $parts[0] ?? '';
                                        $warrantyUnit = $parts[1] ?? 'Bulan';
                                    }
                                @endphp
                                <input type="number" name="warranty_value" value="{{ $warrantyValue }}" placeholder="e.g. 1, 3, 6..." min="1" class="w-32 bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 font-semibold focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none">
                                <select name="warranty_unit" class="bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 font-semibold focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none">
                                    <option value="Hari" {{ $warrantyUnit == 'Hari' ? 'selected' : '' }}>{{ app()->getLocale() == 'en' ? 'Days' : 'Hari' }}</option>
                                    <option value="Bulan" {{ $warrantyUnit == 'Bulan' ? 'selected' : '' }}>{{ app()->getLocale() == 'en' ? 'Months' : 'Bulan' }}</option>
                                    <option value="Tahun" {{ $warrantyUnit == 'Tahun' ? 'selected' : '' }}>{{ app()->getLocale() == 'en' ? 'Years' : 'Tahun' }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Calculations & Mobile Sticky Bottom Bar -->
            <div class="lg:col-span-4">
                <!-- Sticky Bottom Bar Component -->
                <div class="fixed bottom-0 left-0 w-full bg-[#1e293b] text-white p-4 shadow-2xl z-50 flex items-center justify-between border-t border-slate-800 pointer-events-none md:pointer-events-auto md:relative md:block md:p-10 md:rounded-xl md:shadow-2xl md:space-y-8 md:sticky md:top-24 md:z-auto md:border-t-0 animate-fade-in-up">
                    <!-- Desktop-only details section -->
                    <div class="hidden md:block space-y-6">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">{{ __('Financial Summary') }}</h3>
                        
                        <div class="space-y-6">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400 font-medium">{{ __('Net Subtotal') }}</span>
                                <span class="font-bold font-mono" x-text="formatCurrency(subtotal)"></span>
                            </div>
                            
                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-400 text-sm font-medium">{{ __('Discount') }}</span>
                                <input type="number" name="discount" step="0.01" min="0" x-model.number="discount" @input="calculateTotal()" class="w-32 bg-slate-800 border-none rounded text-right text-sm font-bold text-rose-400 p-1 focus:ring-1 focus:ring-rose-500">
                            </div>

                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-400 text-sm font-medium">PPN</span>
                                <input type="number" name="ppn" step="0.01" min="0" x-model.number="ppn" @input="calculateTotal()" class="w-32 bg-slate-800 border-none rounded text-right text-sm font-bold text-gold-400 p-1 focus:ring-1 focus:ring-gold-500">
                            </div>

                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-400 text-sm font-medium">PPh</span>
                                <input type="number" name="pph" step="0.01" min="0" x-model.number="pph" @input="calculateTotal()" class="w-32 bg-slate-800 border-none rounded text-right text-sm font-bold text-cyan-400 p-1 focus:ring-1 focus:ring-cyan-500">
                            </div>
                        </div>
                    </div>

                    <!-- Unified summary & submit action (Row layout on mobile, block layout on desktop) -->
                    <div class="flex items-center justify-between w-full pointer-events-auto md:block md:pt-6 md:border-t md:border-slate-700 md:space-y-6">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none md:text-[10px] md:mb-2">{{ app()->getLocale() == 'en' ? 'Grand Total Due' : 'Total Tagihan' }}</p>
                            <h4 class="text-lg font-black text-gold-400 font-outfit mt-1 md:text-4xl md:text-white" x-text="formatCurrency(total)"></h4>
                        </div>
                        <button type="submit" class="pointer-events-auto px-5 py-3 bg-gold-500 hover:bg-gold-600 text-slate-950 rounded-xl font-black text-[12px] uppercase tracking-widest flex items-center gap-2 active:scale-95 transition-all md:w-full md:py-4 md:rounded-lg md:text-[13px] md:justify-center md:shadow-lg md:shadow-gold-500/20">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            {{ __('Save Changes') }}
                        </button>
                    </div>

                    <p class="hidden md:block text-[10px] text-center text-slate-500 font-bold uppercase tracking-widest">{{ __('Enterprise Billing System v2.0') }}</p>
                </div>
            </div>
        </div>
    </form>

    <script>
        function invoiceForm() {
            return {
                items: @json($invoice->items->map(fn($item) => ['deskripsi' => $item->deskripsi, 'qty' => (float)$item->qty, 'harga' => (float)$item->harga])),
                subtotal: 0,
                discount: {{ (float)$invoice->discount }},
                ppn: {{ (float)$invoice->ppn }},
                pph: {{ (float)$invoice->pph }},
                total: 0,
                previews: [],
                init() {
                    this.calculateTotal();
                },
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
                    let discount = parseFloat(this.discount) || 0;
                    let ppn = parseFloat(this.ppn) || 0;
                    let pph = parseFloat(this.pph) || 0;
                    this.total = (this.subtotal - discount) + ppn - pph;
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                },
                handleFiles(event) {
                    const files = event.target.files;
                    this.previews = [];
                    for (let i = 0; i < files.length; i++) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews.push(e.target.result);
                        };
                        reader.readAsDataURL(files[i]);
                    }
                }
            }
        }
    </script>
</x-app-layout>
