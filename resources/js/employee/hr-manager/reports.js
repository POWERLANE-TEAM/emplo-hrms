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
        
        const showReports = selectedYear < currentYear || 
        (selectedYear == currentYear && currentMonth == 12);
        
        if (reportsContent) reportsContent.style.display = showReports ? 'block' : 'none';
        if (emptyState) emptyState.style.display = showReports ? 'none' : 'block';
        
        yearSpans.forEach(span => {
            span.textContent = selectedYear;
        });
    }

    window.addEventListener('year-changed', function(event) {
        const selectedYear = parseInt(event.detail);
        updateReportsVisibility(selectedYear);
    });

    window.dispatchEvent(new CustomEvent('year-changed', { 
        detail: selectYearElement.value 
    }));
});