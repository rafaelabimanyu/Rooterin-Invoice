<!-- Chart Arus Kas Bulanan -->
<div class="lg:col-span-6 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-w-0">
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                    <?php echo e(__('dashboard.cash_flow_title')); ?>

                </h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                    <?php echo e(__('dashboard.cash_flow_subtitle')); ?>

                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i data-lucide="trending-up" class="w-4.5 h-4.5"></i>
            </div>
        </div>

        <!-- Legend -->
        <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-6 mt-2">
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-sm bg-indigo-600 block"></span>
                <span><?php echo e(__('dashboard.revenue')); ?></span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-sm bg-amber-500 block"></span>
                <span><?php echo e(__('dashboard.receivables')); ?></span>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="overflow-x-auto w-full scrollbar-thin pb-2">
            <div class="relative flex items-end justify-between h-48 w-full min-w-[500px] md:min-w-0 border-b border-slate-100 pb-2 mt-8">
                <!-- Y-Axis Gridlines -->
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-2">
                    <div class="w-full border-t border-slate-100/70"></div>
                    <div class="w-full border-t border-slate-100/70"></div>
                    <div class="w-full border-t border-slate-100/70"></div>
                    <div class="w-full border-t border-slate-100/70"></div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $cashFlowData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="flex flex-col items-center flex-1 h-full justify-end z-10">
                        <div class="flex items-end gap-1.5 h-full w-full justify-center px-1">
                            <!-- Bar 1 (Revenue) -->
                            <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: <?php echo e($data['revenue_height']); ?>%">
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                    <?php echo e($data['revenue_formatted']); ?>

                                </div>
                            </div>
                            <!-- Bar 2 (Receivables) -->
                            <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-amber-500 to-amber-450 hover:from-amber-600 hover:to-amber-500 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: <?php echo e($data['receivables_height']); ?>%">
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                    <?php echo e($data['receivables_formatted']); ?>

                                </div>
                            </div>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 mt-2 tracking-wider uppercase"><?php echo e($data['month_label']); ?></span>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="flex items-center justify-center w-full h-full text-slate-400 text-xs italic">
                        <?php echo e(app()->getLocale() == 'en' ? 'No financial data available' : 'Tidak ada data keuangan tersedia'); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/dashboard/partials/cash-flow-chart.blade.php ENDPATH**/ ?>