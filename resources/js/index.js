
import "../css/index.css";
import initLucideIcons from './icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from './global-scroll-fn.js';
import addGlobalListener, { GlobalListener } from './global-event-listener.js';
import togglePassword from './toggle-password.js';
import InputValidator, { setInvalidMessage } from './forms/input-validator.js';
import initEmailValidation, { validateEmail } from './forms/email-validation.js';
import initPasswordValidation, { validatePassword } from './forms/password-validation.js';
import debounce from './debounce-fn.js';
import './applicant/top-bar.js'
// import './livewire.js'

document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();
});

/* ----------------------------------------------------
    START: CHECK SIGNUP FORM
------------------------------------------------------- */

/* ----------------------------------------------------
  TODO:
  1. Add Captcha
  2. Validate other form fields only if is touched
  3. Track for unsaved or unfinished form
  4. Reset form on confirmed discard signup (on modal dismiss)
------------------------------------------------------- */

let signUpForm = document.querySelector(`form[action='applicant/sign-up']`);

togglePassword(signUpForm, `#signUp-password`, `input.toggle-password`);

let sigUpFormString = `form[action='applicant/sign-up']`;

let signUpBtn = document.querySelector(`form[action='applicant/sign-up'] button`);

function checkConsent(consentForm) {
    return consentForm.checked;
}

function checkCaptcha() {
    return true;
}

const signUpConsentEvent = new GlobalListener('input', document, `${sigUpFormString} input[name="consent"]`, function (event) {
    validateSignUpForm(signUpBtn);
});

function validateSignUpForm(signUpBtn, sigUpFormString = `form[action='applicant/sign-up']`) {
    let passwordInput = document.querySelector(`${sigUpFormString} input[name="password"]`);

    let consentAgreed = checkConsent(document.querySelector(`${sigUpFormString} input[name="consent"]`));
    let isCaptchaValid = checkCaptcha();

    let isWeakPassword;
    try {
        isWeakPassword = evalPassword(passwordInput.value);
    } catch (error) {
        console.warn('Password evaluator not available.')
    }

    let isValidEmail = validateEmail(`${sigUpFormString} input[name="email"]`);
    let isValidPassword = validatePassword(`${sigUpFormString} input[name="password"]`);

    // console.log(sigUpFormString)
    // console.log(isValidEmail)
    // console.log(isValidPassword)
    // console.log(consentAgreed)
    // console.log(isWeakPassword)
    if (!(isValidEmail || isValidPassword) || !consentAgreed || !isCaptchaValid) {
        signUpBtn.disabled = true;
    } else if (isWeakPassword?.valueOf() == true) {
        signUpBtn.disabled = true;
        passwordInput.classList.add('is-invalid');
        setInvalidMessage(passwordInput, 'Password is weak.');
    } else {
        passwordInput.classList.remove('is-invalid');
        signUpBtn.disabled = false;
    }


}

initEmailValidation(`${sigUpFormString} input[name="email"]`, () => {
    validateSignUpForm(signUpBtn);
});

initPasswordValidation(`${sigUpFormString} input[name="password"]`, () => {
    validateSignUpForm(signUpBtn);
});

function validateOnSignUp(event) {
    console.log('singup clicked');
    validateSignUpForm(event.target);
}

const signUpEvent = new GlobalListener('click', document, `${sigUpFormString} #signUpBtn`, validateOnSignUp);

/* ----------------------------------------------------
    END: CHECK SIGNUP FORM
------------------------------------------------------- */

// console.log(document.querySelector(`form[action='applicant/sign-up'] #signUpBtn`))


// $("#form_id").trigger("reset");


