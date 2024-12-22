import '../script.js';
// import './script.js';
import initLucideIcons from '../icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
// import './livewire.js'



document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();

});

let csrf_token;

document.addEventListener('livewire:init', () => {
    Livewire.on('pre-employment-docs-rendered', (event) => {
        setTimeout(() => {
            initLucideIcons();

        }, 0);

        csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('rendered')
    });

});




