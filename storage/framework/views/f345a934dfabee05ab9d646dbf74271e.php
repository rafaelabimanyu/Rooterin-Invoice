<!-- Modal: Add/Edit Reminder -->
<template x-teleport="body">
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-data="{ 
             open: <?php if ((object) ('showModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'->value()); ?>')<?php echo e('showModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'); ?>')<?php endif; ?>, 
             localLoading: false,
             init() {
                 this.$watch('open', value => {
                     if (value) {
                         this.localLoading = true;
                         setTimeout(() => {
                             this.localLoading = false;
                         }, 350);
                     }
                 });
             }
         }"
         x-show="open"
         x-cloak
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="open = false"
        ></div>
        
        <!-- Modal Content Container (Mobile: Automatic Slide-Up 88vh Bottom Sheet | Desktop: Compact Center Modal) -->
        <div class="fixed md:relative bottom-0 md:bottom-auto left-0 right-0 md:left-auto md:right-auto w-full md:max-w-md h-[88vh] md:h-auto bg-white rounded-t-3xl md:rounded-[32px] rounded-b-none md:rounded-b-[32px] shadow-2xl md:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.2)] border border-slate-100 md:border-slate-100/80 z-[110] p-6 transform transition-all duration-300 ease-out flex flex-col"
             x-show="open"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-full md:translate-y-0 md:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
             x-transition:leave-end="opacity-0 translate-y-full md:translate-y-0 md:scale-95"
        >
            <!-- Drag Handle Pill Decorator (Mobile Only) -->
            <div class="md:hidden flex justify-center pb-3 cursor-pointer select-none" @click="open = false">
                <div class="w-12 h-1.5 bg-slate-200 hover:bg-slate-300 rounded-full transition-colors"></div>
            </div>

            <!-- Premium Loading Micro-Interactions -->
            <div x-show="localLoading" class="absolute inset-0 bg-white/80 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-t-3xl md:rounded-[32px] rounded-b-none md:rounded-b-[32px] transition-all duration-350" style="display: none;">
                <div class="flex flex-col items-center gap-4">
                    <div class="relative flex items-center justify-center">
                        <div class="w-12 h-12 border-4 border-indigo-500/20 rounded-full absolute"></div>
                        <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <p class="text-[10px] font-black text-indigo-650 uppercase tracking-[0.2em] animate-pulse">
                        <?php echo e(__('Processing Event...')); ?>

                    </p>
                </div>
            </div>
            <div wire:loading class="absolute inset-0 bg-white/80 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-t-3xl md:rounded-[32px] rounded-b-none md:rounded-b-[32px] transition-all duration-350">
                <div class="flex flex-col items-center gap-4">
                    <div class="relative flex items-center justify-center">
                        <div class="w-12 h-12 border-4 border-indigo-500/20 rounded-full absolute"></div>
                        <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <p class="text-[10px] font-black text-indigo-650 uppercase tracking-[0.2em] animate-pulse">
                        <?php echo e(__('Processing Event...')); ?>

                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50 select-none shrink-0">
                <h3 class="text-xs sm:text-base font-black text-slate-900 font-jakarta uppercase tracking-wider">
                    <?php echo e($selectedReminderId ? __('Edit Reminder') : __('Add Reminder')); ?>

                </h3>
                <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-655 transition-colors p-1.5 hover:bg-slate-50 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form wire:submit.prevent="saveReminder" class="flex-1 flex flex-col min-h-0 space-y-5">
                <!-- Scrollable Input Fields Container -->
                <div class="flex-1 overflow-y-auto pb-10 space-y-5 pr-1 md:pr-0 md:pb-0 scrollbar-none">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            <?php echo e(__('Reminder Title')); ?>

                        </label>
                        <input type="text" wire:model="reminderTitle" class="premium-input w-full" placeholder="<?php echo e(__('e.g. Work on Feature A & B')); ?>" required>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            <?php echo e(__('Description')); ?>

                        </label>
                        <textarea wire:model="reminderDescription" rows="3" class="premium-input w-full" placeholder="<?php echo e(__('Enter reminder details...')); ?>"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                <?php echo e(__('Category')); ?>

                            </label>
                            <select wire:model="reminderCategory" class="premium-input w-full">
                                <option value="internal"><?php echo e(__('Internal Dev')); ?></option>
                                <option value="meeting"><?php echo e(__('Meeting')); ?></option>
                                <option value="draft"><?php echo e(__('Draft / Planning')); ?></option>
                                <option value="overdue"><?php echo e(__('Overdue Task')); ?></option>
                                <option value="other"><?php echo e(__('Other')); ?></option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                <?php echo e(__('Color Indicator')); ?>

                            </label>
                            <select wire:model="reminderColor" class="premium-input w-full">
                                <option value="indigo"><?php echo e(__('Indigo')); ?></option>
                                <option value="emerald"><?php echo e(__('Emerald')); ?></option>
                                <option value="amber"><?php echo e(__('Amber')); ?></option>
                                <option value="rose"><?php echo e(__('Rose')); ?></option>
                                <option value="slate"><?php echo e(__('Slate')); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                <?php echo e(__('Start Date')); ?>

                            </label>
                            <input type="date" wire:model="selectedDate" class="premium-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                <?php echo e(__('End Date (Inclusive)')); ?>

                            </label>
                            <input type="date" wire:model="selectedEndDate" class="premium-input w-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                <?php echo e(__('Related Client (Optional)')); ?>

                            </label>
                            <select wire:model="reminderClientId" class="premium-input w-full">
                                <option value=""><?php echo e(__('None / General')); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <option value="<?php echo e($c->id); ?>"><?php echo e($c->nama_client); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                <?php echo e(__('Assignee (Staff)')); ?>

                            </label>
                            <select wire:model="reminderUserId" class="premium-input w-full">
                                <option value=""><?php echo e(__('Assign to Me')); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Sticky Footer Buttons -->
                <div class="pt-4 border-t border-slate-50 flex justify-between items-center gap-4 bg-white shrink-0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedReminderId): ?>
                        <button type="button" wire:click="deleteReminder(<?php echo e($selectedReminderId); ?>)" class="px-4.5 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[11px] transition-colors flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                            </svg>
                            <span>
                                <?php echo e(__('Delete')); ?>

                            </span>
                        </button>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <div class="flex gap-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4.5 py-2.5 text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-colors">
                            <?php echo e(__('Cancel')); ?>

                        </button>
                        <button type="submit" class="btn-premium py-2.5 px-5 text-[11px]">
                            <?php echo e(__('Save Reminder')); ?>

                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<!-- Modal: View Invoice Details -->
