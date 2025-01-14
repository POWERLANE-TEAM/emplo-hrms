import './bootstrap'; // Laravel axios
import '../../vendor/rappasoft/laravel-livewire-tables/resources/imports/laravel-livewire-tables-all.js'; 
import * as bootstrap from 'bootstrap'; // Add this line
window.bootstrap = bootstrap; // Add this to make bootstrap globally available

import.meta.glob([
    '../images/**',
]);
