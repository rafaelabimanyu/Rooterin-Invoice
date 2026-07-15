<x-app-layout :title="app()->getLocale() == 'en' ? 'Database Backup Control' : 'Kontrol Cadangan Database'">
    <div class="animate-fade-in-up pb-24">
        <!-- Header & Breadcrumbs -->
        <div class="mb-10">
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span>{{ app()->getLocale() == 'en' ? 'Administration' : 'Administrasi' }}</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-gold-600">{{ app()->getLocale() == 'en' ? 'Database Backup' : 'Cadangan Database' }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 font-outfit">{{ app()->getLocale() == 'en' ? 'Database Backup & Export' : 'Pencadangan dan Ekspor Database' }}</h1>
            <p class="text-sm text-slate-500">{{ app()->getLocale() == 'en' ? 'Perform manual database exports and configure the dynamic automated backup schedule.' : 'Lakukan ekspor database manual dan konfigurasikan jadwal pencadangan otomatis dinamis.' }}</p>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 text-xs font-bold uppercase tracking-wide">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-700 text-xs font-bold uppercase tracking-wide">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: System Telemetry & Manual Export -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Manual Backup Danger Zone -->
                <div class="glass-card p-10 border-rose-500/20 bg-rose-50/10">
                    <div class="flex items-start gap-4 mb-8">
                        <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center shrink-0 shadow-sm">
                            <i data-lucide="shield-alert" class="w-7 h-7"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Administrative Danger Zone' : 'Zona Bahaya Administratif' }}</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ app()->getLocale() == 'en' ? 'Exporting the database dumps raw SQL schema and data. Ensure the exported file is stored securely since it contains sensitive customer, invoice, and user credential information.' : 'Mengekspor basis data membuang skema dan data SQL mentah. Pastikan file yang diekspor disimpan dengan aman karena berisi informasi sensitif pelanggan, invoice, dan data login pengguna.' }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('backup.export') }}" x-data="{ exporting: false }" @submit="exporting = true">
                        @csrf
                        <button type="submit" ::disabled="exporting" class="w-full md:w-auto px-8 py-4 bg-rose-600 hover:bg-rose-700 disabled:bg-rose-400 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-rose-600/20 flex items-center justify-center gap-3">
                            <template x-if="exporting">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ app()->getLocale() == 'en' ? 'Generating Dump...' : 'Membuat Salinan...' }}
                                </span>
                            </template>
                            <template x-if="!exporting">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                    {{ app()->getLocale() == 'en' ? 'Generate & Download Database' : 'Buat & Unduh Database' }}
                                </span>
                            </template>
                        </button>
                    </form>
                </div>

                <!-- Database Connection Details -->
                <div class="glass-card p-10">
                    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-gold-50 flex items-center justify-center text-gold-600">
                            <i data-lucide="database" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Database Specifications' : 'Spesifikasi Database' }}</h2>
                            <p class="text-xs text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Active system database connection details.' : 'Detail koneksi database sistem yang aktif.' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Driver' : 'Driver' }}</p>
                            <p class="text-[13px] font-bold text-slate-800 uppercase">{{ $dbConnection }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Host' : 'Host' }}</p>
                            <p class="text-[13px] font-bold text-slate-800">{{ $dbHost }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Port' : 'Port' }}</p>
                            <p class="text-[13px] font-bold text-slate-800">{{ $dbPort }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Database Name' : 'Nama Database' }}</p>
                            <p class="text-[13px] font-bold text-slate-800 truncate">{{ $dbName }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Auto Backup Settings -->
            <div class="lg:col-span-1">
                <div class="glass-card p-8">
                    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i data-lucide="clock" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Automated Backup' : 'Pencadangan Otomatis' }}</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Configure Scheduler' : 'Konfigurasi Penjadwal' }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('backup.update-settings') }}" class="space-y-8">
                        @csrf
                        
                        <!-- Toggle Status -->
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Auto Backup Status' : 'Status Backup Otomatis' }}</label>
                            <div class="flex items-center gap-3">
                                <label class="flex-1">
                                    <input type="radio" name="backup_auto_status" value="on" {{ $autoStatus === 'on' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="py-3.5 rounded-2xl border-2 text-center transition-all font-black uppercase text-[10px] tracking-widest cursor-pointer peer-checked:border-gold-500 peer-checked:bg-gold-50 peer-checked:text-gold-700 border-transparent bg-slate-50 text-slate-400 hover:bg-slate-100">
                                        {{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}
                                    </div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="backup_auto_status" value="off" {{ $autoStatus === 'off' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="py-3.5 rounded-2xl border-2 text-center transition-all font-black uppercase text-[10px] tracking-widest cursor-pointer peer-checked:border-gold-500 peer-checked:bg-gold-50 peer-checked:text-gold-700 border-transparent bg-slate-50 text-slate-400 hover:bg-slate-100">
                                        {{ app()->getLocale() == 'en' ? 'Disabled' : 'Nonaktif' }}
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Frequency Options -->
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Backup Frequency' : 'Frekuensi Pencadangan' }}</label>
                            <div class="flex items-center gap-3">
                                <label class="flex-1">
                                    <input type="radio" name="backup_auto_frequency" value="daily" {{ $autoFrequency === 'daily' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="py-3.5 rounded-2xl border-2 text-center transition-all font-black uppercase text-[10px] tracking-widest cursor-pointer peer-checked:border-gold-500 peer-checked:bg-gold-50 peer-checked:text-gold-700 border-transparent bg-slate-50 text-slate-400 hover:bg-slate-100">
                                        {{ app()->getLocale() == 'en' ? 'Daily' : 'Harian' }}
                                    </div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="backup_auto_frequency" value="weekly" {{ $autoFrequency === 'weekly' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="py-3.5 rounded-2xl border-2 text-center transition-all font-black uppercase text-[10px] tracking-widest cursor-pointer peer-checked:border-gold-500 peer-checked:bg-gold-50 peer-checked:text-gold-700 border-transparent bg-slate-50 text-slate-400 hover:bg-slate-100">
                                        {{ app()->getLocale() == 'en' ? 'Weekly' : 'Mingguan' }}
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Execution Time -->
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Execution Time' : 'Waktu Eksekusi' }}</label>
                            <div class="relative">
                                <i data-lucide="clock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                <input type="time" name="backup_auto_time" value="{{ $autoTime }}" class="w-full pl-12 pr-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-gold-500/5 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-slate-900/20">
                                {{ app()->getLocale() == 'en' ? 'Save Schedule' : 'Simpan Jadwal' }}
                            </button>
                        </div>
                    </form>

                    <!-- Retention Info Panel -->
                    <div class="mt-8 pt-8 border-t border-slate-100 flex items-start gap-3">
                        <i data-lucide="info" class="w-4 h-4 text-slate-400 mt-0.5 shrink-0"></i>
                        <p class="text-[10px] text-slate-400 font-medium leading-relaxed">
                            {{ app()->getLocale() == 'en' ? 'Automated backups are saved under storage/app/backups/automated/ and automatically rotated, keeping only the last 7 days of files.' : 'Pencadangan otomatis disimpan di bawah storage/app/backups/automated/ dan dirotasi secara otomatis dengan menyimpan file 7 hari terakhir saja.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
