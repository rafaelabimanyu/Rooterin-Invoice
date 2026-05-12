@props(['collapsed' => false])

<aside 
    @toggle-sidebar.window="collapsed = !collapsed"
    @toggle-mobile-sidebar.window="mobileOpen = !mobileOpen"
    class="fixed inset-y-0 left-0 z-[70] flex flex-col bg-white transition-all duration-500 cubic-bezier-spring border-r border-slate-200/50 shadow-2xl"
    x-bind:class="{ 
        'w-[72px]': collapsed, 
        'w-72': !collapsed,
        '-translate-x-full lg:translate-x-0': !mobileOpen,
        'translate-x-0 shadow-2xl': mobileOpen
    }"
    x-cloak
>
    <!-- Brand Area -->
    <div 
        class="flex items-center h-20 transition-all duration-500 cubic-bezier-spring"
        x-bind:class="collapsed ? 'justify-center px-0 mb-0' : 'px-6 mb-6'"
    >
        <div class="flex items-center gap-4 group cursor-pointer" onclick="window.location='{{ route('dashboard') }}'">
            <div class="w-12 h-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white shrink-0 shadow-xl shadow-slate-900/10 group-hover:rotate-12 transition-transform duration-300">
                <i data-lucide="zap" class="w-6 h-6 fill-current"></i>
            </div>
            <div x-show="!collapsed" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="flex flex-col">
                <span class="text-lg font-black text-slate-900 tracking-tighter font-jakarta leading-none uppercase">Rooterin<span class="text-indigo-500">.</span></span>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1 leading-none">{{ __('ui.system_live') }}</span>
            </div>
        </div>
    </div>

    <!-- Navigation Area -->
    <div 
        class="flex-1 overflow-y-auto custom-scrollbar transition-all duration-500 cubic-bezier-spring"
        x-bind:class="collapsed ? 'px-2 space-y-4 py-2' : 'px-4 space-y-8 pb-10'"
    >
        <!-- Section: Overview -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400/80">{{ __('ui.terminal') }}</p>
            <nav x-bind:class="collapsed ? 'space-y-4' : 'space-y-1'">
                <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="layout-grid" :label="__('ui.dashboard')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('clients.index') }}" :active="request()->routeIs('clients.*')" icon="users" :label="__('ui.clients')" :collapsed="$collapsed" />
            </nav>
        </div>

        <!-- Section: Operations -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400/80">{{ __('ui.lifecycle') }}</p>
            <nav x-bind:class="collapsed ? 'space-y-4' : 'space-y-1'">
                <x-sidebar-link href="{{ route('receipts.index') }}" :active="request()->routeIs('receipts.*')" icon="file-spreadsheet" :label="__('ui.receipts')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('invoices.index') }}" :active="request()->routeIs('invoices.*')" icon="file-text" :label="__('ui.invoices')" :collapsed="$collapsed" />
            </nav>
        </div>

        @if(auth()->user()->role !== 'staff')
        <!-- Section: Intelligence -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400/80">{{ __('ui.intelligence') }}</p>
            <nav x-bind:class="collapsed ? 'space-y-4' : 'space-y-1'">
                <x-sidebar-link href="{{ route('owner.kpi') }}" :active="request()->routeIs('owner.kpi')" icon="trending-up" :label="__('ui.owner_kpi')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')" icon="pie-chart" :label="__('ui.reports')" :collapsed="$collapsed" />
            </nav>
        </div>
        @endif

        <!-- Section: Administration -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400/80">{{ __('ui.control') }}</p>
            <nav class="space-y-1">
                @if(Auth::user()->role !== 'staff')
                    <x-sidebar-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="shield-check" :label="__('ui.users')" :collapsed="$collapsed" />
                    <x-sidebar-link href="{{ route('settings.index') }}" :active="request()->routeIs('settings.*')" icon="sliders" :label="__('ui.settings')" :collapsed="$collapsed" />
                    <x-sidebar-link href="{{ route('security.center') }}" :active="request()->routeIs('security.*')" icon="fingerprint" :label="__('ui.security_center')" :collapsed="$collapsed" />
                    <x-sidebar-link href="{{ route('guide.index') }}" :active="request()->routeIs('guide.index')" icon="book-open" :label="__('ui.guide')" :collapsed="$collapsed" />
                @else
                    <x-sidebar-link href="{{ route('guide.index') }}?type=sop" :active="request()->routeIs('guide.index')" icon="clipboard-list" label="Operational SOP" :collapsed="$collapsed" />
                @endif
            </nav>
        </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-100 flex justify-center">
        <div 
            class="flex items-center gap-3 rounded-2xl bg-slate-50 border border-transparent hover:border-slate-200 transition-all duration-300 cursor-pointer group" 
            x-bind:class="collapsed ? 'justify-center w-12 h-12' : 'p-3 w-full'"
            onclick="window.location='{{ route('profile.edit') }}'"
        >
            <div class="w-10 h-10 rounded-xl bg-slate-200 flex items-center justify-center text-xs font-black text-slate-500 group-hover:bg-slate-900 group-hover:text-white transition-all duration-300 shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="!collapsed" class="flex-1 overflow-hidden">
                <p class="text-[11px] font-black text-slate-900 truncate uppercase tracking-tight">{{ Auth::user()->name }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest truncate">{{ strtoupper(Auth::user()->role) }} {{ __('ui.mode') ?? 'MODE' }}</p>
                </div>
            </div>
            <i x-show="!collapsed" data-lucide="chevron-right" class="w-3 h-3 text-slate-300 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </div>
</aside>
