<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cardless ?? false): ?>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-xs">
                <?php echo e(app()->getLocale() == 'en' ? 'Top Clients by Revenue' : 'Klien Teratas Berdasarkan Pendapatan'); ?>

            </h4>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                <?php echo e(app()->getLocale() == 'en' ? 'Highest lifetime value clients' : 'Klien dengan kontribusi pendapatan terbesar'); ?>

            </p>
        </div>
        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
            <i data-lucide="award" class="w-4.5 h-4.5"></i>
        </div>
    </div>
    <div class="space-y-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="flex items-center justify-between p-3.5 bg-slate-50/50 hover:bg-indigo-50/40 rounded-xl border border-slate-100/80 hover:border-indigo-100 transition-all duration-200 group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <?php echo e($index + 1); ?>

                    </div>
                    <div class="flex flex-col">
                        <span class="text-[13px] font-bold text-slate-800 group-hover:text-indigo-600 transition-colors"><?php echo e($client->nama_client); ?></span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e($client->nama_perusahaan ?: '-'); ?></span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-black text-slate-950 font-jakarta">
                        Rp <?php echo e(number_format($client->invoices_sum_total ?? 0, 0, ',', '.')); ?>

                    </span>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="text-center py-8 text-slate-400 italic text-xs">
                <?php echo e(app()->getLocale() == 'en' ? 'No revenue records found.' : 'Belum ada data pendapatan.'); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php else: ?>
    <div class="glass-card p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300">
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                        <?php echo e(app()->getLocale() == 'en' ? 'Top Clients by Revenue' : 'Klien Teratas Berdasarkan Pendapatan'); ?>

                    </h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                        <?php echo e(app()->getLocale() == 'en' ? 'Highest lifetime value clients' : 'Klien dengan kontribusi pendapatan terbesar'); ?>

                    </p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i data-lucide="award" class="w-4.5 h-4.5"></i>
                </div>
            </div>

            <div class="space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="flex items-center justify-between p-3.5 bg-slate-50/50 hover:bg-indigo-50/40 rounded-xl border border-slate-100/80 hover:border-indigo-100 transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                <?php echo e($index + 1); ?>

                            </div>
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold text-slate-800 group-hover:text-indigo-600 transition-colors"><?php echo e($client->nama_client); ?></span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e($client->nama_perusahaan ?: '-'); ?></span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-slate-950 font-jakarta">
                                Rp <?php echo e(number_format($client->invoices_sum_total ?? 0, 0, ',', '.')); ?>

                            </span>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="text-center py-8 text-slate-400 italic text-xs">
                        <?php echo e(app()->getLocale() == 'en' ? 'No revenue records found.' : 'Belum ada data pendapatan.'); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/dashboard/partials/top-clients.blade.php ENDPATH**/ ?>