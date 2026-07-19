<x-app-layout :title="__('dashboard.main_dashboard')">
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
        <!-- OWNER EXECUTIVE COMMAND CENTER -->
        <!-- AI Financial Advisory (Topmost Command Panel) -->
        <livewire:dashboard.financial-advisory lazy />


        <!-- TOP BAR: 4 KPI Cards -->
        <div class="mb-8">
            <livewire:owner-kpi :minimal="true" />
        </div>

        <!-- REVENUE PORTFOLIO ANALYSIS WIDGET -->
        <div class="mb-8 glass-card p-8 page-fade-in stagger-2 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gold-500/5 blur-xl group-hover:bg-gold-500/10 transition-colors duration-500 rounded-full"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-md flex items-center gap-2">
                        <i data-lucide="pie-chart" class="w-5 h-5 text-gold-600"></i>
                        {{ app()->getLocale() == 'en' ? 'Revenue Portfolio Analysis' : 'Analisis Portofolio Pendapatan' }}
                    </h3>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                        {{ app()->getLocale() == 'en' ? 'Lifetime revenue split between regular and partnership contracts' : 'Pembagian pendapatan seumur hidup antara reguler dan kontrak kemitraan' }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Combined Net' : 'Total Bersih Terkumpul' }}</span>
                    <h4 class="text-xl font-black text-slate-900 font-jakarta mt-0.5">Rp {{ number_format($regulerRevenue + $kemitraanRevenue, 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- Stacked Progress Bar -->
            <div class="w-full bg-slate-100 h-4 rounded-full overflow-hidden flex mb-4 shadow-inner">
                @if(($regulerRevenue + $kemitraanRevenue) > 0)
                    <div class="bg-slate-900 h-full transition-all duration-500" style="width: {{ $regulerPercentage }}%" title="Reguler: {{ $regulerPercentage }}%"></div>
                    <div class="bg-gold-500 h-full transition-all duration-500" style="width: {{ $kemitraanPercentage }}%" title="Kemitraan: {{ $kemitraanPercentage }}%"></div>
                @else
                    <div class="bg-slate-200 h-full w-full"></div>
                @endif
            </div>

            <!-- Legend and Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                <div class="flex items-center justify-between p-3.5 bg-slate-50/50 border border-slate-100 rounded-xl">
                    <div class="flex items-center gap-3">
                        <span class="w-3.5 h-3.5 rounded-md bg-slate-900 shadow-sm"></span>
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-slate-900">{{ app()->getLocale() == 'en' ? 'Regular Invoices' : 'Invoice Reguler' }}</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">{{ $regulerPercentage }}% {{ app()->getLocale() == 'en' ? 'Share' : 'Pangsa' }}</span>
                        </div>
                    </div>
                    <span class="text-xs font-black text-slate-900">Rp {{ number_format($regulerRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between p-3.5 bg-slate-50/50 border border-slate-100 rounded-xl">
                    <div class="flex items-center gap-3">
                        <span class="w-3.5 h-3.5 rounded-md bg-gold-500 shadow-sm"></span>
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-slate-900">{{ app()->getLocale() == 'en' ? 'Partnership Contracts' : 'Kontrak Kemitraan' }}</span>
                            <span class="text-[10px] text-gold-600 font-bold uppercase mt-0.5">{{ $kemitraanPercentage }}% {{ app()->getLocale() == 'en' ? 'Share' : 'Pangsa' }}</span>
                        </div>
                    </div>
                    <span class="text-xs font-black text-slate-900">Rp {{ number_format($kemitraanRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- MIDDLE BAR: Urgent Action (3 Klien Overdue teratas & AI Predictive Recommendation) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 page-fade-in stagger-2">
            <!-- 3 Klien Overdue Teratas -->
            <div class="lg:col-span-6 flex flex-col">
                @include('dashboard.partials.overdue-clients')
            </div>
            <!-- AI Predictive Recommendation -->
            <div class="lg:col-span-6 flex flex-col">
                @include('dashboard.partials.predictive-insights')
            </div>
        </div>

        <!-- BOTTOM BAR: Kinerja Unit Bisnis & Tren Arus Kas (3 bulan terakhir saja) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 page-fade-in stagger-3">
            <!-- Kinerja Unit Bisnis -->
            <div class="lg:col-span-6 flex flex-col">
                @include('dashboard.partials.business-units-summary')
            </div>
            <!-- Tren Arus Kas (3 Months) -->
            @include('dashboard.partials.cash-flow-chart')
        </div>

        <!-- PAYMENT ANALYTICS ROW: Analisis Metode & Arus Kas Terbaru -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 page-fade-in stagger-4">
            <!-- Analisis Metode Pembayaran -->
            <div class="lg:col-span-6 flex flex-col">
                @include('dashboard.partials.payment-methods-breakdown')
            </div>
            <!-- Arus Masuk Kas Terbaru -->
            <div class="lg:col-span-6 flex flex-col">
                @include('dashboard.partials.recent-payments')
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
                class="glass-card p-8 md:p-12 bg-gradient-to-br from-[#0F2A44] via-[#0B1E33] to-[#05111E] text-white relative overflow-hidden mb-10 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.3)]">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-10">
                    <div class="flex-1 space-y-6">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md rounded-full border border-white/10">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gold-100" x-text="greeting">
                            </p>
                        </div>

                        <h2 class="text-3xl md:text-5xl font-black font-jakarta tracking-tight leading-tight">
                            {{ __('dashboard.keep_up_work') }}<br><span class="text-gold-300">{{ auth()->user()->name }}</span>
                        </h2>

                        <div class="flex items-center gap-4 py-4 border-l-2 border-gold-500/30 pl-6">
                            <i data-lucide="quote" class="w-8 h-8 text-gold-400/50 -mt-4"></i>
                            <p class="text-sm md:text-lg italic text-gold-100/80 font-medium max-w-xl">
                                "{{ $randomQuote }}"
                            </p>
                        </div>

                        <!-- Quick Actions -->
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="{{ route('clients.create') }}"
                                class="group flex items-center gap-3 px-5 py-3 bg-white text-slate-950 rounded-xl font-bold text-xs hover:bg-gold-50 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-gold-100 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="user-plus" class="w-4 h-4 text-gold-600"></i>
                                </div>
                                {{ __('dashboard.new_client') }}
                            </a>
                            <a href="{{ route('invoices.create') }}"
                                class="group flex items-center gap-3 px-5 py-3 bg-gold-500/50 backdrop-blur-md border border-white/20 text-white rounded-xl font-bold text-xs hover:bg-white/20 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-white/20 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="file-edit" class="w-4 h-4"></i>
                                </div>
                                {{ __('dashboard.draft_invoice') }}
                            </a>
                            <a href="{{ route('receipts.create') }}"
                                 class="group flex items-center gap-3 px-5 py-3 bg-slate-800/50 backdrop-blur-md border border-white/10 text-white rounded-xl font-bold text-xs hover:bg-white/10 transition-all shadow-xl hover:-translate-y-1">
                                 <div class="p-1.5 bg-white/10 rounded-lg group-hover:scale-110 transition-transform">
                                     <i data-lucide="receipt" class="w-4 h-4 text-gold-400"></i>
                                 </div>
                                 {{ app()->getLocale() == 'en' ? 'Create Receipt' : 'Buat Kwitansi' }}
                             </a>
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex flex-col items-end gap-4">
                        <!-- Digital Clock -->
                        <div
                            class="p-6 bg-white/5 backdrop-blur-xl rounded-[32px] border border-white/10 text-right min-w-[200px]">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gold-300/60 mb-2">{{ __('dashboard.system_time') }}
                            </p>
                            <p class="text-4xl md:text-5xl font-black font-mono tracking-tighter" x-text="time"></p>
                            <p class="text-[11px] font-bold text-gold-200 mt-2">{{ date('l, F d, Y') }}</p>
                        </div>

                        <!-- Daily Goal Progress -->
                        <div
                            class="p-6 bg-white/5 backdrop-blur-xl rounded-[32px] border border-white/10 flex items-center gap-6 min-w-[200px]">
                            <div class="relative w-16 h-16">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent"
                                        class="text-white/10" />
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="6" fill="transparent"
                                        class="text-gold-400" stroke-dasharray="175.9"
                                        stroke-dashoffset="{{ 175.9 - (175.9 * $goalProgress / 100) }}"
                                        stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-black">{{ $goalProgress }}%</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gold-300/60 mb-1">{{ __('dashboard.daily_target') }}</p>
                                <p class="text-sm font-black">{{ $todayInvoicesCount }} / {{ $dailyGoal }} {{ __('ui.invoices') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Animated Background Particles (Simplified SVG) -->
                <div class="absolute inset-0 pointer-events-none opacity-20">
                    <div class="absolute top-10 left-10 w-64 h-64 bg-gold-500 rounded-full blur-[100px] animate-pulse">
                    </div>
                    <div class="absolute bottom-10 right-10 w-96 h-96 bg-gold-600 rounded-full blur-[120px] animate-pulse"
                        style="animation-delay: 2s;"></div>
                </div>
            </div>

            <!-- Productivity Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gold-50 flex items-center justify-center text-gold-600 group-hover:bg-gold-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="file-text" class="w-7 h-7 group-hover:animate-bounce"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-gold-50 text-gold-600 px-3 py-1 rounded-full uppercase tracking-widest">{{ __('ui.invoices') }}</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ __('dashboard.todays_invoices') }}</p>
                    <h3 class="text-4xl font-black text-slate-900 font-jakarta">{{ $todayInvoicesCount }}</h3>
                </div>

                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gold-50 flex items-center justify-center text-gold-600 group-hover:bg-gold-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="clipboard-check" class="w-7 h-7 group-hover:scale-110 transition-transform"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-gold-50 text-gold-600 px-3 py-1 rounded-full uppercase tracking-widest">{{ __('ui.receipts') }}</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ __('dashboard.receipts_logged') }}</p>
                    <h3 class="text-4xl font-black text-slate-900 font-jakarta">{{ $todayReceiptsCount }}</h3>
                </div>

                <div class="glass-card p-8 group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gold-50 flex items-center justify-center text-gold-600 group-hover:bg-gold-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <i data-lucide="file-edit" class="w-7 h-7 group-hover:rotate-12 transition-transform"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-gold-50 text-gold-600 px-3 py-1 rounded-full uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Drafts' : 'Draft' }}</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ app()->getLocale() == 'en' ? 'Pending Draft Invoices' : 'Draft Invoice Menunggu' }}</p>
                    <h3 class="text-4xl font-black text-slate-900 font-jakarta">{{ $draftInvoicesCount }}</h3>
                </div>
            </div>
        </div>

        <!-- Content Sections (Staff) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6 xl:gap-8 w-full min-w-0">
            <div class="lg:col-span-2 xl:col-span-3 flex flex-col min-w-0 w-full">
                @include('dashboard.partials.billing-operations')
            </div>
            @include('dashboard.partials.upcoming-billing')
        </div>
    @endif
    </div>

    @if(auth()->user()->hasFullAccess())
        <livewire:dashboard-chatbot />
    @endif
</x-app-layout>