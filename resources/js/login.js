
import "../css/login.css";
import './script.js';
import initLucideIcons from './icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from './global-scroll-fn.js';
import addGlobalListener, { GlobalListener } from './global-event-listener.js';
import togglePassword from './toggle-password.js';
import { initPasswordEvaluator, evalPassword } from './forms/eval-password.js';
import InputValidator, { setInvalidMessage } from './forms/input-validator.js';
import initEmailValidation, { validateEmail } from './forms/email-validation.js';
import PasswordValidator from './forms/password-validation.js';
import initPasswordConfirmValidation, { validateConfirmPassword } from './forms/password-confirm-validation.js';
import debounce from './debounce-fn.js';
// import './livewire.js'

document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();
});

document.addEventListener('livewire:init', () => {
    initPasswordEvaluator();
    setTimeout(() => {
        initLucideIcons();
    }, 0);

});

document.addEventListener('livewire:navigate', (event) => {
    initLucideIcons();
})


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

togglePassword(`form[action='/login']`, `#signUp-password`, `#toggle-psw`);
togglePassword(`form[action='/login']`, `#signUp-password-confirm`, `#toggle-psw-confirm`);

let sigUpFormString = `form[action='/login']`;

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

let PASSWORD_VALIDATION = {
    clear_invalid: false,
    clear_trailing: true,
    trailing: {
        ' ': '',
    },
    attributes: {
        required: true,
        max_length: 72,
    },
    errorFeedback: {
        required: 'Password is required.',
        max_length: 'Maximum password length is 72 characters.',
    }
}

const passwordValidator = new PasswordValidator(PASSWORD_VALIDATION);


const signUpConsentEvent = new GlobalListener('input', document, `${sigUpFormString} input[name="consent"]`, function (event) {
    validateSignUpForm(sigUpFormString);
});

function validateSignUpForm(sigUpFormString = `form[action='/login']`) {
    const stack = new Error().stack;

    let signUpBtn = document.querySelector(`${sigUpFormString} #signUpBtn`);
    let passwordInput = document.querySelector(`${sigUpFormString} input[name="password"]`);

    signUpBtn.disabled = true;

    // let consentAgreed = checkConsent(document.querySelector(`${sigUpFormString} input[name="consent"]`));
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
        signUpBool.isValidPassword = passwordValidator.validatePassword(`${sigUpFormString} input[name="password"]`);
    }

    // console.log(sigUpFormString)
    console.log(signUpBool)
    // console.log(consentAgreed)
    // console.log(isWeakPassword)
    if (!signUpBool.isValidEmail) {
        signUpBtn.disabled = true;
    } else

        if (isWeakPassword?.valueOf() == true) {
            passwordInput.classList.add('is-invalid');
            setInvalidMessage(passwordInput, 'Password is weak.');
        } else {
            passwordInput.classList.remove('is-invalid');
            signUpBtn.disabled = false;
            return true;
        }

}

initEmailValidation(`${sigUpFormString} input[name="email"]`, () => {
    validateSignUpForm(sigUpFormString);
}, signUpBool);

passwordValidator.init(`${sigUpFormString} input[name="password"]`, () => {
    validateSignUpForm(sigUpFormString);
}, signUpBool);



function validateOnSignUp(event) {
    validateSignUpForm();
}

const signUpEvent = new GlobalListener('click', document, `${sigUpFormString} ${signUpBtn}`, validateOnSignUp);


/* ----------------------------------------------------
    END: CHECK SIGNUP FORM
------------------------------------------------------- */

// console.log(document.querySelector(`form[action='/login'] #signUpBtn`))


// $("#form_id").trigger("reset");


