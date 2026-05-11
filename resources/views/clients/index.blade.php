<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span>Business Ecosystem</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600">Client Management</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 font-outfit">Portfolio Registry</h1>
            <p class="text-sm text-slate-500">Manage enterprise clients, contacts, and historical billing telemetry.</p>
        </div>
    </div>

    <livewire:client-manager />
</x-app-layout>
