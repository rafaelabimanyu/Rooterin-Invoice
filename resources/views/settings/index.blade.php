<x-app-layout>
    <div class="mb-10">
        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            <span>Administration</span>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-indigo-600">Global Configuration</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">System Settings</h1>
        <p class="text-sm text-slate-500">Manage your company profile, billing defaults, and system preferences.</p>
    </div>

    <livewire:settings-manager />
</x-app-layout>
