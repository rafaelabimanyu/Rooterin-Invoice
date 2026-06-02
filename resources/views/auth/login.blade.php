<x-guest-layout>
    <style>
        .cinematic-ease {
            transition-timing-function: cubic-bezier(0.85, 0, 0.15, 1);
        }
        
        @keyframes slideUpWord {
            0% {
                transform: translateY(100%) scaleY(1.2);
                filter: blur(10px);
                opacity: 0;
            }
            100% {
                transform: translateY(0) scaleY(1);
                filter: blur(0);
                opacity: 1;
            }
        }
        
        .animate-slide-up-word-1 {
            opacity: 0;
            animation: slideUpWord 1.1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            animation-delay: 200ms;
        }
        
        .animate-slide-up-word-2 {
            opacity: 0;
            animation: slideUpWord 1.1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            animation-delay: 450ms;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            opacity: 0;
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
    <div class="min-h-screen flex relative overflow-hidden" 
        x-data="{ 
            showPassword: false, 
            helpRequested: {{ session('status') ? 'true' : 'false' }},
            isLoggingIn: false,
            mouseX: 0,
            mouseY: 0,
            titleText: 'AUTHORIZED ACCESS',
            displayText: '',
            isSplit: false,
            scramble(target) {
                let chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
                let iterations = 0;
                let interval = setInterval(() => {
                    this.displayText = target.split('').map((char, index) => {
                        if (index < iterations) return target[index];
                        return chars[Math.floor(Math.random() * chars.length)];
                    }).join('');
                    if (iterations >= target.length) clearInterval(interval);
                    iterations += 1/3;
                }, 30);
            },
            moveParallax(e) {
                this.mouseX = (e.clientX - window.innerWidth / 2) / 25;
                this.mouseY = (e.clientY - window.innerHeight / 2) / 25;
            }
        }"
        x-init="
            scramble(titleText);
            setTimeout(() => { isSplit = true; }, 1400);
        "
        @mousemove="moveParallax($event)">
        <!-- Mobile Splash Transition Overlay -->
        <div x-data="{ showSplash: true }"
             x-init="setTimeout(() => showSplash = false, 2000)"
             x-show="showSplash"
             x-transition:leave="transition-all duration-700 ease-in-out transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="-translate-y-full"
             class="md:hidden fixed inset-0 z-50 bg-gradient-to-br from-[#111827] via-[#0b0f19] to-[#1e1b4b] flex flex-col justify-between items-center py-16 px-6 text-center overflow-hidden">
            
            <!-- Top Branding -->
            <div class="flex flex-col items-center space-y-4 animate-fade-in" style="animation-delay: 200ms;">
                <div class="w-20 h-20 bg-white/5 border border-white/10 rounded-[24px] flex items-center justify-center backdrop-blur-2xl shadow-2xl overflow-hidden p-4">
                    <img src="{{ asset('img/logo-rooterin.png') }}" alt="Rooterin Logo" class="w-full h-full object-contain">
                </div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/5 border border-white/10 rounded-full text-indigo-300 text-[8px] font-black uppercase tracking-[0.3em]">
                    Authorized Nodes Only
                </div>
            </div>

            <!-- Middle Message -->
            <div class="space-y-4 my-auto animate-fade-in" style="animation-delay: 400ms;">
                <h2 class="text-3xl font-black text-white leading-tight tracking-tighter uppercase">
                    MASTER THE <br>
                    <span class="text-indigo-500">FINANCE MATRIX.</span>
                </h2>
                <p class="text-slate-400 text-xs font-medium leading-relaxed max-w-xs mx-auto">
                    The unified high-fidelity workspace for enterprise billing, job documentation, and performance intelligence.
                </p>
            </div>

            <!-- Bottom Indicators -->
            <div class="w-full animate-fade-in" style="animation-delay: 600ms;">
                <div class="flex justify-center items-center gap-6 mt-6">
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></div>
                        <span class="text-[9px] font-bold tracking-widest text-slate-500 uppercase">Auth Active</span>
                    </div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_#6366f1]"></div>
                        <span class="text-[9px] font-bold tracking-widest text-slate-500 uppercase">Ledger Sync</span>
                    </div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-slate-600"></div>
                        <span class="text-[9px] font-bold tracking-widest text-slate-500 uppercase">Vault Encrypt</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 bg-white relative z-10 transition-opacity duration-1000 lg:delay-[600ms]"
             :class="isSplit ? 'opacity-100' : 'opacity-100 lg:opacity-0'">
            <div class="w-full max-w-md space-y-12">
                <!-- Brand -->
                <div class="space-y-1">
                    <span class="text-4xl md:text-5xl font-black tracking-tighter uppercase text-slate-900 block leading-none" style="font-family: 'Outfit', sans-serif;">Rooterin<span class="text-indigo-600">.</span></span>
                    <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-[0.3em] block mt-1">Enterprise Operating System</p>
                </div>

                <div class="space-y-3 scale-in stagger-2">
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight uppercase" x-text="displayText + '.'"></h1>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Please initialize your security credentials to access the node.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                @if(session('status') || session('success'))
                <div x-show="helpRequested" x-transition class="p-6 bg-emerald-50 border border-emerald-100 rounded-3xl mb-8">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shrink-0">
                            <i data-lucide="check-circle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-emerald-900 uppercase tracking-tight">Help Request Deployed</h4>
                            <p class="text-xs text-emerald-700 font-medium mt-1">
                                {{ session('status') ?? session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-8">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-3 scale-in stagger-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Operative Identity (Email)</label>
                        <div class="relative group input-focus-effect neon-border-pulse rounded-[24px]">
                            <div class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 transition-colors group-focus-within:text-indigo-600 z-10">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full pl-14 pr-5 py-5 bg-slate-50 border-transparent rounded-[24px] text-sm font-bold outline-none transition-all shadow-inner focus:ring-0" placeholder="name@enterprise.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-3 scale-in stagger-4">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Security Token (Password)</label>
                            <a href="{{ route('password.request') }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest transition-colors">
                                Identity Issues?
                            </a>
                        </div>
                        <div class="relative group input-focus-effect neon-border-pulse rounded-[24px]">
                            <div class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 transition-colors group-focus-within:text-indigo-600 z-10">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input id="password" type="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" class="w-full pl-14 pr-14 py-5 bg-slate-50 border-transparent rounded-[24px] text-sm font-bold outline-none transition-all shadow-inner focus:ring-0" placeholder="••••••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-900 transition-colors z-10 p-1">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Submit -->
                    <div class="flex flex-col gap-8">
                        <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                            <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-lg border-slate-200 text-indigo-600 shadow-sm focus:ring-indigo-500 transition-all" name="remember">
                            <span class="ms-3 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] group-hover:text-slate-900 transition-colors">Persistent Access</span>
                        </label>

                        <button type="submit" @click="isLoggingIn = true" class="w-full min-w-[200px] h-[72px] bg-slate-900 text-white rounded-[28px] font-black text-xs shadow-[0_20px_50px_rgba(0,0,0,0.2)] hover:bg-indigo-600 hover:-translate-y-1 transition-all duration-500 uppercase tracking-[0.3em] flex items-center justify-center gap-3 btn-shimmer group relative overflow-hidden">
                            <span x-show="!isLoggingIn" class="relative z-10 flex items-center justify-center gap-3">
                                LOGIN
                                <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                            </span>
                            <span x-show="isLoggingIn" x-cloak class="flex items-center justify-center gap-3">
                                <div class="loading-spinner"></div>
                                <span>PROCESSING...</span>
                            </span>
                        </button>
                    </div>
                </form>

                <div class="pt-8 text-center">
                    <p class="text-[10px] text-slate-300 font-black uppercase tracking-[0.4em]">Node ID: RT-{{ substr(md5(now()), 0, 8) }} — SECURE</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Branding Command Center -->
        <div class="hidden lg:flex absolute top-0 left-0 h-full bg-[#0a0f1d] z-20 transition-all duration-[1400ms] cinematic-ease overflow-hidden items-center justify-center"
             :class="isSplit ? 'lg:w-1/2 lg:left-1/2' : 'lg:w-full lg:left-0'">
            <!-- Animated Background -->
            <div class="absolute inset-0 matrix-flow">
                <div class="parallax-layer absolute top-0 right-0 w-[800px] h-[800px] bg-indigo-600/10 rounded-full blur-[150px] -mr-[400px] -mt-[400px] transform-gpu"
                    :style="'transform: translate3d(' + mouseX * 1.5 + 'px, ' + mouseY * 1.5 + 'px, 0)'"></div>
                <div class="parallax-layer absolute bottom-0 left-0 w-[800px] h-[800px] bg-purple-600/10 rounded-full blur-[150px] -ml-[400px] -mb-[400px] transform-gpu"
                    :style="'transform: translate3d(' + mouseX * -1.5 + 'px, ' + mouseY * -1.5 + 'px, 0)'"></div>
                
                <!-- Grid Pattern -->
                <div class="absolute inset-0 opacity-[0.03] parallax-layer transform-gpu" 
                    style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"
                    :style="'transform: translate3d(' + mouseX * 0.5 + 'px, ' + mouseY * 0.5 + 'px, 0)'"></div>
            </div>
            
            <div class="relative z-20 text-center px-24">
                <!-- Logo with slide-down reveal -->
                <div class="w-32 h-32 bg-white/5 border border-white/10 rounded-[40px] flex items-center justify-center mb-16 mx-auto backdrop-blur-2xl shadow-2xl overflow-hidden p-6 transition-all duration-[1000ms] delay-[1600ms]"
                     :class="isSplit ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform -translate-y-4'">
                    <img src="{{ asset('img/logo-rooterin.png') }}" alt="Rooterin Logo" class="w-full h-full object-contain">
                </div>
                
                <div class="space-y-6">
                    <!-- Badge with fade reveal -->
                    <div class="inline-flex items-center gap-3 px-6 py-2 bg-white/5 border border-white/10 rounded-full text-indigo-300 text-[10px] font-black uppercase tracking-[0.4em] mb-4 transition-all duration-[1000ms] delay-[1500ms]"
                         :class="isSplit ? 'opacity-100' : 'opacity-0'">
                        Authorized Nodes Only
                    </div>
                    
                    <!-- Heading text with 2-line layout and slide-up/fade animation -->
                    <h2 class="text-5xl xl:text-6xl 2xl:text-7xl font-black text-white leading-[0.9] tracking-tighter uppercase">
                        <div class="overflow-hidden">
                            <span class="inline-block animate-slide-up-word-1 whitespace-nowrap">MASTER THE</span>
                        </div>
                        <div class="overflow-hidden mt-2">
                            <span class="inline-block text-indigo-500 animate-slide-up-word-2 whitespace-nowrap">FINANCE MATRIX.</span>
                        </div>
                    </h2>
                    
                    <!-- Description with slide-up reveal -->
                    <p class="text-slate-400 text-base font-medium leading-relaxed max-w-sm mx-auto mt-8 transition-all duration-[1000ms] delay-[1700ms]"
                       :class="isSplit ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'">
                        The unified high-fidelity workspace for enterprise billing, job documentation, and performance intelligence.
                    </p>
                </div>
                
                <!-- Security Badges with slide-up reveal -->
                <div class="mt-20 flex justify-center items-center gap-10 transition-all duration-[1000ms] delay-[1800ms]"
                     :class="isSplit ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></div>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Auth Active</span>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Ledger Sync</span>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-slate-700"></div>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Vault Encrypt</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
