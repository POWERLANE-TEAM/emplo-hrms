
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


