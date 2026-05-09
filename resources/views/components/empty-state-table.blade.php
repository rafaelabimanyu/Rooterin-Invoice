@props([
    'title' => 'No data found',
    'description' => 'You haven\'t added any records yet.',
    'buttonLabel' => 'Add New Record',
    'buttonLink' => '#',
    'icon' => 'file-search'
])

<div class="flex flex-col items-center justify-center py-16 px-4">
    <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center mb-6">
        <i data-lucide="{{ $icon }}" class="w-10 h-10 text-slate-300 dark:text-slate-600"></i>
    </div>
    <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $title }}</h4>
    <p class="text-slate-500 dark:text-slate-400 text-sm text-center max-w-xs mb-8">
        {{ $description }}
    </p>
    @if($buttonLink !== '#')
        <a href="{{ $buttonLink }}" class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/20">
            <i data-lucide="plus" class="w-4 h-4"></i>
            {{ $buttonLabel }}
        </a>
    @endif
</div>
