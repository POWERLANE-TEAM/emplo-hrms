import addGlobalListener from '../global-event-listener.js';

export default function initSidebar() {
    const mainMenuBtn = document.querySelector(`button.main-menu`);
    const mainSideBar = document.querySelector(`sidebar.main-menu`);

    mainMenuBtn.onclick = function () {
        if (mainSideBar.classList.contains('active')) {
            mainSideBar.classList.remove('active');
        } else {
            mainSideBar.classList.add('active', 'opening');

            setTimeout(() => {
                mainSideBar.classList.remove('opening');
            }, 100);
        }
    };

    let sideBarMenu = document.querySelector(`sidebar.main-menu`);

    addGlobalListener("click", sideBarMenu, "a", e => {
        let sidebarLinks = sideBarMenu.querySelectorAll(`a`);

        sidebarLinks.forEach(link => {
            link.classList.remove('active');
        });

        e.target.classList.toggle("active");
    })
}
