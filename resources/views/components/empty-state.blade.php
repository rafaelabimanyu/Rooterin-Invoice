@props([
    'icon' => 'layers',
    'title' => 'No data found',
    'description' => 'Try adjusting your search or filters to find what you are looking for.',
    'action' => null,
    'actionLabel' => 'Create New'
])

<div class="flex flex-col items-center justify-center py-20 px-8 text-center max-w-sm mx-auto">
    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 text-slate-300">
        <i data-lucide="{{ $icon }}" class="w-8 h-8"></i>
    </div>
    <h3 class="text-base font-bold text-slate-900 font-outfit mb-2">{{ $title }}</h3>
    <p class="text-xs text-slate-500 leading-relaxed mb-8">{{ $description }}</p>
    
    @if($action)
        <a href="{{ $action }}" class="btn-premium">
            <i data-lucide="plus" class="w-4 h-4"></i>
            {{ $actionLabel }}
        </a>
    @endif
</div>
