import './bootstrap.js';
import * as lucide from 'lucide';

// Make lucide available globally
window.lucide = lucide;

// Livewire v3 handles Alpine initialization automatically.
// If you need to register plugins, do it before Alpine starts.
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.plugin(persist);
}
// Do NOT call Alpine.start() here as Livewire v3 will handle it.

// Re-initialize Lucide icons on Livewire navigation
document.addEventListener('livewire:navigated', () => { 
    lucide.createIcons(); 
});

// Initialize Lucide icons on initial page load
lucide.createIcons();

// Re-initialize Lucide icons after Livewire component updates (morph)
if (window.Livewire) {
    registerLivewireHooks();
} else {
    document.addEventListener('livewire:init', () => {
        registerLivewireHooks();
    });
}

function registerLivewireHooks() {
    Livewire.hook('morph.updated', ({ el }) => {
        lucide.createIcons();
    });
}
