@php
    $htmlContent = nl2br(e(trim($aiInsight)));
    $formatHeader = function($text) {
        return preg_replace(
            '/(?:<br\s*\/?>\s*){0,2}\[(Analisis Data|Dampak Bisnis|Rekomendasi Aksi|Data Analysis|Business Impact|Action Recommendations)\](?:<br\s*\/?>\s*){0,2}/i',
            '<strong class="block text-slate-900 font-black mt-4 mb-1.5 text-[10px] uppercase tracking-wider text-gold-700">[$1]</strong>',
            $text
        );
    };
    $fullHtmlFormatted = $formatHeader($htmlContent);
@endphp

<!-- AI Financial Insights Card (Owner/Admin Only) -->
<div class="mb-6 page-fade-in stagger-1 w-full">
    <div class="bg-gradient-to-br from-gold-50/60 to-amber-50/20 rounded-3xl border border-gold-100/80 p-5 md:p-6 shadow-sm relative overflow-hidden">
        <!-- Sparkle design background element -->
        <div class="absolute right-0 top-0 w-32 h-32 bg-gold-200/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-4">
            <!-- Header Row: Icon + Badge + Title + Action Button in a neat unified bar -->
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <!-- Icon AI -->
                    <div class="w-10 h-10 rounded-xl bg-gold-500/10 flex items-center justify-center text-gold-600 shrink-0 border border-gold-200/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-gold-600">
                            <path d="M12 8V4H8"/>
                            <rect width="16" height="12" x="4" y="8" rx="2"/>
                            <path d="M2 14h2"/>
                            <path d="M20 14h2"/>
                            <path d="M15 13v2"/>
                            <path d="M9 13v2"/>
                        </svg>
                    </div>
                    
                    <!-- Title & Badges -->
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                            <span class="text-[8px] sm:text-[9px] font-black bg-gold-500 text-slate-950 px-2 py-0.5 rounded uppercase tracking-wider">AI Advisory</span>
                            <span class="text-[8px] sm:text-[9px] text-slate-400 font-bold uppercase tracking-widest">Real-time Analysis</span>
                        </div>
                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900 leading-tight font-jakarta">
                            {{ $locale == 'en' ? 'Financial Strategy & Cash Flow' : 'Taktik Keuangan & Arus Kas' }}
                        </h4>
                    </div>
                </div>

                <!-- Refresh Button (Desktop: Text + Icon, Mobile: Icon Only or compact button) -->
                <button wire:click="refreshAnalysis" class="shrink-0 p-2.5 sm:px-3 sm:py-1.5 bg-white border border-slate-200 hover:border-gold-200 hover:text-gold-600 text-slate-600 rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 active:scale-95">
                    <span wire:loading.remove wire:target="refreshAnalysis" class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5">
                            <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                            <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                            <path d="M16 16h5v5"/>
                        </svg>
                        <span class="hidden sm:inline">{{ $locale == 'en' ? 'Refresh' : 'Perbarui' }}</span>
                    </span>
                    <span wire:loading wire:target="refreshAnalysis" class="flex items-center gap-1.5" x-cloak>
                        <svg class="animate-spin h-3.5 w-3.5 text-gold-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="hidden sm:inline">{{ $locale == 'en' ? 'Analyzing...' : 'Menganalisis...' }}</span>
                    </span>
                </button>
            </div>

            <!-- Narrative Content Area -->
            <div class="w-full" x-data="{ expanded: false }">
                <!-- Text (when not loading) -->
                <div wire:loading.remove wire:target="refreshAnalysis" class="space-y-3">
                    <div class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-white/40 border border-gold-100/30 p-4 rounded-2xl pr-2 break-words max-w-full font-medium">
                        <div class="transition-all duration-300" :class="expanded ? '' : 'line-clamp-2'">
                            {!! $fullHtmlFormatted !!}
                        </div>
                    </div>
                    
                    <!-- Toggle Button -->
                    <div class="flex justify-start">
                        <button 
                            @click="expanded = !expanded" 
                            class="inline-flex items-center gap-1.5 text-[11px] font-black text-gold-600 hover:text-gold-700 transition-colors focus:outline-none uppercase tracking-wider"
                        >
                            <span x-text="expanded ? '{{ $locale == 'en' ? 'Show Less' : 'Lihat Lebih Sedikit' }}' : '{{ $locale == 'en' ? 'Show More' : 'Lihat Selengkapnya' }}'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Premium Shimmer Loading Skeleton -->
                <div wire:loading wire:target="refreshAnalysis" class="w-full bg-white/40 border border-gold-100/30 rounded-2xl p-4 space-y-3 animate-pulse" x-cloak>
                    <div class="h-2.5 bg-gold-200/50 rounded w-1/4"></div>
                    <div class="h-2 bg-slate-200/50 rounded w-full"></div>
                    <div class="h-2 bg-slate-200/50 rounded w-11/12"></div>
                    <div class="h-2 bg-slate-200/50 rounded w-4/5"></div>
                </div>
            </div>
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
