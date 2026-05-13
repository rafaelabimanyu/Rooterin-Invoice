<x-guest-layout>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 overflow-hidden" x-data="{ showPassword: false, helpRequested: {{ session('status') ? 'true' : 'false' }} }">
        <!-- Left Side: Login Form -->
        <div class="flex items-center justify-center p-8 md:p-16 bg-white relative z-10 page-fade-in">
            <div class="w-full max-w-md space-y-12">
                <!-- Brand -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white shadow-2xl shadow-slate-900/20 transition-transform hover:scale-110 duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check text-indigo-500"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <div>
                        <span class="text-2xl font-black tracking-tighter uppercase">Rooterin<span class="text-indigo-600">.</span></span>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Enterprise Operating System</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight uppercase">Authorized <span class="text-indigo-600">Access</span>.</h1>
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
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Operative Identity (Email)</label>
                        <div class="relative group">
                            <div class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 transition-colors group-focus-within:text-indigo-600">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="w-full pl-14 pr-5 py-5 bg-slate-50 border-transparent rounded-[24px] text-sm font-bold outline-none focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner" placeholder="name@enterprise.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Security Token (Password)</label>
                            <a href="{{ route('password.request') }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest transition-colors">
                                Identity Issues?
                            </a>
                        </div>
                        <div class="relative group">
                            <div class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 transition-colors group-focus-within:text-indigo-600">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" class="w-full pl-14 pr-14 py-5 bg-slate-50 border-transparent rounded-[24px] text-sm font-bold outline-none focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner" placeholder="••••••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-900 transition-colors">
                                <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
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

                        <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[28px] font-black text-xs shadow-[0_20px_50px_rgba(0,0,0,0.2)] hover:bg-indigo-600 hover:-translate-y-1 transition-all duration-500 uppercase tracking-[0.3em]">
                            Initialize Node
                        </button>
                    </div>
                </form>

                <div class="pt-8 text-center">
                    <p class="text-[10px] text-slate-300 font-black uppercase tracking-[0.4em]">Node ID: RT-{{ substr(md5(now()), 0, 8) }} — SECURE</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Branding Command Center -->
        <div class="hidden lg:flex relative items-center justify-center bg-[#0a0f1d] overflow-hidden">
            <!-- Animated Background -->
            <div class="absolute inset-0">
                <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-indigo-600/10 rounded-full blur-[150px] -mr-[400px] -mt-[400px]"></div>
                <div class="absolute bottom-0 left-0 w-[800px] h-[800px] bg-purple-600/10 rounded-full blur-[150px] -ml-[400px] -mb-[400px]"></div>
                
                <!-- Grid Pattern -->
                <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
            </div>
            
            <div class="relative z-20 text-center px-24">
                <div class="w-32 h-32 bg-white/5 border border-white/10 rounded-[40px] flex items-center justify-center mb-16 mx-auto backdrop-blur-2xl shadow-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap fill-white/10"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-3 px-6 py-2 bg-white/5 border border-white/10 rounded-full text-indigo-300 text-[10px] font-black uppercase tracking-[0.4em] mb-4">
                        Authorized Nodes Only
                    </div>
                    <h2 class="text-6xl font-black text-white leading-[0.9] tracking-tighter uppercase">
                        Master the <br> <span class="text-indigo-500">Finance</span> <br> Matrix.
                    </h2>
                    <p class="text-slate-400 text-base font-medium leading-relaxed max-w-sm mx-auto mt-8">
                        The unified high-fidelity workspace for enterprise billing, job documentation, and performance intelligence.
                    </p>
                </div>
                
                <!-- Security Badges -->
                <div class="mt-20 flex justify-center items-center gap-10">
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
