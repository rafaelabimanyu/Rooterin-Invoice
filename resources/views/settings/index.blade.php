<x-app-layout>
    <div class="mb-10">
        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            <span>{{ app()->getLocale() == 'en' ? 'Administration' : 'Administrasi' }}</span>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-indigo-600">{{ app()->getLocale() == 'en' ? 'Global Configuration' : 'Konfigurasi Global' }}</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">{{ app()->getLocale() == 'en' ? 'System Settings' : 'Pengaturan Sistem' }}</h1>
        <p class="text-sm text-slate-500">{{ app()->getLocale() == 'en' ? 'Manage your company profile, billing defaults, and system preferences.' : 'Kelola profil perusahaan Anda, default penagihan, dan preferensi sistem.' }}</p>
    </div>

    <livewire:settings-manager />
</x-app-layout>
