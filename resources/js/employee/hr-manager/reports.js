// Imports
import "../../script.js";
import GLOBAL_CONST from "../../global-constant.js";
import addGlobalListener from "globalListener-script";
import "../../auth-listener.js";
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";

document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('priority');
    // Dispatch initial value when page loads
    window.dispatchEvent(new CustomEvent('year-changed', { 
        detail: dropdown.value 
    }));
});