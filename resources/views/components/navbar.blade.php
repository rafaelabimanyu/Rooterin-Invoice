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
    class="sticky top-0 z-30 h-16 flex items-center justify-between px-6 bg-white/80 backdrop-blur-md border-b border-slate-200"
>
    <div class="flex items-center gap-4">
        <!-- Sidebar Toggle (Desktop) -->
        <button @click="$dispatch('toggle-sidebar')" class="hidden lg:flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:text-gold-600 transition-colors">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        
        <!-- Sidebar Toggle (Mobile) -->
        <button @click="$dispatch('toggle-mobile-sidebar')" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:text-gold-600 transition-colors">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <div class="hidden sm:block">
            <h2 class="text-sm font-medium text-slate-500" x-text="greeting + ', {{ Auth::user()->name }}'"></h2>
            <p class="text-xs text-slate-400">Welcome back to your workspace</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <!-- Real-time Clock -->
        <div class="hidden md:flex flex-col items-end px-4 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
            <span class="text-xs font-bold text-slate-800 tracking-wider font-mono" x-text="time"></span>
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
            class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:text-gold-600 transition-colors"
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
            <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full border border-slate-200 hover:bg-slate-50 transition-colors">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover">
            </button>
            
            <div 
                x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-200 py-3 overflow-hidden z-50"
            >
                <div class="px-5 py-3 border-b border-slate-50 bg-slate-50/50 mb-1">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Signed in as</p>
                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                </div>
                
                <a href="{{ route('profile.edit') }}" wire:navigate.hover class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-gold-50 hover:text-gold-600 transition-colors group">
                    <i data-lucide="user" class="w-4 h-4 text-slate-400 group-hover:text-gold-500"></i>
                    Profile
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-50 transition-colors group">
                        <i data-lucide="log-out" class="w-4 h-4 text-rose-400 group-hover:text-rose-500"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
