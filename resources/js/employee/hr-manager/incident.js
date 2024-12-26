import "../../script.js";
import GLOBAL_CONST from "../../global-constant.js";
import addGlobalListener from "globalListener-script";
import "../../auth-listener.js";
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";

document.addEventListener('DOMContentLoaded', function () {

    // Toggle visibility of the Resolution Details section if incident is Resolved.

    const statusDropdown = document.getElementById('status');
    const resolutionDetailsField = document.getElementById('resolutionDetailsField');
    const resolutionDetails = document.getElementById('resolution_details');


    function toggleResolutionDetails() {
        if (statusDropdown.value === 'resolved') {
            resolutionDetailsField.style.display = 'block'; // Show Resolution Details
        } else {
            resolutionDetailsField.style.display = 'none'; // Hide Resolution Details
        }
    }

    // Initial check on page load
    toggleResolutionDetails();

    // Listen for change in status
    statusDropdown.addEventListener('change', toggleResolutionDetails);
});
