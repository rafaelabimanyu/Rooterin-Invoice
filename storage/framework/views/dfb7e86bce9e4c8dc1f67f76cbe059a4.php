<div class="relative" x-data="{ open: false }" x-init="$watch('open', value => { $dispatch('notification-toggle', { open: value }); if (value) { $dispatch('close-chat'); $dispatch('close-chatbot'); } })" @click.away="open = false" wire:poll.30s="loadNotifications">
    <!-- Trigger Button -->
    <button @click="open = !open" class="relative p-2.5 rounded-xl bg-slate-50 text-slate-400 hover:text-slate-900 hover:bg-white hover:shadow-sm transition-all group">
        <i data-lucide="bell" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
            <span class="absolute top-1.5 right-1.5 w-5 h-5 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white animate-pulse">
                <?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?>

            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </button>

    <!-- Mobile Backdrop -->
    <div 
        x-show="open" 
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[90] md:hidden" 
        @click="open = false" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
    ></div>

    <!-- Notification Popover / Bottom Sheet -->
    <div 
        x-show="open"
        x-ref="bottomSheet"
        x-data="{ 
            startY: 0, 
            translateY: 0, 
            isDragging: false,
            resetDrag() {
                this.isDragging = false;
                this.translateY = 0;
                if (this.$refs.bottomSheet) {
                    this.$refs.bottomSheet.style.transform = '';
                    this.$refs.bottomSheet.style.transition = '';
                }
            },
            handleTouchStart(e) {
                const scrollContainer = this.$refs.scrollContainer;
                const onHandle = e.target.closest('.mobile-drag-handle');
                if (onHandle || (scrollContainer && scrollContainer.scrollTop <= 0)) {
                    this.startY = e.touches[0].clientY;
                    this.isDragging = true;
                    if (this.$refs.bottomSheet) {
                        this.$refs.bottomSheet.style.transition = 'none';
                    }
                }
            },
            handleTouchMove(e) {
                if (!this.isDragging) return;
                const clientY = e.touches[0].clientY;
                const diffY = clientY - this.startY;
                const scrollContainer = this.$refs.scrollContainer;

                if (diffY > 0) {
                    if (scrollContainer && scrollContainer.scrollTop <= 0) {
                        e.preventDefault();
                        this.translateY = diffY;
                        if (this.$refs.bottomSheet) {
                            this.$refs.bottomSheet.style.transform = `translateY(${diffY}px)`;
                        }
                    }
                } else {
                    const onHandle = e.target.closest('.mobile-drag-handle');
                    if (onHandle) {
                        e.preventDefault();
                    } else {
                        this.isDragging = false;
                    }
                }
            },
            handleTouchEnd(e) {
                if (!this.isDragging) return;
                this.isDragging = false;
                
                if (this.translateY > 75) {
                    if (this.$refs.bottomSheet) {
                        this.$refs.bottomSheet.style.transition = 'transform 0.2s ease-out';
                        this.$refs.bottomSheet.style.transform = 'translateY(100%)';
                    }
                    setTimeout(() => {
                        open = false;
                        this.resetDrag();
                    }, 200);
                } else {
                    this.resetDrag();
                }
            }
        }"
        @touchstart="if (window.innerWidth < 768) handleTouchStart($event)"
        @touchmove="if (window.innerWidth < 768) handleTouchMove($event)"
        @touchend="if (window.innerWidth < 768) handleTouchEnd($event)"
        x-transition:enter="transition-all transform ease-out duration-300"
        x-transition:enter-start="translate-y-full md:opacity-0 md:translate-y-4 md:scale-95"
        x-transition:enter-end="translate-y-0 md:opacity-100 md:scale-100"
        x-transition:leave="transition-all transform ease-in duration-300"
        x-transition:leave-start="translate-y-0 md:opacity-100 md:scale-100"
        x-transition:leave-end="translate-y-full md:opacity-0 md:translate-y-4 md:scale-95"
        class="fixed inset-x-0 bottom-0 z-[100] w-full bg-white rounded-t-[32px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] overflow-hidden md:absolute md:left-auto md:-right-4 md:bottom-auto md:top-full md:mt-4 md:w-96 md:rounded-[32px] md:border md:border-slate-100 md:shadow-xl md:origin-top flex flex-col max-h-[85vh] md:max-h-[32rem]"
        x-cloak
    >
        <!-- Mobile drag handle with swipe-to-close micro-interaction -->
        <div class="mobile-drag-handle w-full flex justify-center pt-4 pb-2 md:hidden bg-slate-50/50 cursor-grab active:cursor-grabbing select-none" 
             @click="open = false">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full"></div>
        </div>

        <!-- Header -->
        <div class="px-6 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Notifications</h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5"><?php echo e($unreadCount); ?> Unread transmissions</p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                <button wire:click="markAllAsRead" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700 transition-colors">
                    Mark all read
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Body -->
        <div x-ref="scrollContainer" class="max-h-[60vh] md:max-h-96 overflow-y-auto custom-scrollbar">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $type = $notification->data['type'] ?? 'system';
                    $icon = match($type) {
                        'finance' => 'wallet',
                        'security' => 'shield-check',
                        'critical', 'overdue' => 'alert-octagon',
                        'reminder' => 'bell',
                        'due_today' => 'clock',
                        default => 'cpu'
                    };
                    $colorClass = match($type) {
                        'finance' => 'bg-emerald-50 text-emerald-600',
                        'security' => 'bg-amber-50 text-amber-600',
                        'critical', 'overdue' => 'bg-rose-50 text-rose-600',
                        'reminder' => 'bg-indigo-50 text-indigo-600',
                        'due_today' => 'bg-amber-50 text-amber-600',
                        default => 'bg-blue-50 text-blue-600'
                    };
                ?>
                <div 
                    class="px-6 py-5 border-b border-slate-50 hover:bg-slate-50/80 transition-all cursor-pointer group relative <?php echo e($notification->read_at ? 'opacity-60' : ''); ?>"
                    @click="$wire.markAsRead('<?php echo e($notification->id); ?>')"
                >
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notification->read_at): ?>
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-indigo-500 rounded-full shadow-[0_0_8px_rgba(79,70,229,0.5)]"></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl <?php echo e($colorClass); ?> shrink-0 flex items-center justify-center transition-transform group-hover:scale-110">
                            <i data-lucide="<?php echo e($icon); ?>" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="text-[13px] font-black text-slate-900 leading-tight truncate uppercase tracking-tight">
                                    <?php echo e($notification->data['title'] ?? 'Transmission Received'); ?>

                                </h4>
                                <span class="text-[9px] text-slate-400 font-bold uppercase whitespace-nowrap">
                                    <?php echo e($notification->created_at->diffForHumans(null, true)); ?>

                                </span>
                            </div>
                            <p class="text-[12px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                <?php echo e($notification->data['message'] ?? 'Data packet integrity verified.'); ?>

                            </p>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($notification->data['action_url'])): ?>
                                <div class="mt-3 flex items-center gap-2">
                                    <a href="<?php echo e($notification->data['action_url']); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[10px] font-black text-slate-900 uppercase tracking-widest hover:border-slate-900 transition-all">
                                        <?php echo e($notification->data['action_label'] ?? 'Execute View'); ?>

                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="py-16 md:py-20 px-10 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-[32px] shadow-inner flex items-center justify-center mx-auto mb-5 text-slate-300 relative">
                        <i data-lucide="bell-off" class="w-10 h-10 relative z-10"></i>
                        <div class="absolute inset-0 bg-indigo-500/5 rounded-[32px] blur-md"></div>
                    </div>
                    <h4 class="text-[13px] md:text-sm font-black text-slate-900 uppercase tracking-tight">All Caught Up!</h4>
                    <p class="text-[10px] md:text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-2 leading-relaxed max-w-[200px] mx-auto">No pending transmissions detected.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="p-6 bg-slate-50/50 border-t border-slate-100">
            <a href="<?php echo e(route('intelligence.index')); ?>" class="flex items-center justify-center gap-2 w-full py-3 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-900 uppercase tracking-widest hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm">
                View All Intelligence
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/livewire/navbar-notification.blade.php ENDPATH**/ ?>