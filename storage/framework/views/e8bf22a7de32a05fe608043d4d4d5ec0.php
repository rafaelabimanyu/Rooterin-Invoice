<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['title' => app()->getLocale() == 'en' ? 'Audit Reports & Analytics' : 'Laporan Audit & Analisis'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="page-fade-in py-8 px-6 lg:px-8" x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'invoices' }">
        <div class="max-w-full mx-auto space-y-10">
            <!-- Header Block -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 font-jakarta uppercase tracking-tight"><?php echo e(__('ui.reports')); ?></h1>
                    <p class="text-sm text-slate-500 mt-1"><?php echo e(app()->getLocale() == 'en' ? 'Comprehensive financial audit, realtime cashflow monitoring, and performance analytics.' : 'Audit keuangan komprehensif, pemantauan arus kas waktu nyata, dan analisis kinerja.'); ?></p>
                </div>
            </div>

            <!-- Filters -->
            <div class="glass-card p-6">
                <form action="<?php echo e(route('reports.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <input type="hidden" name="tab" :value="tab">
                    <div class="space-y-2 md:col-span-3">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Start Date' : 'Tanggal Mulai'); ?></label>
                        <input type="date" name="start_date" value="<?php echo e($startDate); ?>" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 focus:bg-white transition-colors font-medium">
                    </div>
                    <div class="space-y-2 md:col-span-3">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'End Date' : 'Tanggal Selesai'); ?></label>
                        <input type="date" name="end_date" value="<?php echo e($endDate); ?>" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 focus:bg-white transition-colors font-medium">
                    </div>
                    <div class="space-y-2 md:col-span-3">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Client Account' : 'Akun Klien'); ?></label>
                        <select name="client_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 focus:bg-white transition-colors font-medium">
                            <option value=""><?php echo e(app()->getLocale() == 'en' ? 'All Clients' : 'Semua Klien'); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($client->id); ?>" <?php echo e($clientId == $client->id ? 'selected' : ''); ?>><?php echo e($client->nama_client); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="flex flex-wrap gap-2 md:col-span-3">
                        <button type="submit" class="flex-1 btn-premium py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'Apply Filter' : 'Terapkan Filter'); ?></button>
                        <button type="submit" formaction="<?php echo e(route('reports.export')); ?>" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white transition-colors duration-300 py-2.5 px-4 rounded-xl font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span><?php echo e(app()->getLocale() == 'en' ? 'Export Excel' : 'Ekspor Excel'); ?></span>
                        </button>
                        <a href="<?php echo e(route('reports.index')); ?>" class="btn-secondary py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center">Reset</a>
                    </div>
                </form>
            </div>

            <div>
                <!-- Tabs -->
                <div class="flex flex-wrap items-center gap-4 sm:gap-8 border-b border-slate-100 mb-10">
                    <button @click="tab = 'invoices'" :class="tab === 'invoices' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent'" class="pb-4 text-xs font-black border-b-2 transition-all uppercase tracking-widest">
                        <?php echo e(app()->getLocale() == 'en' ? 'Invoice Performance' : 'Kinerja Faktur'); ?>

                    </button>
                    <button @click="tab = 'receipts'" :class="tab === 'receipts' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent'" class="pb-4 text-xs font-black border-b-2 transition-all uppercase tracking-widest">
                        <?php echo e(app()->getLocale() == 'en' ? 'Receipts & Payments' : 'Kuitansi & Pembayaran'); ?>

                    </button>
                    <button @click="tab = 'clients'" :class="tab === 'clients' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent'" class="pb-4 text-xs font-black border-b-2 transition-all uppercase tracking-widest">
                        <?php echo e(app()->getLocale() == 'en' ? 'Client Analytics & Trends' : 'Analisis Klien & Tren'); ?>

                    </button>
                </div>

                <!-- Invoice Tab -->
                <div x-show="tab === 'invoices'" x-transition>
                    <!-- Metric Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <!-- Total Faktur -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/5 blur-xl group-hover:bg-indigo-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none"><?php echo e(app()->getLocale() == 'en' ? 'Total Invoices' : 'Total Faktur'); ?></p>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight"><?php echo e($invoiceStats['total_count']); ?></h3>
                            <div class="mt-4 flex items-center gap-1.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoiceStats['count_growth'] > 0): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 shadow-sm">
                                        <i data-lucide="trending-up" class="w-2.5 h-2.5"></i>
                                        +<?php echo e(number_format($invoiceStats['count_growth'], 1)); ?>%
                                    </span>
                                <?php elseif($invoiceStats['count_growth'] < 0): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-600 shadow-sm">
                                        <i data-lucide="trending-down" class="w-2.5 h-2.5"></i>
                                        <?php echo e(number_format($invoiceStats['count_growth'], 1)); ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-500 shadow-sm">
                                        <i data-lucide="minus" class="w-2.5 h-2.5"></i>
                                        0.0%
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'vs last period' : 'dibanding periode lalu'); ?></span>
                            </div>
                        </div>

                        <!-- Total Tagihan Kotor -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/5 blur-xl group-hover:bg-indigo-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none"><?php echo e(app()->getLocale() == 'en' ? 'Gross Billing' : 'Total Tagihan Kotor'); ?></p>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="banknote" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight">Rp <?php echo e(number_format($invoiceStats['total_value'], 0, ',', '.')); ?></h3>
                            <div class="mt-4 flex items-center gap-1.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoiceStats['value_growth'] > 0): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 shadow-sm">
                                        <i data-lucide="trending-up" class="w-2.5 h-2.5"></i>
                                        +<?php echo e(number_format($invoiceStats['value_growth'], 1)); ?>%
                                    </span>
                                <?php elseif($invoiceStats['value_growth'] < 0): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-600 shadow-sm">
                                        <i data-lucide="trending-down" class="w-2.5 h-2.5"></i>
                                        <?php echo e(number_format($invoiceStats['value_growth'], 1)); ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-500 shadow-sm">
                                        <i data-lucide="minus" class="w-2.5 h-2.5"></i>
                                        0.0%
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'vs last period' : 'dibanding periode lalu'); ?></span>
                            </div>
                        </div>

                        <!-- Total Tunggakan -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-500/5 blur-xl group-hover:bg-rose-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none"><?php echo e(app()->getLocale() == 'en' ? 'Total Outstanding' : 'Total Tunggakan'); ?></p>
                                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-rose-600 font-jakarta tracking-tight">Rp <?php echo e(number_format($totalOutstanding, 0, ',', '.')); ?></h3>
                            <div class="mt-4 flex items-center gap-1.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($outstandingGrowth > 0): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-600 shadow-sm">
                                        <i data-lucide="trending-up" class="w-2.5 h-2.5"></i>
                                        +<?php echo e(number_format($outstandingGrowth, 1)); ?>%
                                    </span>
                                <?php elseif($outstandingGrowth < 0): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 shadow-sm">
                                        <i data-lucide="trending-down" class="w-2.5 h-2.5"></i>
                                        <?php echo e(number_format($outstandingGrowth, 1)); ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-500 shadow-sm">
                                        <i data-lucide="minus" class="w-2.5 h-2.5"></i>
                                        0.0%
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'vs last period' : 'dibanding periode lalu'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Status Breakdown Card (Left) -->
                        <div class="glass-card overflow-hidden flex flex-col justify-between">
                            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                                <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm"><?php echo e(app()->getLocale() == 'en' ? 'Status Breakdown' : 'Rincian Status'); ?></h4>
                            </div>
                            <div class="p-8 space-y-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $invoiceStats['status_breakdown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="flex items-center justify-between hover:translate-x-1 transition-transform">
                                        <div class="flex items-center gap-3">
                                            <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $stat->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stat->status)]); ?>
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
                                            <span class="text-xs font-bold text-slate-500"><?php echo e($stat->count); ?> <?php echo e(app()->getLocale() == 'en' ? 'Items' : 'Item'); ?></span>
                                        </div>
                                        <span class="text-sm font-black text-slate-900 font-jakarta">Rp <?php echo e(number_format($stat->total, 0, ',', '.')); ?></span>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <div class="py-12 text-center text-slate-400 italic text-sm">
                                        <?php echo e(app()->getLocale() == 'en' ? 'No status data' : 'Tidak ada data status'); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Revenue vs. Receivables Trend Chart (Right) -->
                        <div class="glass-card p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm"><?php echo e(app()->getLocale() == 'en' ? 'Revenue & Receivables Trend' : 'Tren Pendapatan & Piutang'); ?></h4>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1"><?php echo e(app()->getLocale() == 'en' ? 'Monthly financial trends comparison' : 'Perbandingan tren keuangan bulanan'); ?></p>
                                </div>
                            </div>
                            <!-- Responsive Chart Container -->
                            <div class="relative h-[250px] w-full">
                                <div id="trendChart" class="absolute inset-0"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transaction Logs Table -->
                    <div class="glass-card overflow-hidden mt-10">
                        <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm"><?php echo e(app()->getLocale() == 'en' ? 'Recent Transaction Logs' : 'Aktivitas Transaksi Terakhir'); ?></h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1"><?php echo e(app()->getLocale() == 'en' ? 'Realtime invoice audit log' : 'Log audit faktur secara realtime'); ?></p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                <i data-lucide="history" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-50/30">
                                        <th class="px-8 py-4"><?php echo e(app()->getLocale() == 'en' ? 'Invoice Number' : 'No. Faktur'); ?></th>
                                        <th class="px-8 py-4"><?php echo e(app()->getLocale() == 'en' ? 'Client' : 'Klien'); ?></th>
                                        <th class="px-8 py-4"><?php echo e(app()->getLocale() == 'en' ? 'Modified Date' : 'Tanggal Diubah'); ?></th>
                                        <th class="px-8 py-4 text-right"><?php echo e(app()->getLocale() == 'en' ? 'Amount' : 'Jumlah'); ?></th>
                                        <th class="px-8 py-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                            <td class="px-8 py-4">
                                                <span class="text-xs font-black text-indigo-600"><?php echo e($invoice->invoice_number); ?></span>
                                            </td>
                                            <td class="px-8 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-slate-900"><?php echo e($invoice->client->nama_client); ?></span>
                                                    <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider"><?php echo e($invoice->client->nama_perusahaan); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-4 text-xs font-medium text-slate-500">
                                                <?php echo e($invoice->updated_at->format('M d, Y H:i')); ?>

                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <span class="text-xs font-black text-slate-900">Rp <?php echo e(number_format($invoice->total, 0, ',', '.')); ?></span>
                                            </td>
                                            <td class="px-8 py-4 text-center">
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
                                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic text-sm">
                                                <?php echo e(app()->getLocale() == 'en' ? 'No recent activity' : 'Tidak ada aktivitas terbaru'); ?>

                                            </td>
                                        </tr>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Receipt Tab -->
                <div x-show="tab === 'receipts'" x-transition>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <!-- Total Terkumpul -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/5 blur-xl group-hover:bg-emerald-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none"><?php echo e(app()->getLocale() == 'en' ? 'Total Collected' : 'Total Terkumpul'); ?></p>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-emerald-600 font-jakarta tracking-tight">Rp <?php echo e(number_format($paymentStats['total_collected'], 0, ',', '.')); ?></h3>
                            <div class="mt-4 flex items-center gap-1.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paymentStats['collected_growth'] > 0): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 shadow-sm">
                                        <i data-lucide="trending-up" class="w-2.5 h-2.5"></i>
                                        +<?php echo e(number_format($paymentStats['collected_growth'], 1)); ?>%
                                    </span>
                                <?php elseif($paymentStats['collected_growth'] < 0): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-600 shadow-sm">
                                        <i data-lucide="trending-down" class="w-2.5 h-2.5"></i>
                                        <?php echo e(number_format($paymentStats['collected_growth'], 1)); ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-500 shadow-sm">
                                        <i data-lucide="minus" class="w-2.5 h-2.5"></i>
                                        0.0%
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'vs last period' : 'dibanding periode lalu'); ?></span>
                            </div>
                        </div>

                        <!-- Tingkat Pengumpulan -->
                        <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/5 blur-xl group-hover:bg-indigo-500/10 transition-colors duration-500 rounded-full"></div>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none"><?php echo e(app()->getLocale() == 'en' ? 'Collection Rate' : 'Tingkat Pengumpulan'); ?></p>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-sm transition-transform duration-500 group-hover:rotate-3">
                                    <i data-lucide="percent" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight">
                                <?php echo e($invoiceStats['total_value'] > 0 ? round(($paymentStats['total_collected'] / $invoiceStats['total_value']) * 100) : 0); ?>%
                            </h3>
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-4 shadow-inner">
                                <div class="bg-indigo-600 h-full" style="width: <?php echo e($invoiceStats['total_value'] > 0 ? ($paymentStats['total_collected'] / $invoiceStats['total_value']) * 100 : 0); ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card overflow-hidden">
                        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm"><?php echo e(app()->getLocale() == 'en' ? 'Recent Payments History' : 'Riwayat Pembayaran Terbaru'); ?></h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1"><?php echo e(app()->getLocale() == 'en' ? 'Audit payment ledger' : 'Buku besar audit pembayaran'); ?></p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                                <i data-lucide="receipt" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-50/30">
                                        <th class="px-8 py-4"><?php echo e(app()->getLocale() == 'en' ? 'Date' : 'Tanggal'); ?></th>
                                        <th class="px-8 py-4"><?php echo e(app()->getLocale() == 'en' ? 'Client' : 'Klien'); ?></th>
                                        <th class="px-8 py-4"><?php echo e(app()->getLocale() == 'en' ? 'Invoice #' : 'No. Faktur'); ?></th>
                                        <th class="px-8 py-4 text-right"><?php echo e(app()->getLocale() == 'en' ? 'Amount' : 'Jumlah'); ?></th>
                                        <th class="px-8 py-4"><?php echo e(app()->getLocale() == 'en' ? 'Method' : 'Metode'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $paymentStats['recent_payments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                            <td class="px-8 py-4 text-xs font-medium text-slate-500"><?php echo e($payment->payment_date->format('M d, Y')); ?></td>
                                            <td class="px-8 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-slate-900"><?php echo e($payment->invoice->client->nama_client); ?></span>
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest"><?php echo e($payment->invoice->client->nama_perusahaan); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-4">
                                                <span class="text-xs font-bold text-indigo-600"><?php echo e($payment->invoice->invoice_number); ?></span>
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <span class="text-xs font-black text-slate-900">Rp <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></span>
                                            </td>
                                            <td class="px-8 py-4">
                                                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg"><?php echo e($payment->payment_method); ?></span>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <tr>
                                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic text-sm">
                                                <?php echo e(app()->getLocale() == 'en' ? 'No recent payments' : 'Tidak ada pembayaran terbaru'); ?>

                                            </td>
                                        </tr>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Client Analytics & Trends Tab -->
                <div x-show="tab === 'clients'" x-transition>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Top Revenue Drivers -->
                        <div class="glass-card overflow-hidden flex flex-col justify-between">
                            <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                                <div>
                                    <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm"><?php echo e(app()->getLocale() == 'en' ? 'Top Revenue Drivers' : 'Pendorong Pendapatan Utama'); ?></h4>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1"><?php echo e(app()->getLocale() == 'en' ? 'Highest lifetime value client accounts' : 'Akun klien dengan kontribusi pendapatan tertinggi'); ?></p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                                    <i data-lucide="crown" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <div class="p-8 space-y-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topClientRevenue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="flex items-center justify-between hover:translate-x-1 transition-transform duration-300">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs">
                                                LTV
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-900"><?php echo e($client->nama_client); ?></span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest"><?php echo e($client->nama_perusahaan); ?></span>
                                            </div>
                                        </div>
                                        <span class="text-sm font-black text-slate-900 font-jakarta">Rp <?php echo e(number_format($client->total_revenue, 0, ',', '.')); ?></span>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <div class="py-12 text-center text-slate-400 italic text-sm">
                                        <?php echo e(app()->getLocale() == 'en' ? 'No revenue data in this period' : 'Tidak ada data pendapatan dalam periode ini'); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Outstanding Payment Delays -->
                        <div class="glass-card overflow-hidden flex flex-col justify-between">
                            <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                                <div>
                                    <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm"><?php echo e(app()->getLocale() == 'en' ? 'Outstanding Delays' : 'Tunggakan & Hambatan Pembayaran'); ?></h4>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1"><?php echo e(app()->getLocale() == 'en' ? 'Accounts with highest outstanding balance' : 'Akun dengan saldo tunggakan tertinggi'); ?></p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shadow-sm">
                                    <i data-lucide="clock-alert" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <div class="p-8 space-y-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topClientOutstanding; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="flex items-center justify-between hover:translate-x-1 transition-transform duration-300">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center font-black text-xs">
                                                DEBT
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-900"><?php echo e($client->nama_client); ?></span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest"><?php echo e($client->nama_perusahaan); ?></span>
                                            </div>
                                        </div>
                                        <span class="text-sm font-black text-rose-600 font-jakarta">Rp <?php echo e(number_format($client->total_outstanding, 0, ',', '.')); ?></span>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <div class="py-12 text-center text-slate-400 italic text-sm">
                                        <?php echo e(app()->getLocale() == 'en' ? 'No outstanding balance in this period' : 'Tidak ada tunggakan pembayaran dalam periode ini'); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categories = <?php echo json_encode($trendMonths); ?>;
            const revenueData = <?php echo json_encode($trendRevenue); ?>;
            const receivablesData = <?php echo json_encode($trendReceivables); ?>;
            
            const options = {
                series: [
                    {
                        name: '<?php echo e(app()->getLocale() == "en" ? "Revenue" : "Pendapatan"); ?>',
                        data: revenueData
                    },
                    {
                        name: '<?php echo e(app()->getLocale() == "en" ? "Receivables" : "Piutang"); ?>',
                        data: receivablesData
                    }
                ],
                chart: {
                    type: 'area',
                    height: '100%',
                    width: '100%',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                colors: ['#10b981', '#f43f5e'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    lineCap: 'round'
                },
                xaxis: {
                    categories: categories,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '10px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return 'Rp ' + (val/1000000).toFixed(1) + 'M';
                        },
                        style: {
                            colors: '#94a3b8',
                            fontSize: '10px'
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 6,
                    padding: { left: 10, right: 10 }
                },
                markers: {
                    size: 0,
                    hover: { size: 5 }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#trendChart"), options);
            chart.render();
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/reports/index.blade.php ENDPATH**/ ?>