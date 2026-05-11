import './bootstrap.js';

// Livewire v3 handles Alpine initialization automatically.
// If you need to register plugins, do it before Alpine starts.
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.plugin(persist);
}
// Do NOT call Alpine.start() here as Livewire v3 will handle it.
