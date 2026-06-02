<div class="space-y-6 animate-fade-in">
    <div class="p-6 bg-emerald-600 rounded-2xl text-white shadow-xl shadow-emerald-600/10 flex justify-between items-center">
        <div>
            <span class="px-2.5 py-0.5 bg-white/20 rounded-full text-[8px] font-black uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Loyalty Pulse' : 'Indeks Loyalitas'); ?></span>
            <h3 class="text-2xl font-black mt-3 font-jakarta tracking-tight"><?php echo e(number_format($repeatRate, 1)); ?>%</h3>
            <p class="text-[9.5px] text-emerald-100 font-medium mt-2"><?php echo e(app()->getLocale() == 'en' ? 'Ratio of recurring clients vs single transaction' : 'Rasio klien berulang vs transaksi tunggal'); ?></p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
            <i data-lucide="refresh-cw" class="w-6 h-6 text-emerald-200"></i>
        </div>
    </div>

    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'Client Breakdown' : 'Rincian Klien'); ?></h4>
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-600"><?php echo e(app()->getLocale() == 'en' ? 'Total Clients Registered' : 'Total Klien Terdaftar'); ?></span>
            <span class="text-sm font-bold text-slate-800"><?php echo e($totalClients); ?></span>
        </div>
        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
            <span class="text-xs font-semibold text-slate-600"><?php echo e(app()->getLocale() == 'en' ? 'Repeat Clients (2+ Invoices)' : 'Klien Berulang (2+ Faktur)'); ?></span>
            <span class="text-sm font-bold text-slate-800"><?php echo e(\App\Models\Client::has('invoices', '>', 1)->count()); ?></span>
        </div>
    </div>

    <div class="space-y-3">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'Most Active Partners' : 'Mitra Paling Aktif'); ?></h4>
        <div class="space-y-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $topClients->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-slate-800 tracking-tight leading-none block mb-1"><?php echo e($c->nama_client); ?></span>
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block"><?php echo e($c->nama_perusahaan); ?></span>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[9px] font-black uppercase tracking-wider">
                            <i data-lucide="file-text" class="w-3 h-3"></i>
                            <?php echo e($c->invoices_count); ?> <?php echo e(app()->getLocale() == 'en' ? 'Invoices' : 'Faktur'); ?>

                        </span>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/livewire/owner-kpi-modals/loyalty.blade.php ENDPATH**/ ?>