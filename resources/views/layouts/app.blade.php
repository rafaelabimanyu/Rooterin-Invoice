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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="h-full bg-[#f8fafc] font-sans antialiased overflow-x-hidden">
        <div class="flex min-h-screen" x-data="{ collapsed: $persist(false).as('sidebar-collapsed'), mobileOpen: false }">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Mobile Sidebar Backdrop -->
            <div 
                x-show="mobileOpen" 
                @click="mobileOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] lg:hidden"
                x-cloak
            ></div>

            <!-- Main Shell -->
            <div 
                class="flex-1 flex flex-col min-w-0 transition-all duration-300 ease-in-out"
                x-bind:class="collapsed ? 'lg:ml-[72px]' : 'lg:ml-72'"
            >
                <!-- Header / Navbar -->
                <header class="h-16 flex items-center justify-between px-6 md:px-10 bg-white/70 backdrop-blur-2xl border-b border-slate-200/50 sticky top-0 z-50">
                    <div class="flex items-center gap-4 md:gap-8">
                        <!-- Toggle Button -->
                        <button @click="collapsed = !collapsed" class="hidden lg:flex p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all duration-300 group">
                            <i data-lucide="menu" class="w-5 h-5 group-hover:rotate-180 transition-transform duration-300"></i>
                        </button>
                        <!-- Mobile Toggle -->
                        <button @click="mobileOpen = true" class="lg:hidden p-2 text-slate-400 hover:text-slate-900 transition-colors">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                        
                        <div class="h-5 w-px bg-slate-200"></div>
                        
                        <div class="hidden sm:flex items-center gap-3">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] leading-tight">System Status</span>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] animate-pulse"></span>
                                    <span class="text-[11px] font-bold text-slate-700">Enterprise Live</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <!-- Language Switcher -->
                        <div class="hidden md:flex items-center bg-slate-100/50 rounded-xl p-1 border border-slate-200/50">
                            <a href="{{ route('lang.switch', 'id') }}" class="px-3 py-1.5 text-[10px] font-black rounded-lg transition-all {{ App::getLocale() == 'id' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">ID</a>
                            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1.5 text-[10px] font-black rounded-lg transition-all {{ App::getLocale() == 'en' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">EN</a>
                        </div>

                        <!-- Notifications -->
                        <button class="p-2.5 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all relative group">
                            <i data-lucide="bell" class="w-5 h-5 group-hover:shake"></i>
                            <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-indigo-500 rounded-full ring-2 ring-white"></span>
                        </button>

                        <div class="h-8 w-px bg-slate-200"></div>

                        <!-- User Profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-3.5 group focus:outline-none">
                                <div class="flex flex-col text-right hidden md:flex">
                                    <span class="text-[11px] font-black text-slate-900 leading-tight">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ Auth::user()->role }}</span>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white text-xs font-black shadow-lg shadow-slate-900/10 ring-2 ring-transparent group-hover:ring-indigo-500 transition-all duration-300">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>
                            
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                                x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                class="absolute right-0 mt-3 w-64 glass-card p-2 z-[100] border-slate-200/50 shadow-2xl"
                                x-cloak
                            >
                                <div class="px-4 py-4 border-b border-slate-100 mb-2">
                                    <p class="text-xs font-black text-slate-900 uppercase tracking-tight">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold truncate mt-0.5">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="space-y-1">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-[12px] font-bold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors group">
                                        <i data-lucide="user" class="w-4 h-4 group-hover:text-indigo-500"></i> Account Settings
                                    </a>
                                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-[12px] font-bold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors group">
                                        <i data-lucide="shield" class="w-4 h-4 group-hover:text-indigo-500"></i> Privacy Center
                                    </a>
                                </div>
                                <div class="h-px bg-slate-100 my-2 mx-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-[12px] font-bold text-rose-500 hover:bg-rose-50 rounded-xl transition-colors group">
                                        <i data-lucide="log-out" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i> End Session
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Content Area -->
                <main class="flex-1 overflow-x-hidden page-fade-in">
                    <div class="max-w-[1600px] mx-auto p-6 md:p-10 lg:p-14">
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
