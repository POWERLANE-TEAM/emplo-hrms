// '/node_modules/bootstrap'; // Bootstrap Framework

import "../../../css/employee/main.css";
import '../../script.js';
import GLOBAL_CONST from '../../global-constant.js';
import initSidebar from '../side-top-bar.js';
import addGlobalListener from 'globalListener-script';
import { GlobalListener } from 'globalListener-script';
import ThemeManager, { initPageTheme, handleThemeBtn } from '../../theme-listener.js';
import '../../auth-listener.js';
import "employee-page-script";
import "../../modals.js";
import "../../tooltip.js";


const themeToggle = document.getElementById(`theme-toggle-btn`).closest('.dropdown');

initPageTheme(window.ThemeManager, themeToggle);

handleThemeBtn(themeToggle, window.ThemeManager, addGlobalListener);

document.addEventListener("DOMContentLoaded", (event) => {
    //
});

initSidebar();

new GlobalListener('click', document, `[aria-controls="iframe-applicant-resume"]`, (event) => {
    const resumeViewer = document.getElementById('iframe-applicant-resume');
    const container = resumeViewer.parentElement;
    console.log('clicked');
    if (!document.fullscreenElement) {
        event.target.classList.remove('text-dark');
        event.target.classList.add('text-light');
        container.requestFullscreen().then(() => {
            lucide.createIcons();
        });
    } else {
        event.target.classList.remove('text-light');
        event.target.classList.add('text-dark');
        document.exitFullscreen().then(() => {
            lucide.createIcons();
        });
    }
});


document.addEventListener('DOMContentLoaded', () => {
    if (typeof FilePond !== 'undefined') {

        // Plugins
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginPdfPreview);

        const pond = FilePond.create(document.querySelector('.filepond'), {
            allowMultiple: false, 
            maxFiles: 1,
            acceptedFileTypes: [
                'application/pdf'
            ],
            labelFileTypeNotAllowed: 'File type not supported.',
            fileValidateTypeLabelExpectedTypes: 'Expects {allTypes}',
            labelIdle: 'Drag & Drop your file or <span class="filepond--label-action">Browse</span>',
            // PDF Preview Options
            pdfPreviewHeight: 320,
            pdfPreviewShowCoverOnly: true,
            pdfPreviewGenerateThumbnails: true,
        });

        let pondMultipleTimeout;
        pond.onwarning = function() {
            var container = pond.element.parentNode;
            var error = container.querySelector('p.filepond--warning');
            if (!error) {
                showToast('danger', 'Only 1 file can be added at once');
            }
            requestAnimationFrame(function() {
                error.dataset.state = 'visible';
            });
            clearTimeout(pondMultipleTimeout);
            pondMultipleTimeout = setTimeout(function() {
                error.dataset.state = 'hidden';
            }, 5000);
        };

        console.log('FilePond initialized successfully with PDF and Word document support.');
    } else {
        console.error('FilePond failed to initialize.');
    }
});



