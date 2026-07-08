<div class="lg:col-span-1 xl:col-span-1 flex flex-col gap-6 xl:gap-8 min-w-0 w-full">
    @livewire('dashboard.upcoming-billing-horizon')
    @if($isStaff)
        <div class="glass-card p-10 flex flex-col w-full min-w-0">
            <div class="flex items-center justify-between mb-10">
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">{{ app()->getLocale() == 'en' ? 'Activity Feed' : 'Aliran Aktivitas' }}</h3>
                <span
                    class="px-3 py-1 bg-gold-50 text-gold-600 text-[10px] font-black rounded-full uppercase tracking-widest">Live</span>
            </div>

            <div class="flex-1 space-y-8 relative">
                <!-- Timeline Line -->
                <div class="absolute left-[11px] top-2 bottom-0 w-0.5 bg-slate-100"></div>

                @forelse($activityLogs as $log)
                    <div class="relative pl-10">
                        <div
                            class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-4 border-gold-500 flex items-center justify-center z-10">
                        </div>
                        <div class="space-y-1">
                            <p class="text-[13px] font-bold text-slate-800 leading-snug">{{ $log->description }}</p>
                            <p class="text-[11px] text-slate-400 font-medium">{{ $log->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 px-6">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="activity" class="w-8 h-8 text-slate-300"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-900 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'No activities recorded' : 'Tidak ada aktivitas tercatat' }}</p>
                        <p class="text-[11px] text-slate-400 mt-2">{{ app()->getLocale() == 'en' ? 'Activities will appear here once you start processing documents.' : 'Aktivitas akan muncul di sini setelah Anda mulai memproses dokumen.' }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-10 pt-10 border-t border-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 text-center">{{ app()->getLocale() == 'en' ? 'System Information' : 'Informasi Sistem' }}</p>
                <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-100 space-y-4">
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-500 font-bold">{{ app()->getLocale() == 'en' ? 'Node Identity' : 'Identitas Node' }}</span>
                        <span
                            class="font-black text-slate-900">STAFF-{{ str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-500 font-bold">{{ app()->getLocale() == 'en' ? 'Session Integrity' : 'Integritas Sesi' }}</span>
                        <span class="font-black text-emerald-500 flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> {{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Admin Stats Side Card (Moved to bottom grid) -->
    @endif
</div>
