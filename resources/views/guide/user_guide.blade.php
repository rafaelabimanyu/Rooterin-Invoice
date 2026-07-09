<x-app-layout :title="app()->getLocale() == 'en' ? 'Operational SOP' : 'SOP Operasional'">
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 px-4 md:px-0">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-gold-600 transition-colors">{{ app()->getLocale() == 'en' ? 'Dashboard' : 'Dasbor' }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900">{{ app()->getLocale() == 'en' ? 'Operational SOP' : 'SOP Operasional' }}</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 font-outfit leading-tight">{{ app()->getLocale() == 'en' ? 'Operational SOP & User Guide' : 'SOP Operasional & Panduan Pengguna' }}</h1>
            <p class="text-slate-500 mt-1">{{ app()->getLocale() == 'en' ? 'Standard operating procedures for J&J GROUP systems.' : 'Prosedur operasional standar untuk sistem J&J GROUP.' }}</p>
        </div>
        <div>
            <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i>
                {{ app()->getLocale() == 'en' ? 'Print Guide' : 'Cetak Panduan' }}
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 md:px-0 pb-24">
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-[0_10px_30px_-5px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-8 md:p-12">
                <article class="prose prose-slate max-w-none prose-headings:font-outfit prose-headings:font-black prose-h1:text-3xl prose-h2:text-xl prose-h2:border-b prose-h2:border-slate-100 prose-h2:pb-3 prose-h2:mt-10 prose-a:text-gold-600 prose-a:no-underline hover:prose-a:underline prose-strong:text-slate-900 prose-li:text-sm prose-li:leading-relaxed prose-p:text-slate-650 prose-p:leading-relaxed">
                    {!! $content !!}
                </article>
            </div>
        </div>
    </div>
</x-app-layout>
