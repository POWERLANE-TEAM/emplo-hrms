import '../../script.js';
import GLOBAL_CONST from '../../global-constant.js';
import initSidebar from '../side-top-bar.js';
import addGlobalListener from 'globalListener-script';
import ThemeManager, { initPageTheme, handleThemeBtn } from '../../theme-listener.js';
import '../../auth-listener.js';


const themeToggle = document.getElementById(`theme-toggle-btn`).closest('.dropdown');

initPageTheme(window.ThemeManager, themeToggle);

handleThemeBtn(themeToggle, window.ThemeManager, addGlobalListener);

document.addEventListener("DOMContentLoaded", (event) => {
    //
});

initSidebar();




