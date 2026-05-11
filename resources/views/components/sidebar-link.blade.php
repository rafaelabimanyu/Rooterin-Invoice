@props(['href', 'active', 'icon', 'label', 'collapsed' => false])

<a 
    href="{{ $href }}" 
    {{ $attributes->merge(['class' => 'group flex items-center rounded-xl transition-all duration-300 relative ' . 
        ($active ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900') .
        ($collapsed ? ' justify-center h-11 w-11 mx-auto' : ' gap-3 px-4 py-2.5')]) }}
>
    <!-- Indicator Dot (only for active) -->
    @if($active)
        <span 
            class="absolute bg-indigo-500 rounded-r-full shadow-[0_0_8px_rgba(79,70,229,0.8)] transition-all duration-300"
            :class="collapsed ? 'left-[-12px] w-1 h-6' : 'left-0 w-1 h-4'"
        ></span>
    @endif

    <div class="shrink-0 transition-transform duration-300 group-hover:scale-110 flex items-center justify-center">
        <i data-lucide="{{ $icon }}" class="w-[18px] h-[18px] {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500 transition-colors' }}"></i>
    </div>
    
    <span 
        x-show="!collapsed" 
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0 -translate-x-2" 
        x-transition:enter-end="opacity-100 translate-x-0"
        class="text-[13px] font-bold tracking-tight whitespace-nowrap"
    >
        {{ $label }}
    </span>

    <!-- Tooltip (only when collapsed) -->
    <div 
        x-show="collapsed" 
        class="absolute left-full ml-4 px-3 py-1.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-300 pointer-events-none z-[100] shadow-2xl whitespace-nowrap"
    >
        {{ $label }}
    </div>
</a>
