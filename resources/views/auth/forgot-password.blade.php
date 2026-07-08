<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#0a0f1d] p-6 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-red-600/5 rounded-full blur-[120px] -mr-[300px] -mt-[300px]"></div>
            <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-gold-600/5 rounded-full blur-[120px] -ml-[300px] -mb-[300px]"></div>
            <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>

        <div class="w-full max-w-xl relative z-10 page-fade-in">
            <div class="bg-white/5 border border-white/10 backdrop-blur-2xl rounded-[40px] p-10 md:p-16 shadow-2xl space-y-12">
                <!-- Header -->
                <div class="space-y-6 text-center">
                    <div class="w-24 h-24 bg-red-500/10 rounded-[32px] flex items-center justify-center mx-auto border border-red-500/20 shadow-[0_0_50px_rgba(239,68,68,0.1)]">
                        <i data-lucide="shield-alert" class="w-10 h-10 text-red-500"></i>
                    </div>
                    <div class="space-y-3">
                        <h1 class="text-3xl font-black text-white tracking-tight uppercase">Security <span class="text-red-500">Protocol</span>.</h1>
                        <div class="h-1 w-20 bg-red-500 mx-auto rounded-full"></div>
                    </div>
                </div>

                <div class="p-8 bg-white/5 border border-white/5 rounded-3xl space-y-8">
                    <div class="space-y-4">
                        <p class="text-slate-300 text-sm font-medium leading-relaxed text-center">
                            {{ __('SECURITY PROTOCOL: Automated password recovery is disabled. To maintain node integrity, all identity restorations must be performed manually.') }}
                        </p>
                    </div>
                    
                    <div class="pt-8 border-t border-white/5 space-y-6 text-center">
                        <p class="text-white text-base font-bold">
                            {{ __('To regain access to your node, please contact the System Administrator for manual identity verification at:') }}
                        </p>
                        
                        <div class="inline-flex flex-col items-center gap-4">
                            <div class="px-6 py-3 bg-gold-500 text-slate-950 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-gold-500/20">
                                Contact Admin
                            </div>
                            <p class="text-gold-400 text-sm font-black tracking-widest uppercase">admin@jnjgroup.com</p>
                            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">+62 8xx-xxxx-xxxx (WhatsApp)</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <a href="{{ route('login') }}" class="w-full py-6 bg-slate-900 text-white border border-white/10 rounded-[28px] font-black text-xs text-center shadow-xl hover:bg-white hover:text-slate-900 transition-all duration-500 uppercase tracking-[0.3em]">
                        Return to Login Node
                    </a>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="mt-12 text-center">
                <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.4em]">Protocol Active — Manual Override Required</p>
            </div>
        </div>
    </div>
</x-guest-layout>
