<!-- Log Aktivitas Keamanan Tim -->
<div class="lg:col-span-4 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col min-w-0 max-h-[340px]">
    <div class="flex items-center justify-between mb-4 shrink-0">
        <div>
            <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                {{ __('dashboard.team_log_title') }}
            </h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                {{ __('dashboard.team_log_subtitle') }}
            </p>
        </div>
        <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600">
            <i data-lucide="shield-check" class="w-4.5 h-4.5"></i>
        </div>
    </div>

    <!-- Scrollable Timeline Feed -->
    <div class="overflow-y-auto pr-1 space-y-4 scroll-smooth mt-4 flex-1 scrollbar-thin">
        <div class="relative pl-6 border-l-2 border-slate-100 space-y-5 ml-1">
            @forelse($securityLogs as $log)
                <div class="relative">
                    <!-- Circle Timeline Bullet -->
                    <div class="absolute -left-[31px] top-1.5 w-2 h-2 rounded-full border-2 border-white
                        @if($log['type'] == 'success') bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]
                        @elseif($log['type'] == 'info') bg-gold-500 shadow-[0_0_8px_rgba(212,175,55,0.6)]
                        @else bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]
                        @endif
                    "></div>
                    
                    <!-- Header: Action & Time -->
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] font-black uppercase tracking-wider
                                @if($log['type'] == 'success') text-emerald-600
                                @elseif($log['type'] == 'info') text-gold-600
                                @else text-rose-600
                                @endif
                            ">
                                {{ __('dashboard.' . $log['action']) }}
                            </span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest bg-slate-50 px-1.5 py-0.5 rounded">
                                {{ $log['user'] }}
                            </span>
                        </div>
                        <span class="font-mono text-[9px] font-bold text-slate-400">
                            {{ $log['time']->diffForHumans() }}
                        </span>
                    </div>

                    <!-- Description -->
                    <p class="text-slate-600 text-xs font-semibold mt-1 leading-snug">
                        {{ __('dashboard.' . $log['details_key'], $log['details_params']) }}
                    </p>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 italic text-xs">
                    {{ __('dashboard.no_security_activities') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
