import initSidebar from 'emp-sidebar-script';
import addGlobalListener from 'globalListener-script';
import ThemeManager, { initPageTheme, handleThemeBtn } from 'theme-listener-script';
const themeManager = new ThemeManager();
const themeToggle = document.getElementById(`theme-toggle-btn`).closest('.dropdown');

const initAll = () => {
    try {
        initPageTheme(themeManager, themeToggle);
        handleThemeBtn(themeToggle, themeManager, addGlobalListener);
        initSidebar();
    } catch (error) {
        console.error(error);
    }
};

document.addEventListener('livewire:navigated', initAll);
document.addEventListener('DOMContentLoaded', initAll);
