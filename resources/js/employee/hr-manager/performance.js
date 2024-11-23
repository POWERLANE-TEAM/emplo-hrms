import "../../script.js";
import GLOBAL_CONST from "../../global-constant.js";
import initLucideIcons from "../../icons/lucide.js";
import addGlobalListener from "globalListener-script";
import "../../auth-listener.js";
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";


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

