<x-app-layout>
    <div x-data="{ mobileMenuOpen: false }" class="relative">
        
        <!-- Mobile Sticky Header -->
        <div class="lg:hidden sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-100 px-4 py-3 flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i data-lucide="{{ $guideData['header']['icon'] ?? 'book-open' }}" class="w-5 h-5 text-indigo-600"></i>
                <span class="font-bold text-slate-900 text-sm tracking-wide">{{ strtoupper($role) }} {{ app()->getLocale() == 'en' ? 'GUIDE' : 'PANDUAN' }}</span>
            </div>
            <button @click.stop="mobileMenuOpen = true" class="p-2 -mr-2 text-slate-500 hover:text-indigo-600 transition-colors rounded-lg hover:bg-slate-50">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-10 items-start px-4 sm:px-6 lg:px-8">
            
            <!-- Sidebar Navigation -->
            @include('guide.partials.sidebar')

            <!-- Content Area -->
            <div class="flex-1 max-w-4xl space-y-8 lg:space-y-10 pb-20 w-full">
                
                <!-- Contextual Search Bar -->
                @include('guide.partials.search-bar')
                
                <!-- Header -->
                <div class="space-y-4 mb-8 lg:mb-12">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 rounded-full text-indigo-600 text-[10px] font-black uppercase tracking-widest border border-indigo-100 shadow-sm">
                        <i data-lucide="{{ $guideData['header']['icon'] ?? 'book-open' }}" class="w-3 h-3 hidden sm:block"></i> 
                        {{ strtoupper($role) }} {{ app()->getLocale() == 'en' ? 'KNOWLEDGE BASE' : 'PANGKALAN PENGETAHUAN' }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-slate-900 font-outfit tracking-tight break-words">{{ __($guideData['header']['title']) }}</h1>
                    <p class="text-base md:text-lg text-slate-500 leading-relaxed max-w-2xl break-words">{{ __($guideData['header']['subtitle']) }}</p>
                </div>

                <!-- Workflow Diagram -->
                @include('guide.partials.workflow-diagram')

                <!-- Active Section Content -->
                <div class="pt-8 border-t border-slate-100">
                    @include('guide.partials.section')
                </div>

                <!-- Footer Navigation -->
                <div class="pt-10 lg:pt-16 mt-10 lg:mt-16 border-t border-slate-100 flex justify-between items-center text-xs lg:text-sm font-semibold text-slate-500">
                    <p>&copy; {{ date('Y') }} Rooterin Enterprise</p>
                    <a href="#top" class="hover:text-indigo-600 transition-colors flex items-center font-jakarta">
                        {{ app()->getLocale() == 'en' ? 'Back to top' : 'Kembali ke atas' }} <i data-lucide="arrow-up" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
