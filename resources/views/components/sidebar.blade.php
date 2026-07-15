@props(['collapsed' => false])

<aside 
    x-data="{ touchStartX: 0, touchEndX: 0 }"
    @touchstart="touchStartX = $event.changedTouches[0].screenX"
    @touchend="touchEndX = $event.changedTouches[0].screenX; if (touchStartX - touchEndX > 50) { mobileOpen = false; }"
    @toggle-sidebar.window="collapsed = !collapsed"
    @toggle-mobile-sidebar.window="mobileOpen = !mobileOpen"
    class="fixed inset-y-0 left-0 z-[70] flex flex-col bg-white transition-transform duration-300 ease-in-out lg:transition-all lg:duration-500 lg:cubic-bezier-spring border-r border-slate-200/50 shadow-2xl"
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
        class="flex items-center transition-all duration-500 cubic-bezier-spring relative"
        x-bind:class="collapsed ? 'justify-center mb-0 px-0 h-20' : 'justify-between lg:justify-start mb-6 pl-6 pr-4 py-4 h-24'"
    >
        <div class="flex items-center cursor-pointer group" x-bind:class="collapsed ? 'justify-center' : 'justify-start'" onclick="window.location='{{ route('dashboard') }}'">
            <!-- Expanded Brand: Logo + Typography -->
            <div class="flex items-center" x-show="!collapsed">
                <img src="{{ asset('img/logo-jnj.png') }}" alt="J&J GROUP Logo" class="h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                <!-- <div class="flex flex-col ml-3 select-none">
                    <span class="text-[16px] font-black tracking-wider text-slate-900 font-jakarta leading-none">J&J GROUP</span>
                    <span class="text-[8px] font-black text-gold-650 tracking-[0.25em] leading-none uppercase mt-1.5">Enterprise System</span>
                </div> -->
            </div>
            <!-- Collapsed Brand: Logo Only -->
            <div class="flex items-center justify-center" x-show="collapsed" x-cloak>
                <img src="{{ asset('img/logo-jnj.png') }}" alt="J&J GROUP Logo" class="h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
            </div>
        </div>

        <!-- Mobile Close Button -->
        <button 
            @click="mobileOpen = false" 
            class="lg:hidden flex items-center justify-center w-8 h-8 rounded-full bg-slate-100/60 backdrop-blur-md border border-slate-200/40 text-slate-500 hover:text-slate-900 active:scale-95 hover:rotate-90 transition-all duration-300 shadow-sm shrink-0"
            aria-label="Close sidebar"
        >
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
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
                @if(!auth()->user()->hasRole('staff'))
                <x-sidebar-link href="{{ route('ai-assistant.index') }}" :active="request()->routeIs('ai-assistant.*')" icon="bot" :label="__('ui.ai_assistant')" :collapsed="$collapsed" />
                @endif
                <x-sidebar-link href="{{ route('clients.index') }}" :active="request()->routeIs('clients.*')" icon="users" :label="__('ui.clients')" :collapsed="$collapsed" />
            </nav>
        </div>

        <!-- Section: Operations -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400/80">{{ __('ui.lifecycle') }}</p>
            <nav x-bind:class="collapsed ? 'space-y-4' : 'space-y-1'">
                <x-sidebar-link href="{{ route('receipts.index') }}" :active="request()->routeIs('receipts.*')" icon="file-spreadsheet" :label="__('ui.receipts')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('invoices.index') }}" :active="request()->routeIs('invoices.*')" icon="file-text" :label="__('ui.invoices')" :collapsed="$collapsed" />
                @if(!auth()->user()->hasRole('staff'))
                <x-sidebar-link href="{{ route('chronos.index') }}" :active="request()->routeIs('chronos.*')" icon="calendar-days" :label="__('ui.chronos_calendar')" :collapsed="$collapsed" />
                @endif
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

        @if(Auth::user()->role !== 'staff')
        <!-- Section: Administration -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400/80">{{ __('ui.control') }}</p>
            <nav class="space-y-1">
                <x-sidebar-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="shield-check" :label="__('ui.users')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('business-units.index') }}" :active="request()->routeIs('business-units.*')" icon="layers" :label="__('ui.business_units')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('settings.index') }}" :active="request()->routeIs('settings.*')" icon="sliders" :label="__('ui.settings')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('security.center') }}" :active="request()->routeIs('security.*')" icon="fingerprint" :label="__('ui.security_center')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('backup.index') }}" :active="request()->routeIs('backup.*')" icon="database" :label="__('ui.database_backup')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('trash.index') }}" :active="request()->routeIs('trash.*')" icon="trash-2" :label="__('ui.trash')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('guide.index') }}" :active="request()->routeIs('guide.index')" icon="book-open" :label="__('ui.guide')" :collapsed="$collapsed" />
                <x-sidebar-link href="{{ route('guide.sop') }}" :active="request()->routeIs('guide.sop')" icon="clipboard-list" :label="__('ui.operational_sop')" :collapsed="$collapsed" />
            </nav>
        </div>
        @endif
    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-100 flex justify-center">
        <a 
            href="{{ route('profile.edit') }}"
            wire:navigate.hover
            class="flex items-center gap-3 rounded-2xl bg-slate-50 border border-transparent hover:border-slate-200 transition-all duration-300 cursor-pointer group" 
            x-bind:class="collapsed ? 'justify-center w-12 h-12' : 'p-3 w-full'"
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
        </a>
    </div>
</aside>
