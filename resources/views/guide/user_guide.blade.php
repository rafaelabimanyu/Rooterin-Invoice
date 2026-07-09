<x-app-layout :title="app()->getLocale() == 'en' ? 'Operational SOP' : 'SOP Operasional'">
    @php
        $isEn = app()->getLocale() == 'en';
    @endphp

    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 px-4 md:px-0">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 font-jakarta">
                <a href="{{ route('dashboard') }}" class="hover:text-gold-650 transition-colors">{{ $isEn ? 'Dashboard' : 'Dasbor' }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-gold-650">{{ $isEn ? 'Operational SOP' : 'SOP Operasional' }}</span>
            </div>
            <h1 class="text-4xl font-extrabold text-slate-900 font-outfit tracking-tight">
                {{ $isEn ? 'Operational SOP & Guide' : 'SOP & Panduan Operasional' }}
            </h1>
            <p class="text-sm text-slate-500 font-medium mt-1">
                {{ $isEn ? 'Standard operating procedures for J&J GROUP enterprise tools.' : 'Prosedur operasional standar dan panduan penggunaan sistem penagihan J&J GROUP.' }}
            </p>
        </div>
        <div>
            <button onclick="window.print()" class="px-5 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm uppercase tracking-wider">
                <i data-lucide="printer" class="w-4 h-4 text-gold-600"></i>
                {{ $isEn ? 'Print Guide' : 'Cetak Panduan' }}
            </button>
        </div>
    </div>

    <!-- Layout Grid with AlpineJS Navigation -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 px-4 md:px-0 pb-24" x-data="{ activeSection: 'invoice-sop' }">
        
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-3">
            <div class="glass-card p-4 rounded-2xl border-slate-200/60 sticky top-24 space-y-4">
                <div class="px-3 py-1 bg-slate-50 rounded-lg">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">J&J Group SOP</span>
                </div>
                <nav class="space-y-1">
                    <button @click="activeSection = 'invoice-sop'" 
                            :class="activeSection === 'invoice-sop' ? 'bg-[#0F2A44] text-white shadow-lg shadow-[#0F2A44]/15' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-900'"
                            class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold transition-all flex items-center gap-3">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>{{ $isEn ? 'Invoice Creation' : 'Pembuatan Invoice' }}</span>
                    </button>
                    <button @click="activeSection = 'receipt-sop'" 
                            :class="activeSection === 'receipt-sop' ? 'bg-[#0F2A44] text-white shadow-lg shadow-[#0F2A44]/15' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-900'"
                            class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold transition-all flex items-center gap-3">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                        <span>{{ $isEn ? 'Receipts Management' : 'Manajemen Kwitansi' }}</span>
                    </button>
                    <button @click="activeSection = 'technician-sop'" 
                            :class="activeSection === 'technician-sop' ? 'bg-[#0F2A44] text-white shadow-lg shadow-[#0F2A44]/15' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-900'"
                            class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold transition-all flex items-center gap-3">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <span>{{ $isEn ? 'Field Technicians' : 'Teknisi Lapangan' }}</span>
                    </button>
                    <button @click="activeSection = 'business-unit-sop'" 
                            :class="activeSection === 'business-unit-sop' ? 'bg-[#0F2A44] text-white shadow-lg shadow-[#0F2A44]/15' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-900'"
                            class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold transition-all flex items-center gap-3">
                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                        <span>{{ $isEn ? 'Business Unit Reports' : 'Rekapan Unit Bisnis' }}</span>
                    </button>
                    <button @click="activeSection = 'ai-sop'" 
                            :class="activeSection === 'ai-sop' ? 'bg-[#0F2A44] text-white shadow-lg shadow-[#0F2A44]/15' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-900'"
                            class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold transition-all flex items-center gap-3">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        <span>{{ $isEn ? 'AI Assistants' : 'Integrasi AI' }}</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Content Panels -->
        <div class="lg:col-span-9 space-y-6">
            
            <!-- Panel 1: Invoice SOP -->
            <div x-show="activeSection === 'invoice-sop'" class="space-y-6 animate-fade-in" x-cloak>
                <div class="glass-card relative overflow-hidden p-8 md:p-10 border-slate-200/60 shadow-sm">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-[#0F2A44]"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#0F2A44]">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 font-outfit tracking-tight">
                            {{ $isEn ? '1. Invoice Creation SOP' : '1. SOP Pembuatan Tagihan (Invoice)' }}
                        </h2>
                    </div>

                    <p class="text-sm text-slate-600 mb-8 leading-relaxed">
                        {{ $isEn ? 'Follow this sequential workflow to draft and issue billing statements directly to clients and clear ledger balances.' : 'Ikuti alur kerja berurutan berikut untuk merancang dan menerbitkan tagihan secara resmi kepada klien.' }}
                    </p>

                    <!-- Steps Timeline -->
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">1</div>
                                <div class="w-0.5 flex-1 bg-slate-200 my-1"></div>
                            </div>
                            <div class="pb-4">
                                <h4 class="text-sm font-bold text-[#0F2A44]">{{ $isEn ? 'Client & Business Unit Registration' : 'Registrasi Klien & Unit Bisnis' }}</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    {{ $isEn ? 'Ensure the target Client and correct Business Unit are registered in the system ledger.' : 'Pastikan klien tujuan dan unit bisnis yang menaungi proyek telah terdaftar dalam sistem.' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">2</div>
                                <div class="w-0.5 flex-1 bg-slate-200 my-1"></div>
                            </div>
                            <div class="pb-4">
                                <h4 class="text-sm font-bold text-[#0F2A44]">{{ $isEn ? 'Item Descriptions & Rate Input' : 'Pengisian Deskripsi Pekerjaan & Nominal' }}</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    {{ $isEn ? 'Input accurate line item descriptions, quantity (Qty), and unit rate. Descriptions must be transparent and formal.' : 'Tuliskan deskripsi pekerjaan secara rinci (misal: "Pembersihan Saluran Mampet Wastafel"), jumlah (Qty), dan tarif harga satuan.' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">3</div>
                                <div class="w-0.5 flex-1 bg-slate-200 my-1"></div>
                            </div>
                            <div class="pb-4">
                                <h4 class="text-sm font-bold text-[#0F2A44]">{{ $isEn ? 'Taxes & Discounts Configuration' : 'Kalkulasi Pajak & Potongan Harga' }}</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    {{ $isEn ? 'Select applicable VAT (PPN) and Income Tax (PPh) rates. Apply a flat/percentage discount if pre-approved.' : 'Pilih tarif PPN dan PPh yang sesuai. Input potongan harga (Discount) jika disetujui sebelumnya.' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">4</div>
                                <div class="w-0.5 flex-1 bg-slate-200 my-1"></div>
                            </div>
                            <div class="pb-4">
                                <h4 class="text-sm font-bold text-[#0F2A44]">{{ $isEn ? 'Assign Field Technicians' : 'Penugasan Teknisi Lapangan' }}</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    {{ $isEn ? 'Enter the names of the field personnel responsible for work execution. Names will appear in PDF receipts and invoices.' : 'Ketik nama-nama teknisi lapangan yang bertugas mengeksekusi layanan pembersihan di lokasi klien.' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">5</div>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-[#0F2A44]">{{ $isEn ? 'Problem Cause & Documentation' : 'Dokumentasi & Penyebab Masalah' }}</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    {{ $isEn ? 'State the main cause of the plumbing blockage (e.g. grease) and upload photo proof (before/after).' : 'Tuliskan analisis penyebab saluran mampet (misal: lemak beku) dan unggah dokumentasi foto hasil pengerjaan.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Receipts Management -->
            <div x-show="activeSection === 'receipt-sop'" class="space-y-6 animate-fade-in" x-cloak>
                <div class="glass-card relative overflow-hidden p-8 md:p-10 border-slate-200/60 shadow-sm">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-[#1FAF5A]"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#1FAF5A]">
                            <i data-lucide="receipt" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 font-outfit tracking-tight">
                            {{ $isEn ? '2. Receipts & Payments' : '2. Manajemen Tanda Terima / Kwitansi' }}
                        </h2>
                    </div>

                    <p class="text-sm text-slate-650 mb-6 leading-relaxed font-medium">
                        {{ $isEn ? 'A Receipt serves as official proof of payment for clients. The system generates it instantly upon recording financial transaction logs.' : 'Kwitansi atau Tanda Terima dikeluarkan setelah pembayaran (penuh atau sebagian) dikonfirmasi masuk ke rekening perusahaan.' }}
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                            <h4 class="text-xs font-black uppercase text-[#0F2A44] tracking-wider mb-2">{{ $isEn ? 'Record Payments' : 'Pencatatan Pembayaran' }}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{ $isEn ? 'Use the "Record Payment" button inside the Invoice detail panel to log client deposits, partials, or full payments.' : 'Gunakan fitur "Catat Pembayaran" di halaman detail Invoice untuk memasukkan nominal uang yang disetorkan klien.' }}
                            </p>
                        </div>
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                            <h4 class="text-xs font-black uppercase text-[#0F2A44] tracking-wider mb-2">{{ $isEn ? 'Watermark & Security' : 'Watermark & Keabsahan' }}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{ $isEn ? 'Generated PDF files contain a secure, translucent "ORIGINAL" watermark, and dynamic signatures of management to guarantee validity.' : 'PDF Kwitansi memuat secara otomatis watermark "ORIGINAL" dan tanda tangan pejabat penanggung jawab keuangan.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 3: Technicians Module -->
            <div x-show="activeSection === 'technician-sop'" class="space-y-6 animate-fade-in" x-cloak>
                <div class="glass-card relative overflow-hidden p-8 md:p-10 border-slate-200/60 shadow-sm">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-amber-500">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 font-outfit tracking-tight">
                            {{ $isEn ? '3. Field Technicians Module' : '3. Modul Teknisi Lapangan' }}
                        </h2>
                    </div>

                    <p class="text-sm text-slate-650 mb-6 leading-relaxed">
                        {{ $isEn ? 'This module ensures administrative accountability by linking specific service jobs to the field technicians.' : 'Modul ini digunakan untuk merekam akuntabilitas personil lapangan J&J Group yang bertugas mengeksekusi layanan di lokasi.' }}
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-amber-500"></div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                <strong>{{ $isEn ? 'Input Format:' : 'Format Penginputan:' }}</strong> {{ $isEn ? 'Names should be written as a comma-separated string (e.g. "Budi Santoso, Andi Wijaya") inside the invoice create/edit forms.' : 'Ketik nama-nama teknisi dipisahkan dengan tanda koma (misal: "Budi Santoso, Andi Wijaya") pada formulir Invoice.' }}
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-amber-500"></div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                <strong>{{ $isEn ? 'PDF Rendering:' : 'Tampilan Dokumen:' }}</strong> {{ $isEn ? 'The technician metadata is rendered under Page 2, directly below the documentation images to verify crew identity for customers.' : 'Nama teknisi akan tercetak otomatis di Halaman 2 dokumen PDF tepat di bawah blok gambar dokumentasi pekerjaan.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 4: Business Unit Reports -->
            <div x-show="activeSection === 'business-unit-sop'" class="space-y-6 animate-fade-in" x-cloak>
                <div class="glass-card relative overflow-hidden p-8 md:p-10 border-slate-200/60 shadow-sm">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-indigo-500">
                            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 font-outfit tracking-tight">
                            {{ $isEn ? '4. Business Unit Reporting SOP' : '4. Sistem Rekapan Unit Bisnis' }}
                        </h2>
                    </div>

                    <p class="text-sm text-slate-650 mb-6 leading-relaxed">
                        {{ $isEn ? 'All financial dashboards, Owner KPIs, and detailed metrics leverage the single unified backend service to ensure 100% data synchronization.' : 'Semua dasbor pelaporan keuangan menggunakan BusinessUnitReportingService sebagai sumber data tunggal (Single Source of Truth) agar data 100% sinkron.' }}
                    </p>

                    <!-- Metrics Table -->
                    <div class="overflow-x-auto rounded-xl border border-slate-200 mt-6">
                        <table class="w-full text-left text-xs font-jakarta">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold">
                                <tr>
                                    <th class="p-4">{{ $isEn ? 'Metric' : 'Metrik' }}</th>
                                    <th class="p-4">{{ $isEn ? 'Calculation Source' : 'Sumber Perhitungan' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                                <tr>
                                    <td class="p-4 font-bold text-slate-900">Total Billed</td>
                                    <td class="p-4">{{ $isEn ? 'Accumulated subtotal value of all invoices issued.' : 'Akumulasi total nilai kotor seluruh invoice yang pernah dibuat.' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-4 font-bold text-[#1FAF5A]">Total Revenue</td>
                                    <td class="p-4">{{ $isEn ? 'Total paid amounts extracted from settled invoice transactions.' : 'Akumulasi nominal pembayaran yang berstatus lunas (paid).' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-4 font-bold text-rose-500">Outstanding</td>
                                    <td class="p-4">{{ $isEn ? 'Uncollected billings calculated after deducting recorded partial payments.' : 'Sisa piutang klien setelah dikurangi nominal cicilan/pembayaran masuk.' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panel 5: AI Integration -->
            <div x-show="activeSection === 'ai-sop'" class="space-y-6 animate-fade-in" x-cloak>
                <div class="glass-card relative overflow-hidden p-8 md:p-10 border-slate-200/60 shadow-sm">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-[#D4AF37]"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#D4AF37]">
                            <i data-lucide="sparkles" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 font-outfit tracking-tight">
                            {{ $isEn ? '5. AI Copywriter & Assistant' : '5. Integrasi Kecerdasan Buatan (AI)' }}
                        </h2>
                    </div>

                    <p class="text-sm text-slate-650 mb-8 leading-relaxed">
                        {{ $isEn ? 'Leverage J&J Group AI layers to automate manual communication and retrieve real-time system guidance.' : 'Gunakan asisten kecerdasan buatan untuk mempercepat penulisan pesan penagihan dan menanyakan informasi SOP.' }}
                    </p>

                    <div class="space-y-6">
                        <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-200/50">
                            <h4 class="text-sm font-bold text-[#0F2A44] mb-2">{{ $isEn ? 'AI Copywriter (Email Drafts)' : 'AI Copywriter (Draf Email)' }}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{ $isEn ? 'Available on the Invoice detail page. Auto-generates tailored notification drafts based on friendly, formal, or urgent templates.' : 'Dapat diakses di detail invoice. Membantu merumuskan teks email penagihan secara otomatis berdasarkan nada (sopan, tegas, atau urgent).' }}
                            </p>
                        </div>

                        <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-200/50">
                            <h4 class="text-sm font-bold text-[#0F2A44] mb-2">{{ $isEn ? 'AI Chat Assistant' : 'AI Chat Assistant' }}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{ $isEn ? 'Consult the floating chat bubble on the dashboard to query general accounting terms, search specific invoices, or review SOP guidelines.' : 'Hubungi asisten bot pada ikon obrolan di pojok layar untuk membantu navigasi, pencarian status pembayaran, atau panduan teknis.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
