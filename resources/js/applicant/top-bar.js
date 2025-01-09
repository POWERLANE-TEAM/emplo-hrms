import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';

function toggleHeaderNavBg() {
    const scrollPosition = documentScrollPosY();

    const navbar = document.querySelector("header.top-nav");
    navbar.classList.toggle("bg-primary", scrollPosition > 50);
    navbar.classList.toggle("shadow-sm", scrollPosition > 100);
}

toggleHeaderNavBg();
addGlobalScrollListener(toggleHeaderNavBg);
