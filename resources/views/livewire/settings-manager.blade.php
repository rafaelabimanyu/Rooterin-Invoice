<div class="relative pb-24">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1 space-y-2">
            <nav class="flex flex-col space-y-1">
                @foreach(['general' => 'Identity', 'finance' => 'Finance', 'localization' => 'Regional', 'notifications' => 'Communications', 'appearance' => 'Branding', 'security' => 'Security'] as $tab => $label)
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
                                'appearance' => 'palette',
                                'security' => 'shield-check',
                            }
                        }}" class="w-5 h-5"></i>
                        {{ $label }}
                    </button>
                @endforeach
            </nav>

            <div class="mt-8 glass-card p-6 bg-slate-900 text-white overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/10 blur-3xl rounded-full"></div>
                <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4 relative z-10">Telemetry</h3>
                <div class="space-y-4 relative z-10">
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Last Backup</p>
                        <p class="text-[13px] font-bold text-indigo-400">{{ $lastBackup }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Server Health</p>
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
            <div class="glass-card p-10 min-h-[600px]">
                <!-- General Tab -->
                @if($activeTab === 'general')
                <div x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Business Identity</h2>
                            <p class="text-xs text-slate-500 font-medium">Global identification for your enterprise.</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Enterprise Legal Name</label>
                                <div class="relative">
                                    <i data-lucide="building" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                    <input type="text" wire:model.live="settings.company_name" class="w-full pl-12 pr-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Primary Contact Email</label>
                                <div class="relative">
                                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                    <input type="email" wire:model.live="settings.company_email" class="w-full pl-12 pr-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Headquarters Address</label>
                            <textarea wire:model.live="settings.company_address" rows="4" class="w-full px-6 py-5 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm"></textarea>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Finance Tab -->
                @if($activeTab === 'finance')
                <div x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i data-lucide="wallet" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Financial Governance</h2>
                            <p class="text-xs text-slate-500 font-medium">Currency, tax, and billing configurations.</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Currency Symbol</label>
                                <input type="text" wire:model.live="settings.currency_symbol" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Default VAT / PPN (%)</label>
                                <input type="number" wire:model.live="settings.default_tax_percent" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Invoice Sequence Prefix</label>
                                <input type="text" wire:model.live="settings.invoice_prefix" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Payment Terms (Days)</label>
                                <select wire:model.live="settings.payment_terms_days" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                                    <option value="7">Net 7</option>
                                    <option value="15">Net 15</option>
                                    <option value="30">Net 30</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Appearance Tab -->
                @if($activeTab === 'appearance')
                <div x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                            <i data-lucide="palette" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Branding & Visuals</h2>
                            <p class="text-xs text-slate-500 font-medium">Customize the look of your documents.</p>
                        </div>
                    </div>

                    <div class="space-y-10">
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Invoice PDF Logo</label>
                            <div class="flex items-start gap-8">
                                <div class="w-32 h-32 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden group relative">
                                    @if ($companyLogo)
                                        <img src="{{ $companyLogo->temporaryUrl() }}" class="w-full h-full object-contain">
                                    @elseif(isset($settings['invoice_logo']))
                                        <img src="{{ Storage::url($settings['invoice_logo']) }}" class="w-full h-full object-contain">
                                    @else
                                        <i data-lucide="image" class="w-8 h-8 text-slate-300"></i>
                                    @endif
                                </div>
                                <div class="flex-1 space-y-4">
                                    <p class="text-xs text-slate-500 leading-relaxed">Upload a high-resolution transparent PNG or SVG for your invoices. Recommended size: 400x120px.</p>
                                    <input type="file" wire:model="companyLogo" class="hidden" id="logo-upload">
                                    <label for="logo-upload" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[11px] font-black uppercase tracking-widest cursor-pointer hover:bg-indigo-600 transition-all">
                                        <i data-lucide="upload" class="w-4 h-4"></i>
                                        Choose File
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-slate-100">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-4">Primary Brand Color</label>
                            <div class="flex items-center gap-4">
                                <input type="color" wire:model.live="settings.primary_color" class="w-16 h-16 rounded-xl border-none p-1 bg-white shadow-sm cursor-pointer">
                                <input type="text" wire:model.live="settings.primary_color" class="px-5 py-3 bg-slate-50 border-transparent rounded-xl font-mono font-bold text-slate-700">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Localization Tab -->
                @if($activeTab === 'localization')
                <div x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                            <i data-lucide="globe" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Regional Settings</h2>
                            <p class="text-xs text-slate-500 font-medium">Manage localization and time telemetry.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Temporal Zone (Timezone)</label>
                                <select wire:model.live="settings.timezone" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                                    <option value="Asia/Jakarta">Jakarta (GMT+7)</option>
                                    <option value="UTC">Universal Coordinated Time (UTC)</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Date Expression Format</label>
                                <select wire:model.live="settings.date_format" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                                    <option value="d M Y">12 May 2026</option>
                                    <option value="Y-m-d">2026-05-12</option>
                                    <option value="d/m/Y">12/05/2026</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Primary Language</label>
                                <div class="flex items-center gap-3">
                                    <button class="flex-1 py-4 rounded-2xl border-2 transition-all font-black uppercase text-[11px] tracking-widest {{ ($settings['language'] ?? 'id') === 'id' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent bg-slate-50 text-slate-400' }}" wire:click="$set('settings.language', 'id')">Bahasa Indonesia</button>
                                    <button class="flex-1 py-4 rounded-2xl border-2 transition-all font-black uppercase text-[11px] tracking-widest {{ ($settings['language'] ?? 'id') === 'en' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent bg-slate-50 text-slate-400' }}" wire:click="$set('settings.language', 'en')">English (Global)</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notifications Tab -->
                @if($activeTab === 'notifications')
                <div x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                            <i data-lucide="mail" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Communications Hub</h2>
                            <p class="text-xs text-slate-500 font-medium">Configure SMTP and email delivery templates.</p>
                        </div>
                    </div>

                    <div class="space-y-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">SMTP Gateway Host</label>
                                <input type="text" wire:model.live="settings.smtp_host" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">SMTP Port</label>
                                <input type="text" wire:model.live="settings.smtp_port" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                            </div>
                        </div>
                        
                        <div class="space-y-6 pt-8 border-t border-slate-100">
                            <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Automated Intelligence Templates</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Email Opening Salutation</label>
                                    <textarea wire:model.live="settings.email_template_header" rows="3" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm"></textarea>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Email Closure Signature</label>
                                    <textarea wire:model.live="settings.email_template_footer" rows="3" class="w-full px-5 py-4 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Security Tab -->
                @if($activeTab === 'security')
                <div x-transition:enter="fade-in">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-900">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Security & Logs</h2>
                            <p class="text-xs text-slate-500 font-medium">Monitor system activity and access telemetry.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Recent Activity Pulse</h4>
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[9px] font-black rounded uppercase">Encrypted</span>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2 border-b border-slate-200/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                            <i data-lucide="key" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-[12px] font-bold text-slate-700">Owner Login Detected</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-bold">12 mins ago</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-slate-200/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                            <i data-lucide="database" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-[12px] font-bold text-slate-700">Automated SQL Backup</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-bold">2 hours ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
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
                <p class="text-sm font-black text-white uppercase tracking-tight">Unsaved Telemetry Changes</p>
                <p class="text-[11px] text-slate-400 font-medium">You have modified system configurations.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="discard" class="px-6 py-3 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-colors">Discard</button>
            <button wire:click="save" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-indigo-600/20">Apply Changes</button>
        </div>
    </div>
    @endif
</div>
