import 'bootstrap'; // Bootstrap Framework

import "../css/index.css";
import initLucideIcons from './icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from './global-scroll-fn.js';



document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();
});

function toggleHeaderNavBg() {
    const scrollPosition = documentScrollPosY();

    const navbar = document.querySelector("header.top-nav");
    navbar.classList.toggle("bg-primary", scrollPosition > 50);
}

// usage
toggleHeaderNavBg();
addGlobalScrollListener(toggleHeaderNavBg);
