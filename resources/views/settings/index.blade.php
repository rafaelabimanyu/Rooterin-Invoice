<x-app-layout>
    <div class="mb-10">
        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            <span>Administration</span>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-indigo-600">Global Configuration</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">System Settings</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Manage your company profile, billing defaults, and system preferences.</p>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        @csrf
        <!-- Left Side -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card p-10">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-8 pb-4 border-b border-slate-50 dark:border-slate-800">1. Company Identity</h3>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Business Name</label>
                            <input type="text" name="company_name" value="{{ $settings['company_name'] ?? 'Rooterin Technical Services' }}" class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Contact Email</label>
                            <input type="email" name="company_email" value="{{ $settings['company_email'] ?? 'contact@rooterin.com' }}" class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Operational Address</label>
                        <textarea name="company_address" rows="3" class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">{{ $settings['company_address'] ?? 'Sudirman Central Business District (SCBD), Jakarta Selatan' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="glass-card p-10">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-8 pb-4 border-b border-slate-50 dark:border-slate-800">2. Billing & Tax Defaults</h3>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Default VAT / PPN (%)</label>
                            <input type="number" name="default_tax_percent" value="{{ $settings['default_tax_percent'] ?? '11' }}" class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Invoice Prefix</label>
                            <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'ROOT-INV-' }}" class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Global Terms & Conditions</label>
                        <textarea name="default_terms" rows="4" class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">{{ $settings['default_terms'] ?? 'Payment is due within 7 days. Please remit payment via bank transfer.' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="space-y-8">
            <div class="glass-card p-8 bg-[#0f172a] text-white">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-6">Operations Center</h3>
                <p class="text-sm text-slate-400 mb-8 leading-relaxed">Ensure your company information is accurate as it will be reflected in all issued invoices and quotations.</p>
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[13px] uppercase tracking-widest transition-all shadow-lg shadow-indigo-600/20">
                    Apply Changes
                </button>
            </div>

            <div class="glass-card p-8">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-4">Quick Links</h3>
                <div class="space-y-3">
                    <a href="{{ route('users.index') }}" class="flex items-center justify-between text-[12px] font-bold text-slate-600 hover:text-indigo-600 transition-colors">
                        <span>User Management</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                    <div class="h-px bg-slate-100 dark:bg-slate-800"></div>
                    <a href="#" class="flex items-center justify-between text-[12px] font-bold text-slate-600 hover:text-indigo-600 transition-colors">
                        <span>Database Backup</span>
                        <i data-lucide="database" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
