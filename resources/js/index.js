
import "../css/index.css";
import initLucideIcons from './icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from './global-scroll-fn.js';
import addGlobalListener from './global-event-listener.js';
import './applicant/top-bar.js'
// import './livewire.js'

document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();
});

// let selectedJob = null;

// const triggerTabList = document.querySelectorAll('#jobs-list button')
// triggerTabList.forEach(triggerEl => {

//     triggerEl.addEventListener('click', event => {
//         selectedJob = triggerEl.id;
//         console.log(selectedJob);
//     })

// })


let selectedJob = null;
let jobsList = document.getElementById('jobs-list')

addGlobalListener('click', jobsList, 'button', event => {
    selectedJob = event.target.id;
    console.log(selectedJob);
});
