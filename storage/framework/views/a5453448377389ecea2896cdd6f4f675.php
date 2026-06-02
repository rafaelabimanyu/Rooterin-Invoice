<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['href', 'active', 'icon', 'label', 'collapsed' => false]));

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

foreach (array_filter((['href', 'active', 'icon', 'label', 'collapsed' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a 
    href="<?php echo e($href); ?>" 
    wire:navigate.hover
    <?php echo e($attributes->merge(['class' => 'group flex items-center rounded-xl transition-all duration-300 relative ' . 
        ($active ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900') .
        ($collapsed ? ' justify-center h-11 w-11 mx-auto' : ' gap-3 px-4 py-2.5')])); ?>

>
    <!-- Indicator Dot (only for active) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($active): ?>
        <span 
            class="absolute bg-indigo-500 rounded-r-full shadow-[0_0_8px_rgba(79,70,229,0.8)] transition-all duration-300"
            :class="collapsed ? 'left-[-12px] w-1 h-6' : 'left-0 w-1 h-4'"
        ></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="shrink-0 transition-transform duration-300 group-hover:scale-110 flex items-center justify-center">
        <i data-lucide="<?php echo e($icon); ?>" class="w-[18px] h-[18px] <?php echo e($active ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500 transition-colors'); ?>"></i>
    </div>
    
    <span 
        x-show="!collapsed" 
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0 -translate-x-2" 
        x-transition:enter-end="opacity-100 translate-x-0"
        class="text-[13px] font-bold tracking-tight whitespace-nowrap"
    >
        <?php echo e($label); ?>

    </span>

    <!-- Tooltip (only when collapsed) -->
    <div 
        x-show="collapsed" 
        class="absolute left-full ml-4 px-3 py-1.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-300 pointer-events-none z-[100] shadow-2xl whitespace-nowrap"
    >
        <?php echo e($label); ?>

    </div>
</a>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/components/sidebar-link.blade.php ENDPATH**/ ?>