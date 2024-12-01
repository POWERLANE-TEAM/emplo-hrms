import "../../script.js";
import GLOBAL_CONST from "../../global-constant.js";
import initLucideIcons from "../../icons/lucide.js";
import addGlobalListener from "globalListener-script";
import "../../auth-listener.js";
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";


export function toggleText(span) {
    var qualificationsText = span.previousElementSibling; 

    qualificationsText.classList.toggle("expanded");

    if (qualificationsText.classList.contains("expanded")) {
        span.textContent = "See Less";  
    } else {
        span.textContent = "See More"; 
    }
}

function checkOverflow() {
    const qualificationTextElements = document.querySelectorAll('.qualifications-text');
    
    qualificationTextElements.forEach((element) => {
        const seeMoreButton = element.nextElementSibling;

        // Check if the element is overflowing
        if (element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth) {
            seeMoreButton.classList.add('show'); // Show "See More" button
        } else {
            seeMoreButton.classList.remove('show'); // Hide "See More" button
        }
    });
}

window.addEventListener('load', checkOverflow); // Run check on page load
window.addEventListener('resize', checkOverflow); // Recheck on window resize
window.toggleText = toggleText;
