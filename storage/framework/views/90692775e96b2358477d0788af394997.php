<div class="table-container page-fade-in stagger-5 overflow-hidden">
    <div
        class="px-10 py-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between bg-slate-50/30 gap-4">
        <div>
            <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">
                <?php echo e(__('ui.billing_operations')); ?>

            </h3>
            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                <?php echo e(__('ui.latest_transactions')); ?>

            </p>
        </div>
        <a href="<?php echo e(route('invoices.index')); ?>" class="btn-secondary group">
            <span><?php echo e(__('ui.view_all_invoices')); ?></span>
            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="overflow-x-auto w-full hidden md:block">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="table-header">
                    <th class="px-10 py-5"><?php echo e(__('ui.reference')); ?></th>
                    <th class="px-10 py-5"><?php echo e(__('ui.entity')); ?></th>
                    <th class="px-10 py-5"><?php echo e(__('ui.volume')); ?></th>
                    <th class="px-10 py-5"><?php echo e(__('ui.status')); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="table-row-premium cursor-pointer group"
                        onclick="window.location='<?php echo e(route('invoices.show', $invoice)); ?>'">
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center group-hover:bg-slate-900 transition-colors duration-300">
                                    <i data-lucide="hash"
                                        class="w-4 h-4 text-slate-400 group-hover:text-white"></i>
                                </div>
                                <span
                                    class="text-[13px] font-black text-slate-900 tracking-tight"><?php echo e($invoice->invoice_number); ?></span>
                            </div>
                        </td>
                        <td class="px-10 py-6">
                            <div class="flex flex-col">
                                <span
                                    class="text-[14px] font-bold text-slate-900 group-hover:text-indigo-600 transition-colors"><?php echo e($invoice->client->nama_client); ?></span>
                                <span
                                    class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5"><?php echo e($invoice->client->nama_perusahaan); ?></span>
                            </div>
                        </td>
                        <td class="px-10 py-6">
                            <span class="text-[15px] font-black text-slate-900 tracking-tighter">Rp
                                <?php echo e(number_format($invoice->total, 0, ',', '.')); ?></span>
                        </td>
                        <td class="px-10 py-6">
                            <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $invoice->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($invoice->status)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="4" class="px-10 py-32 text-center">
                            <div class="flex flex-col items-center max-w-sm mx-auto">
                                <div
                                    class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mb-8 relative">
                                    <i data-lucide="rocket" class="w-16 h-16 text-slate-200 animate-pulse"></i>
                                    <div
                                        class="absolute top-0 right-0 w-8 h-8 bg-indigo-50 rounded-full flex items-center justify-center animate-bounce">
                                        <i data-lucide="sparkles" class="w-4 h-4 text-indigo-400"></i>
                                    </div>
                                </div>
                                <h4
                                    class="text-xl font-black text-slate-900 font-jakarta uppercase tracking-tight mb-2">
                                    <?php echo e(app()->getLocale() == 'en' ? 'Ready for Lift-off?' : 'Siap Lepas Landas?'); ?></h4>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed"><?php echo e(app()->getLocale() == 'en' ? 'Your workspace is clean and ready. Start your first transaction of the day to see activity logs here.' : 'Ruang kerja Anda bersih dan siap. Mulai transaksi pertama Anda hari ini untuk melihat log aktivitas di sini.'); ?></p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile card list view -->
    <div class="block md:hidden divide-y divide-slate-100/70">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="p-6 hover:bg-slate-50 transition-colors duration-200 cursor-pointer"
                 onclick="window.location='<?php echo e(route('invoices.show', $invoice)); ?>'">
                <div class="flex items-center justify-between gap-4">
                    <div class="space-y-1.5 flex-grow min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-black text-slate-900 tracking-tight"><?php echo e($invoice->invoice_number); ?></span>
                            <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $invoice->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($invoice->status)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-slate-800 truncate"><?php echo e($invoice->client->nama_client); ?></span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider truncate"><?php echo e($invoice->client->nama_perusahaan ?: '-'); ?></span>
                        </div>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="text-[15px] font-black text-slate-950 font-jakarta">
                            Rp <?php echo e(number_format($invoice->total, 0, ',', '.')); ?>

                        </span>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="px-6 py-16 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 mx-auto relative">
                    <i data-lucide="rocket" class="w-10 h-10 text-slate-200 animate-pulse"></i>
                </div>
                <h4 class="text-base font-black text-slate-900 font-jakarta uppercase tracking-tight mb-1">
                    <?php echo e(app()->getLocale() == 'en' ? 'Ready for Lift-off?' : 'Siap Lepas Landas?'); ?>

                </h4>
                <p class="text-xs text-slate-500 font-medium max-w-xs mx-auto leading-relaxed">
                    <?php echo e(app()->getLocale() == 'en' ? 'Your workspace is clean. Start your first transaction of the day.' : 'Ruang kerja Anda bersih. Mulai transaksi pertama Anda hari ini.'); ?>

                </p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/dashboard/partials/billing-operations.blade.php ENDPATH**/ ?>