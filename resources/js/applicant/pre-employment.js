import '../script.js';
import './applicant.js';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
// import './livewire.js'



document.addEventListener("DOMContentLoaded", (event) => {
    //

});

let csrf_token;

document.addEventListener('livewire:init', () => {
    Livewire.on('pre-employment-docs-rendered', (event) => {
        csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('rendered')
    });

});




