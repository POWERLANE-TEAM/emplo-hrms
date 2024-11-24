
export default class ThemeManager {

    listenerFunc = null;
    defaultTheme = 'light';

    constructor() {
        this.pageBody = document.querySelector('body');
    }

    getUserPreference() {
        return localStorage.getItem('pageThemePreference') || 'system';
    }

    getSystemPreference() {
        const isLightMode = window.matchMedia('(prefers-color-scheme: light)').matches;
        return isLightMode ? 'light' : 'dark';
    }

    saveUserPreference(themePrefer) {
        localStorage.setItem('pageThemePreference', themePrefer);
    }

    setPageTheme(themePrefer, isSystem = false) {
        if (isSystem) {
            this.pageBody.setAttribute('data-bs-theme', this.getSystemPreference());
            this.saveUserPreference('system');
            return;
        }

        this.pageBody.setAttribute('data-bs-theme', themePrefer);

        this.saveUserPreference(themePrefer);

    }

    addListener() {
        if (this.listenerFunc) {
            console.log('already listening');
        }
        this.listenerFunc = window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', ({ matches }) => {
            if (this.getUserPreference() !== 'system') {
                // this.listenerFunc.removeEventListener('change', this.listenerFunc);
                return;
            }

            if (matches) {
                this.pageBody.setAttribute('data-bs-theme', 'light');
            } else {
                this.pageBody.setAttribute('data-bs-theme', 'dark');
            }
        });
    }
}

export function initPageTheme(themeManager, themeToggle = false) {
    const themePrefer = themeManager.getUserPreference();
    let prefersLightMode;
    const isSystem = themePrefer == 'system';
    const defaultTheme = 'light';

    try {

        if (themeToggle) {
            let themeOptions = themeToggle.querySelectorAll(`a`);

            themeOptions.forEach(option => {
                let optionValue = option.textContent.trim().toLowerCase();

                option.classList.remove('active');
                if (optionValue == 'system' && isSystem) {
                    option.classList.add('active');
                } else if (optionValue == themePrefer) {
                    option.classList.add('active');
                }
            });
        }

        if (isSystem) {
            const systemTheme = themeManager.getSystemPreference();
            themeManager.setPageTheme(systemTheme, isSystem);

        } else {

            themeManager.setPageTheme(themePrefer);
        }
    } catch (error) {
        console.error(error);
        themeManager.setPageTheme(defaultTheme);
    } finally {
        themeManager.addListener();
    }
}

// Dynamic storage of theme for exteral libraries
function updateTheme(theme) {
    const selectElements = document.querySelectorAll('.choices-custom, select, .choices__input, .choices__inner, .choices__list--dropdown, .choices__item, .choices__item--disabled');
    selectElements.forEach(select => {
        if (theme === 'dark') {
            select.classList.add('dark'); 
            select.classList.remove('light');  
        } else {
            select.classList.add('light'); 
            select.classList.remove('dark');
        }
    });
}

export function handleThemeBtn(themeToggle, themeManager, handlerFunc) {

    try {
        handlerFunc("click", themeToggle, ".dropdown-item", e => {
            let themeOptions = themeToggle.querySelectorAll(`.dropdown-item`);

            themeOptions.forEach(option => {
                option.classList.remove('active');
            });

            e.target.classList.toggle("active");

            let selectedTheme = e.target.textContent.trim().toLowerCase();
            let isSystem = e.target.getAttribute('data-isSystem');
            isSystem = isSystem == 'true';

            themeManager.setPageTheme(selectedTheme, isSystem);
            updateTheme(selectedTheme);

        })
    } catch (error) {
        console.error(error);
    }
}

