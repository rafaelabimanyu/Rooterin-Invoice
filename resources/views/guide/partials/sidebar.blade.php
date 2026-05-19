<!-- Mobile Overlay -->
<div x-show="mobileMenuOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition-opacity ease-linear duration-300" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0" 
     class="fixed inset-0 bg-slate-900/60 z-[100] lg:hidden" 
     @click="mobileMenuOpen = false"
     style="display: none;">
</div>

<aside class="fixed inset-y-0 left-0 z-[110] w-72 bg-white transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 lg:z-0 lg:w-64 lg:sticky lg:top-24 lg:h-[calc(100vh-120px)] overflow-y-auto border-r border-slate-100 lg:pr-6"
       :class="mobileMenuOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full lg:shadow-none'"
       @click.outside="if(window.innerWidth < 1024) mobileMenuOpen = false"
       x-cloak
       >
    
    <!-- Mobile Close Button -->
    <div class="lg:hidden flex items-center justify-between p-4 border-b border-slate-100 mb-4">
        <span class="font-black text-slate-900 text-sm tracking-widest uppercase">{{ app()->getLocale() == 'en' ? 'Navigation' : 'Navigasi' }}</span>
        <button @click="mobileMenuOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg bg-slate-50 transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <nav class="space-y-1 px-4 lg:px-0 pb-8 lg:pb-0">
        <p class="px-3 mb-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
            {{ __($guideData['header']['title']) }}
        </p>
        
        @foreach($guideData['navigation'] as $key => $nav)
            <a href="{{ route('guide.index', $key) }}" 
               class="group flex items-center px-4 py-2.5 text-sm font-bold rounded-lg transition-all border {{ $activeSectionKey === $key ? 'bg-indigo-50 text-indigo-700 border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 border-transparent hover:border-slate-200' }}">
                <i data-lucide="{{ $nav['icon'] ?? 'file-text' }}" class="w-4 h-4 mr-3 {{ $activeSectionKey === $key ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                {{ is_array(__($nav['title'])) ? $nav['title'] : __($nav['title']) }}
            </a>
            
            @if(isset($nav['sub_sections']) && count($nav['sub_sections']) > 0 && $activeSectionKey === $key)
                <div class="ml-6 mt-2 mb-4 pl-4 border-l-2 border-indigo-100 space-y-1" x-data x-show="true" x-transition.opacity>
                    @foreach($nav['sub_sections'] as $subKey => $subSec)
                        <a href="#{{ $subKey }}" @click="if(window.innerWidth < 1024) mobileMenuOpen = false" class="block px-3 py-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-700 hover:bg-indigo-50/50 rounded-md transition-colors">
                            {{ __($subSec['title']) }}
                        </a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </nav>
</aside>
