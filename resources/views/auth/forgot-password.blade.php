<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#0a0f1d] p-6 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px] -mr-[300px] -mt-[300px]"></div>
            <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[120px] -ml-[300px] -mb-[300px]"></div>
            <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>

        <div class="w-full max-w-lg relative z-10 page-fade-in">
            <div class="bg-white/5 border border-white/10 backdrop-blur-2xl rounded-[40px] p-10 md:p-16 shadow-2xl space-y-12">
                <!-- Header -->
                <div class="space-y-6 text-center">
                    <div class="w-20 h-20 bg-indigo-500/20 rounded-3xl flex items-center justify-center mx-auto border border-indigo-500/30">
                        <i data-lucide="key-round" class="w-8 h-8 text-indigo-400"></i>
                    </div>
                    <div class="space-y-2">
                        <h1 class="text-3xl font-black text-white tracking-tight uppercase">Identity <span class="text-indigo-500">Recovery</span>.</h1>
                        <p class="text-slate-400 text-sm font-medium leading-relaxed">
                            {{ __('Enter your operative email to receive a secure password restoration link.') }}
                        </p>
                    </div>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">Operative Identity (Email)</label>
                        <div class="relative group">
                            <div class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500 transition-colors group-focus-within:text-indigo-500">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus class="w-full pl-14 pr-5 py-5 bg-white/5 border-transparent rounded-[24px] text-sm font-bold text-white outline-none focus:bg-white/10 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/50 transition-all shadow-inner placeholder:text-slate-600" placeholder="name@enterprise.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-6">
                        <button type="submit" class="w-full py-6 bg-indigo-600 text-white rounded-[28px] font-black text-xs shadow-[0_20px_50px_rgba(79,70,229,0.3)] hover:bg-indigo-500 hover:-translate-y-1 transition-all duration-500 uppercase tracking-[0.3em]">
                            Send Recovery Link
                        </button>
                        
                        <a href="{{ route('login') }}" class="text-center text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-[0.3em] transition-colors">
                            Return to Login Node
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer Info -->
            <div class="mt-12 text-center">
                <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.4em]">Recovery Protocol Active — Node Secured</p>
            </div>
        </div>
    </div>
</x-guest-layout>
