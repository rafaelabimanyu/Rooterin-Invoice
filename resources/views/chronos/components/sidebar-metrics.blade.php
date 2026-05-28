<!-- Metrics Card -->
<div class="glass-card p-6 border-slate-100 shadow-2xl shadow-rose-500/5 page-fade-in stagger-2 bg-white/80 backdrop-blur-md rounded-3xl">
    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-md mb-6">
        {{ __('Metrics Insights') }}
    </h3>
    
    <div class="space-y-6">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                {{ __('Total Active Arrears') }}
            </p>
            <h4 class="text-2xl font-black text-rose-500 font-jakarta tracking-tighter">
                Rp {{ number_format($activeArrears, 0, ',', '.') }}
            </h4>
        </div>
        
        <div class="pt-6 border-t border-slate-50">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                {{ __('Due This Week') }}
            </p>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                    </svg>
                </div>
                <h4 class="text-2xl font-black text-slate-900 font-jakarta tracking-tighter">
                    {{ $dueThisWeek }} <span class="text-sm text-slate-400 font-medium tracking-normal">{{ __('Invoices') }}</span>
                </h4>
            </div>
        </div>
    </div>
</div>

<!-- Activity Feed Card -->
<div class="glass-card p-6 border-slate-100 shadow-2xl shadow-indigo-500/5 page-fade-in stagger-3 bg-white/80 backdrop-blur-md rounded-3xl flex flex-col flex-1">
    <div class="flex items-center justify-between mb-8">
        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-md">
            {{ __('Live Feed') }}
        </h3>
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
                    <p class="text-[11px] font-bold text-slate-900 uppercase tracking-widest">
                        {{ __('No activities yet') }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
