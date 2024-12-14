console.log("HEREE");

function disableSubmit() {
    document
        .querySelectorAll(
            'form:has(:invalid) button[type="submit"],form:has(:invalid) button:not([type])'
        )
        .forEach((button) => {
            if (!button.closest('form[action*="logout"]')) {
                button.disabled = true;
            }
        });

    // Disable elements based on the style selector
    document
        .querySelectorAll('.submit, .submit-link, [wire\\:click*="validate"]')
        .forEach((element) => {
            element.disabled = true;
        });
}

try {
    const currentWebpage = window.location.href;
    const prevWebpage = sessionStorage.getItem("currentWebpage");

    if (currentWebpage != prevWebpage) {
        sessionStorage.setItem("prevWebpage", prevWebpage);
    }
    sessionStorage.setItem("currentWebpage", currentWebpage);

    const thisprevWebpage = sessionStorage.getItem("prevWebpage");
    const thisCurrentWebpage = sessionStorage.getItem("currentWebpage");
    console.log(thisCurrentWebpage);
    console.log(thisprevWebpage);

    const userLanguage = navigator.language || navigator.userLanguage;
    console.log(`Preferred language: ${userLanguage}`);
} catch (error) {
    console.error(error);
}

document.addEventListener("DOMContentLoaded", () => {
    disableSubmit();
});

try {
    document.addEventListener("livewire:initialized", () => {
        document.addEventListener("livewire:navigate", () => {
            disableSubmit();
        });

        Livewire.hook("request", ({ el, component }) => {
            setTimeout(() => {
                disableSubmit();
            }, 400);
        });
    });
} catch (error) {
    console.error(error);
}

// Hides first before loading
document.querySelectorAll(".hidden-until-load").forEach((element) => {
    element.classList.remove("hidden-until-load");
});

// Truncates announcement items
document.querySelectorAll(".announcement-item").forEach((element) => {
    const words = element.innerText.split(" ");

    if (words.length > 20) {
        element.innerText = words.slice(0, 15).join(" ") + "...";
    }
});

export function showToast(type, message, icon) {
    let toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i data-lucide="${icon}" class="me-2"></i> <strong>${message}</strong>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div> 
    `;
    
    const container = document.querySelector('.toast-container');
    if (container) {
        container.insertAdjacentHTML('beforeend', toastHtml);

        const toastElement = container.querySelector('.toast:last-child');
        const toast = new bootstrap.Toast(toastElement);

        toast.show();

        setTimeout(() => {
            toastElement.classList.add('fade');
            setTimeout(() => toastElement.remove(), 500);
        }, 5000);
    } else {
        console.error('Toast container not found!');
    }

    lucide.createIcons();
}

window.showToast = showToast;

