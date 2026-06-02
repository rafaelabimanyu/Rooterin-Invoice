<div class="lg:col-span-1 xl:col-span-1 flex flex-col gap-6 xl:gap-8 min-w-0 w-full">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('dashboard.upcoming-billing-horizon');

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-603524121-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isStaff): ?>
        <div class="glass-card p-10 flex flex-col w-full min-w-0">
            <div class="flex items-center justify-between mb-10">
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg"><?php echo e(app()->getLocale() == 'en' ? 'Activity Feed' : 'Aliran Aktivitas'); ?></h3>
                <span
                    class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full uppercase tracking-widest">Live</span>
            </div>

            <div class="flex-1 space-y-8 relative">
                <!-- Timeline Line -->
                <div class="absolute left-[11px] top-2 bottom-0 w-0.5 bg-slate-100"></div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="relative pl-10">
                        <div
                            class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-4 border-indigo-500 flex items-center justify-center z-10">
                        </div>
                        <div class="space-y-1">
                            <p class="text-[13px] font-bold text-slate-800 leading-snug"><?php echo e($log->description); ?></p>
                            <p class="text-[11px] text-slate-400 font-medium"><?php echo e($log->created_at->diffForHumans()); ?>

                            </p>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="text-center py-12 px-6">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="activity" class="w-8 h-8 text-slate-300"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-900 uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'No activities recorded' : 'Tidak ada aktivitas tercatat'); ?></p>
                        <p class="text-[11px] text-slate-400 mt-2"><?php echo e(app()->getLocale() == 'en' ? 'Activities will appear here once you start processing documents.' : 'Aktivitas akan muncul di sini setelah Anda mulai memproses dokumen.'); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="mt-10 pt-10 border-t border-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 text-center"><?php echo e(app()->getLocale() == 'en' ? 'System Information' : 'Informasi Sistem'); ?></p>
                <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-100 space-y-4">
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-500 font-bold"><?php echo e(app()->getLocale() == 'en' ? 'Node Identity' : 'Identitas Node'); ?></span>
                        <span
                            class="font-black text-slate-900">STAFF-<?php echo e(str_pad(auth()->id(), 4, '0', STR_PAD_LEFT)); ?></span>
                    </div>
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-500 font-bold"><?php echo e(app()->getLocale() == 'en' ? 'Session Integrity' : 'Integritas Sesi'); ?></span>
                        <span class="font-black text-emerald-500 flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> <?php echo e(app()->getLocale() == 'en' ? 'Active' : 'Aktif'); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Admin Stats Side Card (Moved to bottom grid) -->
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/dashboard/partials/upcoming-billing.blade.php ENDPATH**/ ?>