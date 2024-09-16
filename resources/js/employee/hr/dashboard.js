import "../../../css/employee/dashboard.css";
import '../../script.js';
import GLOBAL_CONST from '../../global-constant.js';
import initSidebar from '../side-top-bar.js';
import initLucideIcons from '../../icons/lucide.js';
import addGlobalListener from '../../global-event-listener.js';
import ThemeManager, { initPageTheme, handleThemeBtn } from '../../theme-listener.js';
import '../../auth-listener.js';

const themeManager = new ThemeManager();
const themeToggle = document.getElementById(`theme-toggle-btn`).closest('.dropdown');

initPageTheme(themeManager, themeToggle);

handleThemeBtn(themeToggle, themeManager, addGlobalListener);

document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();
});

initSidebar();




