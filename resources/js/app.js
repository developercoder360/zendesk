import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import { registerBlatUI } from './blatui-core.js';

// Register BlatUI directly into the bundled Alpine instance
// Configure BlatUI to match the head script's default theme (system)
registerBlatUI(Alpine, { darkMode: 'system' });

// Ensure Livewire navigation doesn't reset the theme (livewire swaps the HTML tag, removing classes)
document.addEventListener('livewire:navigated', () => {
    if (window.Alpine && window.Alpine.store('theme')) {
        window.Alpine.store('theme').apply();
    }
});

// Start Livewire (which internally starts Alpine)
Livewire.start();
