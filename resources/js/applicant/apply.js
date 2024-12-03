import '../script.js';
import initLucideIcons from '../icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from '../global-scroll-fn.js';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import togglePassword from '../toggle-password.js';
import { initPasswordEvaluator, evalPassword } from '../forms/eval-password.js';
import InputValidator, { setInvalidMessage } from '../forms/input-validator.js';
import initEmailValidation, { validateEmail } from '../forms/email-validation.js';
import PasswordValidator from '../forms/password-validation.js';
import initPasswordConfirmValidation, { validateConfirmPassword } from '../forms/password-confirm-validation.js';
import debounce from '../debounce-fn.js';
import initIframeFullScreener from 'iframe-full-screener-script';
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









