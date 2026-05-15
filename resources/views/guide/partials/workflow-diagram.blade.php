<div class="mb-14 p-8 glass-card rounded-3xl border border-slate-100 bg-white/50">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest font-outfit">Workflow Overview</h3>
            <p class="text-xs text-slate-500 mt-1">Siklus operasional standar sistem</p>
        </div>
        <div class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-widest">
            {{ strtoupper($role) }}
        </div>
    </div>
    
    <div class="relative flex items-center justify-between w-full mt-4">
        <!-- Connecting Line -->
        <div class="absolute left-8 right-8 top-5 -translate-y-1/2 h-0.5 bg-slate-200 -z-10"></div>
        
        @foreach($guideData['workflow'] as $index => $step)
        <div class="flex flex-col items-center group relative cursor-pointer w-1/4">
            <!-- Bubble -->
            <div class="w-10 h-10 rounded-xl bg-white border-2 border-slate-200 flex items-center justify-center text-slate-400 font-bold text-sm group-hover:border-indigo-500 group-hover:text-indigo-600 group-hover:bg-indigo-50 shadow-sm group-hover:shadow-md transition-all z-10 group-hover:-translate-y-1 duration-300">
                {{ $index + 1 }}
            </div>
            
            <!-- Text -->
            <div class="mt-4 text-center">
                <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ is_array(__($step['label'])) ? $step['label'] : __($step['label']) }}</h4>
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wide mt-1">{{ is_array(__($step['desc'])) ? $step['desc'] : __($step['desc']) }}</p>
            </div>
            
            <!-- Tooltip -->
            <div class="absolute -top-12 opacity-0 group-hover:opacity-100 transition-all duration-300 bg-slate-900 text-white text-xs font-semibold py-1.5 px-3 rounded-lg shadow-xl whitespace-nowrap pointer-events-none translate-y-2 group-hover:translate-y-0">
                {{ __($step['step']) }}
                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-slate-900 rotate-45"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
