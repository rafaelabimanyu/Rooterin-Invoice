<div class="relative min-h-[600px]">
    <!-- Sudo Mode Overlay -->
    @if(!$isVerified)
    <div class="absolute inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-50/40 backdrop-blur-2xl"></div>
        <div class="relative glass-card p-10 max-w-md w-full border-white/50 shadow-2xl animate-in fade-in zoom-in duration-500">
            <div class="w-16 h-16 rounded-2xl bg-slate-900 flex items-center justify-center text-white mx-auto mb-8 shadow-xl shadow-slate-900/20">
                <i data-lucide="shield-alert" class="w-8 h-8"></i>
            </div>
            <div class="text-center mb-8">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Identity Verification Required</h2>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-2">Entering Secure Command Center</p>
            </div>
            
            <form wire:submit.prevent="verifySudo" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Master Password</label>
                    <div class="relative">
                        <i data-lucide="key" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="password" wire:model="sudoPassword" placeholder="••••••••" class="w-full pl-12 pr-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-slate-900/5 focus:border-slate-900 transition-all font-bold text-slate-900">
                    </div>
                    @error('sudoPassword') <span class="text-[10px] text-rose-500 font-bold uppercase tracking-wide">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-slate-900/20 group">
                    <span class="flex items-center justify-center gap-2">
                        Verify Identity
                        <i data-lucide="chevron-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-100 flex items-center justify-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">AES-256 Encrypted Protocol</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content (Blurred if not verified) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 {{ !$isVerified ? 'blur-md pointer-events-none' : '' }} transition-all duration-700">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-3 space-y-6">
            <div class="glass-card p-6 bg-slate-900 text-white overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/20 blur-3xl rounded-full"></div>
                <div class="flex items-center gap-4 mb-8 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <i data-lucide="fingerprint" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-tight">Global Status</p>
                        @if(auth()->user()->two_factor_confirmed_at)
                            <span class="text-[11px] font-black text-emerald-400 uppercase tracking-tight">System Secured</span>
                        @else
                            <span class="text-[11px] font-black text-rose-400 uppercase tracking-tight">At Risk (Incomplete)</span>
                        @endif
                    </div>
                </div>

                <nav class="space-y-1 relative z-10">
                    @foreach(['sessions' => ['SESSIONS', 'monitor'], 'mfa' => ['PROTECTION', 'shield-check'], 'logs' => ['AUDIT TRAIL', 'scroll-text']] as $tab => $info)
                        <button 
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
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Security Intelligence</h4>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                        <div>
                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-tight">Brute Force Guard</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Active (3 Attempts Max)</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                        <div>
                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-tight">Location Telemetry</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Tracking Enabled</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Workspace -->
        <div class="lg:col-span-9 space-y-8">
            <div class="glass-card p-10 min-h-[600px]">
                <!-- Tab: Sessions -->
                @if($activeTab === 'sessions')
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-100">
                            <div>
                                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Active Transmissions</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Live Device Telemetry</p>
                            </div>
                            <button wire:click="terminateOtherSessions" class="px-5 py-2.5 bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                Terminate Others
                            </button>
                        </div>

                        <div class="space-y-4">
                            @foreach($sessions as $session)
                                <div class="flex items-center justify-between p-6 bg-slate-50/50 rounded-[24px] border border-transparent hover:border-slate-200 hover:bg-white transition-all group">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-indigo-600 transition-colors">
                                            <i data-lucide="{{ $session['device_type'] }}" class="w-6 h-6"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <h4 class="text-sm font-black text-slate-900">{{ $session['browser'] }} on {{ $session['platform'] }}</h4>
                                                @if($session['is_current_device'])
                                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-600 text-[8px] font-black rounded uppercase tracking-widest">Active Now</span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-500 font-bold mt-1">
                                                IP: <span class="text-slate-900">{{ $session['ip_address'] }}</span> 
                                                <span class="mx-2 text-slate-300">•</span> 
                                                Last Activity: {{ $session['last_active'] }}
                                            </p>
                                        </div>
                                    </div>
                                    @if(!$session['is_current_device'])
                                        <button wire:click="terminateSession('{{ $session['id'] }}')" class="p-3 text-slate-400 hover:text-rose-500 transition-colors">
                                            <i data-lucide="log-out" class="w-5 h-5"></i>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tab: MFA -->
                @if($activeTab === 'mfa')
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-100">
                            <div>
                                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Multi-Factor Gateway</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Authenticator App Integration</p>
                            </div>
                            <x-badge :status="auth()->user()->two_factor_confirmed_at ? 'aktif' : 'nonaktif'" />
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            <div class="space-y-8">
                                <div class="glass-card p-8 bg-indigo-50/50 border-indigo-100">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shrink-0">
                                            <i data-lucide="smartphone" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-slate-900 mb-2">Authenticator Protocol</h4>
                                            <p class="text-xs text-slate-500 leading-relaxed font-bold">Secure your account using time-based one-time passwords (TOTP) from apps like Google Authenticator or Authy.</p>
                                        </div>
                                    </div>
                                    <div class="mt-8">
                                        @if(auth()->user()->two_factor_confirmed_at)
                                            <button wire:click="disable2FA" class="w-full py-4 bg-white border-2 border-rose-100 text-rose-500 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">Disable Protection</button>
                                        @else
                                            <button wire:click="initiate2FA" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-xl shadow-slate-900/20">Begin Activation</button>
                                        @endif
                                    </div>
                                </div>

                                @if($recoveryCodes)
                                    <div class="glass-card p-8 border-amber-200 bg-amber-50/30">
                                        <h4 class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-4">Emergency Recovery Codes</h4>
                                        <div class="grid grid-cols-2 gap-2 font-mono text-[10px] font-black text-slate-900 mb-6">
                                            @foreach($recoveryCodes as $code)
                                                <div class="p-2 bg-white rounded-lg border border-amber-100">{{ $code }}</div>
                                            @endforeach
                                        </div>
                                        <p class="text-[9px] text-amber-600 font-bold leading-tight">CRITICAL: Save these codes in a secure vault. They are the only way to recover your account if you lose access to your device.</p>
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
                                            <p class="text-xs text-slate-500 font-bold text-center">Scan the QR code above or enter the key manually: <br><span class="text-indigo-600 font-black text-sm tracking-widest uppercase">{{ $twoFactorSecret }}</span></p>
                                            
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Verification Pulse</label>
                                                <input type="text" wire:model="twoFactorCode" placeholder="Enter 6-digit code" class="w-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-black text-slate-900 tracking-[0.5em] text-center">
                                                @error('twoFactorCode') <span class="text-[10px] text-rose-500 font-bold uppercase tracking-wide">{{ $message }}</span> @enderror
                                            </div>
                                            <button wire:click="confirm2FA" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-600/20">Finalize Encryption</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tab: Logs -->
                @if($activeTab === 'logs')
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-100">
                            <div>
                                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Security Intelligence Audit</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Immutable Activity Logging</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            @foreach($auditLogs as $log)
                                <div class="flex items-center justify-between p-5 rounded-2xl {{ $log->is_suspicious ? 'bg-rose-50 border border-rose-100' : 'bg-slate-50 hover:bg-white border border-transparent hover:border-slate-100' }} transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl {{ $log->is_suspicious ? 'bg-rose-100 text-rose-600' : 'bg-white text-slate-400' }} flex items-center justify-center">
                                            <i data-lucide="{{ $log->is_suspicious ? 'alert-triangle' : 'shield' }}" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-900 uppercase tracking-tight">{{ $log->activity }}</p>
                                            <p class="text-[10px] text-slate-500 font-bold mt-0.5">IP: {{ $log->ip_address }} • {{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    @if($log->is_suspicious)
                                        <span class="px-2.5 py-1 bg-rose-200 text-rose-700 text-[8px] font-black rounded-lg uppercase tracking-widest animate-pulse">Critical Event</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
