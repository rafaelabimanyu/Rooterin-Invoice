<!-- AI Financial Insights Card (Owner/Admin Only) -->
<div class="mb-12 page-fade-in stagger-1">
    <div class="bg-gradient-to-r from-gold-50/50 to-amber-50/20 rounded-3xl border border-gold-100/80 p-6 md:p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <!-- Sparkle design background element -->
        <div class="absolute right-0 top-0 w-32 h-32 bg-gold-200/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-5 relative z-10 flex-1 w-full">
            <div class="w-12 h-12 rounded-2xl bg-gold-600/10 flex items-center justify-center text-gold-600 shrink-0 shadow-sm border border-gold-200/30">
                <i data-lucide="bot" class="w-6 h-6 text-gold-600"></i>
            </div>
            <div class="space-y-2 flex-grow w-full">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[10px] font-black bg-gold-500 text-slate-950 px-2.5 py-1 rounded-full uppercase tracking-wider">AI Financial Advisory</span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Real-time Analysis</span>
                </div>
                <h4 class="text-base font-bold text-slate-900 leading-snug font-jakarta">{{ $locale == 'en' ? 'Financial Strategy & Cash Flow' : 'Taktik Keuangan & Arus Kas' }}</h4>
                <div x-data="{ expanded: false }" class="mt-2">
                    <p 
                        class="text-sm text-slate-600 leading-relaxed max-w-4xl transition-all duration-300 pr-2 whitespace-pre-line"
                        :class="expanded ? '' : 'line-clamp-6'"
                    >
                        {!! nl2br(e($aiInsight)) !!}
                    </p>
                    <button 
                        @click="expanded = !expanded" 
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-gold-600 hover:text-gold-700 transition-colors mt-2 focus:outline-none"
                    >
                        <span x-text="expanded ? '{{ $locale == 'en' ? 'Show Less' : 'Lihat Lebih Sedikit' }}' : '{{ $locale == 'en' ? 'Show More' : 'Lihat Selengkapnya' }}'"></span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0 relative z-10 w-full md:w-auto">
            <button wire:click="refreshAnalysis" class="w-full md:w-auto justify-center px-4 py-2.5 bg-white border border-slate-200 hover:border-gold-200 hover:text-gold-600 rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-2 active:scale-95">
                <span wire:loading.remove wire:target="refreshAnalysis" class="flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    <span>{{ $locale == 'en' ? 'Refresh Analysis' : 'Perbarui Analisis' }}</span>
                </span>
                <span wire:loading wire:target="refreshAnalysis" class="flex items-center gap-2" x-cloak>
                    <svg class="animate-spin h-3.5 w-3.5 text-gold-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ $locale == 'en' ? 'Analyzing...' : 'Menganalisis...' }}</span>
                </span>
            </button>
        </div>
    </div>
</div>

<script>
    // Re-initialize Lucide Icons after Livewire updates
    document.addEventListener('livewire:navigated', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
