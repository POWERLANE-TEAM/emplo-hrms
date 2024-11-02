import addGlobalListener from '../global-event-listener.js';

export function handleMobileSidebar(mainSideBar) {

    document.addEventListener('click', (event) => {

        if (window.innerWidth < 576 && mainSideBar.classList.contains('active') && !mainSideBar.classList.contains('opening')) {
            if (mainSideBar.contains(event.target)) {
                return;
            }

            mainSideBar.classList.remove('active');
        }

    });

}

// Sidebar Toggle

export default function initSidebar() {
    let mainMenuToggles = document.querySelectorAll(`button.main-menu`);
    const mainSideBar = document.querySelector(`sidebar.main-menu`);
    let sidebarLinks = mainSideBar.querySelectorAll(`a`);

    window.addEventListener('resize', () => {
        /* Needes refactoring */
        if (window.innerWidth < 576 && !mainSideBar.classList.contains('active')) {
            let sidebarLinks = mainSideBar.querySelectorAll(`a`);

            sidebarLinks.forEach(link => {
                link.setAttribute('tabindex', '-1');
            });
        } else {
            sidebarLinks.forEach(link => {
                link.removeAttribute('tabindex');
            });
        }

        // handleMobileSidebar(mainSideBar);

    });

    handleMobileSidebar(mainSideBar);

    mainMenuToggles.forEach((btn) => {
        btn.onclick = function () {
            if (mainSideBar.classList.contains('active')) {
                mainSideBar.classList.remove('active');
                if (window.innerWidth < 576 && !mainSideBar.classList.contains('active')) {
                    let sidebarLinks = mainSideBar.querySelectorAll(`a`);

                    sidebarLinks.forEach(link => {
                        link.setAttribute('tabindex', '-1');
                    });
                }

                mainSideBar.classList.add('closing');

                setTimeout(() => {
                    mainSideBar.classList.remove('closing');
                }, 250);

            } else {
                mainSideBar.classList.add('active', 'opening');

                setTimeout(() => {
                    mainSideBar.classList.remove('opening');
                }, 280);


                sidebarLinks.forEach(link => {
                    link.removeAttribute('tabindex');
                });


            }
        };
    });

    addGlobalListener("click", mainSideBar, "a", e => {

        sidebarLinks.forEach(link => {
            link.classList.remove('active');
        });

        e.target.classList.toggle("active");
    })
}

// Customized dropdown behavior for User Profile icon in the top bar
function toggleUserDropdown() {
    const dropdownMenu = document.getElementById('dropdown-menu');
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
}

window.onclick = function(event) {
    const dropdownMenu = document.getElementById('dropdown-menu');
    const dropdownButton = document.getElementById('user-prof-btn');

    if (dropdownMenu.style.display === 'block' && !dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none';
    }
}
