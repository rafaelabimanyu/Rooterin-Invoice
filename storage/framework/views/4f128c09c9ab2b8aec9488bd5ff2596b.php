<div class="space-y-6 animate-fade-in">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client): ?>
        <div class="p-5 bg-slate-900 rounded-2xl text-white shadow-xl shadow-slate-900/10 flex justify-between items-center">
            <div>
                <span class="px-2.5 py-0.5 bg-white/20 rounded-full text-[8px] font-black uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Priority Entities' : 'Entitas Prioritas'); ?> (#<?php echo e($index + 1); ?>)</span>
                <h3 class="text-lg font-black mt-3 font-jakarta tracking-tight"><?php echo e($client->nama_client); ?></h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5"><?php echo e($client->nama_perusahaan); ?></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                <i data-lucide="building-2" class="w-5 h-5 text-indigo-400"></i>
            </div>
        </div>

        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'Enterprise Valuation (LTV)' : 'Valuasi perusahaan (LTV)'); ?></h4>
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-600"><?php echo e(app()->getLocale() == 'en' ? 'Valuation Sum' : 'Total Nilai Kontrak'); ?></span>
                <span class="text-base font-black text-indigo-600">Rp <?php echo e(number_format($client->invoices_sum_total, 0, ',', '.')); ?></span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <span class="text-xs font-semibold text-slate-600"><?php echo e(app()->getLocale() == 'en' ? 'Invoice Volume' : 'Volume Transaksi'); ?></span>
                <span class="text-sm font-bold text-slate-800"><?php echo e($client->invoices_count); ?> Invoices</span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <span class="text-xs font-semibold text-slate-600"><?php echo e(app()->getLocale() == 'en' ? 'Last Invoice Date' : 'Transaksi Terakhir'); ?></span>
                <span class="text-xs font-bold text-slate-800">
                    <?php echo e($client->last_transaction ? \Carbon\Carbon::parse($client->last_transaction)->format('d M Y') : '-'); ?>

                </span>
            </div>
        </div>

        <div class="space-y-2">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'Corporate Credentials' : 'Identitas & Kredensial'); ?></h4>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3">
                <div class="flex items-center gap-3">
                    <i data-lucide="tag" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider">CODE: <?php echo e($client->kode_client); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="briefcase" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-xs font-semibold text-slate-700"><?php echo e($client->client_type_label); ?> | <?php echo e($client->industry_sector_label); ?></span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->npwp): ?>
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-check-2" class="w-4 h-4 text-slate-400"></i>
                        <span class="text-xs font-semibold text-slate-700">NPWP: <?php echo e($client->npwp); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="space-y-2">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'Contact Details' : 'Informasi Hubungan'); ?></h4>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3">
                <div class="flex items-center gap-3">
                    <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-xs font-semibold text-slate-700"><?php echo e($client->email); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-xs font-semibold text-slate-700"><?php echo e($client->no_hp); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 shrink-0"></i>
                    <span class="text-xs font-semibold text-slate-700 leading-tight"><?php echo e($client->alamat); ?>, <?php echo e($client->kota); ?>, <?php echo e($client->provinsi); ?></span>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p class="text-xs text-slate-400 italic text-center py-12"><?php echo e(app()->getLocale() == 'en' ? 'Client details not found.' : 'Detail klien tidak ditemukan.'); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/livewire/owner-kpi-modals/client.blade.php ENDPATH**/ ?>