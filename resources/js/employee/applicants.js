import '../script.js';
import GLOBAL_CONST from '../global-constant.js';
import { GlobalListener } from 'globalListener-script';
import '../auth-listener.js';
import 'employee-page-script';


// submit exam schedule
new GlobalListener('change', document, `#examination-date`, (event) => {
    Livewire.dispatch('examination-date-event', { minDate: event.target.value });
});

// submit exam result
new GlobalListener('click', document, `#submit-exam-result`, (event) => {
    // Todo make livewire and listen to this event
    Livewire.dispatch('exam-result-event');
});

new GlobalListener('click', document, `#toggle-assign-exam-modal`, (event) => {
    // Todo make livewire and listen to this evente
    window.openModal(`modal-assign-exam-result`);
});

new GlobalListener('click', document, `[data-bs-target="#edit-final-interview"]`, (event) => {
    console.log('clicked');
    window.openModal(`edit-final-interview`);
});

new GlobalListener('click', document, `#toggle-assign-final-interview-modal`, (event) => {
    // Todo make livewire and listen to this evente
    window.openModal(`modal-assign-exam-result`);
});

// new GlobalListener('click', document, `#`, (event) => {
//     // Todo make livewire and listen to this event
//     Livewire.dispatch('exam-result-event');
// });

// This is time consuming to implement
// const EXAM_RESULT_VALIDATION = {
//     clear_invalid: false,
//     attributes: {
//         type: 'select',
//         required: true,
//     },
//     customMsg: {
//         required: 'Exam result is required.',
//     },
//     errorFeedback: {
//         required: 'Exam result is required.',
//     }
// }

// let examResultValidator = new InputValidator(EXAM_RESULT_VALIDATION);

// function validateExamResult(elem) {
//     let isValid = examResultValidator.validate(elem, setInvalidMessage);
//     if (!isValid) {
//         elem.classList.add('is-invalid');
//     } else {
//         elem.classList.remove('is-invalid');
//     }
//     return isValid;
// }

// new GlobalListener('change', document, `#exam-result`, (event) => {
//     // Todo make livewire and listen to this evente
//     if(event.target ){

//     }
// });


new GlobalListener('click', document, `#toggle-assign-init-interview-modal`, (event) => {
    // Todo make livewire and listen to this evente
    window.openModal(`modal-assign-init-interview-result`);
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




