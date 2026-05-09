@props(['status'])

@php
    $status = strtolower($status);
    $config = match ($status) {
        'aktif', 'paid' => [
            'bg' => 'bg-emerald-50/50 dark:bg-emerald-500/10',
            'text' => 'text-emerald-700 dark:text-emerald-400',
            'dot' => 'bg-emerald-500',
            'border' => 'border-emerald-100 dark:border-emerald-500/20'
        ],
        'nonaktif', 'cancelled', 'overdue' => [
            'bg' => 'bg-rose-50/50 dark:bg-rose-500/10',
            'text' => 'text-rose-700 dark:text-rose-400',
            'dot' => 'bg-rose-500',
            'border' => 'border-rose-100 dark:border-rose-500/20'
        ],
        'pending', 'dp', 'sent' => [
            'bg' => 'bg-amber-50/50 dark:bg-amber-500/10',
            'text' => 'text-amber-700 dark:text-amber-400',
            'dot' => 'bg-amber-500',
            'border' => 'border-amber-100 dark:border-amber-500/20'
        ],
        'draft' => [
            'bg' => 'bg-slate-50/50 dark:bg-slate-500/10',
            'text' => 'text-slate-600 dark:text-slate-400',
            'dot' => 'bg-slate-400',
            'border' => 'border-slate-200 dark:border-slate-500/20'
        ],
        default => [
            'bg' => 'bg-slate-50/50',
            'text' => 'text-slate-600',
            'dot' => 'bg-slate-400',
            'border' => 'border-slate-200'
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold border {$config['bg']} {$config['text']} {$config['border']}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ strtoupper($status) }}
</span>
