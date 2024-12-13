import '../script.js';
import initLucideIcons from '../icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import InputValidator, { setInvalidMessage } from '../forms/input-validator.js';
import initEmailValidation, { validateEmail } from '../forms/email-validation.js';
import initIframeFullScreener from 'iframe-full-screener-script';
import NameValidator from 'name-validator-script';
import { LAST_NAME_VALIDATION, MIDDLE_NAME_VALIDATION, FIRST_NAME_VALIDATION } from 'name-validate-rule';
import 'websocket-script';
// import './livewire.js'


document.addEventListener('livewire:navigate', () => {
    Livewire.hook('morph.removed', ({ el, component }) => {
        initLucideIcons();
    })
    setTimeout(() => {
        initLucideIcons();
    }, 0);
});

document.addEventListener('livewire:init', () => {
    LivewireFilePond.registerPlugin(FilePondPluginPdfPreview);
});

initIframeFullScreener('apply-resume-preview');


let hasUnsavedChanges = false;
let logoutCallback = null;

try {
    Echo.private(`applicant.applying.${AUTH_BROADCAST_ID}`)
        //  I think livewire is already hnadling the listener for this event
        .listen('Guest.ResumeParsed', (event) => {
            // maybe show a toast message
        })
} catch (error) {

}

LAST_NAME_VALIDATION.attributes.type = 'list';
FIRST_NAME_VALIDATION.attributes.type = 'list';
MIDDLE_NAME_VALIDATION.attributes.type = 'list';

// On input client validations
const applicationForm = 'form[id="application-wizard-form"]';

const firstNameValidator = new NameValidator(`${applicationForm} input[name="applicant.name.firstName"]`, new InputValidator(FIRST_NAME_VALIDATION));
const middleNameValidator = new NameValidator(`${applicationForm} input[name="applicant.name.middleName"]`, new InputValidator(MIDDLE_NAME_VALIDATION));
const lastNameValidator = new NameValidator(`${applicationForm} input[name="applicant.name.lastName"]`, new InputValidator(LAST_NAME_VALIDATION));

firstNameValidator.initValidation(null, null);
middleNameValidator.initValidation(null, null);
lastNameValidator.initValidation(null, null);

initEmailValidation(`${applicationForm} input[name="applicant.email"]`, null, null);




