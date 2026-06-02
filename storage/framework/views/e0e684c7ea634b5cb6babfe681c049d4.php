<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['title' => __('Chronos Operational Calendar')] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="animate-fade-in-up">
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 xl:gap-8 w-full min-w-0">
            <!-- Left Section (75% Width): Main Calendar & Filters -->
            <div class="xl:col-span-3 flex flex-col min-w-0 w-full">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('chronos-calendar');

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3901563896-0', $__key);

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
            </div>

            <!-- Right Section (25% Width): Analytics Insights & Live Feed -->
            <div class="xl:col-span-1 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-1 gap-6 xl:gap-8 min-w-0 w-full">
                <?php echo $__env->make('chronos.components.sidebar-metrics', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        <?php $__env->startPush('styles'); ?>
        <style>
            .toast-enter { animation: toastSlideIn 0.3s ease-out forwards; }
            @keyframes toastSlideIn { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

            /* Premium sheen effect keyframes */
            @keyframes sheen {
                0% { transform: translateX(-150%) skewX(-15deg); }
                50% { transform: translateX(150%) skewX(-15deg); }
                100% { transform: translateX(150%) skewX(-15deg); }
            }
            .animate-sheen {
                animation: sheen 3.5s infinite ease-in-out;
            }

            /* Subtle glowing ring pulse for current date cell */
            .today-pulse {
                position: relative;
            }
            .today-pulse::after {
                content: '';
                position: absolute;
                inset: -2px;
                border-radius: 16px;
                border: 2.5px solid #4f46e5;
                opacity: 0;
                animation: todayPulse 3s infinite ease-out;
                pointer-events: none;
            }
            @keyframes todayPulse {
                0% {
                    transform: scale(1);
                    opacity: 0.6;
                }
                60% {
                    transform: scale(1.05);
                    opacity: 0;
                }
                100% {
                    transform: scale(1);
                    opacity: 0;
                }
            }

            /* Hide scrollbar utility */
            .scrollbar-none::-webkit-scrollbar {
                display: none;
            }
            .scrollbar-none {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
        <?php $__env->stopPush(); ?>

        <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.showToast = function(message, type = 'success') {
                    const existing = document.querySelector('.toast-box');
                    if (existing) existing.remove();

                    const toast = document.createElement('div');
                    toast.className = `toast-box fixed bottom-4 right-4 px-6 py-3.5 rounded-2xl text-white font-bold text-xs shadow-2xl z-[200] toast-enter flex items-center gap-2.5 ${type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'}`;
                    toast.innerHTML = `
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="${type === 'success' ? 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z'}"></path>
                        </svg>
                        <span>${message}</span>
                    `;
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(100%)';
                        toast.style.transition = 'all 0.3s ease';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                window.addEventListener('toast', event => {
                    const data = event.detail[0] || event.detail;
                    window.showToast(data.message, data.type);
                });
            });
        </script>
        <?php $__env->stopPush(); ?>
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
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/chronos/index.blade.php ENDPATH**/ ?>