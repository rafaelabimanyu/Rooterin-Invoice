@php
    $color = $activeSectionData['color'] ?? 'gold';
    $iconColors = [
        'gold' => 'bg-gold-50 text-gold-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'sky' => 'bg-sky-50 text-sky-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'violet' => 'bg-violet-50 text-violet-600',
        'rose' => 'bg-rose-50 text-rose-600',
        'slate' => 'bg-slate-100 text-slate-600',
        'blue' => 'bg-blue-50 text-blue-600',
    ];
    $iconClass = $iconColors[$color] ?? $iconColors['gold'];
@endphp

<section class="space-y-6 lg:space-y-8 animate-in fade-in duration-500 break-words" x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show" x-transition.opacity.duration.500ms>
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl {{ $iconClass }} flex items-center justify-center shadow-sm shrink-0">
            <i data-lucide="{{ $activeSectionData['icon'] ?? 'file-text' }}" class="w-6 h-6"></i>
        </div>
        <h2 class="text-3xl font-black text-slate-900 font-outfit break-words">{{ is_array(__($activeSectionData['title'])) ? $activeSectionData['title'] : __($activeSectionData['title']) }}</h2>
    </div>
    
    <div class="prose prose-slate max-w-none space-y-6 break-words" x-data="{ expanded: window.innerWidth >= 1024 }" @resize.window="if(window.innerWidth >= 1024) expanded = true">
        <p class="text-slate-500 text-base leading-relaxed px-4 lg:px-0 lg:text-lg break-words">{{ is_array(__($activeSectionData['content'])) ? $activeSectionData['content'] : __($activeSectionData['content']) }}</p>
        
        @if(isset($activeSectionData['pro_tip']))
        <div class="bg-amber-50 mx-4 lg:mx-0 p-5 lg:p-6 rounded-2xl border border-amber-100 flex gap-4 mt-6">
            <i data-lucide="lightbulb" class="w-6 h-6 text-amber-600 shrink-0 mt-0.5"></i>
            <p class="text-sm text-amber-900 leading-relaxed">
                <strong class="font-black tracking-wide uppercase text-[10px] lg:text-xs">Pro Tip:</strong><br> 
                {{ is_array(__($activeSectionData['pro_tip'])) ? $activeSectionData['pro_tip'] : __($activeSectionData['pro_tip']) }}
            </p>
        </div>
        @endif

        @if(isset($activeSectionData['sub_sections']) && count($activeSectionData['sub_sections']) > 0)
        <!-- Mobile Expand/Collapse Button -->
        <div class="lg:hidden mt-8 pt-4 border-t border-slate-100 flex justify-center">
            <button @click="expanded = !expanded" class="flex items-center gap-2 px-6 py-2.5 bg-gold-50 text-gold-600 rounded-full text-sm font-bold shadow-sm border border-gold-100 hover:bg-gold-100 transition-colors">
                <span x-text="expanded ? '{{ app()->getLocale() == 'en' ? 'Hide Details' : 'Sembunyikan Detail' }}' : '{{ app()->getLocale() == 'en' ? 'Show Details' : 'Tampilkan Detail' }}'"></span>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
            </button>
        </div>

        <div x-show="expanded" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 -translate-y-4" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mt-6 lg:mt-10 lg:pt-6 lg:border-t border-slate-100 px-4 lg:px-0">
            @foreach($activeSectionData['sub_sections'] as $subKey => $subSec)
            <div id="{{ $subKey }}" 
                 x-data="{ open: window.innerWidth >= 1024 }" 
                 @resize.window="if(window.innerWidth >= 1024) open = true"
                 class="glass-card p-0 overflow-hidden border-l-4 border-l-slate-350 hover:border-l-gold-500 hover:-translate-y-0.5 transition-all duration-300 scroll-mt-32 cursor-pointer lg:cursor-default group"
                 @click="if(window.innerWidth < 1024) open = !open"
            >
                <!-- Accordion Header -->
                <div class="px-4 lg:px-6 py-5 flex items-center justify-between gap-4 select-none">
                    <h5 class="font-bold text-slate-900 text-sm lg:text-base group-hover:text-gold-700 transition-colors">
                        {{ is_array(__($subSec['title'])) ? $subSec['title'] : __($subSec['title']) }}
                    </h5>
                    <!-- Mobile toggle chevron indicator -->
                    <div class="lg:hidden p-1 bg-slate-50 text-slate-400 group-hover:text-gold-600 rounded-lg">
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                    </div>
                </div>
                
                <!-- Accordion Content -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="pb-5 pt-0 border-t border-slate-50 lg:border-t-0"
                >
                    <p class="text-base leading-relaxed text-slate-600 lg:text-sm font-medium px-4 lg:px-6">
                        {{ is_array(__($subSec['content'])) ? $subSec['content'] : __($subSec['content']) }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
