<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['title' => app()->getLocale() == 'en' ? 'Main Dashboard' : 'Dashboard Utama'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="animate-fade-in-up">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8 page-fade-in">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight mb-2 uppercase">
                <?php echo e(__('ui.command_center')); ?>

            </h1>
            <p class="text-sm text-slate-500 font-medium tracking-tight"><?php echo e(__('ui.operational_overview')); ?></p>
        </div>
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('invoices.create')); ?>" class="btn-premium group">
                <i data-lucide="plus" class="w-4 h-4 transition-transform group-hover:rotate-90"></i>
                <span><?php echo e(__('ui.create_invoice')); ?></span>
            </a>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isStaff): ?>
        <?php echo $__env->make('dashboard.partials.financial-advisory', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('dashboard.partials.metric-cards', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
        <!-- Staff: Premium Interactive Dashboard -->
        <div class="mb-12 page-fade-in" x-data="{ 
                    time: '', 
                    greeting: '',
                    updateTime() {
                        const now = new Date();
                        this.time = now.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        const hour = now.getHours();
                        if (hour < 12) this.greeting = 'Good Morning';
                        else if (hour < 18) this.greeting = 'Good Afternoon';
                        else this.greeting = 'Good Evening';
                    }
                }" x-init="updateTime(); setInterval(() => updateTime(), 1000)">

            <!-- Hero Section -->
            <div
                class="glass-card p-8 md:p-12 bg-gradient-to-br from-indigo-900 via-indigo-800 to-slate-900 text-white relative overflow-hidden mb-10 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.3)]">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-10">
                    <div class="flex-1 space-y-6">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md rounded-full border border-white/10">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-100" x-text="greeting">
                            </p>
                        </div>

                        <h2 class="text-3xl md:text-5xl font-black font-jakarta tracking-tight leading-tight">
                            <?php echo e(app()->getLocale() == 'en' ? 'Keep up the great work,' : 'Terus tingkatkan kinerja luar biasa Anda,'); ?><br><span class="text-indigo-300"><?php echo e(auth()->user()->name); ?></span>
                        </h2>

                        <div class="flex items-center gap-4 py-4 border-l-2 border-indigo-500/30 pl-6">
                            <i data-lucide="quote" class="w-8 h-8 text-indigo-400/50 -mt-4"></i>
                            <p class="text-sm md:text-lg italic text-indigo-100/80 font-medium max-w-xl">
                                "<?php echo e($randomQuote); ?>"
                            </p>
                        </div>

                        <!-- Quick Actions -->
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="<?php echo e(route('clients.create')); ?>"
                                class="group flex items-center gap-3 px-5 py-3 bg-white text-indigo-900 rounded-xl font-bold text-xs hover:bg-indigo-50 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-indigo-100 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="user-plus" class="w-4 h-4 text-indigo-600"></i>
                                </div>
                                <?php echo e(app()->getLocale() == 'en' ? 'New Client' : 'Klien Baru'); ?>

                            </a>
                            <a href="<?php echo e(route('invoices.create')); ?>"
                                class="group flex items-center gap-3 px-5 py-3 bg-indigo-600/50 backdrop-blur-md border border-white/20 text-white rounded-xl font-bold text-xs hover:bg-white/20 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-white/20 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="file-edit" class="w-4 h-4"></i>
                                </div>
                                <?php echo e(app()->getLocale() == 'en' ? 'Draft Invoice' : 'Draf Invoice'); ?>

                            </a>
                            <a href="<?php echo e(route('guide.index')); ?>?type=sop"
                                class="group flex items-center gap-3 px-5 py-3 bg-slate-800/50 backdrop-blur-md border border-white/10 text-white rounded-xl font-bold text-xs hover:bg-white/10 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-white/10 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="book-open" class="w-4 h-4"></i>
                                </div>
                                <?php echo e(app()->getLocale() == 'en' ? 'Operational SOP' : 'SOP Operasional'); ?>

                            </a>
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex flex-col items-end gap-4">
                        <!-- Digital Clock -->
                        <div
                            class="p-6 bg-white/5 backdrop-blur-xl rounded-[32px] border border-white/10 text-right min-w-[200px]">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-300/60 mb-2"><?php echo e(app()->getLocale() == 'en' ? 'System Time' : 'Waktu Sistem'); ?>

                            </p>
                            <p class="text-4xl md:text-5xl font-black font-mono tracking-tighter" x-text="time"></p>
                            <p class="text-[11px] font-bold text-indigo-200 mt-2"><?php echo e(date('l, F d, Y')); ?></p>
                        </div>

                        <!-- Daily Goal Progress -->
                        <div
                            class="p-6 bg-white/5 backdrop-blur-xl rounded-[32px] border border-white/10 flex items-center gap-6 min-w-[200px]">
                            <div class="relative w-16 h-16">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent"
                                        class="text-white/10" />
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent"
                                        class="text-indigo-400" stroke-dasharray="175.9"
                                        stroke-dashoffset="<?php echo e(175.9 - (175.9 * $goalProgress / 100)); ?>"
                                        stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-black"><?php echo e($goalProgress); ?>%</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-300/60 mb-1"><?php echo e(app()->getLocale() == 'en' ? 'Daily Target' : 'Target Harian'); ?></p>
                                <p class="text-sm font-black"><?php echo e($todayInvoicesCount); ?> / <?php echo e($dailyGoal); ?> <?php echo e(app()->getLocale() == 'en' ? 'Invoices' : 'Invoice'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Animated Background Particles (Simplified SVG) -->
                <div class="absolute inset-0 pointer-events-none opacity-20">
                    <div class="absolute top-10 left-10 w-64 h-64 bg-indigo-500 rounded-full blur-[100px] animate-pulse">
                    </div>
                    <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-500 rounded-full blur-[120px] animate-pulse"
                        style="animation-delay: 2s;"></div>
                </div>
            </div>

            <!-- Productivity Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="file-text" class="w-7 h-7 group-hover:animate-bounce"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Invoices' : 'Invoice'); ?></span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1"><?php echo e(app()->getLocale() == 'en' ? "Today's Invoices" : 'Invoice Hari Ini'); ?></p>
                    <h3 class="text-4xl font-black text-slate-900 font-jakarta"><?php echo e($todayInvoicesCount); ?></h3>
                </div>

                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="clipboard-check" class="w-7 h-7 group-hover:scale-110 transition-transform"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Receipts' : 'Kuitansi'); ?></span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1"><?php echo e(app()->getLocale() == 'en' ? 'Receipts Logged' : 'Kuitansi Tercatat'); ?></p>
                    <h3 class="text-4xl font-black text-slate-900 font-jakarta"><?php echo e($todayReceiptsCount); ?></h3>
                </div>

                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="zap" class="w-7 h-7 group-hover:rotate-12 transition-transform"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-amber-50 text-amber-600 px-3 py-1 rounded-full uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Revenue' : 'Pendapatan'); ?></span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1"><?php echo e(app()->getLocale() == 'en' ? 'Daily Output Value' : 'Nilai Output Harian'); ?></p>
                    <h3 class="text-2xl font-black text-slate-900 font-jakarta truncate">Rp
                        <?php echo e(number_format($todayRevenue, 0, ',', '.')); ?>

                    </h3>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Content Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6 xl:gap-8 w-full min-w-0">
        <!-- Main Activity Table -->
        <div class="lg:col-span-2 xl:col-span-3 flex flex-col min-w-0 w-full">
            <?php echo $__env->make('dashboard.partials.billing-operations', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isStaff): ?>
                <!-- Top Clients & Invoice Ageing Widgets (Desktop Version) -->
                <div class="hidden md:grid md:grid-cols-2 gap-6 mt-8 page-fade-in stagger-6">
                    <?php echo $__env->make('dashboard.partials.top-clients', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('dashboard.partials.invoice-ageing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <!-- Mobile View (Tab Toggle Container) -->
                <div class="block md:hidden mt-8 page-fade-in stagger-6" x-data="{ activeTab: 'topClients' }">
                    <div class="glass-card p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300">
                        <!-- Tab Header Toggle -->
                        <div class="flex border-b border-slate-100 pb-4 mb-6">
                            <button 
                                @click="activeTab = 'topClients'"
                                :class="activeTab === 'topClients' ? 'text-indigo-600 border-indigo-600 font-black' : 'text-slate-400 border-transparent hover:text-slate-600 font-bold'"
                                class="flex-1 pb-3 text-xs uppercase tracking-wider text-center border-b-2 transition-all focus:outline-none"
                            >
                                <?php echo e(app()->getLocale() == 'en' ? 'Top Clients' : 'Klien Teratas'); ?>

                            </button>
                            <button 
                                @click="activeTab = 'ageing'"
                                :class="activeTab === 'ageing' ? 'text-indigo-600 border-indigo-600 font-black' : 'text-slate-400 border-transparent hover:text-slate-600 font-bold'"
                                class="flex-1 pb-3 text-xs uppercase tracking-wider text-center border-b-2 transition-all focus:outline-none"
                            >
                                <?php echo e(app()->getLocale() == 'en' ? 'AR Ageing' : 'Umur Piutang'); ?>

                            </button>
                        </div>

                        <!-- Tab Content 1: Top Clients -->
                        <div x-show="activeTab === 'topClients'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            <?php echo $__env->make('dashboard.partials.top-clients', ['cardless' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>

                        <!-- Tab Content 2: AR Ageing -->
                        <div x-show="activeTab === 'ageing'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                            <?php echo $__env->make('dashboard.partials.invoice-ageing', ['cardless' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Right Side: Activity Timeline (Staff) or Stats (Admin) -->
        <?php echo $__env->make('dashboard.partials.upcoming-billing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isStaff): ?>
        <!-- Bottom Grid Container -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6 page-fade-in stagger-6">
            <?php echo $__env->make('dashboard.partials.cash-flow-chart', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php echo $__env->make('dashboard.partials.team-activities', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php echo $__env->make('dashboard.partials.system-analytics', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasFullAccess()): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('dashboard-chatbot', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1120212231-0', $__key);

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
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/dashboard.blade.php ENDPATH**/ ?>