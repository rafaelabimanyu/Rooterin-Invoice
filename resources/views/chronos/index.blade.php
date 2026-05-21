<x-app-layout>
    <div class="animate-fade-in-up">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8 page-fade-in">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight mb-2 uppercase">
                Rooterin Chronos
            </h1>
            <p class="text-sm text-slate-500 font-medium tracking-tight">{{ app()->getLocale() == 'en' ? 'Billing Calendar & Operational Workflows' : 'Kalender Penagihan & Alur Kerja Operasional' }}</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex flex-wrap items-center gap-4 px-5 py-2.5 bg-white/40 backdrop-blur-lg rounded-2xl border border-white/20 shadow-lg shadow-indigo-500/5 select-none">
                <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl bg-indigo-50 border border-indigo-100/60">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(79,70,229,0.6)]"></span>
                    <span class="text-[9px] font-black uppercase text-indigo-650 tracking-wider">Internal</span>
                </div>
                <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl bg-emerald-50 border border-emerald-100/60">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                    <span class="text-[9px] font-black uppercase text-emerald-650 tracking-wider">{{ app()->getLocale() == 'en' ? 'Paid / Meeting' : 'Lunas / Meeting' }}</span>
                </div>
                <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl bg-amber-50 border border-amber-100/60">
                    <span class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]"></span>
                    <span class="text-[9px] font-black uppercase text-amber-650 tracking-wider">Draft / AI</span>
                </div>
                <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl bg-rose-50 border border-rose-100/60">
                    <span class="w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]"></span>
                    <span class="text-[9px] font-black uppercase text-rose-650 tracking-wider">{{ app()->getLocale() == 'en' ? 'Overdue' : 'Terlambat' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 xl:gap-8 w-full min-w-0">
        <!-- Left Section (75% Width): Main Calendar -->
        <div class="xl:col-span-3 flex flex-col min-w-0 w-full">
            @livewire('chronos-calendar')
        </div>

        <!-- Right Section (25% Width): Analytics Insights -->
        <div class="xl:col-span-1 flex flex-col gap-6 xl:gap-8 min-w-0 w-full">
            <!-- Metrics Card -->
            <div class="glass-card p-6 border-slate-100 shadow-2xl shadow-rose-500/5 page-fade-in stagger-2 bg-white/80 backdrop-blur-md rounded-3xl">
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-md mb-6">{{ app()->getLocale() == 'en' ? 'Metrics Insights' : 'Wawasan Metrik' }}</h3>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Active Arrears' : 'Total Tunggakan Aktif' }}</p>
                        <h4 class="text-2xl font-black text-rose-500 font-jakarta tracking-tighter">Rp {{ number_format($activeArrears, 0, ',', '.') }}</h4>
                    </div>
                    
                    <div class="pt-6 border-t border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Due This Week' : 'Jatuh Tempo Minggu Ini' }}</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                                </svg>
                            </div>
                            <h4 class="text-2xl font-black text-slate-900 font-jakarta tracking-tighter">{{ $dueThisWeek }} <span class="text-sm text-slate-400 font-medium tracking-normal">Invoices</span></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Feed Card -->
            <div class="glass-card p-6 border-slate-100 shadow-2xl shadow-indigo-500/5 page-fade-in stagger-3 bg-white/80 backdrop-blur-md rounded-3xl flex flex-col flex-1">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-md">{{ app()->getLocale() == 'en' ? 'Live Feed' : 'Aktivitas Terkini' }}</h3>
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>

                <div class="flex-1 relative">
                    <div class="absolute left-[11px] top-2 bottom-0 w-0.5 bg-slate-100"></div>
                    <div class="space-y-6">
                        @forelse($activities as $activity)
                            <div class="relative pl-8">
                                <div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-4 border-indigo-500 flex items-center justify-center z-10"></div>
                                <div class="space-y-1">
                                    <p class="text-[12px] font-bold text-slate-800 leading-snug">{{ $activity->description }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $activity->created_at->diffForHumans() }} - {{ $activity->user->name }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"></path>
                                    </svg>
                                </div>
                                <p class="text-[11px] font-bold text-slate-900 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'No activities yet' : 'Belum ada aktivitas' }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
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
