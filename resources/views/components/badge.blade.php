@props(['status'])

@php
    $status = strtolower($status);
    $config = match ($status) {
        'aktif', 'paid', 'approved' => [
            'bg' => 'bg-emerald-100/50',
            'text' => 'text-emerald-800',
            'dot' => 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]',
            'border' => 'border-emerald-200/50'
        ],
        'nonaktif', 'cancelled', 'overdue', 'rejected' => [
            'bg' => 'bg-rose-100/50',
            'text' => 'text-rose-800',
            'dot' => 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]',
            'border' => 'border-rose-200/50'
        ],
        'pending', 'dp', 'sent', 'partial' => [
            'bg' => 'bg-amber-100/50',
            'text' => 'text-amber-800',
            'dot' => 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]',
            'border' => 'border-amber-200/50'
        ],
        'draft' => [
            'bg' => 'bg-slate-100/50',
            'text' => 'text-slate-800',
            'dot' => 'bg-slate-400',
            'border' => 'border-slate-200/50'
        ],
        default => [
            'bg' => 'bg-slate-50',
            'text' => 'text-slate-600',
            'dot' => 'bg-slate-400',
            'border' => 'border-slate-200'
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {$config['bg']} {$config['text']} {$config['border']} transition-all duration-300"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ strtoupper(__('ui.' . $status)) }}
</span>
