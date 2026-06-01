<!-- AI Financial Insights Card (Owner/Admin Only) -->
<div class="mb-12 page-fade-in stagger-1">
    <div class="bg-gradient-to-r from-indigo-50/50 to-blue-50/30 rounded-3xl border border-indigo-100/80 p-6 md:p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <!-- Sparkle design background element -->
        <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-200/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-5 relative z-10 flex-1 w-full">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-600 shrink-0 shadow-sm border border-indigo-200/30">
                <i data-lucide="bot" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <div class="space-y-2 flex-grow w-full">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[10px] font-black bg-indigo-600 text-white px-2.5 py-1 rounded-full uppercase tracking-wider">AI Financial Advisory</span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Real-time Analysis</span>
                </div>
                <h4 class="text-base font-bold text-slate-900 leading-snug font-jakarta">{{ app()->getLocale() == 'en' ? 'Financial Strategy & Cash Flow' : 'Taktik Keuangan & Arus Kas' }}</h4>
                <div x-data="{ expanded: false }" class="mt-2">
                    <p 
                        class="text-sm text-slate-600 leading-relaxed max-w-4xl transition-all duration-300 pr-2"
                        :class="expanded ? '' : 'line-clamp-2'"
                    >
                        {{ $aiInsight }}
                    </p>
                    <button 
                        @click="expanded = !expanded" 
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors mt-2 focus:outline-none"
                    >
                        <span x-text="expanded ? '{{ app()->getLocale() == 'en' ? 'Show Less' : 'Lihat Lebih Sedikit' }}' : '{{ app()->getLocale() == 'en' ? 'Show More' : 'Lihat Selengkapnya' }}'"></span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0 relative z-10 w-full md:w-auto">
            <a href="{{ route('dashboard', ['refresh_ai' => 1]) }}" class="w-full md:w-auto justify-center px-4 py-2.5 bg-white border border-slate-200 hover:border-indigo-200 hover:text-indigo-600 rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-2 active:scale-95">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                <span>{{ app()->getLocale() == 'en' ? 'Refresh Analysis' : 'Perbarui Analisis' }}</span>
            </a>
        </div>
    </div>
</div>
