
import './script.js';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import togglePassword from './toggle-password.js';
import { initPasswordEvaluator, evalPassword } from './forms/eval-password.js';
import InputValidator, { setInvalidMessage, setFormDirty } from './forms/input-validator.js';
import initEmailValidation, { validateEmail } from './forms/email-validation.js';
import PasswordValidator, { DEFAULT_PASSWORD_VALIDATION } from './forms/password-validation.js';
import initPasswordConfirmValidation, { validateConfirmPassword } from './forms/password-confirm-validation.js';
import ThemeManager, { initPageTheme } from './theme-listener.js';
import debounce from 'debounce-script';

initPageTheme(window.ThemeManager);

document.addEventListener('livewire:init', () => {
    initPasswordEvaluator();
});


/* ----------------------------------------------------
    START: CHECK SIGNUP FORM
------------------------------------------------------- */


togglePassword(`form[action='${RESET_PASSWORD_ACTION}']`, `#reset-password`, `#tgl-reset-password`);
togglePassword(`form[action='${RESET_PASSWORD_ACTION}']`, `#reset-password-confirm`, `#tgl-reset-password-confirm`);

let resetPwFormString = `form[action='${RESET_PASSWORD_ACTION}']`;

let submitResetPassword = `#submitResetPassword`;


const signUpBool = {
    isValidEmail: false,
    isValidPassword: false,
    isPasswordMatch: false,
}

const passwordValidator = new PasswordValidator(DEFAULT_PASSWORD_VALIDATION);

function validateSignUpForm(resetPwFormString = `form[action='${RESET_PASSWORD_ACTION}']`) {
    const stack = new Error().stack;

    let submitResetPassword = document.querySelector(`${resetPwFormString} #submitResetPassword`);
    let passwordInput = document.querySelector(`${resetPwFormString} input[name="password"]`);

    console.log(submitResetPassword);

    submitResetPassword.disabled = true;

    let isWeakPassword;
    try {
        isWeakPassword = evalPassword(passwordInput.value);
    } catch (error) {
        console.warn('Password evaluator not available.')
    }

    signUpBool.isValidEmail = validateEmail(`${resetPwFormString} input[name="email"]`);

    if (!stack.includes('password-validation.js')) {
        signUpBool.isValidPassword = passwordValidator.validatePassword(`${resetPwFormString} input[name="password"]`);
    }

    if (!stack.includes('password-confirm-validation.js')) {
        signUpBool.isPasswordMatch = validateConfirmPassword(`${resetPwFormString} input[name="password_confirmation"]`);
    }

    if (!signUpBool.isValidEmail || !signUpBool.isValidPassword) {
        submitResetPassword.disabled = true;
    } else
        if (isWeakPassword?.valueOf() == true) {
            submitResetPassword.disabled = true;
            passwordInput.classList.add('is-invalid');
            setInvalidMessage(passwordInput, 'Password is weak.');
        } else if (!signUpBool.isPasswordMatch) {
            submitResetPassword.disabled = true;
        } else {
            passwordInput.classList.remove('is-invalid');
            submitResetPassword.disabled = false;
            return true;
        }

}

initEmailValidation(`${resetPwFormString} input[name="email"]`, () => {
    validateSignUpForm(resetPwFormString);
}, signUpBool);

passwordValidator.init(`${resetPwFormString} input[name="password"]`, () => {
    validateSignUpForm(resetPwFormString);
}, signUpBool);

initPasswordConfirmValidation(`${resetPwFormString} input[name="password_confirmation"]`, () => {
    validateSignUpForm(resetPwFormString);
}, signUpBool);

const passwordConfirmPaste = new GlobalListener('paste', document, `${resetPwFormString} input[name="password_confirmation"]`, e => e.preventDefault());
const passwordConfirmDrop = new GlobalListener('drop', document, `${resetPwFormString} input[name="password_confirmation"]`, e => e.preventDefault());

function validateOnSignUp(event) {
    setFormDirty(event);

    validateSignUpForm();
}

const signUpEvent = new GlobalListener('click', document, `${resetPwFormString} ${submitResetPassword}`, validateOnSignUp);


/* ----------------------------------------------------
    END: CHECK SIGNUP FORM
------------------------------------------------------- */
