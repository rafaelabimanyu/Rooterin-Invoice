<div class="space-y-6 animate-fade-in">
    <div class="p-6 bg-amber-500 rounded-2xl text-white shadow-xl shadow-amber-500/10 flex justify-between items-center">
        <div>
            <span class="px-2.5 py-0.5 bg-white/20 rounded-full text-[8px] font-black uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Capital at Risk' : 'Modal dalam Risiko'); ?></span>
            <h3 class="text-2xl font-black mt-3 font-jakarta tracking-tight">Rp <?php echo e(number_format($totalUnpaid, 0, ',', '.')); ?></h3>
            <p class="text-[9.5px] text-amber-100 font-medium mt-2"><?php echo e(app()->getLocale() == 'en' ? 'Outstanding receivables requiring collection' : 'Piutang beredar yang memerlukan penagihan'); ?></p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
            <i data-lucide="alert-triangle" class="w-6 h-6 text-amber-200"></i>
        </div>
    </div>

    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'Risk Breakdown' : 'Rincian Risiko'); ?></h4>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                <span class="text-xs font-semibold text-slate-600"><?php echo e(app()->getLocale() == 'en' ? 'Floating' : 'Berjalan'); ?></span>
            </div>
            <span class="text-sm font-bold text-slate-800">Rp <?php echo e(number_format($pendingUnpaid, 0, ',', '.')); ?></span>
        </div>
        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <span class="text-xs font-semibold text-rose-600"><?php echo e(app()->getLocale() == 'en' ? 'Critical' : 'Kritis'); ?></span>
            </div>
            <span class="text-sm font-bold text-rose-600">Rp <?php echo e(number_format($overdueUnpaid, 0, ',', '.')); ?></span>
        </div>
    </div>

    <div class="space-y-3">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'Top Outstanding Receivables' : 'Piutang Terbesar Terutang'); ?></h4>
        <div class="space-y-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $unpaidInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-slate-800 tracking-tight leading-none block mb-1"><?php echo e($inv->client->nama_client); ?></span>
                        <span class="text-[9px] text-rose-500 font-bold uppercase tracking-wider block"><?php echo e(app()->getLocale() == 'en' ? 'Due' : 'Tempo'); ?>: <?php echo e(\Carbon\Carbon::parse($inv->due_date)->format('d M Y')); ?></span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-slate-900 block">Rp <?php echo e(number_format($inv->total - $inv->payments->sum('amount'), 0, ',', '.')); ?></span>
                        <span class="px-2 py-0.5 bg-rose-50 text-rose-600 rounded text-[8px] font-black uppercase tracking-wider inline-block mt-1"><?php echo e($inv->status); ?></span>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="text-xs text-slate-400 italic text-center py-6"><?php echo e(app()->getLocale() == 'en' ? 'No outstanding invoices!' : 'Tidak ada tagihan tertunggak!'); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/livewire/owner-kpi-modals/risks.blade.php ENDPATH**/ ?>