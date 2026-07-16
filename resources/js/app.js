import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import { registerBlatUI } from './blatui-core.js';

// Register BlatUI directly into the bundled Alpine instance
registerBlatUI(Alpine);

// Start Livewire (which internally starts Alpine)
Livewire.start();
