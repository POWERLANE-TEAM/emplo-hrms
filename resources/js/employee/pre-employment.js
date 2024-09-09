
import "../../css/employee/pre-employment.css";
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

document.addEventListener('livewire:init', () => {
    //

});


document.addEventListener("DOMContentLoaded", (event) => {

});



