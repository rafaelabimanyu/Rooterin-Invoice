@props(['title', 'value', 'change', 'icon', 'color', 'detail' => ''])

@php
    $gradients = [
        'gold' => 'from-gold-500/10 to-gold-500/5 text-gold-600 border-gold-500/10 shadow-gold-500/5',
        'amber' => 'from-amber-500/10 to-amber-500/5 text-amber-600 border-amber-500/10 shadow-amber-500/5',
        'emerald' => 'from-emerald-500/10 to-emerald-500/5 text-emerald-600 border-emerald-500/10 shadow-emerald-500/5',
        'rose' => 'from-rose-500/10 to-rose-500/5 text-rose-600 border-rose-500/10 shadow-rose-500/5',
    ];
    $grad = $gradients[$color] ?? $gradients['gold'];
@endphp

<div 
    class="glass-card p-6 md:p-7 group md:hover:-translate-y-3 md:hover:shadow-[0_20px_50px_rgba(200,157,60,0.15),0_0_8px_rgba(200,157,60,0.3)] md:hover:border-gold-500/30 active:scale-95 transition-all duration-500 relative overflow-hidden cursor-pointer"
    x-data="{ 
        displayValue: '0', 
        targetValue: '{{ preg_replace('/[^0-9.]/', '', $value) }}',
        isCurrency: {{ str_contains($value, 'Rp') ? 'true' : 'false' }}
    }"
    x-init="
        let start = 0;
        let end = parseFloat(targetValue);
        let duration = 1500;
        let startTime = null;
        
        const animate = (currentTime) => {
            if (!startTime) startTime = currentTime;
            const progress = Math.min((currentTime - startTime) / duration, 1);
            const current = Math.floor(progress * end);
            displayValue = isCurrency ? 'Rp ' + new Intl.NumberFormat('id-ID').format(current) : current;
            if (progress < 1) requestAnimationFrame(animate);
            else displayValue = '{{ $value }}';
        };
        setTimeout(() => requestAnimationFrame(animate), 500);
    }"
    @click="$dispatch('open-slide-over', { title: '{{ $title }}', content: `{{ $detail }}` })"
>
    <!-- Subtle Background Glow -->
    <div class="absolute -right-10 -top-10 w-32 h-32 bg-gold-500/5 blur-3xl group-hover:bg-gold-500/15 transition-colors duration-500 rounded-full"></div>

    <div class="flex items-center justify-between mb-6 relative z-10">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $grad }} flex items-center justify-center border shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
            <i data-lucide="{{ $icon }}" class="w-7 h-7"></i>
        </div>
        <div class="flex flex-col items-end">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black {{ str_contains($change, '+') ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} shadow-sm">
                <i data-lucide="{{ str_contains($change, '+') ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                {{ $change }}
            </span>
        </div>
    </div>
    
    <div class="relative z-10">
        <p class="text-[10px] md:text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] mb-2">{{ $title }}</p>
        <h3 class="text-2xl md:text-3xl font-black text-slate-900 font-jakarta tracking-tight group-hover:translate-x-1 transition-transform duration-500" x-text="displayValue">0</h3>
    </div>
    
    <!-- Shimmer Effect -->
    <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
</div>
