@props(['href', 'active', 'icon', 'label'])

<a href="{{ $href }}" 
   class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ $active ? 'bg-indigo-500/10 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
    <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
        <i data-lucide="{{ $icon }}" class="w-[18px] h-[18px] {{ $active ? 'text-indigo-400' : 'group-hover:text-white' }}"></i>
    </div>
    <span x-show="!collapsed" class="font-semibold text-[13px] tracking-tight">{{ $label }}</span>
    
    @if($active)
    <div x-show="!collapsed" class="ml-auto w-1 h-4 rounded-full bg-indigo-500"></div>
    @endif
</a>
