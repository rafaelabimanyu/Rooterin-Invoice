<div class="relative min-h-[600px]">
    <!-- Sudo Mode Overlay -->
    @if(!$isVerified)
    <div class="absolute inset-0 z-[60] flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-50/40 backdrop-blur-2xl"></div>
        <div class="relative glass-card p-10 max-w-md w-full border-white/50 shadow-2xl animate-in fade-in zoom-in duration-500">
            <div class="w-16 h-16 rounded-2xl bg-slate-900 flex items-center justify-center text-white mx-auto mb-8 shadow-xl shadow-slate-900/20">
                <i data-lucide="shield-alert" class="w-8 h-8"></i>
            </div>
            <div class="text-center mb-8">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Identity Verification Required' : 'Verifikasi Identitas Diperlukan' }}</h2>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-2">{{ app()->getLocale() == 'en' ? 'Entering Secure Command Center' : 'Memasuki Pusat Kontrol Aman' }}</p>
            </div>
            
            <form wire:submit.prevent="verifySudo" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Master Password' : 'Kata Sandi Utama' }}</label>
                    <div class="relative">
                        <i data-lucide="key" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="password" id="sudoPassword" name="sudoPassword" wire:model="sudoPassword" placeholder="••••••••" class="w-full pl-12 pr-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-slate-900/5 focus:border-slate-900 transition-all font-bold text-slate-900">
                    </div>
                    @error('sudoPassword') <span class="text-[10px] text-rose-500 font-bold uppercase tracking-wide">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-slate-900/20 group">
                    <span class="flex items-center justify-center gap-2">
                        {{ app()->getLocale() == 'en' ? 'Verify Identity' : 'Verifikasi Identitas' }}
                        <i data-lucide="chevron-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-100 flex items-center justify-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'AES-256 Encrypted Protocol' : 'Protokol Terenkripsi AES-256' }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Termination Confirmation Modal -->
    <div 
        x-show="$wire.confirmingTermination" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6"
        x-cloak
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="$wire.confirmingTermination = false"></div>
        <div class="relative bg-white w-full max-w-lg rounded-[40px] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <div class="p-10 text-center">
                <div class="w-20 h-20 bg-rose-50 text-rose-600 rounded-3xl flex items-center justify-center mx-auto mb-8">
                    <i data-lucide="alert-triangle" class="w-10 h-10"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-4">{{ app()->getLocale() == 'en' ? 'Security Purge Protocol' : 'Protokol Pembersihan Keamanan' }}</h3>
                <p class="text-slate-500 font-medium leading-relaxed">{{ app()->getLocale() == 'en' ? 'This action will immediately invalidate all other active sessions across all devices. This operation is irreversible. Proceed with caution.' : 'Tindakan ini akan segera membatalkan semua sesi aktif lainnya di semua perangkat. Operasi ini tidak dapat dibatalkan. Lanjutkan dengan hati-hati.' }}</p>
            </div>
            <div class="p-8 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-4">
                <button @click="$wire.confirmingTermination = false" class="flex-1 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">{{ app()->getLocale() == 'en' ? 'Abort Mission' : 'Batalkan Protokol' }}</button>
                <button wire:click="terminateOtherSessions" class="flex-1 py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-rose-600/20">{{ app()->getLocale() == 'en' ? 'Purge Sessions' : 'Bersihkan Sesi' }}</button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 {{ !$isVerified ? 'blur-md pointer-events-none' : '' }} transition-all duration-700">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-3 space-y-6">
            <div class="glass-card p-6 bg-slate-900 text-white overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-gold-500/20 blur-3xl rounded-full"></div>
                <div class="flex items-center gap-4 mb-8 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <i data-lucide="fingerprint" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-tight">{{ app()->getLocale() == 'en' ? 'Global Status' : 'Status Global' }}</p>
                        @if(auth()->user()->two_factor_confirmed_at)
                            <span class="text-[11px] font-black text-emerald-400 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'System Secured' : 'Sistem Aman' }}</span>
                        @else
                            <span class="text-[11px] font-black text-rose-400 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'At Risk (Incomplete)' : 'Berisiko (Belum Lengkap)' }}</span>
                        @endif
                    </div>
                </div>

                <nav class="space-y-1 relative z-10">
                    @foreach(['sessions' => [app()->getLocale() == 'en' ? 'SESSIONS' : 'SESI AKTIF', 'monitor'], 'mfa' => [app()->getLocale() == 'en' ? 'PROTECTION' : 'PERLINDUNGAN', 'shield-check'], 'logs' => [app()->getLocale() == 'en' ? 'AUDIT TRAIL' : 'JEJAK AUDIT', 'scroll-text']] as $tab => $info)
                        <button 
                            wire:key="tab-{{ $tab }}"
                            wire:click="$set('activeTab', '{{ $tab }}')"
                            class="flex items-center gap-3 w-full px-4 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all
                            {{ $activeTab === $tab ? 'bg-white text-slate-900 shadow-xl shadow-black/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                        >
                            <i data-lucide="{{ $info[1] }}" class="w-4 h-4"></i>
                            {{ $info[0] }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <div class="glass-card p-6">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">{{ app()->getLocale() == 'en' ? 'Security Intelligence' : 'Kecerdasan Keamanan' }}</h4>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-1.5 h-1.5 rounded-full bg-gold-500"></div>
                        <div>
                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Brute Force Guard' : 'Perlindungan Brute Force' }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Active (3 Attempts Max)' : 'Aktif (Maks 3 Percobaan)' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                        <div>
                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Location Telemetry' : 'Telemetri Lokasi' }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Tracking Enabled' : 'Pelacakan Aktif' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Workspace -->
        <div class="lg:col-span-9 space-y-8">
            <div class="glass-card p-10 min-h-[600px] relative overflow-hidden">
                <!-- Skeleton Loader -->
                <div wire:loading class="absolute inset-0 z-50 bg-white/60 backdrop-blur-[2px]">
                    <div class="p-10 space-y-8 animate-pulse">
                        <div class="flex items-center justify-between">
                            <div class="h-8 w-48 bg-slate-100 rounded-xl"></div>
                            <div class="h-10 w-32 bg-slate-100 rounded-xl"></div>
                        </div>
                        <div class="space-y-4">
                            @for($i = 0; $i < 3; $i++)
                                <div class="h-24 bg-slate-50 rounded-[24px]"></div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Tab: Sessions -->
                @if($activeTab === 'sessions')
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-500" wire:key="content-sessions">
                        <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-100">
                            <div>
                                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Active Transmissions' : 'Transmisi Aktif' }}</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Live Device Telemetry' : 'Telemetri Perangkat Langsung' }}</p>
                            </div>
                            @if(count($sessions) > 1)
                                <button wire:click="confirmTerminateOthers" class="px-5 py-2.5 bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all shadow-sm font-jakarta">
                                    {{ app()->getLocale() == 'en' ? 'Terminate Others' : 'Hentikan Sesi Lain' }}
                                </button>
                            @endif
                        </div>

                        <div>
                            @forelse($sessions as $session)
                                <div wire:key="session-{{ $session['id'] }}" class="flex items-center justify-between p-6 bg-slate-50/50 rounded-[24px] border border-transparent hover:border-slate-200 hover:bg-white transition-all group">
                                    <div class="flex items-center gap-5">
                                        <!-- Multi-Icon Indicator -->
                                        <div class="relative shrink-0">
                                            <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-gold-600 transition-colors">
                                                <i data-lucide="{{ $session['platform_icon'] }}" class="w-6 h-6"></i>
                                            </div>
                                            <div class="absolute -right-1 -bottom-1 w-6 h-6 rounded-lg bg-gold-500 text-slate-950 flex items-center justify-center shadow-lg ring-2 ring-white font-bold">
                                                <i data-lucide="{{ $session['browser_icon'] }}" class="w-3.5 h-3.5"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <h4 class="text-sm font-black text-slate-900 tracking-tight">{{ $session['browser'] }} {{ app()->getLocale() == 'en' ? 'on' : 'di' }} {{ $session['platform'] }}</h4>
                                                @if($session['is_current_device'])
                                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-600 text-[8px] font-black rounded uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Your Device' : 'Perangkat Anda' }}</span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-500 font-bold mt-1 uppercase tracking-widest">
                                                IP: <span class="text-slate-900">{{ $session['ip_address'] }}</span> 
                                                <span class="mx-2 text-slate-300">•</span> 
                                                {{ app()->getLocale() == 'en' ? 'Transmission' : 'Transmisi' }}: {{ $session['last_active'] }}
                                            </p>
                                        </div>
                                    </div>
                                    @if(!$session['is_current_device'])
                                        <button wire:click="terminateSession('{{ $session['id'] }}')" class="p-3 text-slate-400 hover:text-rose-500 transition-colors">
                                            <i data-lucide="log-out" class="w-5 h-5"></i>
                                        </button>
                                    @endif
                                </div>
                            @empty
                                <div class="py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-[32px] flex items-center justify-center mx-auto mb-6 text-slate-200">
                                        <i data-lucide="shield-check" class="w-10 h-10"></i>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'No other active transmissions' : 'Tidak ada transmisi aktif lainnya' }}</h4>
                                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-2">{{ app()->getLocale() == 'en' ? 'Your current device is the only one connected.' : 'Perangkat Anda saat ini adalah satu-satunya yang terhubung.' }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- Tab: MFA -->
                @if($activeTab === 'mfa')
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-500" wire:key="content-mfa">
                        <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-100">
                            <div>
                                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Multi-Factor Gateway' : 'Gerbang Multi-Faktor' }}</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Authenticator App Integration' : 'Integrasi Aplikasi Autentikator' }}</p>
                            </div>
                            <x-badge :status="auth()->user()->two_factor_confirmed_at ? 'aktif' : 'nonaktif'" />
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            <div class="space-y-8">
                                <div class="glass-card p-8 bg-gold-50/50 border-gold-100">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-gold-500 flex items-center justify-center text-slate-950 shrink-0 font-bold">
                                            <i data-lucide="smartphone" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-slate-900 mb-2">{{ app()->getLocale() == 'en' ? 'Authenticator Protocol' : 'Protokol Autentikator' }}</h4>
                                            <p class="text-xs text-slate-500 leading-relaxed font-bold">{{ app()->getLocale() == 'en' ? 'Secure your account using time-based one-time passwords (TOTP) from apps like Google Authenticator or Authy.' : 'Amankan akun Anda menggunakan kata sandi sekali pakai berbasis waktu (TOTP) dari aplikasi seperti Google Authenticator atau Authy.' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-8">
                                        @if(auth()->user()->two_factor_confirmed_at)
                                            <button wire:click="disable2FA" class="w-full py-4 bg-white border-2 border-rose-100 text-rose-500 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">{{ app()->getLocale() == 'en' ? 'Disable Protection' : 'Nonaktifkan Perlindungan' }}</button>
                                        @else
                                            <button wire:click="initiate2FA" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-gold-600 hover:text-slate-950 transition-all shadow-xl shadow-slate-900/20">{{ app()->getLocale() == 'en' ? 'Begin Activation' : 'Mulai Aktivasi' }}</button>
                                        @endif
                                    </div>
                                </div>
                                @if($recoveryCodes)
                                    <div class="glass-card p-8 border-amber-200 bg-amber-50/30">
                                        <h4 class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-4">{{ app()->getLocale() == 'en' ? 'Emergency Recovery Codes' : 'Kode Pemulihan Darurat' }}</h4>
                                        <div class="grid grid-cols-2 gap-2 font-mono text-[10px] font-black text-slate-900 mb-6">
                                            @foreach($recoveryCodes as $code)
                                                <div wire:key="recovery-{{ $loop->index }}" class="p-2 bg-white rounded-lg border border-amber-100">{{ $code }}</div>
                                            @endforeach
                                        </div>
                                        <p class="text-[9px] text-amber-600 font-bold leading-tight">{{ app()->getLocale() == 'en' ? 'CRITICAL: Save these codes in a secure vault. They are the only way to recover your account if you lose access to your device.' : 'KRITIS: Simpan kode ini di brankas yang aman. Ini adalah satu-satunya cara untuk memulihkan akun Anda jika Anda kehilangan akses ke perangkat Anda.' }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-8">
                                @if($show2FASetup)
                                    <div class="space-y-8 animate-in zoom-in duration-500">
                                        <div class="flex items-center justify-center bg-white p-6 rounded-[32px] shadow-inner border-slate-100 border">
                                            {!! $qrCodeSvg !!}
                                        </div>
                                        <div class="space-y-4">
                                            <p class="text-xs text-slate-500 font-bold text-center">{{ app()->getLocale() == 'en' ? 'Scan the QR code above or enter the key manually:' : 'Pindai kode QR di atas atau masukkan kunci secara manual:' }} <br><span class="text-gold-600 font-black text-sm tracking-widest uppercase">{{ $twoFactorSecret }}</span></p>
                                            
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Verification Pulse' : 'Pulsa Verifikasi' }}</label>
                                                <input type="text" id="twoFactorCode" name="twoFactorCode" wire:model="twoFactorCode" placeholder="{{ app()->getLocale() == 'en' ? 'Enter 6-digit code' : 'Masukkan kode 6 digit' }}" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-gold-500/5 focus:border-gold-500 transition-all font-black text-slate-900 tracking-[0.5em] text-center">
                                                @error('twoFactorCode') <span class="text-[10px] text-rose-500 font-bold uppercase tracking-wide">{{ $message }}</span> @enderror
                                            </div>
                                            <button wire:click="confirm2FA" class="w-full py-4 bg-gold-500 text-slate-950 font-bold rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-gold-600 transition-all shadow-xl shadow-gold-500/20">{{ app()->getLocale() == 'en' ? 'Finalize Encryption' : 'Selesaikan Enkripsi' }}</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tab: Logs -->
                @if($activeTab === 'logs')
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-500" wire:key="content-logs">
                        <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-100">
                            <div>
                                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Security Intelligence Audit' : 'Audit Intelijen Keamanan' }}</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">{{ app()->getLocale() == 'en' ? 'Immutable Activity Logging' : 'Pencatatan Aktivitas Tidak Berubah' }}</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            @forelse($auditLogs as $log)
                                <div wire:key="log-{{ $log->id }}" class="flex items-center justify-between p-5 rounded-2xl {{ $log->is_suspicious ? 'bg-rose-50 border border-rose-100' : 'bg-slate-50 hover:bg-white border border-transparent hover:border-slate-100' }} transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl {{ $log->is_suspicious ? 'bg-rose-100 text-rose-600' : 'bg-white text-slate-400' }} flex items-center justify-center">
                                            <i data-lucide="{{ $log->is_suspicious ? 'alert-triangle' : 'shield' }}" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-900 uppercase tracking-tight">
                                                {{ $log->activity }}
                                                @if($log->user && $log->user_id !== auth()->id())
                                                    <span class="ml-2 px-2 py-0.5 rounded bg-slate-250/70 text-[9px] text-slate-600 font-extrabold uppercase tracking-wider">
                                                        {{ $log->user->name }} ({{ $log->user->role }})
                                                    </span>
                                                @endif
                                            </p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <div x-data="{ showIP: false }" class="relative">
                                                    <button @click="showIP = !showIP" class="text-[10px] text-gold-600 font-black uppercase tracking-widest hover:underline font-mono">
                                                        IP: {{ $log->ip_address }}
                                                    </button>
                                                    <!-- IP Intelligence Popover -->
                                                    <div 
                                                        x-show="showIP" 
                                                        @click.away="showIP = false"
                                                        class="absolute bottom-full left-0 mb-2 w-48 p-4 bg-slate-900 text-white rounded-2xl shadow-2xl z-[110] animate-in slide-in-from-top-2 duration-300"
                                                        x-cloak
                                                     >
                                                         <div class="space-y-2">
                                                             <div class="flex items-center justify-between">
                                                                 <span class="text-[9px] text-slate-400 font-black uppercase">City</span>
                                                                 <span class="text-[10px] font-bold">Jakarta</span>
                                                             </div>
                                                             <div class="flex items-center justify-between">
                                                                 <span class="text-[9px] text-slate-400 font-black uppercase">Country</span>
                                                                 <span class="text-[10px] font-bold">Indonesia</span>
                                                             </div>
                                                             <div class="flex items-center justify-between">
                                                                 <span class="text-[9px] text-slate-400 font-black uppercase">Provider</span>
                                                                 <span class="text-[10px] font-bold">PT. Telekomunikasi</span>
                                                             </div>
                                                         </div>
                                                         <div class="absolute bottom-[-6px] left-4 w-3 h-3 bg-slate-900 rotate-45"></div>
                                                     </div>
                                                 </div>
                                                 <span class="text-[10px] text-slate-300">•</span>
                                                 <span class="text-[10px] text-slate-400 font-bold">{{ $log->created_at->diffForHumans() }}</span>
                                             </div>
                                         </div>
                                     </div>
                                     @if($log->is_suspicious)
                                         <span class="px-2.5 py-1 bg-rose-200 text-rose-700 text-[8px] font-black rounded-lg uppercase tracking-widest animate-pulse">{{ app()->getLocale() == 'en' ? 'Critical Event' : 'Peristiwa Kritis' }}</span>
                                     @endif
                                 </div>
                             @empty
                                 <div class="py-20 text-center">
                                     <div class="w-20 h-20 bg-slate-50 rounded-[32px] flex items-center justify-center mx-auto mb-6 text-slate-200">
                                         <i data-lucide="scroll-text" class="w-10 h-10"></i>
                                     </div>
                                     <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Audit trail empty' : 'Jejak audit kosong' }}</h4>
                                     <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-2">{{ app()->getLocale() == 'en' ? 'No security events have been logged yet.' : 'Belum ada peristiwa keamanan yang dicatat.' }}</p>
                                 </div>
                             @endforelse
                         </div>
                     </div>
                 @endif
             </div>
         </div>
     </div>
</div>
