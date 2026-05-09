@props(['collapsed' => false])

<aside 
    x-data="{ 
        collapsed: $persist(false).as('sidebar-collapsed'),
        mobileOpen: false 
    }"
    @toggle-sidebar.window="collapsed = !collapsed"
    @toggle-mobile-sidebar.window="mobileOpen = !mobileOpen"
    class="fixed inset-y-0 left-0 z-50 flex flex-col bg-[#0f172a] text-slate-400 transition-all duration-300 ease-in-out border-r border-slate-800/50 shadow-2xl"
    :class="collapsed ? 'w-[72px]' : 'w-64'"
    x-cloak
>
    <!-- Brand -->
    <div class="flex items-center h-16 px-6 border-b border-slate-800/50">
        <div class="flex items-center gap-3.5">
            <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-indigo-500/20">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <div x-show="!collapsed" x-transition.opacity class="flex flex-col">
                <span class="text-base font-black text-white tracking-tight font-outfit leading-none">Rooterin<span class="text-indigo-400">.</span></span>
                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">Enterprise System</span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 px-3 py-8 space-y-10 overflow-y-auto custom-scrollbar">
        <!-- Main -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-600">Overview</p>
            <nav class="space-y-1.5">
                <x-nav-link-premium href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="layout-grid" :label="__('ui.dashboard')" />
                <x-nav-link-premium href="{{ route('clients.index') }}" :active="request()->routeIs('clients.*')" icon="users" :label="__('ui.clients')" />
            </nav>
        </div>

        <!-- Billing -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-600">Billing Lifecycle</p>
            <nav class="space-y-1.5">
                <x-nav-link-premium href="{{ route('quotations.index') }}" :active="request()->routeIs('quotations.*')" icon="file-spreadsheet" :label="__('ui.quotations')" />
                <x-nav-link-premium href="{{ route('invoices.index') }}" :active="request()->routeIs('invoices.*')" icon="file-text" :label="__('ui.invoices')" />
            </nav>
        </div>

        <!-- Intelligence -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-600">Intelligence</p>
            <nav class="space-y-1.5">
                <x-nav-link-premium href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')" icon="bar-chart-2" :label="__('ui.reports')" />
            </nav>
        </div>

        <!-- Tools -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-600">Administration</p>
            <nav class="space-y-1.5">
                @if(Auth::user()->role !== 'staff')
                    <x-nav-link-premium href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="user-cog" :label="__('ui.users')" />
                @endif
                <x-nav-link-premium href="{{ route('settings.index') }}" :active="request()->routeIs('settings.*')" icon="settings" :label="__('ui.settings')" />
            </nav>
        </div>
    </div>

    <!-- Language & Status -->
    <div x-show="!collapsed" class="px-6 py-6 mx-3 mb-6 bg-slate-800/40 rounded-xl border border-slate-700/50">
        <div class="flex items-center justify-between mb-4">
            <p class="text-[10px] font-bold text-white flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> {{ __('ui.system_live') }}
            </p>
            <div class="flex gap-2">
                <a href="{{ route('lang.switch', 'id') }}" class="text-[9px] font-bold {{ App::getLocale() == 'id' ? 'text-indigo-400' : 'text-slate-500' }}">ID</a>
                <a href="{{ route('lang.switch', 'en') }}" class="text-[9px] font-bold {{ App::getLocale() == 'en' ? 'text-indigo-400' : 'text-slate-500' }}">EN</a>
            </div>
        </div>
        <div class="h-1 bg-slate-700 rounded-full overflow-hidden">
            <div class="bg-emerald-500 h-full w-full"></div>
        </div>
    </div>

    <!-- User Section Slim -->
    <div class="p-4 border-t border-slate-800/50 bg-slate-900/50">
        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800 transition-colors cursor-pointer group" onclick="window.location='{{ route('profile.edit') }}'">
            <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-400 group-hover:text-white transition-colors">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="!collapsed" class="flex-1 overflow-hidden">
                <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-500 truncate capitalize">{{ Auth::user()->role }} Account</p>
            </div>
            <i x-show="!collapsed" data-lucide="chevron-right" class="w-3 h-3 text-slate-600"></i>
        </div>
    </div>
</aside>
