<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<span <?php echo e($attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest border {$config['bg']} {$config['text']} {$config['border']} transition-all duration-300"])); ?>>
    <span class="w-1.5 h-1.5 rounded-full <?php echo e($config['dot']); ?>"></span>
    <?php echo e(strtoupper(__('ui.' . $status))); ?>

</span>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/components/badge.blade.php ENDPATH**/ ?>