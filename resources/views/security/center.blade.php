<x-app-layout>
    <div class="mb-10">
        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            <span>{{ app()->getLocale() == 'en' ? 'Core Intelligence' : 'Kecerdasan Inti' }}</span>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-indigo-600">{{ app()->getLocale() == 'en' ? 'Security Command Center' : 'Pusat Kontrol Keamanan' }}</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">{{ app()->getLocale() == 'en' ? 'Security Protocol Control' : 'Kontrol Protokol Keamanan' }}</h1>
        <p class="text-sm text-slate-500">{{ app()->getLocale() == 'en' ? 'Manage multi-factor authentication, active transmissions, and security audit telemetry.' : 'Kelola autentikasi multifaktor, transmisi aktif, dan telemetri audit keamanan.' }}</p>
    </div>

    <livewire:security-command-center />
</x-app-layout>
