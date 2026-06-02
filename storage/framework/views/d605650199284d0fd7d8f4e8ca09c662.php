<div class="glass-card p-10 flex flex-col shadow-2xl shadow-indigo-500/5 border-slate-100 page-fade-in stagger-4 w-full min-w-0">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg"><?php echo e(app()->getLocale() == 'en' ? 'Upcoming Billing Horizon' : 'Cakrawala Penagihan Mendatang'); ?></h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1"><?php echo e(app()->getLocale() == 'en' ? 'Next 7 Days Projections' : 'Proyeksi 7 Hari ke Depan'); ?></p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
            <i data-lucide="calendar-clock" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="flex-1 space-y-6 max-h-[480px] overflow-y-auto pr-2 scrollbar-thin">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $upcomingInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="group flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-transparent hover:border-indigo-100 hover:bg-white transition-all duration-300 gap-3">
                <!-- Left Section: Avatar -->
                <div class="shrink-0 w-12 h-12 flex flex-col items-center justify-center rounded-xl bg-white shadow-sm border border-slate-100">
                    <span class="text-[9px] font-black uppercase text-slate-400 leading-none mb-1"><?php echo e($invoice->due_date->format('M')); ?></span>
                    <span class="text-lg font-black text-slate-900 leading-none"><?php echo e($invoice->due_date->format('d')); ?></span>
                </div>

                <!-- Middle Section: Texts -->
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-black text-slate-900 group-hover:text-indigo-600 transition-colors truncate">
                        <?php echo e($invoice->invoice_number); ?>

                    </p>
                    <p class="text-[11px] text-slate-500 font-medium truncate">
                        <?php echo e($invoice->client->nama_client); ?>

                    </p>
                </div>

                <!-- Right Section: Amounts -->
                <div class="shrink-0 text-right">
                    <p class="text-[13px] font-black text-slate-900">
                        Rp <?php echo e(number_format($invoice->total, 0, ',', '.')); ?>

                    </p>
                    <span class="text-[9px] font-black uppercase tracking-widest block whitespace-nowrap <?php echo e($invoice->due_date->isToday() ? 'text-rose-500' : 'text-slate-400'); ?>">
                        <?php echo e($invoice->due_date->isToday() ? (app()->getLocale() == 'en' ? 'Due Today' : 'Jatuh Tempo Hari Ini') : $invoice->due_date->diffForHumans()); ?>

                    </span>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="sun" class="w-8 h-8 text-slate-200"></i>
                </div>
                <p class="text-xs font-bold text-slate-900 uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Horizon is Clear' : 'Cakrawala Bersih'); ?></p>
                <p class="text-[11px] text-slate-400 mt-2"><?php echo e(app()->getLocale() == 'en' ? 'No invoices are due within the next 7 days.' : 'Tidak ada invoice jatuh tempo dalam 7 hari ke depan.'); ?></p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!auth()->user()->hasRole('staff')): ?>
    <div class="mt-10 pt-10 border-t border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?php echo e(app()->getLocale() == 'en' ? 'Expected Cash Flow' : 'Estimasi Arus Kas Masuk'); ?></p>
                <h4 class="text-2xl font-black text-slate-900 font-jakarta tracking-tighter">Rp <?php echo e(number_format($totalExpectedCashFlow, 0, ',', '.')); ?></h4>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl">
                <i data-lucide="trending-up" class="w-5 h-5 text-emerald-600"></i>
            </div>
        </div>
        <a href="<?php echo e(route('chronos.index')); ?>" class="mt-6 w-full flex items-center justify-center gap-2 px-4 py-3 bg-slate-50 text-slate-600 rounded-xl font-bold text-xs hover:bg-indigo-600 hover:text-white transition-all duration-300 group">
            <i data-lucide="calendar-days" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
            <?php echo e(app()->getLocale() == 'en' ? 'View Full Calendar' : 'Lihat Kalender Lengkap'); ?>

        </a>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/livewire/dashboard/upcoming-billing-horizon.blade.php ENDPATH**/ ?>