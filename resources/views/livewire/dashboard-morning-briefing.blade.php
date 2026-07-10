<div class="mb-10 w-full">
    {{-- 1. High Priority Overdue Alerts Banner --}}
    @if(count($urgentAlerts) > 0)
        <div class="mb-6 relative overflow-hidden rounded-[24px] border border-rose-200/80 bg-rose-50/50 backdrop-blur-md p-6 shadow-sm animate-pulse-slow">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-rose-500"></div>
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center text-rose-600 shrink-0 shadow-sm relative">
                        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                        <span class="absolute top-0 right-0 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                        </span>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest block mb-1">High Priority Attention Required</span>
                        <h4 class="text-base font-black text-slate-900 font-jakarta uppercase tracking-tight">
                            Urgent Overdue Invoices Alert
                        </h4>
                        <p class="text-xs text-slate-500 font-semibold mt-1">
                            The following invoices are currently overdue with amounts exceeding Rp 10.000.000:
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($urgentAlerts as $alert)
                    <div class="glass-card p-4 border-rose-150 bg-white/60 hover:bg-white hover:shadow-md transition-all duration-300">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black text-rose-600 tracking-tight">{{ $alert['invoice_number'] }}</span>
                            <span class="text-[10px] font-bold text-slate-400">Due: {{ $alert['due_date'] }}</span>
                        </div>
                        <h5 class="text-sm font-black text-slate-800 truncate mb-1">{{ $alert['client_name'] }}</h5>
                        <p class="text-lg font-black text-slate-900 font-mono tracking-tight">{{ $alert['formatted_total'] }}</p>
                        
                        <div class="mt-4 flex items-center justify-end">
                            <a href="{{ route('invoices.show', $alert['id']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-all">
                                <span>Act Now</span>
                                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- 2. Morning Briefing Card --}}
    <div class="glass-card relative overflow-hidden p-8 border-slate-200/60 bg-gradient-to-br from-white via-slate-50/50 to-white shadow-sm transition-all duration-300 hover:shadow-md">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gold-400/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex items-center justify-between gap-6 border-b border-slate-100 pb-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gold-50 flex items-center justify-center text-gold-600 shrink-0 shadow-sm">
                    <i data-lucide="sparkles" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-[9px] font-black text-gold-600 uppercase tracking-widest block mb-1">Operational Assistant</span>
                    <h3 class="text-lg font-black text-slate-900 font-jakarta uppercase tracking-tight">
                        J&J GROUP Morning Briefing
                    </h3>
                </div>
            </div>
            
            <button wire:click="refreshBriefing" wire:loading.attr="disabled" class="btn-premium py-2 px-4 flex items-center gap-2 group text-xs">
                <i data-lucide="rotate-cw" class="w-3.5 h-3.5 group-hover:rotate-45 transition-transform" wire:loading.class="animate-spin" wire:target="refreshBriefing"></i>
                <span>Refresh Briefing</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                <div class="prose prose-sm text-slate-650 font-medium leading-relaxed max-w-none">
                    @if($briefing)
                        {!! nl2br(preg_replace('/\* \*\*(.*?)\*\*/', '<li><strong>$1</strong>', preg_replace('/\* /', '• ', e($briefing['text'])))) !!}
                    @else
                        <p class="text-slate-400 italic">No briefing summary available. Please click Refresh to generate one.</p>
                    @endif
                </div>
            </div>

            <div class="bg-slate-50/60 rounded-[24px] border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4">Briefing Metrics</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100/60">
                            <span class="text-xs text-slate-500 font-bold">Total Revenue</span>
                            <span class="text-sm font-black text-slate-900 font-mono">Rp {{ number_format($briefing['total_revenue'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100/60">
                            <span class="text-xs text-slate-500 font-bold">Overdue Invoices</span>
                            <span class="text-sm font-black text-rose-600 font-mono">Rp {{ number_format($briefing['overdue_amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs text-slate-500 font-bold">Pending Invoices</span>
                            <span class="text-sm font-black text-slate-700 font-mono">Rp {{ number_format($briefing['pending_amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-slate-150 flex items-center justify-between text-[10px] text-slate-400 font-bold">
                    <span>Last Updated:</span>
                    <span>{{ $lastGenerated ? \Carbon\Carbon::parse($lastGenerated)->format('d M Y H:i:s') : 'Never' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
