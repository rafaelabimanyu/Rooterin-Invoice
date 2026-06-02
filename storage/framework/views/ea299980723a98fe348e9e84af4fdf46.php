<!-- Log Aktivitas Keamanan Tim -->
<div class="lg:col-span-4 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col min-w-0 max-h-[340px]">
    <div class="flex items-center justify-between mb-4 shrink-0">
        <div>
            <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                <?php echo e(__('dashboard.team_log_title')); ?>

            </h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                <?php echo e(__('dashboard.team_log_subtitle')); ?>

            </p>
        </div>
        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
            <i data-lucide="shield-check" class="w-4.5 h-4.5"></i>
        </div>
    </div>

    <!-- Scrollable Timeline Feed -->
    <div class="overflow-y-auto pr-1 space-y-4 scroll-smooth mt-4 flex-1 scrollbar-thin">
        <div class="relative pl-6 border-l-2 border-slate-100 space-y-5 ml-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $securityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="relative">
                    <!-- Circle Timeline Bullet -->
                    <div class="absolute -left-[31px] top-1.5 w-2 h-2 rounded-full border-2 border-white
                        <?php if($log['type'] == 'success'): ?> bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]
                        <?php elseif($log['type'] == 'info'): ?> bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]
                        <?php else: ?> bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]
                        <?php endif; ?>
                    "></div>
                    
                    <!-- Header: Action & Time -->
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] font-black uppercase tracking-wider
                                <?php if($log['type'] == 'success'): ?> text-emerald-600
                                <?php elseif($log['type'] == 'info'): ?> text-blue-600
                                <?php else: ?> text-rose-600
                                <?php endif; ?>
                            ">
                                <?php echo e(__('dashboard.' . $log['action'])); ?>

                            </span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest bg-slate-50 px-1.5 py-0.5 rounded">
                                <?php echo e($log['user']); ?>

                            </span>
                        </div>
                        <span class="font-mono text-[9px] font-bold text-slate-400">
                            <?php echo e($log['time']->diffForHumans()); ?>

                        </span>
                    </div>

                    <!-- Description -->
                    <p class="text-slate-600 text-xs font-semibold mt-1 leading-snug">
                        <?php echo e(__('dashboard.' . $log['details_key'], $log['details_params'])); ?>

                    </p>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="py-8 text-center text-slate-400 italic text-xs">
                    <?php echo e(app()->getLocale() == 'en' ? 'No recent security activities.' : 'Tidak ada aktivitas keamanan terbaru.'); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/dashboard/partials/team-activities.blade.php ENDPATH**/ ?>