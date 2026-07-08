<x-app-layout :title="__('terms.title')">
    <div class="page-fade-in py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Table of Contents Sidebar (sticky on desktop) -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 space-y-6 bg-slate-50/50 border border-slate-100 rounded-3xl p-6">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">{{ __('terms.toc.title') }}</h4>
                    <nav class="flex flex-col gap-3">
                        <a href="#service-usage" class="text-xs font-black uppercase tracking-wider text-slate-500 hover:text-gold-600 transition-colors">
                            1. {{ __('terms.toc.service_usage') }}
                        </a>
                        <a href="#intellectual-property" class="text-xs font-black uppercase tracking-wider text-slate-500 hover:text-gold-600 transition-colors">
                            2. {{ __('terms.toc.intellectual_property') }}
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Content Card -->
            <div class="lg:col-span-3">
                <div class="glass-card overflow-hidden">
                    <!-- Card Header -->
                    <div class="px-8 py-10 bg-slate-50/50 border-b border-slate-100">
                        <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight leading-none">{{ __('terms.title') }}</h1>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-3">{{ __('terms.last_updated') }}</p>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-8 md:p-12">
                        <p class="text-slate-600 leading-relaxed text-sm md:text-base font-medium mb-10">
                            {{ __('terms.intro') }}
                        </p>
                        
                        <div class="h-px bg-slate-100 my-8"></div>
                        
                        <div class="space-y-12">
                            <!-- Section 1 -->
                            <div class="scroll-mt-24" id="service-usage">
                                <h3 class="text-lg font-black text-slate-900 mb-4 uppercase tracking-wider">{{ __('terms.sections.service_usage_title') }}</h3>
                                <p class="text-slate-600 leading-relaxed text-sm md:text-base font-medium">
                                    {{ __('terms.sections.service_usage_desc') }}
                                </p>
                            </div>
                            
                            <!-- Section 2 -->
                            <div class="scroll-mt-24" id="intellectual-property">
                                <h3 class="text-lg font-black text-slate-900 mb-4 uppercase tracking-wider">{{ __('terms.sections.intellectual_property_title') }}</h3>
                                <p class="text-slate-600 leading-relaxed text-sm md:text-base font-medium">
                                    {{ __('terms.sections.intellectual_property_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
