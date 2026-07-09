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
        @if(count($insights) > 0)
            <div class="mb-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up stagger-1">
                @foreach($insights as $insight)
                    <div class="glass-card relative overflow-hidden p-6 border-slate-200/60 transition-all duration-300 hover:shadow-lg">
                        @if($insight['type'] === 'danger')
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-rose-500/80"></div>
                            @php $iconBg = 'bg-rose-50 text-rose-600'; @endphp
                        @elseif($insight['type'] === 'warning')
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500/80"></div>
                            @php $iconBg = 'bg-amber-50 text-amber-600'; @endphp
                        @else
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500/80"></div>
                            @php $iconBg = 'bg-emerald-50 text-emerald-600'; @endphp
                        @endif

                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">AI Predictive Recommendation</span>
                                <h4 class="text-sm font-bold text-slate-900 font-outfit">{{ $insight['title'] }}</h4>
                            </div>
                            <div class="w-8 h-8 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $insight['icon'] }}" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 font-medium mb-3 leading-relaxed">
                            {{ $insight['message'] }}
                        </p>
                        <div class="p-3 bg-slate-55 rounded-xl border border-slate-100 text-[11px] text-slate-650 font-medium leading-relaxed">
                            <span class="font-bold text-slate-800 block mb-1">Rekomendasi Tindakan:</span>
                            {{ $insight['recommendation'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <livewire:dashboard.financial-advisory lazy />

        @include('dashboard.partials.metric-cards')
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
                            <a href="{{ route('guide.index') }}?type=sop"
                                class="group flex items-center gap-3 px-5 py-3 bg-slate-800/50 backdrop-blur-md border border-white/10 text-white rounded-xl font-bold text-xs hover:bg-white/10 transition-all shadow-xl hover:-translate-y-1">
                                <div class="p-1.5 bg-white/10 rounded-lg group-hover:scale-110 transition-transform">
                                    <i data-lucide="book-open" class="w-4 h-4"></i>
                                </div>
                                {{ __('ui.operational_sop') }}
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
                            <i data-lucide="zap" class="w-7 h-7 group-hover:rotate-12 transition-transform"></i>
                        </div>
                        <span
                            class="text-[9px] font-black bg-gold-50 text-gold-600 px-3 py-1 rounded-full uppercase tracking-widest">{{ __('dashboard.revenue') }}</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">{{ __('dashboard.daily_output_value') }}</p>
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
            @include('dashboard.partials.billing-operations')

            @if(!$isStaff)
                @include('dashboard.partials.business-units-summary')

                <!-- Top Clients & Invoice Ageing Widgets (Desktop Version) -->
                <div class="hidden md:grid md:grid-cols-2 gap-6 mt-8 page-fade-in stagger-6">
                    @include('dashboard.partials.top-clients')
                    @include('dashboard.partials.invoice-ageing')
                </div>

                <!-- Mobile View (Tab Toggle Container) -->
                <div class="block md:hidden mt-8 page-fade-in stagger-6" x-data="{ activeTab: 'topClients' }">
                    <div class="glass-card p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300">
                        <!-- Tab Header Toggle -->
                        <div class="flex border-b border-slate-100 pb-4 mb-6">
                             <button 
                                @click="activeTab = 'topClients'"
                                :class="activeTab === 'topClients' ? 'text-gold-600 border-gold-600 font-black' : 'text-slate-400 border-transparent hover:text-slate-600 font-bold'"
                                class="flex-1 pb-3 text-xs uppercase tracking-wider text-center border-b-2 transition-all focus:outline-none"
                            >
                                {{ __('dashboard.top_clients') }}
                            </button>
                            <button 
                                @click="activeTab = 'ageing'"
                                :class="activeTab === 'ageing' ? 'text-gold-600 border-gold-600 font-black' : 'text-slate-400 border-transparent hover:text-slate-600 font-bold'"
                                class="flex-1 pb-3 text-xs uppercase tracking-wider text-center border-b-2 transition-all focus:outline-none"
                            >
                                {{ __('dashboard.ar_ageing') }}
                            </button>
                        </div>

                        <!-- Tab Content 1: Top Clients -->
                        <div x-show="activeTab === 'topClients'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            @include('dashboard.partials.top-clients', ['cardless' => true])
                        </div>

                        <!-- Tab Content 2: AR Ageing -->
                        <div x-show="activeTab === 'ageing'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                            @include('dashboard.partials.invoice-ageing', ['cardless' => true])
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Side: Activity Timeline (Staff) or Stats (Admin) -->
        @include('dashboard.partials.upcoming-billing')
    </div>

    @if(!$isStaff)
        <!-- Bottom Grid Container -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6 page-fade-in stagger-6">
            @include('dashboard.partials.cash-flow-chart')

            @include('dashboard.partials.team-activities')

            @include('dashboard.partials.system-analytics')
        </div>
    @endif
    </div>

    @if(auth()->user()->hasFullAccess())
        <livewire:dashboard-chatbot />
    @endif
</x-app-layout>