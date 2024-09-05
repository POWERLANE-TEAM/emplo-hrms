
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


try {
    document.addEventListener('livewire:navigate', () => {
        // Remove any existing internal styles in the head
        document.querySelectorAll('style[data-livewire]').forEach(el => el.remove());

        // Add new styles from the currently loaded page
        const styles = document.querySelectorAll('style');
        styles.forEach(style => {
            const newStyle = document.createElement('style');
            newStyle.setAttribute('data-livewire', 'true');
            newStyle.innerHTML = style.innerHTML;
            document.head.appendChild(newStyle);
        });
    });
} catch (error) {
    console.error(error)
}


