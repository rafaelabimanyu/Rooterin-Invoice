<div x-data="{ 
    showPermissionsModal: <?php if ((object) ('showPermissionsModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showPermissionsModal'->value()); ?>')<?php echo e('showPermissionsModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showPermissionsModal'); ?>')<?php endif; ?>,
    showSuspendModal: <?php if ((object) ('showSuspendModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showSuspendModal'->value()); ?>')<?php echo e('showSuspendModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showSuspendModal'); ?>')<?php endif; ?>,
    showEditModal: <?php if ((object) ('showEditModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showEditModal'->value()); ?>')<?php echo e('showEditModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showEditModal'); ?>')<?php endif; ?> 
}">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 page-fade-in">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">
                <span><?php echo e(app()->getLocale() == 'en' ? 'Administration' : 'Administrasi'); ?></span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600"><?php echo e(app()->getLocale() == 'en' ? 'Team Control Center' : 'Pusat Kontrol Tim'); ?></span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight uppercase"><?php echo e(__('ui.users') ?? (app()->getLocale() == 'en' ? 'Team Management' : 'Manajemen Tim')); ?></h1>
            <p class="text-sm text-slate-500 font-medium mt-1"><?php echo e(app()->getLocale() == 'en' ? 'Manage operatives, security clearances, and operational status.' : 'Kelola staf pelaksana, izin keamanan, dan status operasional.'); ?></p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Search field -->
            <input type="text" style="display:none;" />
            <input type="password" style="display:none;" />
            <div class="relative w-64">
                <input type="search" autocomplete="new-password" wire:model.live="search" placeholder="<?php echo e(app()->getLocale() == 'en' ? 'Search operative...' : 'Cari pelaksana...'); ?>" class="w-full pl-10 pr-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/10 transition-all">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm flex items-center gap-3 shrink-0">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-bold text-slate-600"><?php echo e($users->count()); ?> <?php echo e(app()->getLocale() == 'en' ? 'Total Operatives' : 'Total Staf'); ?></span>
            </div>
            <a href="<?php echo e(route('users.create')); ?>" class="btn-premium group shrink-0">
                <i data-lucide="user-plus" class="w-4 h-4 transition-transform group-hover:rotate-12"></i>
                <span><?php echo e(app()->getLocale() == 'en' ? 'Add New Operative' : 'Tambah Pelaksana'); ?></span>
            </a>
        </div>
    </div>

    <!-- User Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'user-card-'.e($user->id).''; ?>wire:key="user-card-<?php echo e($user->id); ?>" class="glass-card p-8 group hover:-translate-y-2 hover:shadow-[0_30px_60px_rgba(0,0,0,0.12)] transition-all duration-500 relative overflow-hidden page-fade-in stagger-<?php echo e($loop->iteration % 5); ?> <?php echo e(!$user->is_active ? 'opacity-75 bg-slate-100/50' : ''); ?>">
                <!-- Status Indicator -->
                <div class="absolute top-0 right-0 p-4">
                    <div class="flex flex-col items-end gap-1.5">
                        <div class="flex items-center gap-2 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest <?php echo e($user->is_active ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-rose-50 text-rose-600 border border-rose-100'); ?>">
                            <?php echo e($user->is_active ? (app()->getLocale() == 'en' ? 'Authorized' : 'Diizinkan') : (app()->getLocale() == 'en' ? 'Suspended' : 'Ditangguhkan')); ?>

                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isOnline()): ?>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500 text-white rounded-full text-[8px] font-black uppercase tracking-widest shadow-[0_0_10px_rgba(16,185,129,0.4)]">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                                Online
                            </div>
                        <?php else: ?>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-400 rounded-full text-[8px] font-black uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                Offline
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Role Accent -->
                <div class="absolute top-0 left-0 w-1.5 h-full <?php echo e($user->role === 'owner' ? 'bg-indigo-500' : ($user->role === 'admin' ? 'bg-emerald-500' : 'bg-slate-300')); ?>"></div>
                
                <div class="flex items-start justify-between mb-8">
                    <div class="flex items-center gap-5">
                        <div class="relative">
                            <img src="<?php echo e($user->profile_photo_url); ?>" alt="<?php echo e($user->name); ?>" class="w-16 h-16 rounded-2xl object-cover shadow-xl shadow-slate-900/10 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 border-2 border-white">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->last_login_at && $user->last_login_at->isToday()): ?>
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full shadow-lg"></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors"><?php echo e($user->name); ?></h3>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->two_factor_secret): ?>
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500" title="2FA Enabled"></i>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-tight"><?php echo e($user->email); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 group-hover:bg-white group-hover:border-indigo-100 transition-all duration-500">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1"><?php echo e(app()->getLocale() == 'en' ? 'Productivity' : 'Produktivitas'); ?></p>
                        <div class="flex items-center gap-2">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 text-indigo-500"></i>
                            <span class="text-sm font-black text-slate-900"><?php echo e($user->invoices_count); ?> <?php echo e(app()->getLocale() == 'en' ? 'Invoices' : 'Faktur'); ?></span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 group-hover:bg-white group-hover:border-emerald-100 transition-all duration-500">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1"><?php echo e(app()->getLocale() == 'en' ? 'Status' : 'Status'); ?></p>
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-3.5 h-3.5 <?php echo e($user->isOnline() ? 'text-emerald-500' : 'text-slate-400'); ?>"></i>
                            <span class="text-[11px] font-black text-slate-900"><?php echo e($user->isOnline() ? (app()->getLocale() == 'en' ? 'Active Now' : 'Aktif Sekarang') : ($user->last_seen ? $user->last_seen->diffForHumans() : (app()->getLocale() == 'en' ? 'Never' : 'Tidak Pernah'))); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Footer / Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider
                            <?php echo e($user->role === 'owner' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 
                               ($user->role === 'admin' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-100')); ?>">
                            <?php echo e($user->role); ?>

                        </span>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <!-- Action 1: Sliders (Permissions settings) -->
                        <button 
                            wire:click="openPermissions(<?php echo e($user->id); ?>)"
                            @click="showPermissionsModal = true"
                            class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all relative flex items-center justify-center"
                            title="<?php echo e(app()->getLocale() == 'en' ? 'Atur Hak Akses Staf' : 'Atur Hak Akses Staf'); ?>"
                            wire:loading.class="opacity-50 pointer-events-none"
                            wire:target="openPermissions(<?php echo e($user->id); ?>)"
                        >
                            <i data-lucide="sliders" class="w-5 h-5" wire:loading.remove wire:target="openPermissions(<?php echo e($user->id); ?>)"></i>
                            <svg class="animate-spin w-5 h-5 text-indigo-600 hidden" wire:loading.class="!block" wire:target="openPermissions(<?php echo e($user->id); ?>)" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>

                        <!-- Action 2: Settings (Profile settings) -->
                        <button 
                            wire:click="openEditModal(<?php echo e($user->id); ?>)"
                            @click="showEditModal = true"
                            class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all relative flex items-center justify-center"
                            title="<?php echo e(app()->getLocale() == 'en' ? 'Edit Profil & Kredensial' : 'Edit Profil & Kredensial'); ?>"
                            wire:loading.class="opacity-50 pointer-events-none"
                            wire:target="openEditModal(<?php echo e($user->id); ?>)"
                        >
                            <i data-lucide="settings-2" class="w-5 h-5" wire:loading.remove wire:target="openEditModal(<?php echo e($user->id); ?>)"></i>
                            <svg class="animate-spin w-5 h-5 text-indigo-600 hidden" wire:loading.class="!block" wire:target="openEditModal(<?php echo e($user->id); ?>)" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>

                        <!-- Action 3: Shield Off (Suspend/Deactivate) -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->id !== auth()->id()): ?>
                            <button 
                                wire:click="confirmSuspend(<?php echo e($user->id); ?>)"
                                @click="showSuspendModal = true"
                                class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all relative flex items-center justify-center"
                                title="<?php echo e(app()->getLocale() == 'en' ? 'Nonaktifkan Akses Staf' : 'Nonaktifkan Akses Staf'); ?>"
                                wire:loading.class="opacity-50 pointer-events-none"
                                wire:target="confirmSuspend(<?php echo e($user->id); ?>)"
                            >
                                <i data-lucide="shield-off" class="w-5 h-5" wire:loading.remove wire:target="confirmSuspend(<?php echo e($user->id); ?>)"></i>
                                <svg class="animate-spin w-5 h-5 text-rose-600 hidden" wire:loading.class="!block" wire:target="confirmSuspend(<?php echo e($user->id); ?>)" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <?php echo $__env->make('livewire.modals.hak-akses-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('livewire.modals.keamanan-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('livewire.modals.nonaktifkan-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <script>
        window.addEventListener('notify', () => {
            setTimeout(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 50);
        });
        window.addEventListener('refreshLucide', () => {
            setTimeout(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 50);
        });
    </script>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/livewire/team-manager.blade.php ENDPATH**/ ?>