<template x-teleport="body">
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-data="{ open: <?php if ((object) ('showInvoiceModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showInvoiceModal'->value()); ?>')<?php echo e('showInvoiceModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showInvoiceModal'); ?>')<?php endif; ?> }"
         x-show="open"
         x-cloak
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="open = false"
        ></div>
        
        <!-- Modal Content Container -->
        <div class="relative bg-white rounded-[32px] shadow-[0_25px_60px_-15px_rgba(0,0,0,0.2)] w-full max-w-lg overflow-hidden transform border border-slate-100 z-10"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        >
            <!-- Premium Loading Micro-Interaction -->
            <div wire:loading wire:target="viewInvoiceDetails" class="absolute inset-0 bg-white/80 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-[32px] transition-all duration-355">
                <div class="flex flex-col items-center gap-4">
                    <div class="relative flex items-center justify-center">
                        <div class="w-12 h-12 border-4 border-indigo-500/20 rounded-full absolute"></div>
                        <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <p class="text-[10px] font-black text-indigo-650 uppercase tracking-[0.2em] animate-pulse">
                        <?php echo e(__('Loading Details...')); ?>

                    </p>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($viewedInvoice): ?>
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white bg-indigo-655/10 border border-indigo-200/40">
                            <svg class="w-8 h-8 text-indigo-655" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                            </svg>
                        </div>
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest
                            <?php echo e($viewedInvoice->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100/50' : ''); ?>

                            <?php echo e($viewedInvoice->status === 'overdue' ? 'bg-rose-50 text-rose-700 border border-rose-100/50' : ''); ?>

                            <?php echo e($viewedInvoice->status === 'draft' ? 'bg-amber-50 text-amber-700 border border-amber-100/50' : ''); ?>

                            <?php echo e($viewedInvoice->status === 'sent' ? 'bg-blue-50 text-blue-700 border border-blue-100/50' : ''); ?>

                        ">
                            <?php echo e($viewedInvoice->status); ?>

                        </span>
                    </div>
                    
                    <h3 class="text-2xl font-black text-slate-900 mb-2 font-jakarta"><?php echo e($viewedInvoice->invoice_number); ?></h3>
                    <p class="text-sm text-slate-500 font-medium mb-8">
                        <?php echo e(app()->getLocale() == 'en' ? 'Due on' : 'Jatuh tempo pada'); ?> <?php echo e($viewedInvoice->due_date->translatedFormat('d F Y')); ?>

                    </p>
                    
                    <div class="grid grid-cols-2 gap-8 py-8 border-y border-slate-100">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">
                                <?php echo e(__('Total Amount')); ?>

                            </p>
                            <p class="text-xl font-black text-slate-900 font-jakarta">Rp <?php echo e(number_format($viewedInvoice->total, 0, ',', '.')); ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">
                                <?php echo e(__('Client Entity')); ?>

                            </p>
                            <p class="text-sm font-bold text-slate-800"><?php echo e($viewedInvoice->client?->nama_client); ?></p>
                            <p class="text-[10px] text-slate-450 font-medium mt-0.5"><?php echo e($viewedInvoice->client?->nama_perusahaan); ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-4">
                    <button type="button" wire:click="$set('showInvoiceModal', false)" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                        <?php echo e(__('Close')); ?>

                    </button>
                    <a href="/invoices/<?php echo e($viewedInvoice->id); ?>" class="btn-premium">
                        <?php echo e(__('View Full Invoice')); ?>

                    </a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</template>

<!-- Floating Preview Card Tooltip -->
<div id="calendar-tooltip" class="absolute hidden z-[200] w-72 bg-white/95 backdrop-blur-md border border-slate-200/50 shadow-2xl rounded-2xl p-4 pointer-events-none transition-all duration-200">
    <div class="flex items-center justify-between mb-2">
        <span id="tooltip-badge" class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider"></span>
        <span id="tooltip-date" class="text-[9px] font-bold text-slate-400"></span>
    </div>
    <h4 id="tooltip-title" class="text-xs font-black text-slate-900 font-jakarta leading-snug mb-1"></h4>
    <p id="tooltip-desc" class="text-[10px] text-slate-500 font-medium leading-relaxed mb-2.5"></p>
    <div class="grid grid-cols-2 gap-2 pt-2.5 border-t border-slate-100">
        <div>
            <p class="text-[8px] font-black uppercase text-slate-400 mb-0.5">Client</p>
            <p id="tooltip-client" class="text-[9px] font-bold text-slate-800 truncate"></p>
        </div>
        <div>
            <p class="text-[8px] font-black uppercase text-slate-400 mb-0.5">Assignee</p>
            <p id="tooltip-staff" class="text-[9px] font-bold text-slate-800 truncate"></p>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/chronos/components/modal-reminder.blade.php ENDPATH**/ ?>