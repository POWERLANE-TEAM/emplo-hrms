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
    const currentYear = new Date().getFullYear(); // Get the current year

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
});

document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('select-year-report');
    window.dispatchEvent(new CustomEvent('year-changed', { 
        detail: dropdown.value 
    }));
});