// Imports
import "../../script.js";
import GLOBAL_CONST from "../../global-constant.js";
import addGlobalListener from "globalListener-script";
import "../../auth-listener.js";
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";

document.addEventListener('DOMContentLoaded', function() {
    const selectYearElement = document.getElementById('select-year-report');
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1; // Get current month (1-12)

    // Start year is 2020. This can be changed.
    const startYear = 2020;
    const endYear = currentYear;

    selectYearElement.innerHTML = '';

    // Add the new options dynamically from start year up to the current year
    for (let year = startYear; year <= endYear; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        selectYearElement.appendChild(option);
    }
    
    selectYearElement.value = currentYear;

    // Function to handle reports visibility
    function updateReportsVisibility(selectedYear) {
        const reportsContent = document.querySelector('.reports-content');
        const emptyState = document.querySelector('.empty-state');
        const yearSpans = document.querySelectorAll('.selected-year');
        
        // Show reports if it's a previous year OR if it's December of current year
        const showReports = selectedYear < currentYear || 
        (selectedYear == currentYear && currentMonth == 12);
        // BACK-END REPLACE NOTE: Replace the 12 to 1 to test.
        
        if (reportsContent) {
            reportsContent.classList.toggle('hidden', !showReports);
            reportsContent.classList.toggle('visible', showReports);
        }
        if (emptyState) {
            emptyState.classList.toggle('hidden', showReports);
            emptyState.classList.toggle('visible', !showReports);
        }
        
        // Update all year spans in the empty state message
        yearSpans.forEach(span => {
            span.textContent = selectedYear;
        });
    }

    // Listen for year changes
    window.addEventListener('year-changed', function(event) {
        const selectedYear = parseInt(event.detail);
        updateReportsVisibility(selectedYear);
    });

    // Dispatch initial event
    window.dispatchEvent(new CustomEvent('year-changed', { 
        detail: selectYearElement.value 
    }));
});