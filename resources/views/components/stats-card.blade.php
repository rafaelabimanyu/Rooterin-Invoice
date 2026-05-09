@props(['title', 'value', 'change', 'icon', 'color'])

@php
    $gradients = [
        'indigo' => 'from-indigo-500/10 to-indigo-500/5 text-indigo-600 dark:text-indigo-400 border-indigo-500/10',
        'amber' => 'from-amber-500/10 to-amber-500/5 text-amber-600 dark:text-amber-400 border-amber-500/10',
        'emerald' => 'from-emerald-500/10 to-emerald-500/5 text-emerald-600 dark:text-emerald-400 border-emerald-500/10',
        'rose' => 'from-rose-500/10 to-rose-500/5 text-rose-600 dark:text-rose-400 border-rose-500/10',
    ];
    $grad = $gradients[$color] ?? $gradients['indigo'];
@endphp

<div class="glass-card p-7 group hover:-translate-y-2 hover:shadow-2xl hover:border-indigo-500/20 transition-all duration-500 relative overflow-hidden">
    <!-- Subtle Background Glow -->
    <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/5 blur-3xl group-hover:bg-indigo-500/10 transition-colors duration-500 rounded-full"></div>

    <div class="flex items-center justify-between mb-6 relative z-10">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $grad }} flex items-center justify-center border shadow-sm group-hover:scale-110 transition-transform duration-500">
            <i data-lucide="{{ $icon }}" class="w-7 h-7"></i>
        </div>
        <div class="flex flex-col items-end">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black {{ str_contains($change, '+') ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' }} shadow-sm">
                <i data-lucide="{{ str_contains($change, '+') ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                {{ $change }}
            </span>
        </div>
    </div>
    
    <div class="relative z-10">
        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ $title }}</p>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white font-jakarta tracking-tight group-hover:translate-x-1 transition-transform duration-500">{{ $value }}</h3>
    </div>
    
    <!-- Shimmer Effect -->
    <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
</div>
