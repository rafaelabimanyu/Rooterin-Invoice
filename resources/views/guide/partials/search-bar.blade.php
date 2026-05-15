<div class="relative w-full max-w-md mb-10 group">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i data-lucide="search" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
    </div>
    <input type="text" 
           class="block w-full pl-10 pr-16 py-2.5 border border-slate-200 rounded-xl leading-5 bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white sm:text-sm transition-all shadow-sm hover:bg-white" 
           placeholder="{{ __('Cari di ' . strtolower($guideData['header']['title']) . '...') }}">
    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <span class="text-[10px] text-slate-400 font-mono bg-white px-2 py-0.5 rounded border border-slate-200 shadow-sm">Ctrl+K</span>
    </div>
</div>
