<x-app-layout :title="app()->getLocale() == 'en' ? 'Edit Receipt' : 'Ubah Kwitansi'">
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 px-4 md:px-0">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                <a href="{{ route('receipts.index') }}" class="hover:text-gold-600 transition-colors">{{ app()->getLocale() == 'en' ? 'Receipts' : 'Kwitansi' }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900">{{ app()->getLocale() == 'en' ? 'Edit Receipt' : 'Ubah Kwitansi' }}</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 font-outfit leading-tight">{{ app()->getLocale() == 'en' ? 'Edit Receipt & Sync' : 'Ubah & Sinkronkan Kwitansi' }}</h1>
            <p class="text-slate-500 mt-1">{{ app()->getLocale() == 'en' ? 'Edit payment receipt details. All changes will automatically synchronize to the connected Invoice.' : 'Ubah rincian kwitansi pembayaran. Semua perubahan akan disinkronisasikan otomatis ke Invoice terkait.' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('receipts.show', $receipt) }}" class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all">
                {{ app()->getLocale() == 'en' ? 'Discard' : 'Batalkan' }}
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-sm mx-4 md:mx-0">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="receipt-edit-form" action="{{ route('receipts.update', $receipt) }}" method="POST" x-data="receiptForm()" enctype="multipart/form-data" class="pb-24 md:pb-0 px-4 md:px-0">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Side -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white p-6 md:p-10 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">1. {{ app()->getLocale() == 'en' ? 'Receipt Details & Metadata' : 'Rincian Kwitansi & Metadata' }}</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Receipt Number' : 'Nomor Kwitansi' }}</label>
                            <input type="text" value="{{ $receipt->receipt_number }}" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 font-mono cursor-not-allowed">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Client Account' : 'Akun Klien' }}</label>
                            <input type="text" value="{{ optional($receipt->client)->nama_client ?? 'Klien Tidak Ditemukan' }}" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Connected Invoice' : 'Invoice Terhubung' }}</label>
                            <input type="text" value="{{ $receipt->invoice ? $receipt->invoice->invoice_number : '-' }}" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 font-mono cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Payment Date' : 'Tanggal Pembayaran' }}</label>
                            <input type="datetime-local" name="payment_date" value="{{ $receipt->payment_date ? $receipt->payment_date->format('Y-m-d\TH:i') : '' }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none text-sm text-slate-900 transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</label>
                            <input type="text" value="PAID (Synchronized)" readonly class="w-full px-4 py-2.5 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 font-bold cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Billing Items -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 md:px-10 py-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">2. {{ app()->getLocale() == 'en' ? 'Invoice Items Sync' : 'Sinkronisasi Item Invoice' }}</h3>
                        <button type="button" @click="addItem" class="text-[12px] font-bold text-gold-600 hover:text-gold-700 flex items-center gap-1.5 transition-colors">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            {{ app()->getLocale() == 'en' ? 'Append Line Item' : 'Tambah Baris Item' }}
                        </button>
                    </div>
                    
                    <div class="p-6 md:p-10 space-y-6">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="relative grid grid-cols-1 md:grid-cols-12 gap-6 pb-6 border-b border-slate-50 last:border-0 last:pb-0 group">
                                <div class="md:col-span-6 space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Service Description' : 'Deskripsi Layanan' }}</label>
                                    <input type="text" :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" required placeholder="{{ app()->getLocale() == 'en' ? 'Service or product description...' : 'Deskripsi layanan...' }}" class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 font-semibold outline-none border-b border-dashed border-slate-200 focus:border-gold-500">
                                </div>
                                <div class="md:col-span-1.5 space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center block">{{ app()->getLocale() == 'en' ? 'Qty' : 'Jumlah' }}</label>
                                    <input type="number" step="0.01" :name="`items[${index}][qty]`" x-model="item.qty" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 font-semibold text-center outline-none border-b border-dashed border-slate-200 focus:border-gold-500">
                                </div>
                                <div class="md:col-span-2 space-y-2 text-right">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">{{ app()->getLocale() == 'en' ? 'Rate' : 'Harga' }}</label>
                                    <input type="number" :name="`items[${index}][harga]`" x-model="item.harga" @input="calculateTotal()" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-[13px] text-slate-900 font-semibold text-right outline-none border-b border-dashed border-slate-200 focus:border-gold-500">
                                </div>
                                <div class="md:col-span-2.5 space-y-2 text-right">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total</label>
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
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Discount' : 'Diskon' }} (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" x-model.number="discount" @input="calculateTotal()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-rose-500 outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">PPN (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" x-model.number="ppn" @input="calculateTotal()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-gold-600 outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">PPh (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" x-model.number="pph" @input="calculateTotal()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-cyan-500 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Information Section -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 md:px-10 py-5 border-b border-slate-100 bg-slate-50/50">
                        <h4 class="text-sm font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="wrench" class="w-4 h-4 text-gold-500"></i>
                            3. {{ __('ui.technical_info') }}
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">{{ __('ui.technical_info_desc') }}</p>
                    </div>
                    <div class="p-6 md:p-10 space-y-6">

                        {{-- Teknisi & Penyebab --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('ui.field_technicians') }}</label>
                                <input type="text" name="technician_names" value="{{ $receipt->invoice?->technician_names }}" placeholder="{{ __('ui.technician_placeholder') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('ui.cause_of_problem') }}</label>
                                <input type="text" name="cause_of_problem" value="{{ $receipt->invoice?->cause_of_problem }}" placeholder="{{ __('ui.cause_placeholder') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                            </div>
                        </div>

                        {{-- Masa Garansi --}}
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('ui.warranty_period') }}</label>
                            @php
                                $wVal = ''; $wUnit = 'Bulan';
                                if ($receipt->invoice?->warranty) {
                                    $wParts = explode(' ', $receipt->invoice->warranty, 2);
                                    $wVal = $wParts[0] ?? '';
                                    $wUnit = $wParts[1] ?? 'Bulan';
                                }
                            @endphp
                            <div class="flex items-center gap-3">
                                <input type="number" name="warranty_value" value="{{ $wVal }}" placeholder="e.g. 3" min="1" class="w-28 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none">
                                <select name="warranty_unit" class="bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-semibold focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none">
                                    <option value="Hari" {{ $wUnit == 'Hari' ? 'selected' : '' }}>{{ __('ui.warranty_days') }}</option>
                                    <option value="Bulan" {{ ($wUnit == 'Bulan' || !$wUnit) ? 'selected' : '' }}>{{ __('ui.warranty_months') }}</option>
                                    <option value="Tahun" {{ $wUnit == 'Tahun' ? 'selected' : '' }}>{{ __('ui.warranty_years') }}</option>
                                </select>
                                <span class="text-xs text-slate-400">{{ __('ui.no_warranty') }}</span>
                            </div>
                        </div>

                        {{-- Catatan Tambahan --}}
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">{{ __('ui.additional_notes_label') }}</label>
                            <p class="text-[11px] text-slate-400">{{ __('ui.additional_notes_hint') }}</p>
                            <textarea name="notes" rows="3" placeholder="{{ __('ui.additional_notes_placeholder') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:ring-2 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">{{ $receipt->invoice?->notes }}</textarea>
                        </div>

                        {{-- Dokumentasi Pekerjaan --}}
                        <div class="space-y-4" x-data="attachmentManager()">
                            <div>
                                <h5 class="text-xs font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2 mb-1">
                                    <i data-lucide="image" class="w-4 h-4 text-gold-500"></i>
                                    {{ __('ui.job_documentation') }}
                                </h5>
                                <p class="text-xs text-slate-400">{{ __('ui.job_documentation_desc') }}</p>
                            </div>

                            {{-- Existing Attachments --}}
                            @if($receipt->invoice && $receipt->invoice->attachments->count() > 0)
                                <div class="mb-2">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">{{ __('ui.existing_photos') }} ({{ $receipt->invoice->attachments->count() }})</p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($receipt->invoice->attachments as $attachment)
                                            <div class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                                <img src="{{ Storage::url($attachment->file_path) }}" alt="doc" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                                        <input type="checkbox" name="delete_attachments[]" value="{{ $attachment->id }}" class="w-4 h-4 accent-rose-500">
                                                        <span class="text-white text-[10px] font-bold uppercase">{{ __('ui.delete_photo') }}</span>
                                                    </label>
                                                </div>
                                                @if($attachment->caption)
                                                    <div class="absolute inset-x-0 bottom-0 bg-slate-900/70 text-white text-[9px] truncate px-2 py-1 text-center">{{ $attachment->caption }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-[10px] text-rose-400 mt-2 font-semibold">{{ app()->getLocale() == 'en' ? 'Check the box on a photo to mark it for deletion on save.' : 'Centang foto untuk menandainya agar dihapus saat disimpan.' }}</p>
                                </div>
                            @else
                                <p class="text-xs text-slate-400 italic">{{ __('ui.no_photos') }}</p>
                            @endif

                            {{-- Upload New Photos --}}
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ __('ui.add_more_photos') }}</p>
                                <div class="relative group cursor-pointer border-2 border-dashed border-slate-200 rounded-xl p-6 hover:border-gold-500 transition-all flex flex-col items-center justify-center bg-slate-50/50">
                                    <input type="file" name="attachments[]" multiple @change="handleFiles" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                                    <i data-lucide="upload-cloud" class="w-7 h-7 text-slate-400 group-hover:text-gold-500 mb-2"></i>
                                    <p class="text-[11px] font-bold text-slate-400 group-hover:text-gold-500 uppercase tracking-widest">{{ __('Select Images') }}</p>
                                </div>
                                <div x-show="newFiles.length > 0" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4" x-cloak>
                                    <template x-for="(item, idx) in newFiles" :key="idx">
                                        <div class="relative aspect-square rounded-xl overflow-hidden border border-slate-200 shadow-sm group">
                                            <img :src="item.preview" class="w-full h-full object-cover">
                                            <button type="button" @click="removeNew(idx)" class="absolute top-1.5 right-1.5 bg-rose-500 text-white rounded-full p-1.5 shadow opacity-90 hover:opacity-100 hover:scale-105 z-10 transition-all">
                                                <i data-lucide="x" class="w-3 h-3"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Side: Calculations & Submit -->
            <div class="lg:col-span-4">
                <div class="fixed bottom-0 left-0 w-full bg-[#1e293b] text-white p-4 shadow-2xl z-50 flex items-center justify-between border-t border-slate-800 pointer-events-none md:pointer-events-auto md:relative md:block md:p-10 md:rounded-2xl md:shadow-2xl md:space-y-8 md:sticky md:top-24 md:z-auto md:border-t-0 animate-fade-in-up">
                    <div class="hidden md:block space-y-6">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">{{ app()->getLocale() == 'en' ? 'Financial Sync Summary' : 'Ringkasan Keuangan Sinkronisasi' }}</h3>
                        
                        <div class="space-y-6">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400 font-medium">Subtotal</span>
                                <span class="font-bold font-mono" x-text="formatCurrency(subtotal)"></span>
                            </div>
                            
                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-400 text-sm font-medium">{{ app()->getLocale() == 'en' ? 'Discount' : 'Diskon' }} (%)</span>
                                <div class="flex items-center gap-1.5">
                                    <input type="number" name="discount" step="0.01" min="0" max="100" x-model.number="discount" @input="calculateTotal()" class="w-20 bg-slate-800 border-none rounded text-right text-sm font-bold text-rose-400 p-1 focus:ring-1 focus:ring-rose-500 outline-none">
                                    <span class="text-slate-400 text-sm">%</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-400 text-sm font-medium">PPN (%)</span>
                                <div class="flex items-center gap-1.5">
                                    <input type="number" name="ppn" step="0.01" min="0" max="100" x-model.number="ppn" @input="calculateTotal()" class="w-20 bg-slate-800 border-none rounded text-right text-sm font-bold text-gold-400 p-1 focus:ring-1 focus:ring-gold-500 outline-none">
                                    <span class="text-slate-400 text-sm">%</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-400 text-sm font-medium">PPh (%)</span>
                                <div class="flex items-center gap-1.5">
                                    <input type="number" name="pph" step="0.01" min="0" max="100" x-model.number="pph" @input="calculateTotal()" class="w-20 bg-slate-800 border-none rounded text-right text-sm font-bold text-cyan-400 p-1 focus:ring-1 focus:ring-cyan-500 outline-none">
                                    <span class="text-slate-400 text-sm">%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Calculation breakdown -->
                        <div class="mt-4 p-4 bg-slate-800/50 rounded-lg border border-slate-700/50 text-[11px] space-y-2 font-mono text-slate-300">
                            <p class="text-slate-400 font-bold uppercase tracking-wider mb-2 font-sans">{{ app()->getLocale() == 'en' ? 'Calculation Flow' : 'Alur Perhitungan' }}</p>
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span class="text-white" x-text="formatCurrency(subtotal)"></span>
                            </div>
                            <div class="flex justify-between text-rose-400">
                                <span>{{ app()->getLocale() == 'en' ? 'Discount' : 'Diskon' }} (<span x-text="discount"></span>%):</span>
                                <span>- <span x-text="formatCurrency(discountNominal)"></span></span>
                            </div>
                            <div class="flex justify-between text-slate-400 font-sans border-t border-slate-700/60 pt-1">
                                <span>DPP (Tax Base):</span>
                                <span class="text-white font-mono" x-text="formatCurrency(dpp)"></span>
                            </div>
                            <div class="flex justify-between text-gold-400">
                                <span>PPN (<span x-text="ppn"></span>%):</span>
                                <span>+ <span x-text="formatCurrency(ppnNominal)"></span></span>
                            </div>
                            <div class="flex justify-between text-cyan-400">
                                <span>PPh (<span x-text="pph"></span>%):</span>
                                <span>+ <span x-text="formatCurrency(pphNominal)"></span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit section -->
                    <div class="flex items-center justify-between w-full pointer-events-auto md:block md:pt-6 md:border-t md:border-slate-700 md:space-y-6">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none md:text-[10px] md:mb-2">{{ app()->getLocale() == 'en' ? 'Total Sync Value' : 'Total Nilai Sinkronisasi' }}</p>
                            <h4 class="text-lg font-black text-gold-400 font-outfit mt-1 md:text-4xl md:text-white" x-text="formatCurrency(total)"></h4>
                        </div>
                        <button type="submit" class="pointer-events-auto px-5 py-3 bg-gold-500 hover:bg-gold-600 text-slate-950 rounded-xl font-black text-[12px] uppercase tracking-widest flex items-center gap-2 active:scale-95 transition-all md:w-full md:py-4 md:rounded-lg md:text-[13px] md:justify-center md:shadow-lg md:shadow-gold-500/20">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            {{ app()->getLocale() == 'en' ? 'Sync & Save' : 'Sinkronkan & Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        function receiptForm() {
            return {
                items: {!! json_encode($receipt->invoice ? $receipt->invoice->items->map(fn($item) => ['deskripsi' => $item->deskripsi, 'qty' => (float)$item->qty, 'harga' => (float)$item->harga]) : [['deskripsi' => '', 'qty' => 1, 'harga' => 0]]) !!},
                subtotal: 0,
                discount: {{ $receipt->invoice ? (float)$receipt->invoice->discount_percent : 0 }},
                ppn: {{ $receipt->invoice ? (float)$receipt->invoice->tax_percent : 0 }},
                pph: {{ $receipt->invoice ? (float)$receipt->invoice->pph_percent : 0 }},
                discountNominal: 0,
                dpp: 0,
                ppnNominal: 0,
                pphNominal: 0,
                total: 0,
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
                    let discountPercent = parseFloat(this.discount) || 0;
                    let ppnPercent = parseFloat(this.ppn) || 0;
                    let pphPercent = parseFloat(this.pph) || 0;

                    this.discountNominal = Math.round((this.subtotal * (discountPercent / 100)) * 100) / 100;
                    this.dpp = Math.round((this.subtotal - this.discountNominal) * 100) / 100;
                    this.ppnNominal = Math.round((this.dpp * (ppnPercent / 100)) * 100) / 100;
                    this.pphNominal = Math.round((this.dpp * (pphPercent / 100)) * 100) / 100;

                    this.total = Math.round((this.dpp + this.ppnNominal + this.pphNominal) * 100) / 100;
                },
                formatCurrency(value) {
                    const symbol = '{{ \App\Models\Setting::get('currency_symbol', 'Rp') }}';
                    const formatted = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
                    return `${symbol} ${formatted}`;
                }
            }
        }

        function attachmentManager() {
            return {
                newFiles: [],
                handleFiles(event) {
                    const fileList = event.target.files;
                    for (let i = 0; i < fileList.length; i++) {
                        const file = fileList[i];
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.newFiles.push({ preview: e.target.result, name: file.name });
                            this.$nextTick(() => lucide.createIcons());
                        };
                        reader.readAsDataURL(file);
                    }
                },
                removeNew(idx) {
                    this.newFiles.splice(idx, 1);
                }
            }
        }


        // SweetAlert2 Pre-Submit Confirmation
        document.getElementById('receipt-edit-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: "{{ app()->getLocale() == 'en' ? 'Are you sure?' : 'Anda yakin?' }}",
                    text: "{{ app()->getLocale() == 'en' ? 'Are you sure you want to change this data? Changes will re-synchronize the connected Invoice values.' : 'Anda yakin ingin mengubah data ini? Perubahan akan menyinkronisasi ulang nilai Invoice yang terhubung dengan Kwitansi ini.' }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ app()->getLocale() == 'en' ? 'Yes, save it!' : 'Ya, simpan!' }}",
                    cancelButtonText: "{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}",
                    customClass: {
                        confirmButton: 'px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-slate-950 rounded-xl font-bold text-xs uppercase tracking-wider text-center mr-2 transition-all duration-300',
                        cancelButton: 'px-5 py-2.5 bg-slate-500 hover:bg-slate-650 text-white rounded-xl font-bold text-xs uppercase tracking-wider text-center transition-all duration-300'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm("{{ app()->getLocale() == 'en' ? 'Confirm syncing changes to connected invoice?' : 'Konfirmasi sinkronisasi perubahan ke invoice terkait?' }}")) {
                    form.submit();
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
