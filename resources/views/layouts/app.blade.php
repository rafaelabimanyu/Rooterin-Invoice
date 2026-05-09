<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rooterin') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        <script>
            if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <style>
            [x-cloak] { display: none !important; }
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
            
            .page-transition-enter { opacity: 0; transform: translateY(10px); }
            .page-transition-enter-active { opacity: 1; transform: translateY(0); transition: all 0.4s ease-out; }
        </style>
    </head>
    <body class="h-full bg-slate-50 dark:bg-slate-950 font-inter antialiased" x-data="{ sidebarCollapsed: $persist(false).as('sidebar-collapsed') }">
        <div class="flex h-full">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content -->
            <div 
                class="flex-1 flex flex-col min-w-0 transition-all duration-300"
                :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'"
            >
                <x-navbar />

                <main class="flex-1 overflow-y-auto p-6 md:p-8">
                    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)" class="max-w-7xl mx-auto">
                        <!-- Smooth Page Transition Container -->
                        <div x-show="loaded" x-transition:enter="page-transition-enter-active" x-transition:enter-start="page-transition-enter">
                            {{ $slot }}
                        </div>

                        <!-- Skeleton State (Shown while loading) -->
                        <div x-show="!loaded">
                            @if(isset($header))
                                <div class="h-8 w-48 bg-slate-200 dark:bg-slate-800 animate-pulse rounded-lg mb-6"></div>
                            @endif
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                                <div class="h-32 bg-slate-200 dark:bg-slate-800 animate-pulse rounded-2xl"></div>
                                <div class="h-32 bg-slate-200 dark:bg-slate-800 animate-pulse rounded-2xl"></div>
                                <div class="h-32 bg-slate-200 dark:bg-slate-800 animate-pulse rounded-2xl"></div>
                            </div>
                            <div class="h-96 bg-slate-200 dark:bg-slate-800 animate-pulse rounded-2xl"></div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <script>
            // Initialize Lucide icons
            lucide.createIcons();
            
            // Re-initialize icons when Alpine updates (if needed)
            window.addEventListener('alpine:initialized', () => {
                lucide.createIcons();
            });
        </script>
    </body>
</html>
