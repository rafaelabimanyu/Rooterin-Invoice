<div class="glass-card p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300 h-full">
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                    {{ app()->getLocale() == 'en' ? 'AI Predictive Recommendations' : 'Rekomendasi Prediktif AI' }}
                </h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                    {{ app()->getLocale() == 'en' ? 'Automated risk and efficiency analysis' : 'Analisis risiko dan efisiensi otomatis' }}
                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-gold-50 flex items-center justify-center text-gold-600">
                <i data-lucide="brain-circuit" class="w-4.5 h-4.5"></i>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($insights as $insight)
                <div class="p-4 bg-slate-50/50 hover:bg-gold-50/20 rounded-2xl border border-slate-100/80 transition-all duration-300 relative overflow-hidden">
                    @if($insight['type'] === 'danger')
                        <div class="absolute top-0 left-0 w-1 h-full bg-rose-500/80"></div>
                        @php $iconBg = 'bg-rose-50 text-rose-600'; @endphp
                    @elseif($insight['type'] === 'warning')
                        <div class="absolute top-0 left-0 w-1 h-full bg-amber-500/80"></div>
                        @php $iconBg = 'bg-amber-50 text-amber-600'; @endphp
                    @else
                        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500/80"></div>
                        @php $iconBg = 'bg-emerald-50 text-emerald-600'; @endphp
                    @endif

                    <div class="flex items-start justify-between gap-4 mb-2">
                        <h4 class="text-xs font-bold text-slate-900 font-outfit">{{ $insight['title'] }}</h4>
                        <div class="w-6 h-6 rounded-lg {{ $iconBg }} flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $insight['icon'] }}" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium mb-2 leading-relaxed">
                        {{ $insight['message'] }}
                    </p>
                    <div class="p-2.5 bg-slate-100/50 rounded-lg border border-slate-100/50 text-[10px] text-slate-650 font-bold leading-relaxed">
                        <span class="text-slate-800 font-extrabold uppercase tracking-wider block mb-0.5 text-[9px]">{{ app()->getLocale() == 'en' ? 'Recommendation:' : 'Rekomendasi:' }}</span>
                        {{ $insight['recommendation'] }}
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400 italic text-xs">
                    {{ app()->getLocale() == 'en' ? 'No recommendations available.' : 'Tidak ada rekomendasi yang tersedia.' }}
                </div>
            @endforelse
        </div>
    </div>
</div>
