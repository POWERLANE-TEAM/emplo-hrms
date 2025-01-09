// '/node_modules/bootstrap'; // Bootstrap Framework

import "../../../css/employee/main.css";
import '../../script.js';
import GLOBAL_CONST from '../../global-constant.js';
import initSidebar from '../side-top-bar.js';
import addGlobalListener from 'globalListener-script';
import ThemeManager, { initPageTheme, handleThemeBtn } from '../../theme-listener.js';
import '../../auth-listener.js';
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";


const themeToggle = document.getElementById(`theme-toggle-btn`).closest('.dropdown');

initPageTheme(window.ThemeManager, themeToggle);

handleThemeBtn(themeToggle, window.ThemeManager, addGlobalListener);

document.addEventListener("DOMContentLoaded", (event) => {
    //
});

initSidebar();




