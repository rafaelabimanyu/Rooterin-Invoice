@php
    $color = $activeSectionData['color'] ?? 'indigo';
    $iconColors = [
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'sky' => 'bg-sky-50 text-sky-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'violet' => 'bg-violet-50 text-violet-600',
        'rose' => 'bg-rose-50 text-rose-600',
        'slate' => 'bg-slate-100 text-slate-600',
        'blue' => 'bg-blue-50 text-blue-600',
    ];
    $iconClass = $iconColors[$color] ?? $iconColors['indigo'];
@endphp

<section class="space-y-8 animate-in fade-in duration-500" x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show" x-transition.opacity.duration.500ms>
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl {{ $iconClass }} flex items-center justify-center shadow-sm">
            <i data-lucide="{{ $activeSectionData['icon'] ?? 'file-text' }}" class="w-6 h-6"></i>
        </div>
        <h2 class="text-3xl font-black text-slate-900 font-outfit">{{ __($activeSectionData['title']) }}</h2>
    </div>
    
    <div class="prose prose-slate max-w-none space-y-6">
        <p class="text-slate-500 leading-relaxed text-lg">{{ __($activeSectionData['content']) }}</p>
        
        @if(isset($activeSectionData['pro_tip']))
        <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100 flex gap-4 mt-6">
            <i data-lucide="lightbulb" class="w-6 h-6 text-amber-600 shrink-0 mt-0.5"></i>
            <p class="text-sm text-amber-900 leading-relaxed">
                <strong class="font-black tracking-wide uppercase text-xs">Pro Tip:</strong><br> 
                {{ __($activeSectionData['pro_tip']) }}
            </p>
        </div>
        @endif

        @if(isset($activeSectionData['sub_sections']) && count($activeSectionData['sub_sections']) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10 pt-6 border-t border-slate-100">
            @foreach($activeSectionData['sub_sections'] as $subKey => $subSec)
            <div id="{{ $subKey }}" class="glass-card p-6 border-l-4 border-l-slate-300 hover:border-l-indigo-500 hover:-translate-y-1 transition-all duration-300 scroll-mt-32 cursor-default group">
                <h5 class="font-bold text-slate-900 text-base mb-3 group-hover:text-indigo-700 transition-colors">{{ __($subSec['title']) }}</h5>
                <p class="text-sm text-slate-600 leading-relaxed">{{ __($subSec['content']) }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
