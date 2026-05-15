<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-10 items-start">
        
        <!-- Sidebar Navigation -->
        @include('guide.partials.sidebar')

        <!-- Content Area -->
        <div class="flex-1 max-w-4xl space-y-10 pb-20 w-full">
            
            <!-- Contextual Search Bar -->
            @include('guide.partials.search-bar')
            
            <!-- Header -->
            <div class="space-y-4 mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 rounded-full text-indigo-600 text-[10px] font-black uppercase tracking-widest border border-indigo-100 shadow-sm">
                    <i data-lucide="{{ $guideData['header']['icon'] ?? 'book-open' }}" class="w-3 h-3"></i> 
                    {{ strtoupper($role) }} KNOWLEDGE BASE
                </div>
                <h1 class="text-5xl font-black text-slate-900 font-outfit tracking-tight">{{ __($guideData['header']['title']) }}</h1>
                <p class="text-lg text-slate-500 leading-relaxed max-w-2xl">{{ __($guideData['header']['subtitle']) }}</p>
            </div>

            <!-- Workflow Diagram -->
            @include('guide.partials.workflow-diagram')

            <!-- Active Section Content -->
            <div class="pt-8 border-t border-slate-100">
                @include('guide.partials.section')
            </div>

            <!-- Footer Navigation (Next/Prev) Placeholder -->
            <div class="pt-16 mt-16 border-t border-slate-100 flex justify-between items-center text-sm font-semibold text-slate-500">
                <p>&copy; {{ date('Y') }} Rooterin Enterprise</p>
                <a href="#top" class="hover:text-indigo-600 transition-colors flex items-center">
                    Back to top <i data-lucide="arrow-up" class="w-4 h-4 ml-2"></i>
                </a>
            </div>
            
        </div>
    </div>
</x-app-layout>
