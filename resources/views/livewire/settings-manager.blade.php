<div class="relative pb-24">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1 space-y-2">
            @php
                $localizedTabs = [
                    'general' => app()->getLocale() == 'en' ? 'Identity' : 'Identitas',
                    'finance' => app()->getLocale() == 'en' ? 'Finance' : 'Keuangan',
                    'localization' => app()->getLocale() == 'en' ? 'Regional' : 'Regional',
                    'notifications' => app()->getLocale() == 'en' ? 'Communications' : 'Komunikasi',
                    'security' => app()->getLocale() == 'en' ? 'Security' : 'Keamanan'
                ];
            @endphp

            <!-- Mobile Horizontal Swipeable Tabs -->
            <nav class="flex overflow-x-auto snap-x gap-2 pb-3 no-scrollbar md:hidden">
                @foreach($localizedTabs as $tab => $label)
                    <button 
                        wire:click="$set('activeTab', '{{ $tab }}')"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-full text-[11px] font-black uppercase tracking-widest transition-all duration-300 shrink-0 snap-center
                        {{ $activeTab === $tab ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10' : 'bg-slate-100 text-slate-500 hover:bg-slate-200/60' }}"
                    >
                        <i data-lucide="{{ 
                            match($tab) {
                                'general' => 'building-2',
                                'finance' => 'wallet',
                                'localization' => 'globe',
                                'notifications' => 'mail',
                                'security' => 'shield-check',
                            }
                        }}" class="w-4 h-4"></i>
                        {{ $label }}
                    </button>
                @endforeach
            </nav>

            <!-- Desktop Vertical Navigation -->
            <nav class="hidden md:flex md:flex-col space-y-1">
                @foreach($localizedTabs as $tab => $label)
                    <button 
                        wire:click="$set('activeTab', '{{ $tab }}')"
                        class="flex items-center gap-3 px-5 py-4 rounded-2xl text-[13px] font-black uppercase tracking-widest transition-all duration-300 w-full text-left
                        {{ $activeTab === $tab ? 'bg-slate-900 text-white shadow-xl shadow-slate-900/20 translate-x-2' : 'bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        <i data-lucide="{{ 
                            match($tab) {
                                'general' => 'building-2',
                                'finance' => 'wallet',
                                'localization' => 'globe',
                                'notifications' => 'mail',
                                'security' => 'shield-check',
                            }
                        }}" class="w-5 h-5"></i>
                        {{ $label }}
                    </button>
                @endforeach
            </nav>

            <!-- Desktop Telemetry Box -->
            <div class="hidden md:block mt-8 glass-card p-6 bg-slate-900 text-white overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-gold-500/10 blur-3xl rounded-full"></div>
                <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4 relative z-10">{{ app()->getLocale() == 'en' ? 'Telemetry' : 'Telemetri' }}</h3>
                <div class="space-y-4 relative z-10">
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Last Backup' : 'Pencadangan Terakhir' }}</p>
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-[13px] font-bold text-gold-400">{{ $lastBackup }}</p>
                            <button wire:click="runBackup" wire:loading.attr="disabled" class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 bg-gold-500 text-slate-950 rounded-lg hover:bg-gold-600 transition-all active:scale-95 flex items-center gap-1">
                                <span wire:loading.remove wire:target="runBackup">{{ app()->getLocale() == 'en' ? 'Backup' : 'Cadangkan' }}</span>
                                <span wire:loading wire:target="runBackup">{{ app()->getLocale() == 'en' ? 'Backing up...' : 'Mencadangkan...' }}</span>
                            </button>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Server Health' : 'Kesehatan Server' }}</p>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[13px] font-bold text-emerald-400">{{ $serverStatus }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-3 space-y-8">
            <div class="glass-card p-4 md:p-8 min-h-[600px]">
                <!-- General Tab -->
                @if($activeTab === 'general')
                <div x-data x-init="lucide.createIcons()" x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-gold-50 flex items-center justify-center text-gold-600">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Business Identity' : 'Identitas Bisnis' }}</h2>
                            <p class="text-xs text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Global identification for your enterprise.' : 'Identitas global untuk perusahaan Anda.' }}</p>
                        </div>
                    </div>

                     <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Enterprise Legal Name' : 'Nama Hukum Perusahaan' }}</label>
                                <div class="relative">
                                    <i data-lucide="building" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                    <input type="text" wire:model.live="settings.company_name" class="w-full pl-12 pr-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm">
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Primary Contact Email' : 'Email Kontak Utama' }}</label>
                                <div class="relative">
                                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                    <input type="email" wire:model.live="settings.company_email" class="w-full pl-12 pr-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm">
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Contact Phone' : 'Nomor Telepon Kontak' }}</label>
                                <div class="relative">
                                    <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                    <input type="text" wire:model.live="settings.company_phone" class="w-full pl-12 pr-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm">
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Company Website' : 'Website Perusahaan' }}</label>
                                <div class="relative">
                                    <i data-lucide="globe" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                    <input type="text" wire:model.live="settings.company_website" class="w-full pl-12 pr-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Headquarters Address' : 'Alamat Kantor Pusat' }}</label>
                            <textarea wire:model.live="settings.company_address" rows="4" class="w-full px-6 py-5 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm"></textarea>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Finance Tab -->
                @if($activeTab === 'finance')
                <div x-data x-init="lucide.createIcons()" x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i data-lucide="wallet" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Financial Governance' : 'Tata Kelola Keuangan' }}</h2>
                            <p class="text-xs text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Currency, tax, and billing configurations.' : 'Konfigurasi mata uang, pajak, dan penagihan.' }}</p>
                        </div>
                    </div>

                     <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Currency Symbol' : 'Simbol Mata Uang' }}</label>
                                <input type="text" wire:model.live="settings.currency_symbol" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Invoice Starting Number' : 'Nomor Faktur Mulai' }}</label>
                                <input type="number" wire:model.live="settings.invoice_start_number" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">PPN Default (%)</label>
                                <input type="number" step="0.01" wire:model.live="settings.ppn_percent" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm" placeholder="0">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">PPh Default (%)</label>
                                <input type="number" step="0.01" wire:model.live="settings.pph_percent" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm" placeholder="0">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Payment Terms' : 'Syarat Pembayaran' }}</label>
                                <div class="px-5 py-4 bg-slate-100 border-transparent rounded-2xl font-bold text-slate-500 text-sm">
                                    {{ app()->getLocale() == 'en' ? 'Managed per-invoice level' : 'Dikelola langsung pada masing-masing invoice' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Localization Tab -->
                @if($activeTab === 'localization')
                <div x-data x-init="lucide.createIcons()" x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                            <i data-lucide="globe" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Regional Settings' : 'Pengaturan Regional' }}</h2>
                            <p class="text-xs text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Manage localization and time telemetry.' : 'Kelola lokalisasi dan telemetri waktu.' }}</p>
                        </div>
                    </div>

                     <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Temporal Zone (Timezone)' : 'Zona Waktu' }}</label>
                                <select wire:model.live="settings.timezone" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm">
                                    <option value="Asia/Jakarta">Jakarta (GMT+7)</option>
                                    <option value="UTC">Universal Coordinated Time (UTC)</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Date Expression Format' : 'Format Tampilan Tanggal' }}</label>
                                <select wire:model.live="settings.date_format" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-bold text-slate-900 text-sm">
                                    <option value="d M Y">12 May 2026</option>
                                    <option value="Y-m-d">2026-05-12</option>
                                    <option value="d/m/Y">12/05/2026</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Primary Language' : 'Bahasa Utama' }}</label>
                                <div class="flex items-center gap-3">
                                    <button class="flex-1 py-4 rounded-2xl border-2 transition-all font-black uppercase text-[11px] tracking-widest {{ ($settings['language'] ?? 'id') === 'id' ? 'border-gold-500 bg-gold-50 text-gold-700' : 'border-transparent bg-slate-50 text-slate-400' }}" wire:click="$set('settings.language', 'id')">Bahasa Indonesia</button>
                                    <button class="flex-1 py-4 rounded-2xl border-2 transition-all font-black uppercase text-[11px] tracking-widest {{ ($settings['language'] ?? 'id') === 'en' ? 'border-gold-500 bg-gold-50 text-gold-700' : 'border-transparent bg-slate-50 text-slate-400' }}" wire:click="$set('settings.language', 'en')">English (Global)</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notifications Tab -->
                @if($activeTab === 'notifications')
                <div x-data x-init="lucide.createIcons()" x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-gold-50 flex items-center justify-center text-gold-600">
                            <i data-lucide="mail" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Communications Hub' : 'Pusat Komunikasi' }}</h2>
                            <p class="text-xs text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Configure SMTP and email delivery templates.' : 'Konfigurasi SMTP dan templat pengiriman email.' }}</p>
                        </div>
                    </div>

                     <div class="py-12 text-center space-y-6">
                        <div class="w-20 h-20 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto shadow-inner">
                            <i data-lucide="shield-alert" class="w-10 h-10"></i>
                        </div>
                        <div class="max-w-md mx-auto space-y-2">
                            <h3 class="text-lg font-bold text-slate-900 font-outfit">{{ app()->getLocale() == 'en' ? 'Feature Disabled' : 'Fitur Belum Diaktifkan' }}</h3>
                            <p class="text-sm text-slate-500 leading-relaxed font-semibold">
                                {{ app()->getLocale() == 'en' ? 'Communications Hub is currently deactivated.' : 'Fitur Pusat Komunikasi saat ini belum diaktifkan.' }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Security Tab -->
                @if($activeTab === 'security')
                <div x-data x-init="lucide.createIcons()" x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-900">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Security & Logs' : 'Keamanan & Log' }}</h2>
                            <p class="text-xs text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Monitor system activity and access telemetry.' : 'Pantau aktivitas sistem dan telemetri akses.' }}</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Recent Activity Pulse' : 'Aktivitas Terbaru' }}</h4>
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[9px] font-black rounded uppercase">{{ app()->getLocale() == 'en' ? 'Encrypted' : 'Terenkripsi' }}</span>
                            </div>
                            <div class="space-y-4">
                                @forelse($logs as $log)
                                @php
                                    $icon = match($log->action) {
                                        'login' => 'key',
                                        'created_invoice', 'updated_invoice', 'deleted_invoice' => 'file-text',
                                        'updated_settings' => 'settings',
                                        'system_backup' => 'database',
                                        default => 'activity'
                                    };
                                    $bgClass = match($log->action) {
                                        'login' => 'bg-gold-50 text-gold-600',
                                        'created_invoice', 'updated_invoice', 'deleted_invoice' => 'bg-blue-50 text-blue-600',
                                        'updated_settings' => 'bg-indigo-50 text-indigo-600',
                                        'system_backup' => 'bg-emerald-50 text-emerald-600',
                                        default => 'bg-slate-50 text-slate-600'
                                    };
                                @endphp
                                <div class="flex items-center justify-between py-2 border-b border-slate-200/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg {{ $bgClass }} flex items-center justify-center shrink-0">
                                            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <span class="text-[12px] font-bold text-slate-700 block text-left">{{ $log->description }}</span>
                                            <span class="text-[9px] text-slate-400 font-medium block text-left">By: {{ optional($log->user)->name ?? 'System' }}</span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-bold shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                @empty
                                <div class="text-center py-6 text-xs text-slate-400 font-bold">
                                    {{ app()->getLocale() == 'en' ? 'No recent activities recorded.' : 'Tidak ada aktivitas terbaru yang tercatat.' }}
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Telemetry Box -->
    <div class="md:hidden mt-8 glass-card p-6 bg-slate-900 text-white overflow-hidden relative">
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-gold-500/10 blur-3xl rounded-full"></div>
        <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4 relative z-10">{{ app()->getLocale() == 'en' ? 'Telemetry' : 'Telemetri' }}</h3>
        <div class="space-y-4 relative z-10">
            <div>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Last Backup' : 'Pencadangan Terakhir' }}</p>
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[13px] font-bold text-gold-400">{{ $lastBackup }}</p>
                    <button wire:click="runBackup" wire:loading.attr="disabled" class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 bg-gold-500 text-slate-950 rounded-lg hover:bg-gold-600 transition-all active:scale-95 flex items-center gap-1">
                        <span wire:loading.remove wire:target="runBackup">{{ app()->getLocale() == 'en' ? 'Backup' : 'Cadangkan' }}</span>
                        <span wire:loading wire:target="runBackup">{{ app()->getLocale() == 'en' ? 'Backing up...' : 'Mencadangkan...' }}</span>
                    </button>
                </div>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Server Health' : 'Kesehatan Server' }}</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[13px] font-bold text-emerald-400">{{ $serverStatus }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Bar -->
    @if($isDirty)
    <div 
        x-transition:enter="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="fixed bottom-10 left-1/2 -translate-x-1/2 w-[90%] max-w-2xl bg-slate-900/90 backdrop-blur-xl border border-white/10 rounded-3xl p-5 shadow-2xl flex items-center justify-between z-50 transition-all duration-500"
    >
        <div class="flex items-center gap-4 px-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-500">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-sm font-black text-white uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Unsaved Telemetry Changes' : 'Perubahan Telemetri Belum Disimpan' }}</p>
                <p class="text-[11px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'You have modified system configurations.' : 'Anda telah mengubah konfigurasi sistem.' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="discard" class="px-6 py-3 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Discard' : 'Batalkan' }}</button>
            <button wire:click="save" class="px-8 py-3 bg-gold-500 hover:bg-gold-600 text-slate-950 font-bold rounded-xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-gold-500/20">{{ app()->getLocale() == 'en' ? 'Apply Changes' : 'Terapkan Perubahan' }}</button>
        </div>
    </div>
    @endif
</div>
