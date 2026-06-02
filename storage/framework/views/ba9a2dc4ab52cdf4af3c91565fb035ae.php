<!-- Modal 3: Advanced Profile / Credentials Management Modal -->
<div x-show="showEditModal" 
     x-transition:enter="transition ease-out duration-200" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition ease-in duration-150" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4" 
     x-cloak 
     style="display: none;" 
     <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'modal-edit'; ?>wire:key="modal-edit"
>
    <!-- BACKDROP OVERLAY (Latar Hitam Transparan) -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showEditModal = false"></div>

    <!-- MAIN MODAL CONTAINER (Wajib Relative & Overflow Hidden) -->
    <div x-show="showEditModal" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95" 
         class="relative bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden z-10"
    >
        <!-- TOTAL OVERLAY LOADING SCREEN (Mengunci Sempurna di Dalam Box Putih) -->
        <div wire:loading wire:target="openEditModal, saveEdit, generatePassword, deleteUser" class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center">
            <div class="flex flex-col items-center gap-3">
                <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-xs font-bold text-slate-600 tracking-wide">Sinkronisasi Data...</span>
            </div>
        </div>

        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
            <div class="flex items-center gap-4">
                <div class="p-2.5 bg-slate-900 text-white rounded-xl">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight"><?php echo e(app()->getLocale() == 'en' ? 'Security & Command Center' : 'Pusat Keamanan & Kontrol'); ?></h2>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">
                        ID: <?php echo e($editingUser?->id); ?> • 
                        Status: <span class="<?php echo e($editingIsActive ? 'text-emerald-600' : 'text-rose-600'); ?>"><?php echo e($editingIsActive ? (app()->getLocale() == 'en' ? 'Authorized' : 'Diizinkan') : (app()->getLocale() == 'en' ? 'Suspended' : 'Ditangguhkan')); ?></span> • 
                        Presence: <span class="<?php echo e($editingUser?->isOnline() ? 'text-emerald-500' : 'text-slate-400'); ?>"><?php echo e($editingUser?->isOnline() ? 'Online' : 'Offline'); ?></span>
                    </p>
                </div>
            </div>
            <button @click="showEditModal = false" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto space-y-6 flex-1 bg-white">
            <!-- Two Column Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Settings -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <img src="<?php echo e($editingUser?->profile_photo_url); ?>" class="w-16 h-16 rounded-2xl object-cover shadow-lg border-2 border-slate-100">
                        <div>
                            <h3 class="text-base font-black text-slate-900"><?php echo e($editingName); ?></h3>
                            <p class="text-xs text-slate-500 font-medium"><?php echo e($editingEmail); ?></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"><?php echo e(app()->getLocale() == 'en' ? 'Full Identity' : 'Identitas Lengkap'); ?></label>
                            <input type="text" wire:model="editingName" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 text-xs">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"><?php echo e(app()->getLocale() == 'en' ? 'Email Address' : 'Alamat Email'); ?></label>
                            <input type="email" wire:model="editingEmail" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"><?php echo e(app()->getLocale() == 'en' ? 'Access Level' : 'Tingkat Akses'); ?></label>
                                <select wire:model="editingRole" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 uppercase text-xs tracking-widest">
                                    <option value="owner">Owner</option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"><?php echo e(app()->getLocale() == 'en' ? 'Account Status' : 'Status Akun'); ?></label>
                                <div class="flex items-center h-full">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="editingIsActive" class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        <span class="ms-2 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                            <?php echo e($editingIsActive ? (app()->getLocale() == 'en' ? 'Active' : 'Aktif') : (app()->getLocale() == 'en' ? 'Suspended' : 'Ditangguhkan')); ?>

                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Center -->
                    <div class="p-6 bg-slate-900 rounded-2xl text-white space-y-4 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -mr-12 -mt-12 blur-xl"></div>
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-indigo-400 mb-0.5"><?php echo e(app()->getLocale() == 'en' ? 'Overrule Password' : 'Ganti Kata Sandi'); ?></h4>
                            <p class="text-[10px] text-slate-400 font-medium"><?php echo e(app()->getLocale() == 'en' ? "Reset this operative's credentials manually." : 'Reset kredensial pelaksana ini secara manual.'); ?></p>
                        </div>
                        <div class="relative">
                            <input :type="$wire.showPassword ? 'text' : 'password'" wire:model="editingPassword" class="w-full bg-white/5 border-white/10 rounded-xl py-2 px-4 text-xs font-mono tracking-wider focus:border-indigo-500 focus:ring-0 transition-all text-white" placeholder="<?php echo e(app()->getLocale() == 'en' ? 'Enter new password...' : 'Masukkan kata sandi baru...'); ?>">
                            <button type="button" wire:click="$toggle('showPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showPassword): ?>
                                    <i data-lucide="eye-off" class="w-3.5 h-3.5"></i>
                                <?php else: ?>
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="generatePassword()" class="flex-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-1.5 text-white">
                                <i data-lucide="sparkles" class="w-3 h-3"></i>
                                <?php echo e(app()->getLocale() == 'en' ? 'Generate' : 'Buat'); ?>

                            </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingPassword): ?>
                                <button type="button" @click="navigator.clipboard.writeText($wire.editingPassword); $wire.copyPassword()" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-1.5 text-indigo-300">
                                    <i data-lucide="<?php echo e($copied ? 'check' : 'copy'); ?>" class="w-3 h-3 <?php echo e($copied ? 'text-emerald-400' : ''); ?>"></i>
                                    <span><?php echo e($copied ? (app()->getLocale() == 'en' ? 'Copied' : 'Disalin') : (app()->getLocale() == 'en' ? 'Copy' : 'Salin')); ?></span>
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- Archive Option -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingUser?->id !== auth()->id()): ?>
                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                            <div class="space-y-0.5">
                                <h4 class="text-xs font-black text-rose-600 uppercase tracking-widest"><?php echo e(app()->getLocale() == 'en' ? 'Purge Record' : 'Hapus Akun'); ?></h4>
                                <p class="text-[9px] text-slate-400 font-medium"><?php echo e(app()->getLocale() == 'en' ? 'Permanently remove this team member.' : 'Hapus data pelaksana ini secara permanen.'); ?></p>
                            </div>
                            <button type="button" wire:confirm="<?php echo e(app()->getLocale() == 'en' ? 'Deep purge this operative data?' : 'Hapus permanen data pelaksana ini?'); ?>" wire:click="deleteUser(<?php echo e($editingUser?->id ?? 0); ?>)" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-bold transition-all">
                                <?php echo e(app()->getLocale() == 'en' ? 'Delete Operative' : 'Hapus Pelaksana'); ?>

                            </button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Right Column: Monitoring & Timeline -->
                <div class="space-y-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4"><?php echo e(app()->getLocale() == 'en' ? 'Operative Intelligence' : 'Informasi Pelaksana'); ?></h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 shadow-sm">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5"><?php echo e(app()->getLocale() == 'en' ? 'Last Sync' : 'Sinkronisasi Terakhir'); ?></p>
                                <p class="text-xs font-black text-slate-900"><?php echo e($editingUser?->last_seen ? $editingUser->last_seen->format('M d, H:i') : (app()->getLocale() == 'en' ? 'Never' : 'Tidak Pernah')); ?></p>
                                <p class="text-[9px] text-slate-400 font-bold">IP: <?php echo e($editingUser?->last_login_ip ?? 'N/A'); ?></p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 shadow-sm">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5"><?php echo e(app()->getLocale() == 'en' ? 'Pass Age' : 'Umur Sandi'); ?></p>
                                <p class="text-xs font-black text-slate-900"><?php echo e($editingUser?->last_password_change_at ? $editingUser->last_password_change_at->diffForHumans() : (app()->getLocale() == 'en' ? 'Never' : 'Tidak Pernah')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col min-h-0">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4"><?php echo e(app()->getLocale() == 'en' ? 'Activity Timeline' : 'Lini Masa Aktivitas'); ?></h4>
                        <!-- Scrollable Timeline -->
                        <div class="overflow-y-auto max-h-[400px] pr-2 space-y-4 custom-scrollbar">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $selectedUserLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="flex gap-3 relative">
                                    <div class="shrink-0 w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center relative z-10">
                                        <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div class="flex-1 pt-0.5">
                                        <p class="text-xs font-bold text-slate-800 leading-tight"><?php echo e($log['desc']); ?></p>
                                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1"><?php echo e($log['time']); ?></p>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <div class="text-center py-4">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider"><?php echo e(app()->getLocale() == 'en' ? 'No recent activities recorded' : 'Tidak ada aktivitas tercatat baru-baru ini'); ?></p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
            <button type="button" @click="showEditModal = false" class="text-xs font-bold text-slate-500 hover:text-slate-800"><?php echo e(app()->getLocale() == 'en' ? 'Cancel' : 'Batal'); ?></button>
            <button type="button" wire:click="saveEdit" class="btn-premium px-6 py-2.5 rounded-lg text-xs uppercase tracking-wider font-bold" wire:loading.attr="disabled" wire:target="saveEdit">
                <span wire:loading.remove wire:target="saveEdit" class="flex items-center justify-center">
                    <span><?php echo e(app()->getLocale() == 'en' ? 'Deploy Changes' : 'Terapkan Perubahan'); ?></span>
                    <i data-lucide="send" class="w-4 h-4 ml-1.5"></i>
                </span>
                <svg class="animate-spin w-4 h-4 text-white hidden" wire:loading.class="!block" wire:target="saveEdit" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/livewire/modals/keamanan-modal.blade.php ENDPATH**/ ?>