import initSidebar from 'emp-sidebar-script';
import addGlobalListener from 'globalListener-script';
import { initPageTheme, handleThemeBtn } from 'theme-listener-script';

const themeToggle = document.getElementById(`theme-toggle-btn`).closest('.dropdown');

const initAll = () => {
    try {
        document.querySelectorAll('.not-transition').forEach(element => {
            element.classList.remove('not-transition');
        });
        initPageTheme(window.ThemeManager, themeToggle);
        handleThemeBtn(themeToggle, window.ThemeManager, addGlobalListener);
        initSidebar();
    } catch (error) {
        console.error(error);
    }
};

document.addEventListener('livewire:navigated', initAll);

