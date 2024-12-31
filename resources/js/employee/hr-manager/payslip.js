import "../../script.js";
import GLOBAL_CONST from "../../global-constant.js";
import addGlobalListener from "globalListener-script";
import "../../auth-listener.js";
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";

document.addEventListener('DOMContentLoaded', () => {
    if (typeof FilePond !== 'undefined') {

        FilePond.create(document.querySelector('.filepond'), {
            allowMultiple: false,
            acceptedFileTypes: ['application/pdf'],
            labelIdle: 'Drag & Drop your files or <span class="filepond--label-action">Browse</span>',
            files: [],
        });

        console.log('FilePond initialized successfully.');
    } else {
        console.error('FilePond failed to initialize.');
    }


});
