<x-app-layout>
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
        <!-- KPI Metrics (Admin/Owner Only) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="page-fade-in stagger-1">
                <x-stats-card :title="__('ui.total_billing')" value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}"
                    change="+12.5%" icon="bar-chart-3" color="indigo" detail="..." />
            </div>
            <div class="page-fade-in stagger-2">
                <x-stats-card :title="__('ui.amount_due')" value="Rp {{ number_format($pendingRevenue, 0, ',', '.') }}"
                    change="-5.2%" icon="clock" color="amber" detail="..." />
            </div>
            <div class="page-fade-in stagger-3">
                <x-stats-card :title="__('ui.clients')" value="{{ $totalClients }}" change="+3" icon="users" color="emerald"
                    detail="..." />
            </div>
            <div
                class="page-fade-in stagger-4 glass-card p-7 group hover:-translate-y-3 hover:shadow-[0_20px_50px_rgba(79,70,229,0.15)] hover:border-indigo-500/30 transition-all duration-500 relative overflow-hidden cursor-pointer">
                <!-- Efficiency card content -->
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-indigo-500/5 text-indigo-600 flex items-center justify-center border border-indigo-500/10 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                        <i data-lucide="check-circle-2" class="w-7 h-7"></i>
                    </div>
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-600 shadow-sm">
                        {{ __('ui.efficiency') }}
                    </span>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] mb-2">
                        {{ __('ui.collection_rate') }}
                    </p>
                    <div class="flex items-end justify-between mb-2">
                        <h3 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight">
                            {{ $totalInvoices > 0 ? round(($paidInvoicesCount / $totalInvoices) * 100) : 0 }}%
                        </h3>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden shadow-inner">
                        <div class="bg-indigo-600 h-full progress-bar-fill shadow-[0_0_12px_rgba(79,70,229,0.5)]"
                            style="width: {{ $totalInvoices > 0 ? ($paidInvoicesCount / $totalInvoices) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            Keep up the great work,<br><span class="text-indigo-300">{{ auth()->user()->name }}</span>
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
                                New Client
                            </a>
                            <a href="{{ route('invoices.create') }}"
                                class="group flex items-center gap-3 px-5 py-3 bg-indigo-600/50 backdrop-blur-md border border-white/20 text-white rounded-xl font-bold text-xs hover:bg-white/20 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-white/20 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="file-edit" class="w-4 h-4"></i>
                                </div>
                                Draft Invoice
                            </a>
                            <a href="{{ route('guide.index') }}?type=sop"
                                class="group flex items-center gap-3 px-5 py-3 bg-slate-800/50 backdrop-blur-md border border-white/10 text-white rounded-xl font-bold text-xs hover:bg-white/10 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-white/10 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="book-open" class="w-4 h-4"></i>
                                </div>
                                Operational SOP
                            </a>
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex flex-col items-end gap-4">
                        <!-- Digital Clock -->
                        <div
                            class="p-6 bg-white/5 backdrop-blur-xl rounded-[32px] border border-white/10 text-right min-w-[200px]">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-300/60 mb-2">System Time
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
                                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-300/60 mb-1">Daily
                                    Target</p>
                                <p class="text-sm font-black">{{ $todayInvoicesCount }} / {{ $dailyGoal }} Invoices</p>
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
                            class="text-[9px] font-black bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full uppercase tracking-widest">Invoices</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Today's Invoices</p>
                    <h3 class="text-4xl font-black text-slate-900 font-jakarta">{{ $todayInvoicesCount }}</h3>
                </div>

                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="clipboard-check" class="w-7 h-7 group-hover:scale-110 transition-transform"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full uppercase tracking-widest">Receipts</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Receipts Logged</p>
                    <h3 class="text-4xl font-black text-slate-900 font-jakarta">{{ $todayReceiptsCount }}</h3>
                </div>

                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="zap" class="w-7 h-7 group-hover:rotate-12 transition-transform"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-amber-50 text-amber-600 px-3 py-1 rounded-full uppercase tracking-widest">Revenue</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Daily Output Value</p>
                    <h3 class="text-2xl font-black text-slate-900 font-jakarta truncate">Rp
                        {{ number_format($todayRevenue, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    @endif

    <!-- Content Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-10">
        <!-- Main Activity Table -->
        <div class="lg:col-span-8 xl:col-span-9 flex flex-col">
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

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
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
                                                Ready for Lift-off?</h4>
                                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Your workspace is
                                                clean and ready. Start your first transaction of the day to see activity
                                                logs here.</p>
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
        <div class="lg:col-span-4 xl:col-span-3 space-y-10 flex flex-col">
            @livewire('dashboard.upcoming-billing-horizon')
            @if($isStaff)
                <div class="glass-card p-10 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">Activity Feed
                        </h3>
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
                                <p class="text-xs font-bold text-slate-900 uppercase tracking-widest">No activities recorded</p>
                                <p class="text-[11px] text-slate-400 mt-2">Activities will appear here once you start processing
                                    documents.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-10 pt-10 border-t border-slate-50">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 text-center">System
                            Information</p>
                        <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-100 space-y-4">
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-500 font-bold">Node Identity</span>
                                <span
                                    class="font-black text-slate-900">STAFF-{{ str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-500 font-bold">Session Integrity</span>
                                <span class="font-black text-emerald-500 flex items-center gap-1.5">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Admin Stats Side Card (Placeholder or mini charts) -->
                <div class="glass-card p-10">
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg mb-8">System Analytics</h3>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Operational Load</p>
                        <div class="flex items-end gap-3 mb-4">
                            <span class="text-3xl font-black text-slate-900">Optimal</span>
                        </div>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full w-[100%] shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>