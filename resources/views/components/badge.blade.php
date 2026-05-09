@props(['status'])

@php
    $status = strtolower($status);
    $config = match ($status) {
        'aktif', 'paid' => [
            'bg' => 'bg-emerald-50 dark:bg-emerald-500/10',
            'text' => 'text-emerald-700 dark:text-emerald-400',
            'dot' => 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]',
            'border' => 'border-emerald-200/50 dark:border-emerald-500/20'
        ],
        'nonaktif', 'cancelled', 'overdue' => [
            'bg' => 'bg-rose-50 dark:bg-rose-500/10',
            'text' => 'text-rose-700 dark:text-rose-400',
            'dot' => 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]',
            'border' => 'border-rose-200/50 dark:border-rose-500/20'
        ],
        'pending', 'dp', 'sent', 'partial' => [
            'bg' => 'bg-amber-50 dark:bg-amber-500/10',
            'text' => 'text-amber-700 dark:text-amber-400',
            'dot' => 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]',
            'border' => 'border-amber-200/50 dark:border-amber-500/20'
        ],
        'draft' => [
            'bg' => 'bg-slate-50 dark:bg-white/5',
            'text' => 'text-slate-600 dark:text-slate-400',
            'dot' => 'bg-slate-400',
            'border' => 'border-slate-200 dark:border-white/10'
        ],
        default => [
            'bg' => 'bg-slate-50 dark:bg-white/5',
            'text' => 'text-slate-600 dark:text-slate-400',
            'dot' => 'bg-slate-400',
            'border' => 'border-slate-200 dark:border-white/10'
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {$config['bg']} {$config['text']} {$config['border']} transition-all duration-300 hover:scale-105"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ strtoupper($status) }}
</span>
