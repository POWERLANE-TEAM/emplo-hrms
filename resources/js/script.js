

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
