@props([
    'title' => 'No data found',
    'description' => 'You haven\'t added any records yet.',
    'buttonLabel' => 'Add New Record',
    'buttonLink' => '#',
    'icon' => 'file-search'
])

<div class="flex flex-col items-center justify-center py-16 px-4">
    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mb-6">
        <i data-lucide="{{ $icon }}" class="w-10 h-10 text-slate-300"></i>
    </div>
    <h4 class="text-lg font-bold text-slate-900 mb-1">{{ $title }}</h4>
    <p class="text-slate-500 text-sm text-center max-w-xs mb-8">
        {{ $description }}
    </p>
    @if($buttonLink !== '#')
        <a href="{{ $buttonLink }}" class="flex items-center gap-2 px-6 py-2.5 bg-gold-500 hover:bg-gold-600 text-slate-950 rounded-xl font-black transition-all shadow-lg shadow-gold-500/20">
            <i data-lucide="plus" class="w-4 h-4"></i>
            {{ $buttonLabel }}
        </a>
    @endif
</div>
