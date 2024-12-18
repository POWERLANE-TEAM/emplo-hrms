import addGlobalListener from 'globalListener-script';
import ThemeManager, { initPageTheme, handleThemeBtn } from 'theme-listener-script';
const themeManager = new ThemeManager();
const themeToggle = document.getElementById(`theme-toggle-btn`).closest('.dropdown');

const initAll = () => {
    try {
        document.querySelectorAll('.not-transition').forEach(element => {
            element.classList.remove('not-transition');
        });
        initPageTheme(themeManager, themeToggle);
        handleThemeBtn(themeToggle, themeManager, addGlobalListener);
    } catch (error) {
        console.error(error);
    }
};

document.addEventListener('livewire:navigated', initAll);
document.addEventListener('DOMContentLoaded', initAll);
