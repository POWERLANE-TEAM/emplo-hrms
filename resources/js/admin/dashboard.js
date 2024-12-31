import '../script.js';
import GLOBAL_CONST from '../global-constant.js';
import initSidebar from '../employee/side-top-bar.js';
import addGlobalListener from 'globalListener-script';
import ThemeManager, { initPageTheme, handleThemeBtn } from '../theme-listener.js';
import '../auth-listener.js';
import '../tooltip.js';


const themeToggle = document.getElementById(`theme-toggle-btn`).closest('.dropdown');

document.addEventListener('livewire:navigated', () => {
    initSidebar();
    initPageTheme(window.ThemeManager, themeToggle);
    handleThemeBtn(themeToggle, window.ThemeManager, addGlobalListener);
});
