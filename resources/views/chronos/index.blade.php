<x-app-layout :title="__('Chronos Operational Calendar')">
    <div class="animate-fade-in-up">
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 xl:gap-8 w-full min-w-0">
            <!-- Left Section (75% Width): Main Calendar & Filters -->
            <div class="xl:col-span-3 flex flex-col min-w-0 w-full">
                @livewire('chronos-calendar')
            </div>

            <!-- Right Section (25% Width): Analytics Insights & Live Feed -->
            <div class="xl:col-span-1 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-1 gap-6 xl:gap-8 min-w-0 w-full">
                @include('chronos.components.sidebar-metrics')
            </div>
        </div>

        @push('styles')
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
        @endpush

        @push('scripts')
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
        @endpush
    </div>
</x-app-layout>
