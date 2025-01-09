document.addEventListener("DOMContentLoaded", () => {
    const section = document.querySelector(".scroll-trigger-section");

    // Check if section exists
    if (!section) {
        console.error("Section not found!");
        return;
    }

    // Debug: Confirm that the script is loaded
    console.log("Scroll event listener is set up...");

    // Add scroll event listener to window
    let hasScrolled = false;
    window.addEventListener("scroll", () => {
        if (!hasScrolled) {
            console.log("User has started scrolling! Applying visible class...");
            section.classList.add("visible-after-scroll");
            section.classList.remove("hidden-until-scroll");

            // Debug: Log section classes
            console.log("Current classes:", section.classList);

            // Ensure the effect only happens once
            hasScrolled = true;
        }
    });
});
