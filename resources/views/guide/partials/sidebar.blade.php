<aside class="w-full lg:w-64 sticky top-24 lg:h-[calc(100vh-120px)] overflow-y-auto hidden lg:block border-r border-slate-100 pr-6">
    <nav class="space-y-1">
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
                        <a href="#{{ $subKey }}" class="block px-3 py-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-700 hover:bg-indigo-50/50 rounded-md transition-colors">
                            {{ __($subSec['title']) }}
                        </a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </nav>
</aside>
