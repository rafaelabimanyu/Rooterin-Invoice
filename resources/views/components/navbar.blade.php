<header 
    x-data="{ 
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
    }"
    x-init="updateTime(); setInterval(() => updateTime(), 1000)"
    class="sticky top-0 z-30 h-16 flex items-center justify-between px-6 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800"
>
    <div class="flex items-center gap-4">
        <!-- Sidebar Toggle (Desktop) -->
        <button @click="$dispatch('toggle-sidebar')" class="hidden lg:flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        
        <!-- Sidebar Toggle (Mobile) -->
        <button @click="$dispatch('toggle-mobile-sidebar')" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-indigo-600 transition-colors">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <div class="hidden sm:block">
            <h2 class="text-sm font-medium text-slate-500 dark:text-slate-400" x-text="greeting + ', {{ Auth::user()->name }}'"></h2>
            <p class="text-xs text-slate-400 dark:text-slate-500">Welcome back to your workspace</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <!-- Real-time Clock -->
        <div class="hidden md:flex flex-col items-end px-4 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 tracking-wider font-mono" x-text="time"></span>
            <span class="text-[10px] text-slate-400 uppercase font-medium">Local Time</span>
        </div>

        <!-- Dark Mode Toggle -->
        <button 
            x-data="{ darkMode: localStorage.getItem('dark-mode') === 'true' }"
            x-init="$watch('darkMode', val => {
                localStorage.setItem('dark-mode', val);
                document.documentElement.classList.toggle('dark', val);
            })"
            @click="darkMode = !darkMode"
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-indigo-600 transition-colors"
        >
            <template x-if="!darkMode">
                <i data-lucide="moon" class="w-5 h-5"></i>
            </template>
            <template x-if="darkMode">
                <i data-lucide="sun" class="w-5 h-5"></i>
            </template>
        </button>

        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </button>
            
            <div 
                x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                class="absolute right-0 mt-2 w-48 rounded-2xl bg-white dark:bg-slate-800 shadow-xl border border-slate-200 dark:border-slate-700 py-2"
            >
                <a href="#" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Profile Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</header>
