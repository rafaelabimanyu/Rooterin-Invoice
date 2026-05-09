@props(['href', 'active', 'icon', 'label'])

<a href="{{ $href }}" 
   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white' }}">
    <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
        <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $active ? 'stroke-[2.5px]' : 'stroke-[1.5px]' }}"></i>
    </div>
    <span x-show="!collapsed" class="font-medium text-sm">{{ $label }}</span>
    
    @if($active)
    <div x-show="!collapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400"></div>
    @endif
</a>
