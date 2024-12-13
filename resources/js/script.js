

function disableSubmit() {
    document.querySelectorAll('form:has(:invalid) button[type="submit"],form:has(:invalid) button:not([type])').forEach(button => {
        if (!button.closest('form[action*="logout"]')) {
            button.disabled = true;
        }

    });

    // Disable elements based on the style selector
    document.querySelectorAll('.submit, .submit-link, [wire\\:click*="validate"]').forEach(element => {
        element.disabled = true;
    });
}

try {
    const currentWebpage = window.location.href;
    const prevWebpage = sessionStorage.getItem('currentWebpage');

    if (currentWebpage != prevWebpage) {
        sessionStorage.setItem('prevWebpage', prevWebpage);
    }
    sessionStorage.setItem('currentWebpage', currentWebpage);

    const thisprevWebpage = sessionStorage.getItem('prevWebpage');
    const thisCurrentWebpage = sessionStorage.getItem('currentWebpage');
    console.log(thisCurrentWebpage);
    console.log(thisprevWebpage);

    const userLanguage = navigator.language || navigator.userLanguage;
    console.log(`Preferred language: ${userLanguage}`);

} catch (error) {
    console.error(error)
}

document.addEventListener('DOMContentLoaded', () => {
    disableSubmit();
});

try {

    document.addEventListener('livewire:initialized', () => {
        document.addEventListener('livewire:navigate', () => {
            disableSubmit();
        });

        Livewire.hook('request', ({ el, component }) => {

            setTimeout(() => {
                disableSubmit();
            }, 400);

        })
    })


} catch (error) {
    console.error(error)
}

// Hides first before loading
document.querySelectorAll('.hidden-until-load').forEach(element => {
    element.classList.remove('hidden-until-load');
});

// Truncates announcement items
document.querySelectorAll('.announcement-item').forEach(element => {
    const words = element.innerText.split(' ');

    if (words.length > 20) {
        element.innerText = words.slice(0, 15).join(' ') + '...';
    }
});


// ================================
// Toggle of Active Tab Sections
// ================================


document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".tab-link");
    const sections = document.querySelectorAll(".tab-section");

    // Default active tab and section
    tabs[0].classList.add("fw-bold", "text-primary", "underline-padded");
    tabs[0].classList.remove("text-muted"); 
    sections[0].classList.add("active-section");

    tabs.forEach((tab) => {
        tab.addEventListener("click", (e) => {
            e.preventDefault();

            // Get the target section from the tab's data-section attribute
            const targetSection = document.getElementById(tab.dataset.section);

            // Remove active classes and text-muted from all tabs and sections
            tabs.forEach((t) => {
                t.classList.remove("fw-bold", "text-primary", "underline-padded");
                t.classList.add("text-muted"); 
            });
            sections.forEach((section) => {
                section.classList.remove("active-section");
            });

            // Add active classes to clicked tab and corresponding section
            tab.classList.add("fw-bold", "text-primary", "underline-padded");
            tab.classList.remove("text-muted"); 
            targetSection.classList.add("active-section");
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const notificationContainer = document.querySelector(".notification-container");

    // Prevent closing when clicking inside the notifications container
    notificationContainer.addEventListener("click", function (event) {
        event.stopPropagation();
    });

    // Close notifications when clicking outside
    document.addEventListener("click", function () {
        notificationContainer.classList.remove("show");
    });
});