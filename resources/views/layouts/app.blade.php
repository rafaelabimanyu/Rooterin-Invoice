<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rooterin') }} — Enterprise Billing System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Initial Dark Mode Script -->
        <script>
            if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="h-full bg-[#f8fafc] dark:bg-slate-950 font-inter antialiased overflow-x-hidden">
        <div class="flex min-h-screen" x-data="{ sidebarCollapsed: $persist(false).as('sidebar-collapsed'), mobileSidebarOpen: false }">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Mobile Sidebar Backdrop -->
            <div 
                x-show="mobileSidebarOpen" 
                @click="mobileSidebarOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"
                x-cloak
            ></div>

            <!-- Main Shell -->
            <div 
                class="flex-1 flex flex-col min-w-0 transition-all duration-300 ease-in-out"
                :class="sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-64'"
            >
                <!-- Header / Navbar -->
                <header class="h-16 flex items-center justify-between px-6 md:px-8 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-b border-slate-200/60 dark:border-slate-800/60 sticky top-0 z-40">
                    <div class="flex items-center gap-4 md:gap-6">
                        <!-- Desktop Toggle -->
                        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-1.5 text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                        <!-- Mobile Toggle -->
                        <button @click="mobileSidebarOpen = true" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-900 transition-colors">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                        
                        <div class="h-4 w-px bg-slate-200 dark:bg-slate-800"></div>
                        
                        <div class="hidden sm:flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">{{ __('ui.system_live') }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300">Operational</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-5">
                        <!-- Language Switcher -->
                        <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-lg p-1 border border-slate-200/50 dark:border-slate-800/50">
                            <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 text-[10px] font-bold rounded-md transition-all {{ App::getLocale() == 'id' ? 'bg-white dark:bg-slate-700 text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">ID</a>
                            <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-[10px] font-bold rounded-md transition-all {{ App::getLocale() == 'en' ? 'bg-white dark:bg-slate-700 text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">EN</a>
                        </div>

                        <!-- Notifications -->
                        <button class="p-2 text-slate-400 hover:text-slate-900 transition-colors relative">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                        </button>

                        <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

                        <!-- User Profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-3 group focus:outline-none">
                                <div class="flex flex-col text-right hidden md:flex">
                                    <span class="text-[11px] font-bold text-slate-900 dark:text-white leading-tight">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">Administrator</span>
                                </div>
                                <div class="w-9 h-9 rounded-lg bg-[#0f172a] dark:bg-indigo-600 flex items-center justify-center text-white text-xs font-bold ring-2 ring-transparent group-hover:ring-slate-100 transition-all">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>
                            
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                class="absolute right-0 mt-3 w-56 glass-card py-2 z-[100]"
                                x-cloak
                            >
                                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 mb-1">
                                    <p class="text-xs font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="#" class="flex items-center gap-2 px-4 py-2 text-[12px] text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <i data-lucide="user" class="w-3.5 h-3.5"></i> Profile Account
                                </a>
                                <a href="#" class="flex items-center gap-2 px-4 py-2 text-[12px] text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i> Billing Subscription
                                </a>
                                <div class="h-px bg-slate-100 dark:bg-slate-800 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-[12px] text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Sign Out Account
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Content Area -->
                <main class="flex-1 bg-[#fcfdfe] dark:bg-slate-950">
                    <div class="max-w-[1600px] mx-auto p-4 md:p-8 lg:p-12">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <script>
            lucide.createIcons();
            window.addEventListener('alpine:initialized', () => {
                lucide.createIcons();
            });
        </script>
    </body>
</html>
