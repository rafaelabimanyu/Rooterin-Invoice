<x-app-layout>
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                <a href="{{ route('invoices.index') }}" class="hover:text-indigo-600 transition-colors">Invoices</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900">{{ __('Create Invoice') }}</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 font-outfit leading-tight">{{ __('Create Invoice') }}</h1>
            <p class="text-slate-500 mt-1">{{ __('Configure billing details and items for your client.') }}</p>
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

    <form action="{{ route('invoices.store') }}" method="POST" x-data="invoiceForm()" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Side -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white p-10 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">1. {{ __('Client & Dates') }}</h3>
                        <button type="button" @click="$dispatch('open-modal', 'quick-client')" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> {{ __('Add New Client') }}
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Client Account') }}</label>
                            <select name="client_id" id="client_select" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm text-slate-900 outline-none focus:ring-2 focus:ring-indigo-500/10 transition-all">
                                <option value="">{{ __('Choose a client...') }}</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nama_client }} ({{ $client->nama_perusahaan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Invoice Identifier') }}</label>
                            <input type="text" name="invoice_number" value="{{ $invoice_number }}" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-500 font-mono cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Issuance Date') }}</label>
                            <input type="date" name="tanggal_invoice" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none text-sm text-slate-900 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Due Date') }}</label>
                            <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none text-sm text-slate-900 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Billing Items -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-10 py-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">2. {{ __('Billing Items') }}</h3>
                        <button type="button" @click="addItem" class="text-[12px] font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 transition-colors">
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
                    </div>
                </div>

                <!-- Documentation Section -->
                <div class="bg-white p-10 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-8 pb-4 border-b border-slate-50 flex items-center gap-2">
                        <i data-lucide="image" class="w-4 h-4 text-indigo-500"></i>
                        3. {{ __('Job Documentation') }}
                    </h3>
                    <div class="space-y-4">
                        <p class="text-xs text-slate-500">{{ __('Upload work evidence or job site documentation. Support multiple files.') }}</p>
                        <div class="relative group cursor-pointer border-2 border-dashed border-slate-200 rounded-xl p-8 hover:border-indigo-500 transition-all flex flex-col items-center justify-center bg-slate-50/50">
                            <input type="file" name="attachments[]" multiple @change="handleFiles" class="absolute inset-0 opacity-0 cursor-pointer">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 group-hover:text-indigo-500 mb-2"></i>
                            <p class="text-[11px] font-bold text-slate-400 group-hover:text-indigo-500 uppercase tracking-widest">{{ __('Select Images') }}</p>
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
                </div>

                <!-- Terms & Memo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm space-y-4">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Internal Memo') }}</label>
                        <textarea name="notes_internal" rows="3" placeholder="{{ __('Private notes for the team...') }}" class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-700"></textarea>
                    </div>
                    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm space-y-4">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Client Terms') }}</label>
                        <textarea name="terms_condition" rows="3" class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-700">Net 7. Please remit payment via bank transfer.</textarea>
                    </div>
                    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm space-y-4">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Bank Account Details (Displayed on PDF)') }}</label>
                        <textarea name="bank_account_info" rows="3" placeholder="Bank BCA Account No: 123456..." class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-700">Bank Central Asia (BCA)
Acc No: 123-456-7890
Name: Rooterin Technical Services</textarea>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Warranty Period') }}</label>
                    <input type="text" name="warranty" placeholder="e.g. 1 Month, 1 Year..." class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-700 font-semibold">
                </div>
            </div>

            <!-- Right Side: Calculations -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-[#1e293b] text-white p-10 rounded-xl shadow-2xl space-y-8 sticky top-24">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">{{ __('Financial Summary') }}</h3>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400 font-medium">{{ __('Net Subtotal') }}</span>
                            <span class="font-bold font-mono" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        
                        <div class="flex justify-between items-center gap-4">
                            <span class="text-slate-400 text-sm font-medium">{{ __('Tax Rate (%)') }}</span>
                            <input type="number" name="tax_percent" x-model="tax_percent" @input="calculateTotal()" class="w-20 bg-slate-800 border-none rounded text-right text-sm font-bold text-indigo-400 p-1 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div class="flex justify-between items-center gap-4">
                            <span class="text-slate-400 text-sm font-medium">{{ __('Adjustment (%)') }}</span>
                            <input type="number" name="discount_percent" x-model="discount_percent" @input="calculateTotal()" class="w-20 bg-slate-800 border-none rounded text-right text-sm font-bold text-rose-400 p-1 focus:ring-1 focus:ring-rose-500">
                        </div>

                        <div class="pt-6 border-t border-slate-700 space-y-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Grand Total Due') }}</p>
                            <h4 class="text-4xl font-black text-white font-outfit" x-text="formatCurrency(total)"></h4>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[13px] transition-all shadow-lg shadow-indigo-600/20 uppercase tracking-widest flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        {{ __('Confirm & Issue') }}
                    </button>
                    
                    <p class="text-[10px] text-center text-slate-500 font-bold uppercase tracking-widest">{{ __('Enterprise Billing System v2.0') }}</p>
                </div>
            </div>
        </div>
    </form>

    <!-- Quick Client Modal -->
    <x-modal name="quick-client" :show="false">
        <div class="p-10" x-data="quickClientForm()">
            <h3 class="text-xl font-bold text-slate-900 font-outfit mb-2">{{ __('Add New Client') }}</h3>
            <p class="text-sm text-slate-500 mb-8">Register a new client account directly to the system ledger.</p>
            
            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Full Name</label>
                        <input type="text" x-model="form.nama_client" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Client Type</label>
                        <select x-model="form.client_type" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                            <option value="perumahan">Perumahan (Home)</option>
                            <option value="perusahaan">Perusahaan (Corporate)</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Company Name (Optional)</label>
                    <input type="text" x-model="form.nama_perusahaan" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Email Address</label>
                        <input type="email" x-model="form.email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">WhatsApp / Phone</label>
                        <input type="text" x-model="form.no_hp" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Primary Address</label>
                    <textarea x-model="form.alamat" rows="2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none"></textarea>
                </div>

                <div class="pt-6 flex items-center justify-end gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'quick-client')" class="px-5 py-2.5 text-sm font-bold text-slate-500">Cancel</button>
                    <button type="submit" :disabled="loading" class="px-8 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-lg shadow-indigo-600/20 disabled:opacity-50">
                        <span x-show="!loading">Register & Select</span>
                        <span x-show="loading">Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <script>
        function quickClientForm() {
            return {
                form: {
                    nama_client: '',
                    client_type: 'perumahan',
                    nama_perusahaan: '',
                    email: '',
                    no_hp: '',
                    alamat: '',
                    status: 'aktif'
                },
                loading: false,
                async submitForm() {
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('api.clients.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.form)
                        });
                        const data = await response.json();
                        if (data.success) {
                            const select = document.getElementById('client_select');
                            const option = new Option(`${data.client.nama_client} (${data.client.nama_perusahaan || 'Individual'})`, data.client.id, true, true);
                            select.add(option);
                            this.$dispatch('close-modal', 'quick-client');
                            this.form = { nama_client: '', nama_perusahaan: '', email: '', no_hp: '', alamat: '', status: 'aktif' };
                        }
                    } catch (error) {
                        alert('Failed to register client. Please check your data.');
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }

        function invoiceForm() {
            return {
                items: [{ deskripsi: '', qty: 1, harga: 0 }],
                subtotal: 0,
                tax_percent: 11,
                discount_percent: 0,
                total: 0,
                previews: [],
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
