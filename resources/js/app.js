import './bootstrap.js';
import { createIcons, icons } from 'lucide';

// Make it available globally
window.lucide = { createIcons, icons };

// Livewire v3 handles Alpine initialization automatically.
// If you need to register plugins, do it before Alpine starts.
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.plugin(persist);
}
// Do NOT call Alpine.start() here as Livewire v3 will handle it.

function initGlobalIcons() {
    createIcons({
        icons
    });
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
    Livewire.hook('morph.updated', () => {
        initGlobalIcons();
    });
}
