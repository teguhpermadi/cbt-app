import './globals/theme.js'; /* By Sheaf.dev */ 

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

// Import MathJax loader
import './mathjax-loader.js';

// now you can register
// components using Alpine.data(...) and
// plugins using Alpine.plugin(...) 

import './globals/modals.js';
 
Livewire.start()