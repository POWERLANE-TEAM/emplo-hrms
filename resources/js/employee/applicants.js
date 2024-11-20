import '../script.js';
import GLOBAL_CONST from '../global-constant.js';
import initLucideIcons from '../icons/lucide.js';
import { GlobalListener } from 'globalListener-script';
import '../auth-listener.js';
import 'employee-page-script';



new GlobalListener('change', document, `#examination-date`, (event) => {
    Livewire.dispatch('examination-date-event', { minDate: event.target.value });
});

new GlobalListener('click', document, `#applicant-profile-sched[name="submit"]`, () => {
    Livewire.dispatch('submit-init-interview-sched-form');
    Livewire.dispatch('submit-exam-sched-form');
});

new GlobalListener('click', document, `[aria-controls="iframe-applicant-resume"]`, (event) => {
    const resumeViewer = document.getElementById('iframe-applicant-resume');
    const container = resumeViewer.parentElement;
    console.log('clicked');
    if (!document.fullscreenElement) {
        event.target.classList.remove('text-dark');
        event.target.classList.add('text-light');
        container.requestFullscreen();
    } else {
        event.target.classList.remove('text-light');
        event.target.classList.add('text-dark');
        document.exitFullscreen();
    }
});



// document.addEventListener('DOMContentLoaded', function() {
//     const resumeViewer = document.getElementById('applicant-resume');
//     const fullScreenButton = document.querySelector('[aria-controls="applicant-resume"]');


//     fullScreenButton.addEventListener('click', function() {
//         if (!document.fullscreenElement) {
//             fullScreenButton.classList.remove('text-dark');
//             fullScreenButton.classList.add('text-light');
//             container.requestFullscreen();
//         } else {
//             fullScreenButton.classList.remove('text-light');
//             fullScreenButton.classList.add('text-dark');
//             document.exitFullscreen();
//         }
//     });
// });




