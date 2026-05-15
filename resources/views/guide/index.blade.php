<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-10 items-start">
        <!-- Sidebar Navigation (Sticky) -->
        <aside class="w-full lg:w-64 sticky top-24 lg:h-[calc(100vh-120px)] overflow-y-auto hidden lg:block">
            <nav class="space-y-1">
                <p class="px-3 mb-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                    {{ $activeRole === 'owner' ? 'Executive Modules' : 'Staff Modules' }}
                </p>
                @foreach($guideData['menus'] as $menu)
                <a href="#{{ $menu['id'] }}" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white hover:text-indigo-600 rounded-lg transition-all border border-transparent hover:border-slate-100">
                    {{ $menu['label'] }}
                </a>
                @endforeach
                
                @if($activeRole === 'owner')
                <div class="pt-4 border-t border-slate-100 mt-4">
                    <a href="?role=staff" class="group flex items-center px-4 py-2 text-xs font-bold text-slate-400 hover:text-slate-600 transition-all">
                        <i data-lucide="eye" class="w-3 h-3 mr-2"></i> Preview Staff Guide
                    </a>
                </div>
                @else
                <div class="pt-4 border-t border-slate-100 mt-4">
                    <a href="#faq" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white hover:text-indigo-600 rounded-lg transition-all border border-transparent hover:border-slate-100">
                        FAQ Operasional
                    </a>
                </div>
                @endif
            </nav>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 max-w-4xl space-y-20 pb-20">
            <!-- Header -->
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 rounded-full text-indigo-600 text-[10px] font-black uppercase tracking-widest">
                    <i data-lucide="book-open" class="w-3 h-3"></i> 
                    {{ $activeRole === 'owner' ? 'Owner Knowledge Base' : 'Staff SOP' }}
                </div>
                <h1 class="text-5xl font-black text-slate-900 font-outfit tracking-tight">{{ $guideData['header']['title'] }}</h1>
                <p class="text-lg text-slate-500 leading-relaxed">{{ $guideData['header']['subtitle'] }}</p>
            </div>

            <!-- Quick Start Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($guideData['steps'] as $step)
                <div class="glass-card p-6 text-center border-t-4 border-t-indigo-500 hover:-translate-y-1 transition-transform duration-300">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">{{ $step['step'] }}</div>
                    <p class="text-sm font-bold text-slate-900">{{ $step['label'] }}</p>
                </div>
                @endforeach
            </div>

            <!-- Shared Getting Started -->
            <section id="getting-started" class="scroll-mt-32 space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">Getting Started</h2>
                </div>
                <div class="prose prose-slate max-w-none space-y-6">
                    <p class="text-slate-500 leading-relaxed">
                        @if($activeRole === 'owner')
                        Selamat datang di panel pengawasan tingkat lanjut. Fokus utama Anda adalah memastikan performa penagihan berjalan maksimal, memantau data klien, dan mengawasi cashflow secara holistik.
                        @else
                        Rooterin-Invoice dirancang untuk mempermudah alur kerja harian Anda. Pastikan Anda selalu memasukkan data dengan teliti dan mengikuti prosedur yang telah ditetapkan.
                        @endif
                    </p>
                    <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100 flex gap-4">
                        <i data-lucide="lightbulb" class="w-6 h-6 text-amber-600 shrink-0"></i>
                        <p class="text-sm text-amber-900">
                            <strong>Pro Tip:</strong> 
                            @if($activeRole === 'owner')
                            Gunakan Laporan Keuangan secara berkala untuk memantau kesehatan bisnis Anda.
                            @else
                            Gunakan fitur "Inline Client Creation" saat membuat Invoice agar proses penagihan lebih cepat!
                            @endif
                        </p>
                    </div>
                </div>
            </section>

            @if($activeRole === 'staff')
            <!-- ================= STAFF SECTIONS ================= -->
            
            <!-- Clients -->
            <section id="clients" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-600">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">1. Input Client</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Sebelum membuat invoice, pastikan data klien sudah terdaftar di sistem. Anda wajib memasukkan detail dengan akurat untuk mencegah kesalahan penagihan dan administrasi.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <h5 class="font-bold text-slate-900 mb-2">Kelengkapan Data</h5>
                            <p class="text-xs text-slate-500">Selalu isi Nama Perusahaan, Alamat, dan NPWP (jika ada) sesuai dokumen resmi klien.</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <h5 class="font-bold text-slate-900 mb-2">Kontak Utama</h5>
                            <p class="text-xs text-slate-500">Pastikan email dan nomor telepon valid agar pengiriman notifikasi dari sistem tidak gagal.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Invoices -->
            <section id="invoices" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">2. Pembuatan Invoice</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Proses utama Anda adalah merilis tagihan. Sistem Rooterin telah dilengkapi fitur kalkulasi otomatis (Auto-Calc) untuk menangani subtotal, pajak (PPN), dan final total.</p>
                    <div class="glass-card overflow-hidden">
                        <div class="bg-slate-900 px-6 py-4 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">SOP Pembuatan Tagihan</span>
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                        </div>
                        <div class="p-8 space-y-4">
                            <ul class="list-disc list-inside text-sm text-slate-600 space-y-2">
                                <li>Pilih klien yang sudah di-input sebelumnya.</li>
                                <li>Masukkan detail item pekerjaan dengan deskripsi yang sangat jelas.</li>
                                <li>Isi kuantitas dan harga satuan. Total akan terhitung otomatis.</li>
                                <li>Periksa kembali sebelum menekan tombol "Save".</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Send PDF -->
            <section id="send-pdf" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                        <i data-lucide="send" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">3. Pengiriman PDF</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Setelah invoice berstatus siap, Anda harus segera mengirimkannya ke klien dalam format dokumen resmi (PDF) agar proses pencairan dapat dimulai.</p>
                    <div class="p-6 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                        <p class="text-sm text-slate-700"><strong>Langkah:</strong> Buka detail invoice, lalu klik tombol <span class="px-2 py-1 bg-white border border-slate-200 rounded text-xs mx-1 shadow-sm font-bold text-indigo-600"><i data-lucide="download" class="w-3 h-3 inline"></i> Download PDF</span>. Setelah PDF terunduh, kirimkan file tersebut ke email atau WhatsApp klien.</p>
                    </div>
                </div>
            </section>

            <!-- FAQ Staff -->
            <section id="faq" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="help-circle" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">FAQ Operasional</h2>
                </div>
                <div class="space-y-4" x-data="{ active: null }">
                    <div class="glass-card p-6 cursor-pointer hover:border-indigo-500/30 transition-all" @click="active = active === 1 ? null : 1">
                        <div class="flex items-center justify-between">
                            <h5 class="text-sm font-bold text-slate-900">Bagaimana jika salah input invoice?</h5>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="active === 1 ? 'rotate-180' : ''"></i>
                        </div>
                        <div x-show="active === 1" class="mt-4 text-xs text-slate-500 leading-relaxed" x-collapse>
                            Segera hubungi Owner atau Admin untuk meminta penghapusan atau revisi jika invoice sudah berstatus 'Sent' dan terkirim. Jangan membuat invoice ganda.
                        </div>
                    </div>
                    <div class="glass-card p-6 cursor-pointer hover:border-indigo-500/30 transition-all" @click="active = active === 2 ? null : 2">
                        <div class="flex items-center justify-between">
                            <h5 class="text-sm font-bold text-slate-900">Bisakah saya mencatat DP (Down Payment)?</h5>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="active === 2 ? 'rotate-180' : ''"></i>
                        </div>
                        <div x-show="active === 2" class="mt-4 text-xs text-slate-500 leading-relaxed" x-collapse>
                            Ya, di halaman Detail Invoice, klik tombol "Record Payment" lalu masukkan nominal pembayaran parsial yang diterima. Sistem akan menghitung sisa tagihan secara otomatis.
                        </div>
                    </div>
                </div>
            </section>

            @endif

            @if($activeRole === 'owner')
            <!-- ================= OWNER SECTIONS ================= -->
            
            <!-- Owner KPI -->
            <section id="owner-kpi" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="pie-chart" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">1. Analisis Owner KPI</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Dashboard menyajikan Key Performance Indicators (KPI) secara real-time. Metrik ini sangat penting untuk menilai kesehatan keuangan perusahaan Anda dengan cepat.</p>
                    <ul class="space-y-4 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-[10px] shrink-0">01</span>
                            <div><strong>Total Billing:</strong> Akumulasi nilai dari seluruh invoice yang pernah dibuat. Ini merepresentasikan potensi pendapatan kotor perusahaan.</div>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-lg bg-rose-100 flex items-center justify-center text-rose-700 font-bold text-[10px] shrink-0">02</span>
                            <div><strong>Amount Due:</strong> Total saldo yang masih menunggak (piutang). Jika angka ini tinggi, instruksikan staf operasional untuk melakukan penagihan agresif (follow-up).</div>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Reports -->
            <section id="reports" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 flex items-center justify-center text-violet-600">
                        <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">2. Laporan Keuangan</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Gunakan modul Financial Reports untuk memfilter pendapatan berdasarkan rentang tanggal tertentu (harian, bulanan, tahunan) untuk akuntansi.</p>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <h5 class="font-bold text-slate-900 mb-2">Tujuan Strategis</h5>
                        <p class="text-sm text-slate-600">Laporan ini membantu Anda mengidentifikasi tren pertumbuhan revenue, mengevaluasi klien terbaik, dan memprediksi arus kas (cashflow) di kuartal berikutnya.</p>
                    </div>
                </div>
            </section>

            <!-- Settings -->
            <section id="settings" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600">
                        <i data-lucide="settings" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">3. System Settings</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Anda memegang kendali penuh atas identitas dan variabel inti perusahaan di dalam sistem. Modul pengaturan memungkinkan Anda untuk mengonfigurasi:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="glass-card p-6 border-l-4 border-l-slate-400 hover:border-l-indigo-500 transition-colors">
                            <h5 class="font-bold text-slate-900 text-sm">Profil Bisnis</h5>
                            <p class="text-xs text-slate-500 mt-1">Logo resmi, alamat perusahaan, detail rekening bank, dan informasi kontak yang muncul di kop surat PDF Invoice.</p>
                        </div>
                        <div class="glass-card p-6 border-l-4 border-l-slate-400 hover:border-l-indigo-500 transition-colors">
                            <h5 class="font-bold text-slate-900 text-sm">Konfigurasi Tagihan</h5>
                            <p class="text-xs text-slate-500 mt-1">Format penomoran invoice kustom (misal: INV-2026-001) dan persentase tarif pajak (PPN) default.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- User Management -->
            <section id="user-management" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <i data-lucide="users-cog" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">4. Manajemen User</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Kelola akses tim Anda ke dalam sistem Rooterin. Pastikan setiap karyawan memiliki Role yang sesuai dengan fungsinya untuk mencegah kebocoran data.</p>
                    <div class="glass-card overflow-hidden">
                        <div class="bg-slate-900 px-6 py-4 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistem Audit & Keamanan</span>
                            <i data-lucide="shield-alert" class="w-4 h-4 text-rose-500"></i>
                        </div>
                        <div class="p-8 space-y-4">
                            <p class="text-sm text-slate-600">Sebagai Owner, Anda berhak menonaktifkan akun staf yang sudah tidak aktif bekerja. Anda juga dapat memantau <strong>Activity Log</strong> untuk mengaudit dan melacak secara presisi siapa yang membuat atau mengubah data klien, serta menghapus invoice.</p>
                        </div>
                    </div>
                </div>
            </section>
            @endif

        </div>
    </div>
</x-app-layout>
