<x-app-layout :title="app()->getLocale() == 'en' ? 'Billing & Invoice List' : 'Daftar Penagihan & Invoice'">
    <!-- Top Action Header -->
    <div class="mb-8 md:mb-12 flex flex-col lg:flex-row lg:items-end justify-between gap-6 px-4 md:px-0">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 overflow-hidden">
                <a href="{{ route('invoices.index') }}" class="hover:text-gold-600 transition-colors shrink-0">{{ app()->getLocale() == 'en' ? 'Invoices' : 'Invoice' }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 shrink-0"></i>
                <span class="text-slate-900 truncate">{{ $invoice->invoice_number }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 font-outfit leading-tight truncate">{{ app()->getLocale() == 'en' ? 'Invoice Details' : 'Rincian Invoice' }}</h1>
            <p class="text-sm text-slate-500 mt-1 truncate">{{ app()->getLocale() == 'en' ? 'Manage billing for ' : 'Kelola tagihan untuk ' }}{{ optional($invoice->client)->nama_client ?? 'Klien Tidak Ditemukan' }}.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 md:gap-4">
            <div class="flex items-center justify-between sm:justify-start gap-4">
                <x-badge :status="$invoice->status" class="px-3 py-1.5 text-[10px] md:text-[11px]" />
                <div class="flex items-center gap-2">
                    <button title="Print" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 transition-all shadow-sm active:scale-95">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <button type="button" @click="$dispatch('download-pdf', { url: '{{ route('invoices.pdf', $invoice) }}', filename: 'Invoice-{{ $invoice->invoice_number }}.pdf' })" title="Download PDF" class="w-full sm:w-auto px-6 py-3 bg-[#0F2A44] text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all shadow-lg hover:shadow-[#0F2A44]/30 flex items-center justify-center gap-2 active:scale-95">
                <i data-lucide="download" class="w-4 h-4 text-[#D4AF37]"></i>
                <span class="whitespace-nowrap">Download PDF</span>
            </button>
            <button @click="$dispatch('open-modal', 'ai-copywriter'); setTimeout(() => typeof lucide !== 'undefined' && lucide.createIcons(), 50)" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-200 text-slate-700 hover:text-gold-600 rounded-xl text-sm font-bold transition-all shadow-sm flex items-center justify-center gap-2 active:scale-95">
                <i data-lucide="sparkles" class="w-4 h-4 text-gold-500"></i>
                <span class="whitespace-nowrap">AI Copywriter</span>
            </button>
            <button class="w-full sm:w-auto px-6 py-3 bg-gold-500 text-slate-950 rounded-xl text-sm font-black hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20 flex items-center justify-center gap-2 active:scale-95">
                <i data-lucide="send" class="w-4 h-4"></i>
                <span class="whitespace-nowrap">{{ __('ui.send_invoice') ?? 'Send Invoice' }}</span>
            </button>
        </div>
    </div>

    <!-- Main Invoice Card -->
    <div class="bg-white rounded-[24px] md:rounded-[32px] border border-slate-200/60 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] overflow-hidden max-w-5xl mx-auto mb-20 relative w-full">
        <!-- Decorative Elements (Hidden on small mobile) -->
        <div class="hidden sm:block absolute top-0 right-0 w-64 h-64 bg-gold-50/30 rounded-full blur-3xl -mr-32 -mt-32"></div>
        <div class="hidden sm:block absolute bottom-0 left-0 w-64 h-64 bg-slate-50 rounded-full blur-3xl -ml-32 -mb-32"></div>
        
        <!-- Professional Invoice Header -->
        <div class="p-6 md:p-16 border-b border-slate-100 relative">
            <div class="flex flex-col md:flex-row justify-between items-start gap-10 md:gap-12">
                <div class="w-full md:w-auto space-y-6 md:space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#0f172a] flex items-center justify-center text-white font-bold">J</div>
                        <span class="text-xl font-black text-slate-900 tracking-tighter uppercase">J&J GROUP<span class="text-gold-500">.</span></span>
                    </div>
                    <div class="space-y-1 text-sm text-slate-500 text-left">
                        <p class="font-bold text-slate-900">{{ \App\Models\Setting::get('company_name', 'J&J GROUP PLUMBING SERVICES') }}</p>
                        <p>{{ \App\Models\Setting::get('company_address', 'Jl. Dewa RT.002/002 No.70, Ciracas, Jakarta Timur') }}</p>
                        <p class="pt-2 font-medium">
                            @php
                                $phonesStr = \App\Models\Setting::get('company_phone', '0812-40000-759 / 0812-40000-749 / 0812-83-300-900');
                                $phones = array_map('trim', explode('/', $phonesStr));
                                $primary = '0812-40000-759';
                                $reordered = [];
                                if (in_array($primary, $phones)) {
                                    $reordered[] = $primary;
                                    foreach ($phones as $phone) {
                                        if ($phone !== $primary) {
                                            $reordered[] = $phone;
                                        }
                                    }
                                } else {
                                    $reordered = $phones;
                                }
                            @endphp
                            T: 
                            @foreach($reordered as $phone)
                                @if($phone === $primary)
                                    <span class="font-bold text-slate-900">{{ $phone }} (Utama)</span>
                                @else
                                    {{ $phone }}
                                @endif
                                @if(!$loop->last) / @endif
                            @endforeach
                            <br>
                            E: {{ \App\Models\Setting::get('company_email', 'Jayarooter@gmail.com / Jawarooter@gmail.com') }}<br>
                            W: {{ \App\Models\Setting::get('company_website', 'Jayarooter.com / Jawarooter.com') }}
                        </p>
                    </div>
                </div>
                
                <div class="w-full md:w-auto text-left md:text-right">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em] mb-4 md:mb-6">{{ app()->getLocale() == 'en' ? 'Tax Invoice' : 'Faktur Pajak' }}</h2>
                    <div class="space-y-1">
                        <p class="text-2xl font-black text-slate-900 font-outfit">{{ $invoice->invoice_number }}</p>
                        <p class="text-xs font-bold text-slate-500">{{ app()->getLocale() == 'en' ? 'Issued' : 'Dibuat' }}: {{ $invoice->tanggal_invoice ? $invoice->tanggal_invoice->format('M d, Y') : '-' }}</p>
                        <div class="inline-block mt-4 px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded text-[10px] font-bold uppercase tracking-widest">
                            {{ app()->getLocale() == 'en' ? 'Due' : 'Tempo' }}: {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Relations -->
        <div class="flex flex-col md:flex-row p-6 md:p-16 gap-10 md:gap-16 bg-slate-50/30 border-b border-slate-100">
            <div class="w-full md:flex-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">{{ app()->getLocale() == 'en' ? 'Customer Account' : 'Akun Pelanggan' }}</p>
                <div class="space-y-2">
                    <p class="text-base md:text-lg font-black text-slate-900">{{ optional($invoice->client)->nama_client ?? 'Klien Tidak Ditemukan' }}</p>
                    <p class="text-xs md:text-sm font-bold text-gold-600">{{ optional($invoice->client)->nama_perusahaan ?? '-' }}</p>
                    <p class="text-xs md:text-sm text-slate-500 leading-relaxed max-w-xs">
                        {{ optional($invoice->client)->alamat ?? '-' }}<br>
                        {{ optional($invoice->client)->kota ?? '-' }}, {{ optional($invoice->client)->provinsi ?? '-' }}
                    </p>
                </div>
            </div>
            
            <div class="w-full md:w-auto text-left md:text-right">
                <div class="p-6 md:p-8 bg-white rounded-xl border border-slate-200 shadow-sm w-full">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Total Payable' : 'Total Tagihan' }}</p>
                    <p class="text-2xl md:text-3xl font-black text-slate-900 font-outfit">Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Line Items -->
        <div class="w-full">
            <!-- Desktop Table -->
            <table class="hidden md:table w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase text-slate-400 tracking-widest border-b border-slate-200">
                        <th class="py-4 px-16">{{ app()->getLocale() == 'en' ? 'Line Item Description' : 'Deskripsi Item Tagihan' }}</th>
                        <th class="py-4 text-center w-24">{{ app()->getLocale() == 'en' ? 'Qty' : 'Jumlah' }}</th>
                        <th class="py-4 text-right w-40">{{ app()->getLocale() == 'en' ? 'Rate' : 'Harga Satuan' }}</th>
                        <th class="py-4 text-right px-16 w-40">{{ app()->getLocale() == 'en' ? 'Amount' : 'Total' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="py-6 px-16">
                                <p class="text-[13px] font-bold text-slate-900">{{ $item->deskripsi }}</p>
                            </td>
                            <td class="py-6 text-center text-[13px] font-medium text-slate-600">{{ number_format($item->qty, 0) }}</td>
                            <td class="py-6 text-right text-[13px] font-medium text-slate-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="py-6 text-right px-16 text-[13px] font-black text-slate-900">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Mobile Card Style Items (Strict Vertical Stack) -->
            <div class="md:hidden divide-y divide-slate-100">
                @foreach($invoice->items as $item)
                    <div class="p-6 space-y-5">
                        <div class="w-full">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5">{{ app()->getLocale() == 'en' ? 'Description' : 'Deskripsi' }}</p>
                            <p class="text-sm font-bold text-slate-900 leading-snug">{{ $item->deskripsi }}</p>
                        </div>
                        <div class="w-full">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5">{{ app()->getLocale() == 'en' ? 'Quantity & Rate' : 'Jumlah & Harga Satuan' }}</p>
                            <p class="text-xs font-medium text-slate-600">
                                {{ number_format($item->qty, 0) }} Unit &times; Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="w-full bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ app()->getLocale() == 'en' ? 'Item Subtotal' : 'Subtotal Item' }}</p>
                            <p class="text-base font-black text-slate-900">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Calculation Summary -->
        <div class="p-6 md:p-16 bg-slate-50/20 border-t border-slate-100">
            <div class="flex flex-col md:flex-row justify-between gap-12">
                <div class="w-full md:flex-1 space-y-8">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ app()->getLocale() == 'en' ? 'Terms & Conditions' : 'Syarat & Ketentuan' }}</p>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ $invoice->terms_condition }}</p>
                    </div>
                    
                    <!-- Payment History Section -->
                    @if($invoice->payments->count() > 0)
                    <div class="w-full">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">{{ app()->getLocale() == 'en' ? 'Payment History' : 'Riwayat Pembayaran' }}</p>
                        <div class="space-y-3">
                            @foreach($invoice->payments as $payment)
                            <div class="flex items-center justify-between p-4 bg-white border border-slate-200/60 rounded-xl shadow-sm">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-slate-900">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase mt-0.5">{{ $payment->payment_method }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-black text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() == 'en' ? 'Delete this payment record?' : 'Hapus catatan pembayaran ini?' }}')">
                                        @csrf @method('DELETE')
                                        <button class="p-1.5 text-slate-300 hover:text-rose-500 transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
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
                        <span class="text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Adjustment' : 'Penyesuaian' }}</span>
                        <span class="font-bold text-rose-500">- Rp {{ number_format($invoice->subtotal * ($invoice->discount_percent / 100), 0, ',', '.') }}</span>
                    </div>
                    
                    @if($invoice->status === 'dp')
                    <div class="flex justify-between items-center p-4 bg-gold-50 rounded-xl border border-gold-100">
                        <span class="text-[11px] text-gold-600 font-bold uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Remaining' : 'Sisa Tagihan' }}</span>
                        <span class="text-sm font-black text-gold-700">Rp {{ number_format($invoice->amount_due, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
                        <span class="text-xs md:text-sm font-black text-slate-900 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Total Due' : 'Total Tagihan' }}</span>
                        <span class="text-xl md:text-3xl font-black text-gold-600 font-outfit">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($invoice->status !== 'paid')
                    <button @click="$dispatch('open-modal', 'record-payment')" class="w-full py-4 bg-[#0f172a] text-white rounded-xl font-bold text-[12px] uppercase tracking-widest hover:bg-slate-800 transition-all mt-6 shadow-xl shadow-slate-900/10 active:scale-[0.98]">
                        {{ app()->getLocale() == 'en' ? 'Record Payment' : 'Catat Pembayaran' }}
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 md:px-16 py-10 bg-slate-50 border-t border-slate-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="text-[11px] text-slate-400 font-medium leading-relaxed max-w-lg text-left w-full md:w-auto">
                    <p class="font-bold text-slate-500 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Payment Instructions' : 'Instruksi Pembayaran' }}</p>
                    <p>{{ $invoice->terms_condition }}</p>
                </div>
                <div class="text-left md:text-right w-full md:w-auto">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Digitally Issued By' : 'Diterbitkan Secara Digital Oleh' }}</p>
                    <p class="text-xs font-black text-slate-900 uppercase">{{ optional($invoice->creator)->name ?? 'System' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <x-modal name="record-payment" :show="false">
        <div class="p-6 md:p-10">
            <h3 class="text-xl font-bold text-slate-900 font-outfit mb-2">{{ app()->getLocale() == 'en' ? 'Record Payment' : 'Catat Pembayaran' }}</h3>
            <p class="text-sm text-slate-500 mb-8">{{ app()->getLocale() == 'en' ? 'Enter the amount received for this invoice.' : 'Masukkan jumlah yang diterima untuk invoice ini.' }}</p>
            
            <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Amount Received (IDR)' : 'Jumlah Diterima (IDR)' }}</label>
                    <input type="number" name="amount" value="{{ $invoice->amount_due }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-lg font-black text-gold-600 outline-none focus:ring-2 focus:ring-gold-500/20 transition-all">
                    <p class="text-[10px] text-slate-400 font-medium italic">{{ app()->getLocale() == 'en' ? 'Remaining balance' : 'Sisa saldo' }}: Rp {{ number_format($invoice->amount_due, 0, ',', '.') }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Payment Date' : 'Tanggal Pembayaran' }}</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none focus:ring-2 focus:ring-gold-500/20 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Method' : 'Metode' }}</label>
                        <select name="payment_method" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none focus:ring-2 focus:ring-gold-500/20 transition-all">
                            <option value="Transfer Bank">{{ app()->getLocale() == 'en' ? 'Bank Transfer' : 'Transfer Bank' }}</option>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">{{ app()->getLocale() == 'en' ? 'Credit Card' : 'Kartu Kredit' }}</option>
                            <option value="E-Wallet">E-Wallet</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Reference / Transaction ID' : 'Referensi / ID Transaksi' }}</label>
                    <input type="text" name="reference_number" placeholder="e.g. TRX-123456" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none focus:ring-2 focus:ring-gold-500/20 transition-all">
                </div>

                <div class="pt-6 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'record-payment')" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gold-500 text-slate-950 rounded-lg text-sm font-black shadow-lg shadow-gold-500/20 active:scale-95">{{ app()->getLocale() == 'en' ? 'Save Payment' : 'Simpan Pembayaran' }}</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- AI Copywriter Modal -->
    <x-modal name="ai-copywriter" :show="false">
        <div class="p-6 md:p-10" x-data="{ 
            tone: 'sopan',
            loading: false,
            subject: '',
            body: '',
            warning: '',
            copied: false,
            generateDraft() {
                this.loading = true;
                this.warning = '';
                this.subject = '';
                this.body = '';
                
                fetch('{{ route('invoices.ai-email-draft', $invoice) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ tone: this.tone })
                })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    if (data.success) {
                        this.subject = data.subject;
                        this.body = data.body;
                        if (data.warning) {
                            this.warning = data.warning;
                        }
                    } else {
                        this.warning = '{{ app()->getLocale() == 'en' ? 'Failed to process email draft.' : 'Gagal memproses draf email.' }}';
                    }
                    setTimeout(() => typeof lucide !== 'undefined' && lucide.createIcons(), 50);
                })
                .catch(err => {
                    this.loading = false;
                    this.warning = '{{ app()->getLocale() == 'en' ? 'Connection error occurred.' : 'Terjadi kesalahan koneksi.' }}';
                    setTimeout(() => typeof lucide !== 'undefined' && lucide.createIcons(), 50);
                });
            },
            copyToClipboard(text) {
                navigator.clipboard.writeText(text);
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            }
        }">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-gold-50 flex items-center justify-center text-gold-600">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 font-outfit">AI Billing Copywriter</h3>
                    <p class="text-xs text-slate-500">{{ app()->getLocale() == 'en' ? 'Draft professional billing email reminders instantly.' : 'Buat draf pengingat email tagihan profesional secara instan.' }}</p>
                </div>
            </div>

            <!-- Warning Notice -->
            <div x-show="warning" class="mt-4 p-4 bg-amber-50 border border-amber-100 rounded-xl flex items-start gap-3 text-amber-800 text-xs" style="display: none;">
                <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5 text-amber-600"></i>
                <p x-text="warning"></p>
            </div>

            <!-- Parameters -->
            <div class="mt-6 space-y-4">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Select Email Tone' : 'Pilih Nada Email (Tone)' }}</label>
                    <select x-model="tone" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 outline-none focus:ring-2 focus:ring-gold-500/20 transition-all">
                        <option value="sopan">{{ app()->getLocale() == 'en' ? 'Polite & Professional (Friendly Reminder)' : 'Sopan & Profesional (Friendly Reminder)' }}</option>
                        <option value="tegas">{{ app()->getLocale() == 'en' ? 'Firm & Formal (Immediate Payment)' : 'Tegas & Formal (Pembayaran Segera)' }}</option>
                        <option value="urgent">{{ app()->getLocale() == 'en' ? 'Urgent (Past Due)' : 'Mendesak / Urgent (Batas Waktu Lewat)' }}</option>
                    </select>
                </div>

                <button @click="generateDraft()" :disabled="loading" class="w-full py-3 bg-gold-500 text-slate-950 rounded-xl text-sm font-black hover:bg-gold-600 transition-all shadow-md shadow-gold-500/10 flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50">
                    <span x-show="!loading" class="flex items-center gap-2">
                        <i data-lucide="zap" class="w-4 h-4"></i> {{ app()->getLocale() == 'en' ? 'Generate AI Draft' : 'Hasilkan Draf AI' }}
                    </span>
                    <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ app()->getLocale() == 'en' ? 'Analyzing & Writing...' : 'Menganalisis & Menulis...' }}
                    </span>
                </button>
            </div>

            <!-- Loading Skeleton -->
            <div x-show="loading" class="mt-8 space-y-4" style="display: none;">
                <div class="animate-pulse space-y-2">
                    <div class="h-4 bg-slate-100 rounded w-1/4"></div>
                    <div class="h-10 bg-slate-50 border border-slate-200 rounded-lg"></div>
                </div>
                <div class="animate-pulse space-y-2">
                    <div class="h-4 bg-slate-100 rounded w-1/4"></div>
                    <div class="h-32 bg-slate-50 border border-slate-200 rounded-lg"></div>
                </div>
            </div>

            <!-- Output Form -->
            <div x-show="!loading && subject" class="mt-8 space-y-6" style="display: none;">
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Email Subject' : 'Subjek Email' }}</label>
                        <button @click="copyToClipboard(subject)" class="text-xs text-gold-600 hover:text-gold-800 font-semibold flex items-center gap-1">
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i> {{ app()->getLocale() == 'en' ? 'Copy' : 'Salin' }}
                        </button>
                    </div>
                    <input type="text" x-model="subject" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-900 outline-none focus:ring-2 focus:ring-gold-500/20 transition-all">
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Email Body' : 'Isi Email (Body)' }}</label>
                        <button @click="copyToClipboard(body)" class="text-xs text-gold-600 hover:text-gold-800 font-semibold flex items-center gap-1">
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i> {{ app()->getLocale() == 'en' ? 'Copy' : 'Salin' }}
                        </button>
                    </div>
                    <textarea x-model="body" rows="8" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 transition-all leading-relaxed"></textarea>
                </div>

                <div class="pt-4 flex justify-between items-center border-t border-slate-100">
                    <span x-show="copied" class="text-xs text-emerald-600 font-bold flex items-center gap-1" style="display: none;">
                        <i data-lucide="check" class="w-4 h-4"></i> {{ app()->getLocale() == 'en' ? 'Copied successfully!' : 'Berhasil disalin!' }}
                    </span>
                    <span x-show="!copied"></span>
                    
                    <div class="flex gap-2">
                        <button type="button" @click="$dispatch('close-modal', 'ai-copywriter')" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800">{{ app()->getLocale() == 'en' ? 'Close' : 'Tutup' }}</button>
                        <button @click="copyToClipboard('Subject: ' + subject + '\n\n' + body)" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center gap-2">
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i> {{ app()->getLocale() == 'en' ? 'Copy All Drafts' : 'Salin Semua Draf' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>
</x-app-layout>
