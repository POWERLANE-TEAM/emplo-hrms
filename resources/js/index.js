
import "../css/index.css";
import initLucideIcons from './icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from './global-scroll-fn.js';
import addGlobalListener, { GlobalListener } from './global-event-listener.js';
import togglePassword from './toggle-password.js';
import { initPasswordEvaluator, evalPassword } from './forms/eval-password.js';
import InputValidator, { setInvalidMessage } from './forms/input-validator.js';
import initEmailValidation, { validateEmail } from './forms/email-validation.js';
import initPasswordValidation, { validatePassword } from './forms/password-validation.js';
import initPasswordConfirmValidation, { validateConfirmPassword } from './forms/password-confirm-validation.js';
import debounce from './debounce-fn.js';
import './applicant/top-bar.js'
// import './livewire.js'

document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();
});

document.addEventListener('livewire:init', () => {
    let loadSignUp = Livewire.on('guest-sign-up-load', (event) => {
        initPasswordEvaluator();
        loadSignUp();
    });


    Livewire.on('guest-job-view-pane-rendered', (event) => {
        setTimeout(() => {
            initLucideIcons();
        }, 0);
    });
    Livewire.on('guest-sign-up-rendered', (event) => {
        setTimeout(() => {
            initLucideIcons();
        }, 0);
    });


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

togglePassword(`form[action='applicant/sign-up']`, `#signUp-password`, `#toggle-psw`);
togglePassword(`form[action='applicant/sign-up']`, `#signUp-password-confirm`, `#toggle-psw-confirm`);

let sigUpFormString = `form[action='applicant/sign-up']`;

let signUpBtn = `#signUpBtn`;

function checkConsent(consentForm) {
    return consentForm.checked;
}

function checkCaptcha() {
    return true;
}

const signUpBool = {
    isValidEmail: false,
    isValidPassword: false,
    isPasswordMatch: false,
}

const signUpConsentEvent = new GlobalListener('input', document, `${sigUpFormString} input[name="consent"]`, function (event) {
    validateSignUpForm(sigUpFormString);
});

function validateSignUpForm(sigUpFormString = `form[action='applicant/sign-up']`) {
    const stack = new Error().stack;


    let signUpBtn = document.querySelector(`${sigUpFormString} #signUpBtn`);
    let passwordInput = document.querySelector(`${sigUpFormString} input[name="password"]`);

    signUpBtn.disabled = true;

    let consentAgreed = checkConsent(document.querySelector(`${sigUpFormString} input[name="consent"]`));
    let isCaptchaValid = checkCaptcha();

    let isWeakPassword;
    try {
        isWeakPassword = evalPassword(passwordInput.value);
    } catch (error) {
        console.warn('Password evaluator not available.')
    }

    if (!stack.includes('email-validation.js')) {
        signUpBool.isValidEmail = validateEmail(`${sigUpFormString} input[name="email"]`);
    }

    if (!stack.includes('password-validation.js')) {
        signUpBool.isValidPassword = validatePassword(`${sigUpFormString} input[name="password"]`);
    }

    if (!stack.includes('password-confirm-validation.js')) {
        signUpBool.isPasswordMatch = validateConfirmPassword(`${sigUpFormString} input[name="password_confirmation"]`);
    }

    // console.log(sigUpFormString)
    console.log(signUpBool)
    // console.log(consentAgreed)
    // console.log(isWeakPassword)
    if (!signUpBool.isValidEmail || !signUpBool.isValidPassword) {
        signUpBtn.disabled = true;
    } else
        if (isWeakPassword?.valueOf() == true) {
            signUpBtn.disabled = true;
            passwordInput.classList.add('is-invalid');
            setInvalidMessage(passwordInput, 'Password is weak.');
        } else if (!signUpBool.isPasswordMatch || !consentAgreed || !isCaptchaValid) {
            signUpBtn.disabled = true;
        } else {
            passwordInput.classList.remove('is-invalid');
            signUpBtn.disabled = false;
            return true;
        }

}

initEmailValidation(`${sigUpFormString} input[name="email"]`, () => {
    validateSignUpForm(sigUpFormString);
}, signUpBool);

initPasswordValidation(`${sigUpFormString} input[name="password"]`, () => {
    validateSignUpForm(sigUpFormString);
}, signUpBool);

initPasswordConfirmValidation(`${sigUpFormString} input[name="password_confirmation"]`, () => {
    validateSignUpForm(sigUpFormString);
}, signUpBool);

const passwordConfirmPaste = new GlobalListener('paste', document, `${sigUpFormString} input[name="password_confirmation"]`, e => e.preventDefault());
const passwordConfirmDrop = new GlobalListener('drop', document, `${sigUpFormString} input[name="password_confirmation"]`, e => e.preventDefault());

function validateOnSignUp(event) {
    validateSignUpForm();
}

const signUpEvent = new GlobalListener('click', document, `${sigUpFormString} ${signUpBtn}`, validateOnSignUp);


/* ----------------------------------------------------
    END: CHECK SIGNUP FORM
------------------------------------------------------- */

// console.log(document.querySelector(`form[action='applicant/sign-up'] #signUpBtn`))


// $("#form_id").trigger("reset");


