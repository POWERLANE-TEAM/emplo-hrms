
import '../script.js';
import '../animations/texts-effect.js';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import togglePassword from '../toggle-password.js';
import { initPasswordEvaluator, evalPassword } from '../forms/eval-password.js';
import InputValidator, { setInvalidMessage } from '../forms/input-validator.js';
import initEmailValidation, { validateEmail } from '../forms/email-validation.js';
import PasswordValidator from '../forms/password-validation.js';
import debounce from 'debounce-script';
import ThemeManager, { initPageTheme } from 'theme-listener-script';

const themeManager = new ThemeManager();

initPageTheme(themeManager);

document.addEventListener("DOMContentLoaded", (event) => {
    //
});

document.addEventListener('livewire:init', () => {
    initPasswordEvaluator();
});

document.addEventListener('livewire:navigate', (event) => {
    //
})


/* ----------------------------------------------------
    START: CHECK SIGNUP FORM
------------------------------------------------------- */

/* ----------------------------------------------------
  TODO:
  1. Validate other form fields only if is touched
  2. Track for unsaved or unfinished form
  3. Reset form on confirmed discard signup (on modal dismiss)
------------------------------------------------------- */

togglePassword(`form[action='login']`, `#userLogin-password`, `#toggle-psw`);

let userLoginFormString = `form[action='login']`;

let userLoginBtn = `#userLoginBtn`;

function checkConsent(consentForm) {
    return consentForm.checked;
}

function checkCaptcha() {
    return true;
}

const userLoginBool = {
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


function validateUserLoginForm(userLoginFormString = `form[action='login']`) {
    const stack = new Error().stack;

    let userLoginBtn = document.querySelector(`${userLoginFormString} #userLoginBtn`);
    let passwordInput = document.querySelector(`${userLoginFormString} input[name="password"]`);

    userLoginBtn.disabled = true;

    let isWeakPassword;
    try {
        isWeakPassword = evalPassword(passwordInput.value);
    } catch (error) {
        console.warn('Password evaluator not available.')
    }

    if (!stack.includes('email-validation.js')) {
        userLoginBool.isValidEmail = validateEmail(`${userLoginFormString} input[name="email"]`);
    }

    if (!stack.includes('password-validation.js')) {
        userLoginBool.isValidPassword = passwordValidator.validatePassword(`${userLoginFormString} input[name="password"]`);
    }

    if (!userLoginBool.isValidEmail) {
        userLoginBtn.disabled = true;
    } else

        if (isWeakPassword?.valueOf() == true) {
            passwordInput.classList.add('is-invalid');
            setInvalidMessage(passwordInput, 'Password is weak.');
        } else {
            passwordInput.classList.remove('is-invalid');
            userLoginBtn.disabled = false;
            return true;
        }

}

initEmailValidation(`${userLoginFormString} input[name="email"]`, () => {
    validateUserLoginForm();
}, userLoginBool);

passwordValidator.init(`${userLoginFormString} input[name="password"]`, () => {
    validateUserLoginForm();
}, userLoginBool);

function validateOnSignUp(event) {
    validateUserLoginForm();
}

const signUpEvent = new GlobalListener('click', document, `${userLoginFormString} ${userLoginBtn}`, validateOnSignUp);


/* ----------------------------------------------------
    END: CHECK SIGNUP FORM
------------------------------------------------------- */



