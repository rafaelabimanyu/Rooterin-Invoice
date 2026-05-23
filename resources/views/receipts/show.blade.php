<x-app-layout :title="app()->getLocale() == 'en' ? 'Receipt & Payment Management' : 'Manajemen Kwitansi & Pembayaran'">
    <div class="mb-8 md:mb-12 flex flex-col lg:flex-row lg:items-end justify-between gap-6 px-4 md:px-0">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 overflow-hidden">
                <a href="{{ route('receipts.index') }}" class="hover:text-indigo-600 transition-colors shrink-0">{{ app()->getLocale() == 'en' ? 'Receipts' : 'Kuitansi' }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 shrink-0"></i>
                <span class="text-slate-900 truncate">{{ $receipt->receipt_number }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 font-outfit leading-tight truncate">{{ app()->getLocale() == 'en' ? 'Receipt Details' : 'Detail Kuitansi' }}</h1>
            <p class="text-sm text-slate-500 mt-1 truncate">{{ app()->getLocale() == 'en' ? 'Review payment receipt for ' : 'Tinjau kuitansi pembayaran untuk ' }}{{ $receipt->client->nama_client }}.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 md:gap-4">
            <div class="flex items-center justify-between sm:justify-start gap-4">
                <x-badge :status="$receipt->status" class="px-3 py-1.5 text-[10px] md:text-[11px]" />
                <div class="flex items-center gap-2">
                    <button title="Print" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 transition-all shadow-sm active:scale-95">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                    </button>
                    <a href="{{ route('receipts.pdf', $receipt) }}" title="{{ app()->getLocale() == 'en' ? 'Download PDF' : 'Unduh PDF' }}" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-indigo-600 transition-all shadow-sm active:scale-95">
                        <i data-lucide="download" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
            
            @if($receipt->status !== 'invoiced' && $receipt->status !== 'rejected')
                <form action="{{ route('receipts.convert', $receipt) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2 active:scale-95">
                        <i data-lucide="file-check" class="w-4 h-4"></i>
                        {{ app()->getLocale() == 'en' ? 'Convert to Invoice' : 'Ubah ke Faktur' }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-[24px] md:rounded-[32px] border border-slate-200/60 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] overflow-hidden max-w-5xl mx-auto mb-20 relative w-full">
        <!-- Decorative Elements -->
        <div class="hidden sm:block absolute top-0 right-0 w-64 h-64 bg-indigo-50/30 rounded-full blur-3xl -mr-32 -mt-32"></div>
        <div class="hidden sm:block absolute bottom-0 left-0 w-64 h-64 bg-slate-50 rounded-full blur-3xl -ml-32 -mb-32"></div>
        
        <!-- Professional Receipt Header -->
        <div class="p-6 md:p-16 border-b border-slate-100 relative">
            <div class="flex flex-col md:flex-row justify-between items-start gap-10 md:gap-12">
                <div class="w-full md:w-auto space-y-6 md:space-y-8 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#0f172a] flex items-center justify-center text-white font-bold">R</div>
                        <span class="text-xl font-black text-slate-900 tracking-tighter uppercase">Rooterin<span class="text-indigo-500">.</span></span>
                    </div>
                    <div class="space-y-1 text-sm text-slate-500">
                        <p class="font-bold text-slate-900">Rooterin Technical Services</p>
                        <p>Jakarta, Indonesia</p>
                        <p class="pt-2 font-medium">billing@rooterin.com</p>
                    </div>
                </div>
                
                <div class="w-full md:w-auto text-left md:text-right space-y-2">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em] mb-4 md:mb-6">{{ app()->getLocale() == 'en' ? 'Payment Receipt' : 'Kuitansi Pembayaran' }}</h2>
                    <p class="text-2xl font-black text-slate-900 font-outfit">{{ $receipt->receipt_number }}</p>
                    <p class="text-xs font-bold text-slate-500">{{ app()->getLocale() == 'en' ? 'Issued' : 'Diterbitkan' }}: {{ $receipt->tanggal_receipt->format('M d, Y') }}</p>
                    <div class="inline-block mt-4 px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded text-[10px] font-bold uppercase tracking-widest">
                        {{ app()->getLocale() == 'en' ? 'Expiry' : 'Kedaluwarsa' }}: {{ $receipt->expiry_date->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Relations -->
        <div class="flex flex-col md:flex-row p-6 md:p-16 gap-10 md:gap-16 bg-slate-50/30 border-b border-slate-100">
            <div class="w-full md:flex-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">{{ app()->getLocale() == 'en' ? 'Client Account' : 'Akun Klien' }}</p>
                <div class="space-y-2">
                    <p class="text-base md:text-lg font-black text-slate-900">{{ $receipt->client->nama_client }}</p>
                    <p class="text-xs md:text-sm font-bold text-indigo-600">{{ $receipt->client->nama_perusahaan }}</p>
                    <p class="text-xs md:text-sm text-slate-500 leading-relaxed max-w-xs">
                        {{ $receipt->client->alamat }}
                    </p>
                </div>
            </div>
            
            <div class="w-full md:w-auto text-left md:text-right">
                <div class="p-6 md:p-8 bg-white rounded-xl border border-slate-200 shadow-sm w-full">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Amount Paid' : 'Jumlah Dibayar' }}</p>
                    <p class="text-2xl md:text-3xl font-black text-slate-900 font-outfit">Rp {{ number_format($receipt->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Line Items -->
        <div class="w-full">
            <!-- Desktop Table -->
            <table class="hidden md:table w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase text-slate-400 tracking-widest border-b border-slate-200">
                        <th class="py-4 px-16">{{ app()->getLocale() == 'en' ? 'Service Description' : 'Deskripsi Layanan' }}</th>
                        <th class="py-4 text-center w-24">{{ app()->getLocale() == 'en' ? 'Qty' : 'Jumlah' }}</th>
                        <th class="py-4 text-right w-40">{{ app()->getLocale() == 'en' ? 'Rate' : 'Harga' }}</th>
                        <th class="py-4 text-right px-16 w-40">{{ app()->getLocale() == 'en' ? 'Amount' : 'Jumlah' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($receipt->items as $item)
                        <tr>
                            <td class="py-6 px-16">
                                <p class="text-[13px] font-bold text-slate-900">{{ $item->deskripsi }}</p>
                            </td>
                            <td class="py-6 text-center text-[13px] font-medium text-slate-600">{{ number_format($item->qty, 0) }}</td>
                            <td class="py-6 text-right text-[13px] font-medium text-slate-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="py-6 text-right px-16 text-[13px] font-black text-slate-900">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Mobile Card List -->
            <div class="md:hidden divide-y divide-slate-100">
                @foreach($receipt->items as $item)
                    <div class="p-6 space-y-5">
                        <div class="w-full">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5">{{ app()->getLocale() == 'en' ? 'Description' : 'Deskripsi' }}</p>
                            <p class="text-sm font-bold text-slate-900 leading-snug">{{ $item->deskripsi }}</p>
                        </div>
                        <div class="w-full">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5">{{ app()->getLocale() == 'en' ? 'Quantity & Rate' : 'Jumlah & Harga' }}</p>
                            <p class="text-xs font-medium text-slate-600">
                                {{ number_format($item->qty, 0) }} Unit &times; Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="w-full bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Subtotal</p>
                            <p class="text-base font-black text-slate-900">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
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
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ $receipt->terms_condition }}</p>
                    </div>
                </div>
                
                <div class="w-full md:w-80 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Subtotal</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($receipt->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">VAT ({{ $receipt->tax_percent }}%)</span>
                        <span class="font-bold text-slate-900">+ Rp {{ number_format($receipt->subtotal * ($receipt->tax_percent / 100), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Discount' : 'Diskon' }}</span>
                        <span class="font-bold text-rose-500">- Rp {{ number_format($receipt->subtotal * ($receipt->discount_percent / 100), 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
                        <span class="text-xs md:text-sm font-black text-slate-900 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Grand Total' : 'Total Keseluruhan' }}</span>
                        <span class="text-xl md:text-3xl font-black text-indigo-600 font-outfit">Rp {{ number_format($receipt->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
