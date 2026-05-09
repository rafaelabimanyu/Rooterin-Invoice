@props(['status'])

@php
    $status = strtolower($status);
    $classes = match ($status) {
        'aktif', 'paid' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
        'nonaktif', 'cancelled', 'overdue' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border-rose-100 dark:border-rose-500/20',
        'pending', 'dp', 'sent' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border-amber-100 dark:border-amber-500/20',
        'draft' => 'bg-slate-50 text-slate-700 dark:bg-slate-500/10 dark:text-slate-400 border-slate-100 dark:border-slate-500/20',
        default => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border-indigo-100 dark:border-indigo-500/20',
    };
@endphp

<span {{ $attributes->merge(['class' => "px-2.5 py-1 rounded-full text-xs font-bold border $classes"]) }}>
    {{ ucfirst($status) }}
</span>
