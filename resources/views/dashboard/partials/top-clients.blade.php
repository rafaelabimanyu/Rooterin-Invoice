@if($cardless ?? false)
    <div class="flex items-center justify-between mb-6">
        <div>
            <h4 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-xs">
                {{ app()->getLocale() == 'en' ? 'Top Clients by Revenue' : 'Klien Teratas Berdasarkan Pendapatan' }}
            </h4>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                {{ app()->getLocale() == 'en' ? 'Highest lifetime value clients' : 'Klien dengan kontribusi pendapatan terbesar' }}
            </p>
        </div>
        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
            <i data-lucide="award" class="w-4.5 h-4.5"></i>
        </div>
    </div>
    <div class="space-y-4">
        @forelse($topClients as $index => $client)
            <div class="flex items-center justify-between p-3.5 bg-slate-50/50 hover:bg-indigo-50/40 rounded-xl border border-slate-100/80 hover:border-indigo-100 transition-all duration-200 group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[13px] font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $client->nama_client }}</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $client->nama_perusahaan ?: '-' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-black text-slate-950 font-jakarta">
                        Rp {{ number_format($client->invoices_sum_total ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-400 italic text-xs">
                {{ app()->getLocale() == 'en' ? 'No revenue records found.' : 'Belum ada data pendapatan.' }}
            </div>
        @endforelse
    </div>
@else
    <div class="glass-card p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300">
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                        {{ app()->getLocale() == 'en' ? 'Top Clients by Revenue' : 'Klien Teratas Berdasarkan Pendapatan' }}
                    </h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                        {{ app()->getLocale() == 'en' ? 'Highest lifetime value clients' : 'Klien dengan kontribusi pendapatan terbesar' }}
                    </p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i data-lucide="award" class="w-4.5 h-4.5"></i>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($topClients as $index => $client)
                    <div class="flex items-center justify-between p-3.5 bg-slate-50/50 hover:bg-indigo-50/40 rounded-xl border border-slate-100/80 hover:border-indigo-100 transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $client->nama_client }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $client->nama_perusahaan ?: '-' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-slate-950 font-jakarta">
                                Rp {{ number_format($client->invoices_sum_total ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 italic text-xs">
                        {{ app()->getLocale() == 'en' ? 'No revenue records found.' : 'Belum ada data pendapatan.' }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endif
