@props(['title', 'value', 'change', 'icon', 'color'])

@php
    $colorClasses = [
        'indigo' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400',
        'amber' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400',
        'emerald' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
        'rose' => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400',
    ];
    $iconColor = $colorClasses[$color] ?? $colorClasses['indigo'];
@endphp

<div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $iconColor }}">
            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
        </div>
        <div class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ str_contains($change, '+') ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
            {{ $change }}
        </div>
    </div>
    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">{{ $title }}</p>
    <h3 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit tracking-tight">{{ $value }}</h3>
</div>
