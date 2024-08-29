
import "../css/index.css";
import initLucideIcons from './icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from './global-scroll-fn.js';
import addGlobalListener from './global-event-listener.js';
import togglePassword from './toggle-password.js';
import InputValidator, { setInvalidMessage } from './forms/input-validator.js';
import initEmailValidation from './forms/email-validation.js';
import initPasswordValidation from './forms/password-validation.js';
import debounce from './debounce-fn.js';
import './applicant/top-bar.js'
// import './livewire.js'

document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();
});

togglePassword(`form[action='applicant/sign-up']`, `#signUp-password`, `input.toggle-password`);

initEmailValidation(`form[action='applicant/sign-up'] input[name="email"]`);
initPasswordValidation(`form[action='applicant/sign-up'] input[name="password"]`);


console.log(document.querySelector(`form[action='applicant/sign-up'] input[name="email"]`))


// $("#form_id").trigger("reset");


