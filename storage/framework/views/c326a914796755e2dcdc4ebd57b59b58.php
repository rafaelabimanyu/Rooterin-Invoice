<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['title' => app()->getLocale() == 'en' ? 'User Profile' : 'Profil Pengguna'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="animate-fade-in-up">
        <div class="mb-10 page-fade-in px-4 md:px-0">
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span><?php echo e(app()->getLocale() == 'en' ? 'System' : 'Sistem'); ?></span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600"><?php echo e(app()->getLocale() == 'en' ? 'User Profile' : 'Profil Pengguna'); ?></span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight truncate"><?php echo e(__('Profile Information')); ?></h1>
            <p class="text-sm text-slate-500 font-medium"><?php echo e(app()->getLocale() == 'en' ? 'Manage your identity, security preferences, and system localization.' : 'Kelola identitas, preferensi keamanan, dan lokalisasi sistem Anda.'); ?></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Column: Profile Summary -->
            <div class="lg:col-span-4 space-y-8">
                <div class="glass-card p-10 text-center relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                    
                    <!-- Profile Image -->
                    <div class="relative inline-block mb-6">
                        <img src="<?php echo e($user->profile_photo_url); ?>" alt="<?php echo e($user->name); ?>" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl mx-auto">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2">
                            <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $user->role,'label' => $user->role_badge['label']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->role),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->role_badge['label'])]); ?>
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
                        </div>
                    </div>

                    <h3 class="text-xl font-black text-slate-900 font-jakarta mt-4"><?php echo e($user->name); ?></h3>
                    <p class="text-sm text-slate-500 font-medium mb-8"><?php echo e($user->email); ?></p>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-4 text-left">
                            <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-600">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Member Since' : 'Anggota Sejak'); ?></p>
                                <p class="text-sm font-bold text-slate-900"><?php echo e($user->created_at->format('M d, Y')); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Security Quick Info -->
                    <div class="mt-10 pt-8 border-t border-slate-50 space-y-6 text-left">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4"><?php echo e(app()->getLocale() == 'en' ? 'Security Overview' : 'Ikhtisar Keamanan'); ?></p>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                                        <span class="text-xs font-bold text-emerald-900"><?php echo e(app()->getLocale() == 'en' ? '2FA Protected' : 'Dilindungi 2FA'); ?></span>
                                    </div>
                                    <div class="relative inline-flex items-center cursor-pointer">
                                        <div class="w-8 h-4 bg-emerald-500 rounded-full transition-colors"></div>
                                        <div class="absolute left-4 w-3 h-3 bg-white rounded-full transition-transform"></div>
                                    </div>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Last Login' : 'Login Terakhir'); ?></span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-900"><?php echo e($user->last_login_at ? $user->last_login_at->diffForHumans() : (app()->getLocale() == 'en' ? 'Never' : 'Belum Pernah')); ?></p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5"><?php echo e($user->last_login_ip ?? '0.0.0.0'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Log -->
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4"><?php echo e(app()->getLocale() == 'en' ? 'Recent Activity' : 'Aktivitas Terbaru'); ?></p>
                            <div class="space-y-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="flex gap-3">
                                        <div class="shrink-0 w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5"></div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-700 leading-tight"><?php echo e($log->description); ?></p>
                                            <p class="text-[10px] text-slate-400 mt-1 font-medium"><?php echo e($log->created_at->diffForHumans()); ?></p>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <p class="text-xs text-slate-400 italic"><?php echo e(app()->getLocale() == 'en' ? 'No recent activity recorded.' : 'Tidak ada aktivitas terbaru yang tercatat.'); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Action Center -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Profile Info -->
                <div class="glass-card p-8 md:p-10">
                    <?php echo $__env->make('profile.partials.update-profile-information-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <!-- Password Update -->
                <div class="glass-card p-8 md:p-10">
                    <?php echo $__env->make('profile.partials.update-password-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <!-- Danger Zone -->
                <div class="p-8 md:p-10 bg-rose-50/30 rounded-[32px] border-2 border-dashed border-rose-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-3 bg-rose-100 text-rose-600 rounded-2xl">
                            <i data-lucide="trash-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-slate-900 font-jakarta"><?php echo e(app()->getLocale() == 'en' ? 'Security: Danger Zone' : 'Keamanan: Zona Bahaya'); ?></h4>
                            <p class="text-sm text-slate-500 font-medium"><?php echo e(app()->getLocale() == 'en' ? 'Irreversible actions that will remove your access permanently.' : 'Tindakan tidak dapat dibatalkan yang akan menghapus akses Anda secara permanen.'); ?></p>
                        </div>
                    </div>
                    <?php echo $__env->make('profile.partials.delete-user-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>
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
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/profile/edit.blade.php ENDPATH**/ ?>