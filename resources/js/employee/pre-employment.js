import '../script.js';
import initLucideIcons from '../icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from '../global-scroll-fn.js';
import addGlobalListener, { GlobalListener } from '../global-event-listener.js';
import ThemeManager, { initPageTheme, handleThemeBtn } from '../theme-listener.js';
// import './livewire.js'

const themeManager = new ThemeManager();
const themeToggle = document.getElementById(`theme-toggle-btn`).closest('.dropdown');

initPageTheme(themeManager, themeToggle);
handleThemeBtn(themeToggle, themeManager, addGlobalListener);

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




