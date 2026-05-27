<x-app-layout :title="__('help.title')">
    <div class="page-fade-in py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-10">
            <!-- Main Layout Card -->
            <div class="glass-card overflow-hidden">
                <!-- Header block -->
                <div class="px-8 py-10 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-slate-100">
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight leading-none">{{ __('help.title') }}</h1>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mt-3">{{ __('help.subtitle') }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-md">
                        <i data-lucide="help-circle" class="w-6 h-6"></i>
                    </div>
                </div>
                
                <!-- Support Cards Grid -->
                <div class="p-8 md:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Card 1: Quick Start Guide -->
                        <div class="p-8 rounded-3xl bg-slate-50/50 hover:bg-white border border-slate-200/50 hover:border-slate-350 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-64 group relative overflow-hidden">
                            <div class="absolute -right-10 -top-10 w-28 h-28 bg-indigo-500/5 blur-2xl group-hover:bg-indigo-500/10 transition-colors duration-300 rounded-full"></div>
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 transition-transform duration-500 group-hover:rotate-6">
                                    <i data-lucide="book-open" class="w-6 h-6"></i>
                                </div>
                                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">{{ __('help.cards.quick_start_title') }}</h3>
                                <p class="text-xs md:text-sm text-slate-500 leading-relaxed font-semibold">{{ __('help.cards.quick_start_desc') }}</p>
                            </div>
                            <a href="{{ route('guide.index') }}" class="inline-flex items-center gap-2 text-xs font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition-colors mt-6">
                                {{ __('help.cards.quick_start_action') }} &rarr;
                            </a>
                        </div>
                        
                        <!-- Card 2: Technical Support -->
                        <div class="p-8 rounded-3xl bg-slate-50/50 hover:bg-white border border-slate-200/50 hover:border-slate-350 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-64 group relative overflow-hidden">
                            <div class="absolute -right-10 -top-10 w-28 h-28 bg-emerald-500/5 blur-2xl group-hover:bg-emerald-500/10 transition-colors duration-300 rounded-full"></div>
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 transition-transform duration-500 group-hover:rotate-6">
                                    <i data-lucide="life-buoy" class="w-6 h-6"></i>
                                </div>
                                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">{{ __('help.cards.tech_support_title') }}</h3>
                                <p class="text-xs md:text-sm text-slate-500 leading-relaxed font-semibold">{{ __('help.cards.tech_support_desc') }}</p>
                            </div>
                            <a href="mailto:support@rooterin.com" class="inline-flex items-center gap-2 text-xs font-black text-emerald-600 uppercase tracking-widest hover:text-emerald-800 transition-colors mt-6">
                                {{ __('help.cards.tech_support_action') }} &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
