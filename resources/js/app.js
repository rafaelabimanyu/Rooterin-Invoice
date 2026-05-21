import './bootstrap.js';
import { createIcons, icons } from 'lucide';

// Make it available globally with fallback to default icons configuration
window.lucide = {
    createIcons: (options = {}) => createIcons({ icons, ...options }),
    icons
};

// Livewire v3 handles Alpine initialization automatically.
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.plugin(persist);
}

function initGlobalIcons() {
    window.lucide.createIcons();
}

// Initialize on DOM load and Livewire navigation
document.addEventListener('DOMContentLoaded', initGlobalIcons);
document.addEventListener('livewire:navigated', initGlobalIcons);

// Run immediately as Vite modules load after DOM construction
initGlobalIcons();

// Hook into Livewire morph updates to persist icons on component render
if (window.Livewire) {
    registerLivewireHooks();
} else {
    document.addEventListener('livewire:init', () => {
        registerLivewireHooks();
    });
}

function registerLivewireHooks() {
    Livewire.hook('morph.updated', ({ el, component }) => {
        window.lucide.createIcons();
    });
}
