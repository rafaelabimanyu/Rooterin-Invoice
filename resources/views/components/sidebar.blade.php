@props(['collapsed' => false])

<aside 
    x-data="{ 
        collapsed: $persist(false).as('sidebar-collapsed'),
        mobileOpen: false 
    }"
    @toggle-sidebar.window="collapsed = !collapsed"
    @toggle-mobile-sidebar.window="mobileOpen = !mobileOpen"
    class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-all duration-300"
    :class="collapsed ? 'w-20' : 'w-64'"
    x-cloak
>
    <!-- Logo -->
    <div class="flex items-center h-16 px-6 border-b border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">
                R
            </div>
            <span x-show="!collapsed" x-transition.opacity class="text-xl font-bold text-slate-800 dark:text-white">
                Rooterin
            </span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
        <x-nav-link-premium href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="layout-dashboard" label="Dashboard" />
        <x-nav-link-premium href="#" :active="false" icon="users" label="Clients" />
        <x-nav-link-premium href="#" :active="false" icon="file-text" label="Invoices" />
        <x-nav-link-premium href="#" :active="false" icon="package" label="Products" />
        <x-nav-link-premium href="#" :active="false" icon="settings" label="Settings" />
    </nav>

    <!-- User Profile Mini -->
    <div class="p-4 border-t border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="!collapsed" x-transition.opacity class="flex-1 overflow-hidden text-sm">
                <p class="font-semibold text-slate-800 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">Admin</p>
            </div>
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
    class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
></div>
