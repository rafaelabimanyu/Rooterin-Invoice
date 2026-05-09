<x-guest-layout>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        <!-- Left Side: Login Form -->
        <div class="flex items-center justify-center p-8 md:p-16 bg-white">
            <div class="w-full max-w-md space-y-12">
                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <span class="text-2xl font-black font-outfit tracking-tight">Rooterin<span class="text-indigo-600">.</span></span>
                </div>

                <div class="space-y-4">
                    <h1 class="text-3xl font-black text-slate-900 font-outfit tracking-tight">Access Workspace</h1>
                    <p class="text-slate-500 text-sm leading-relaxed">Securely log in to manage your company billing and financial ledger.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Administrator Email</label>
                        <div class="relative group">
                            <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 transition-colors group-focus-within:text-indigo-600"></i>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Security Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700" href="{{ route('password.request') }}">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative group">
                            <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 transition-colors group-focus-within:text-indigo-600"></i>
                            <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-200 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-xs font-semibold text-slate-600">Keep me logged in</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm shadow-2xl shadow-slate-900/20 hover:bg-slate-800 hover:-translate-y-0.5 transition-all">
                            Sign In to System
                        </button>
                    </div>
                </form>

                <div class="pt-8 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Rooterin Enterprise v1.2.0 — Secure Access Point</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Decorative -->
        <div class="hidden lg:flex relative items-center justify-center bg-slate-900 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-slate-900 z-10"></div>
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600 rounded-full blur-[100px] opacity-20"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-600 rounded-full blur-[100px] opacity-20"></div>
            
            <div class="relative z-20 text-center px-20">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-full text-indigo-400 text-[10px] font-bold uppercase tracking-widest mb-10">
                    Enterprise Edition
                </div>
                <h2 class="text-4xl font-black text-white font-outfit leading-tight mb-6">Master your business <br> finances in one place.</h2>
                <p class="text-slate-400 text-sm leading-relaxed max-w-sm mx-auto">The unified workspace for professional invoicing, job documentation, and performance reporting.</p>
                
                <!-- Mockup Indicator -->
                <div class="mt-20 flex justify-center gap-4">
                    <div class="w-2 h-2 rounded-full bg-white"></div>
                    <div class="w-2 h-2 rounded-full bg-white/20"></div>
                    <div class="w-2 h-2 rounded-full bg-white/20"></div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
