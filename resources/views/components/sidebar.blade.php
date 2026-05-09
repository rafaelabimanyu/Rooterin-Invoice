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
                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">Enterprise</span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 px-3 py-8 space-y-10 overflow-y-auto custom-scrollbar">
        <!-- Main -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-600">Overview</p>
            <nav class="space-y-1.5">
                <x-nav-link-premium href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="layout-grid" label="Dashboard" />
                <x-nav-link-premium href="{{ route('invoices.index') }}" :active="request()->routeIs('invoices.*')" icon="file-text" label="Billing Ledger" />
                <x-nav-link-premium href="{{ route('clients.index') }}" :active="request()->routeIs('clients.*')" icon="users" label="Client Accounts" />
            </nav>
        </div>

        <!-- Management -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-600">Intelligence</p>
            <nav class="space-y-1.5">
                <x-nav-link-premium href="#" :active="false" icon="bar-chart-2" label="Revenue Reports" />
                <x-nav-link-premium href="#" :active="false" icon="pie-chart" label="Tax Summary" />
            </nav>
        </div>

        <!-- Tools -->
        <div>
            <p x-show="!collapsed" class="px-4 mb-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-600">Administration</p>
            <nav class="space-y-1.5">
                <x-nav-link-premium href="#" :active="false" icon="settings" label="System Settings" />
                <x-nav-link-premium href="#" :active="false" icon="life-buoy" label="Support Center" />
            </nav>
        </div>
    </div>

    <!-- Upgrade / Status Card -->
    <div x-show="!collapsed" class="px-6 py-6 mx-3 mb-6 bg-slate-800/40 rounded-xl border border-slate-700/50">
        <p class="text-[10px] font-bold text-white mb-2 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Pro License
        </p>
        <p class="text-[10px] text-slate-500 leading-relaxed mb-4">You are currently using the enterprise license.</p>
        <div class="h-1 bg-slate-700 rounded-full overflow-hidden">
            <div class="bg-indigo-500 h-full w-3/4"></div>
        </div>
    </div>

    <!-- User Section Slim -->
    <div class="p-4 border-t border-slate-800/50 bg-slate-900/50">
        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800 transition-colors cursor-pointer group">
            <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-400 group-hover:text-white transition-colors">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <div x-show="!collapsed" class="flex-1 overflow-hidden">
                <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-500 truncate">Administrator</p>
            </div>
            <i x-show="!collapsed" data-lucide="chevron-up" class="w-3 h-3 text-slate-600"></i>
        </div>
    </div>
</aside>

<!-- Mobile Overlay -->
<div 
    x-show="mobileOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="mobileOpen = false"
    class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
    x-cloak
></div>
