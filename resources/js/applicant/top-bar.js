import addGlobalScrollListener, { documentScrollPosY } from '../global-scroll-fn.js';

function toggleHeaderNavBg() {
    const scrollPosition = documentScrollPosY();

    const navbar = document.querySelector("header.top-nav");
    navbar.classList.toggle("bg-primary", scrollPosition > 50);
}

toggleHeaderNavBg();
addGlobalScrollListener(toggleHeaderNavBg);
