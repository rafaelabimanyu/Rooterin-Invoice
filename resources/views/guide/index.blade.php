<x-app-layout :title="app()->getLocale() == 'en' ? 'Guide Center & Documentation' : 'Pusat Panduan & Dokumentasi'">
    <div class="animate-fade-in-up">
        <div x-data="{ mobileMenuOpen: false }" class="relative">
            
            <!-- Mobile Sticky Header with Dropdown / Floating Table of Contents -->
            <div class="lg:hidden sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 px-4 py-3 flex flex-col mb-6"
                x-data="{ dropdownOpen: false }"
            >
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-3 min-w-0">
                        <i data-lucide="{{ $guideData['header']['icon'] ?? 'book-open' }}" class="w-5 h-5 text-indigo-600 shrink-0"></i>
                        <span class="font-bold text-slate-900 text-sm tracking-wide truncate">
                            {{ is_array(__($activeSectionData['title'])) ? $activeSectionData['title'] : __($activeSectionData['title']) }}
                        </span>
                    </div>
                    <button @click="dropdownOpen = !dropdownOpen" class="p-2 -mr-2 text-slate-500 hover:text-indigo-600 transition-all rounded-xl hover:bg-slate-50 flex items-center gap-1.5 border border-slate-100/80 bg-slate-50/50">
                        <span class="text-xs font-bold">{{ app()->getLocale() == 'en' ? 'Topics' : 'Topik' }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                    </button>
                </div>
                
                <!-- Dropdown items -->
                <div x-show="dropdownOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="mt-3 bg-white rounded-2xl border border-slate-200 shadow-xl p-2.5 space-y-1"
                    style="display: none;"
                    @click.away="dropdownOpen = false"
                >
                    @foreach($guideData['navigation'] as $key => $nav)
                        <a href="{{ route('guide.index', $key) }}"
                            class="flex items-center px-3 py-2 text-xs font-bold rounded-xl transition-all {{ $activeSectionKey === $key ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-750 hover:bg-slate-50' }}"
                        >
                            <i data-lucide="{{ $nav['icon'] ?? 'file-text' }}" class="w-3.5 h-3.5 mr-2"></i>
                            {{ is_array(__($nav['title'])) ? $nav['title'] : __($nav['title']) }}
                        </a>
                        
                        @if(isset($nav['sub_sections']) && count($nav['sub_sections']) > 0 && $activeSectionKey === $key)
                            <div class="ml-5 pl-3 border-l border-slate-200 space-y-1 py-1">
                                @foreach($nav['sub_sections'] as $subKey => $subSec)
                                    <a href="#{{ $subKey }}" @click="dropdownOpen = false" class="block px-3 py-1.5 text-[11px] font-semibold text-slate-500 hover:text-indigo-600">
                                        • {{ __($subSec['title']) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
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
    </div>
</x-app-layout>
