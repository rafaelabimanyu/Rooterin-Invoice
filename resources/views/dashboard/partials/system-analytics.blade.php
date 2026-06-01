<!-- System Analytics (Widget Lama) -->
<div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-w-0">
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-xs">
                {{ app()->getLocale() == 'en' ? 'System Analytics' : 'Analitik Sistem' }}
            </h3>
            <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i data-lucide="activity" class="w-4.5 h-4.5"></i>
            </div>
        </div>

        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100/60 mt-4">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">
                {{ app()->getLocale() == 'en' ? 'Operational Load' : 'Beban Operasional' }}
            </p>
            <div class="flex items-end gap-2 mb-3">
                <span class="text-xl font-black text-slate-900 leading-none">
                    {{ app()->getLocale() == 'en' ? 'Efficient' : 'Optimal' }}
                </span>
            </div>
            <div class="w-full bg-slate-200 h-1 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full w-[100%] shadow-[0_0_6px_rgba(16,185,129,0.4)]"></div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 pt-4 border-t border-slate-50 flex flex-col gap-2">
        <div class="flex justify-between items-center text-[10px]">
            <span class="text-slate-400 font-bold">API Latency</span>
            <span class="font-mono font-black text-emerald-500">14ms</span>
        </div>
        <div class="flex justify-between items-center text-[10px]">
            <span class="text-slate-400 font-bold">Database</span>
            <span class="font-mono font-black text-emerald-500">99.9%</span>
        </div>
    </div>
</div>
