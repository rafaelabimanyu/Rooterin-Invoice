<div class="glass-card p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300 h-full">
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                    {{ app()->getLocale() == 'en' ? 'Urgent Action: Top Overdue Clients' : 'Tindakan Mendesak: Klien Menunggak Teratas' }}
                </h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                    {{ app()->getLocale() == 'en' ? 'Top 3 clients requiring immediate follow-up' : '3 klien teratas yang membutuhkan tindak lanjut segera' }}
                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                <i data-lucide="alert-octagon" class="w-4.5 h-4.5"></i>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($overdueClients as $index => $client)
                <div class="flex items-center justify-between p-3.5 bg-rose-50/20 hover:bg-rose-50/40 rounded-xl border border-rose-100/50 hover:border-rose-200 transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-100/80 text-rose-700 flex items-center justify-center font-black text-xs group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[13px] font-bold text-slate-800 group-hover:text-rose-600 transition-colors">{{ $client->nama_client }}</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $client->nama_perusahaan ?: '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="text-xs font-black text-rose-600 font-jakarta">
                                Rp {{ number_format($client->invoices_sum_total ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('clients.show', $client->id) }}" class="p-1.5 rounded-lg bg-slate-100/80 text-slate-550 hover:bg-slate-900 hover:text-white transition-all">
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400 italic text-xs">
                    {{ app()->getLocale() == 'en' ? 'No overdue clients records.' : 'Tidak ada catatan klien menunggak.' }}
                </div>
            @endforelse
        </div>
    </div>
</div>
