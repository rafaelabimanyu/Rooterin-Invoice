<x-app-layout>
    <div class="animate-fade-in-up">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8 page-fade-in">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight mb-2 uppercase">
                {{ __('ui.command_center') }}
            </h1>
            <p class="text-sm text-slate-500 font-medium tracking-tight">{{ __('ui.operational_overview') }}</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('invoices.create') }}" class="btn-premium group">
                <i data-lucide="plus" class="w-4 h-4 transition-transform group-hover:rotate-90"></i>
                <span>{{ __('ui.create_invoice') }}</span>
            </a>
        </div>
    </div>

    @if(!$isStaff)
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

        <!-- KPI Metrics (Admin/Owner Only) -->
        <livewire:owner-kpi :minimal="true" />
    @else
        <!-- Staff: Premium Interactive Dashboard -->
        <div class="mb-12 page-fade-in" x-data="{ 
                    time: '', 
                    greeting: '',
                    updateTime() {
                        const now = new Date();
                        this.time = now.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        const hour = now.getHours();
                        if (hour < 12) this.greeting = 'Good Morning';
                        else if (hour < 18) this.greeting = 'Good Afternoon';
                        else this.greeting = 'Good Evening';
                    }
                }" x-init="updateTime(); setInterval(() => updateTime(), 1000)">

            <!-- Hero Section -->
            <div
                class="glass-card p-8 md:p-12 bg-gradient-to-br from-indigo-900 via-indigo-800 to-slate-900 text-white relative overflow-hidden mb-10 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.3)]">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-10">
                    <div class="flex-1 space-y-6">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md rounded-full border border-white/10">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-100" x-text="greeting">
                            </p>
                        </div>

                        <h2 class="text-3xl md:text-5xl font-black font-jakarta tracking-tight leading-tight">
                            {{ app()->getLocale() == 'en' ? 'Keep up the great work,' : 'Terus tingkatkan kinerja luar biasa Anda,' }}<br><span class="text-indigo-300">{{ auth()->user()->name }}</span>
                        </h2>

                        <div class="flex items-center gap-4 py-4 border-l-2 border-indigo-500/30 pl-6">
                            <i data-lucide="quote" class="w-8 h-8 text-indigo-400/50 -mt-4"></i>
                            <p class="text-sm md:text-lg italic text-indigo-100/80 font-medium max-w-xl">
                                "{{ $randomQuote }}"
                            </p>
                        </div>

                        <!-- Quick Actions -->
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="{{ route('clients.create') }}"
                                class="group flex items-center gap-3 px-5 py-3 bg-white text-indigo-900 rounded-xl font-bold text-xs hover:bg-indigo-50 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-indigo-100 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="user-plus" class="w-4 h-4 text-indigo-600"></i>
                                </div>
                                {{ app()->getLocale() == 'en' ? 'New Client' : 'Klien Baru' }}
                            </a>
                            <a href="{{ route('invoices.create') }}"
                                class="group flex items-center gap-3 px-5 py-3 bg-indigo-600/50 backdrop-blur-md border border-white/20 text-white rounded-xl font-bold text-xs hover:bg-white/20 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-white/20 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="file-edit" class="w-4 h-4"></i>
                                </div>
                                {{ app()->getLocale() == 'en' ? 'Draft Invoice' : 'Draf Invoice' }}
                            </a>
                            <a href="{{ route('guide.index') }}?type=sop"
                                class="group flex items-center gap-3 px-5 py-3 bg-slate-800/50 backdrop-blur-md border border-white/10 text-white rounded-xl font-bold text-xs hover:bg-white/10 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-white/10 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="book-open" class="w-4 h-4"></i>
                                </div>
                                {{ app()->getLocale() == 'en' ? 'Operational SOP' : 'SOP Operasional' }}
                            </a>
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex flex-col items-end gap-4">
                        <!-- Digital Clock -->
                        <div
                            class="p-6 bg-white/5 backdrop-blur-xl rounded-[32px] border border-white/10 text-right min-w-[200px]">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-300/60 mb-2">{{ app()->getLocale() == 'en' ? 'System Time' : 'Waktu Sistem' }}
                            </p>
                            <p class="text-4xl md:text-5xl font-black font-mono tracking-tighter" x-text="time"></p>
                            <p class="text-[11px] font-bold text-indigo-200 mt-2">{{ date('l, F d, Y') }}</p>
                        </div>

                        <!-- Daily Goal Progress -->
                        <div
                            class="p-6 bg-white/5 backdrop-blur-xl rounded-[32px] border border-white/10 flex items-center gap-6 min-w-[200px]">
                            <div class="relative w-16 h-16">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent"
                                        class="text-white/10" />
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent"
                                        class="text-indigo-400" stroke-dasharray="175.9"
                                        stroke-dashoffset="{{ 175.9 - (175.9 * $goalProgress / 100) }}"
                                        stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-black">{{ $goalProgress }}%</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-300/60 mb-1">{{ app()->getLocale() == 'en' ? 'Daily Target' : 'Target Harian' }}</p>
                                <p class="text-sm font-black">{{ $todayInvoicesCount }} / {{ $dailyGoal }} {{ app()->getLocale() == 'en' ? 'Invoices' : 'Invoice' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Animated Background Particles (Simplified SVG) -->
                <div class="absolute inset-0 pointer-events-none opacity-20">
                    <div class="absolute top-10 left-10 w-64 h-64 bg-indigo-500 rounded-full blur-[100px] animate-pulse">
                    </div>
                    <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-500 rounded-full blur-[120px] animate-pulse"
                        style="animation-delay: 2s;"></div>
                </div>
            </div>

            <!-- Productivity Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="file-text" class="w-7 h-7 group-hover:animate-bounce"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Invoices' : 'Invoice' }}</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ app()->getLocale() == 'en' ? "Today's Invoices" : 'Invoice Hari Ini' }}</p>
                    <h3 class="text-4xl font-black text-slate-900 font-jakarta">{{ $todayInvoicesCount }}</h3>
                </div>

                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="clipboard-check" class="w-7 h-7 group-hover:scale-110 transition-transform"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Receipts' : 'Kuitansi' }}</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ app()->getLocale() == 'en' ? 'Receipts Logged' : 'Kuitansi Tercatat' }}</p>
                    <h3 class="text-4xl font-black text-slate-900 font-jakarta">{{ $todayReceiptsCount }}</h3>
                </div>

                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="zap" class="w-7 h-7 group-hover:rotate-12 transition-transform"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-amber-50 text-amber-600 px-3 py-1 rounded-full uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Revenue' : 'Pendapatan' }}</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ app()->getLocale() == 'en' ? 'Daily Output Value' : 'Nilai Output Harian' }}</p>
                    <h3 class="text-2xl font-black text-slate-900 font-jakarta truncate">Rp
                        {{ number_format($todayRevenue, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    @endif

    <!-- Content Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6 xl:gap-8 w-full min-w-0">
        <!-- Main Activity Table -->
        <div class="lg:col-span-2 xl:col-span-3 flex flex-col min-w-0 w-full">
            <div class="table-container page-fade-in stagger-5 overflow-hidden">
                <div
                    class="px-10 py-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between bg-slate-50/30 gap-4">
                    <div>
                        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">
                            {{ __('ui.billing_operations') }}
                        </h3>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                            {{ __('ui.latest_transactions') }}
                        </p>
                    </div>
                    <a href="{{ route('invoices.index') }}" class="btn-secondary group">
                        <span>{{ __('ui.view_all_invoices') }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="table-header">
                                <th class="px-10 py-5">{{ __('ui.reference') }}</th>
                                <th class="px-10 py-5">{{ __('ui.entity') }}</th>
                                <th class="px-10 py-5">{{ __('ui.volume') }}</th>
                                <th class="px-10 py-5">{{ __('ui.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentInvoices as $invoice)
                                <tr class="table-row-premium cursor-pointer group"
                                    onclick="window.location='{{ route('invoices.show', $invoice) }}'">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center group-hover:bg-slate-900 transition-colors duration-300">
                                                <i data-lucide="hash"
                                                    class="w-4 h-4 text-slate-400 group-hover:text-white"></i>
                                            </div>
                                            <span
                                                class="text-[13px] font-black text-slate-900 tracking-tight">{{ $invoice->invoice_number }}</span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-[14px] font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $invoice->client->nama_client }}</span>
                                            <span
                                                class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $invoice->client->nama_perusahaan }}</span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <span class="text-[15px] font-black text-slate-900 tracking-tighter">Rp
                                            {{ number_format($invoice->total, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-10 py-6">
                                        <x-badge :status="$invoice->status" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-10 py-32 text-center">
                                        <div class="flex flex-col items-center max-w-sm mx-auto">
                                            <div
                                                class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mb-8 relative">
                                                <i data-lucide="rocket" class="w-16 h-16 text-slate-200 animate-pulse"></i>
                                                <div
                                                    class="absolute top-0 right-0 w-8 h-8 bg-indigo-50 rounded-full flex items-center justify-center animate-bounce">
                                                    <i data-lucide="sparkles" class="w-4 h-4 text-indigo-400"></i>
                                                </div>
                                            </div>
                                            <h4
                                                class="text-xl font-black text-slate-900 font-jakarta uppercase tracking-tight mb-2">
                                                {{ app()->getLocale() == 'en' ? 'Ready for Lift-off?' : 'Siap Lepas Landas?' }}</h4>
                                            <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ app()->getLocale() == 'en' ? 'Your workspace is clean and ready. Start your first transaction of the day to see activity logs here.' : 'Ruang kerja Anda bersih dan siap. Mulai transaksi pertama Anda hari ini untuk melihat log aktivitas di sini.' }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side: Activity Timeline (Staff) or Stats (Admin) -->
        <div class="lg:col-span-1 xl:col-span-1 flex flex-col gap-6 xl:gap-8 min-w-0 w-full">
            @livewire('dashboard.upcoming-billing-horizon')
            @if($isStaff)
                <div class="glass-card p-10 flex flex-col w-full min-w-0">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">{{ app()->getLocale() == 'en' ? 'Activity Feed' : 'Aliran Aktivitas' }}</h3>
                        <span
                            class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full uppercase tracking-widest">Live</span>
                    </div>

                    <div class="flex-1 space-y-8 relative">
                        <!-- Timeline Line -->
                        <div class="absolute left-[11px] top-2 bottom-0 w-0.5 bg-slate-100"></div>

                        @forelse($activityLogs as $log)
                            <div class="relative pl-10">
                                <div
                                    class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-4 border-indigo-500 flex items-center justify-center z-10">
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
    </div>

    @if(!$isStaff)
        <!-- Bottom Grid Container -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6 page-fade-in stagger-6">
            <!-- Chart Arus Kas Bulanan -->
            <div class="lg:col-span-6 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-w-0">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                                {{ app()->getLocale() == 'en' ? 'Cash Flow & Financial Projections' : 'Arus Kas & Proyeksi Keuangan' }}
                            </h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                                {{ app()->getLocale() == 'en' ? 'Trends comparison over the last 6 months' : 'Tren perbandingan 6 bulan terakhir' }}
                            </p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i data-lucide="trending-up" class="w-4.5 h-4.5"></i>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-6 mt-2">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-sm bg-indigo-600 block"></span>
                            <span>{{ app()->getLocale() == 'en' ? 'Revenue (Incoming)' : 'Uang Masuk (Revenue)' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-sm bg-amber-500 block"></span>
                            <span>{{ app()->getLocale() == 'en' ? 'Receivables' : 'Proyeksi Piutang' }}</span>
                        </div>
                    </div>

                    <!-- Chart Container -->
                    <div class="relative flex items-end justify-between h-48 w-full border-b border-slate-100 pb-2 mt-8">
                        <!-- Y-Axis Gridlines -->
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-2">
                            <div class="w-full border-t border-slate-100/70"></div>
                            <div class="w-full border-t border-slate-100/70"></div>
                            <div class="w-full border-t border-slate-100/70"></div>
                            <div class="w-full border-t border-slate-100/70"></div>
                        </div>

                        <!-- Month: Dec -->
                        <div class="flex flex-col items-center flex-1 h-full justify-end z-10">
                            <div class="flex items-end gap-1.5 h-full w-full justify-center px-1">
                                <!-- Bar 1 (Revenue) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 53%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 85.0M
                                    </div>
                                </div>
                                <!-- Bar 2 (Receivables) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-amber-500 to-amber-450 hover:from-amber-600 hover:to-amber-500 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 19%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 30.0M
                                    </div>
                                </div>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 mt-2 tracking-wider uppercase">Dec</span>
                        </div>

                        <!-- Month: Jan -->
                        <div class="flex flex-col items-center flex-1 h-full justify-end z-10">
                            <div class="flex items-end gap-1.5 h-full w-full justify-center px-1">
                                <!-- Bar 1 (Revenue) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 59%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 95.0M
                                    </div>
                                </div>
                                <!-- Bar 2 (Receivables) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-amber-500 to-amber-450 hover:from-amber-600 hover:to-amber-500 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 28%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 45.0M
                                    </div>
                                </div>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 mt-2 tracking-wider uppercase">Jan</span>
                        </div>

                        <!-- Month: Feb -->
                        <div class="flex flex-col items-center flex-1 h-full justify-end z-10">
                            <div class="flex items-end gap-1.5 h-full w-full justify-center px-1">
                                <!-- Bar 1 (Revenue) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 75%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 120.0M
                                    </div>
                                </div>
                                <!-- Bar 2 (Receivables) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-amber-500 to-amber-450 hover:from-amber-600 hover:to-amber-500 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 16%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 25.0M
                                    </div>
                                </div>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 mt-2 tracking-wider uppercase">Feb</span>
                        </div>

                        <!-- Month: Mar -->
                        <div class="flex flex-col items-center flex-1 h-full justify-end z-10">
                            <div class="flex items-end gap-1.5 h-full w-full justify-center px-1">
                                <!-- Bar 1 (Revenue) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 69%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 110.0M
                                    </div>
                                </div>
                                <!-- Bar 2 (Receivables) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-amber-500 to-amber-450 hover:from-amber-600 hover:to-amber-500 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 34%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 55.0M
                                    </div>
                                </div>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 mt-2 tracking-wider uppercase">Mar</span>
                        </div>

                        <!-- Month: Apr -->
                        <div class="flex flex-col items-center flex-1 h-full justify-end z-10">
                            <div class="flex items-end gap-1.5 h-full w-full justify-center px-1">
                                <!-- Bar 1 (Revenue) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 91%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 145.0M
                                    </div>
                                </div>
                                <!-- Bar 2 (Receivables) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-amber-500 to-amber-450 hover:from-amber-600 hover:to-amber-500 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 25%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 40.0M
                                    </div>
                                </div>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 mt-2 tracking-wider uppercase">Apr</span>
                        </div>

                        <!-- Month: May -->
                        <div class="flex flex-col items-center flex-1 h-full justify-end z-10">
                            <div class="flex items-end gap-1.5 h-full w-full justify-center px-1">
                                <!-- Bar 1 (Revenue) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 100%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 160.0M
                                    </div>
                                </div>
                                <!-- Bar 2 (Receivables) -->
                                <div class="w-2.5 sm:w-3.5 bg-gradient-to-t from-amber-500 to-amber-450 hover:from-amber-600 hover:to-amber-500 rounded-t-sm transition-all duration-300 relative group/bar cursor-pointer" style="height: 22%">
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[10px] font-mono font-bold py-1 px-2 rounded opacity-0 invisible group-hover/bar:opacity-100 group-hover/bar:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-30">
                                        Rp 35.0M
                                    </div>
                                </div>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 mt-2 tracking-wider uppercase">May</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Log Aktivitas Keamanan Tim -->
            <div class="lg:col-span-4 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col min-w-0 max-h-[340px]">
                <div class="flex items-center justify-between mb-4 shrink-0">
                    <div>
                        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-sm">
                            {{ app()->getLocale() == 'en' ? 'Team Activity Log' : 'Log Aktivitas Tim' }}
                        </h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                            {{ app()->getLocale() == 'en' ? 'Staff operational audit trail' : 'Audit pengawasan operasional staf' }}
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="shield-check" class="w-4.5 h-4.5"></i>
                    </div>
                </div>

                <!-- Scrollable Timeline Feed -->
                <div class="overflow-y-auto pr-1 space-y-4 scroll-smooth mt-4 flex-1 scrollbar-thin">
                    @php
                        $securityLogs = [
                            [
                                'time' => '10 mins ago',
                                'user' => 'Rian Wijaya',
                                'role' => 'Staff',
                                'action' => 'Success Login',
                                'details' => 'Logged in from authorized IP: 192.168.1.45',
                                'type' => 'success'
                            ],
                            [
                                'time' => '45 mins ago',
                                'user' => 'Budi Santoso',
                                'role' => 'Staff',
                                'action' => 'Invoice Updated',
                                'details' => 'Updated total for INV-2026-089 (PT Sukses Jaya)',
                                'type' => 'info'
                            ],
                            [
                                'time' => '2 hours ago',
                                'user' => 'System Monitor',
                                'role' => 'Security',
                                'action' => 'Security Alert',
                                'details' => 'Detected high rate of API requests from Node-02',
                                'type' => 'danger'
                            ],
                            [
                                'time' => '3 hours ago',
                                'user' => 'Amalia Putri',
                                'role' => 'Staff',
                                'action' => 'Invoice Created',
                                'details' => 'Created draft invoice INV-2026-092',
                                'type' => 'info'
                            ],
                            [
                                'time' => '5 hours ago',
                                'user' => 'Budi Santoso',
                                'role' => 'Staff',
                                'action' => 'Success Login',
                                'details' => 'Session verified under token BST-4929',
                                'type' => 'success'
                            ],
                            [
                                'time' => '8 hours ago',
                                'user' => 'System Monitor',
                                'role' => 'Security',
                                'action' => 'Security Alert',
                                'details' => 'Failed admin login attempt from IP 182.253.140.23',
                                'type' => 'danger'
                            ]
                        ];
                    @endphp

                    <div class="relative pl-6 border-l-2 border-slate-100 space-y-5 ml-1">
                        @foreach($securityLogs as $log)
                            <div class="relative">
                                <!-- Circle Timeline Bullet -->
                                <div class="absolute -left-[31px] top-1.5 w-2 h-2 rounded-full border-2 border-white
                                    @if($log['type'] == 'success') bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]
                                    @elseif($log['type'] == 'info') bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]
                                    @else bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]
                                    @endif
                                "></div>
                                
                                <!-- Header: Action & Time -->
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] font-black uppercase tracking-wider
                                            @if($log['type'] == 'success') text-emerald-600
                                            @elseif($log['type'] == 'info') text-blue-600
                                            @else text-rose-600
                                            @endif
                                        ">
                                            {{ $log['action'] }}
                                        </span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest bg-slate-50 px-1.5 py-0.5 rounded">
                                            {{ $log['user'] }}
                                        </span>
                                    </div>
                                    <span class="font-mono text-[9px] font-bold text-slate-400">
                                        {{ $log['time'] }}
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="text-slate-600 text-xs font-semibold mt-1 leading-snug">
                                    {{ $log['details'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- System Analytics (Widget Lama) -->
            <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-w-0">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-xs">
                            {{ app()->getLocale() == 'en' ? 'System Analytics' : 'Analitik Sistem' }}
                        </h3>
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i data-lucide="activity" class="w-4 h-4"></i>
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
        </div>
    @endif
    </div>

    @if(auth()->user()->hasFullAccess())
        <livewire:dashboard-chatbot />
    @endif
</x-app-layout>