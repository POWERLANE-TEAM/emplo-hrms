
import './script.js';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import { initPasswordEvaluator, evalPassword } from './forms/eval-password.js';
import InputValidator, { setInvalidMessage, setFormDirty } from './forms/input-validator.js';
import initEmailValidation, { validateEmail } from './forms/email-validation.js';
import ThemeManager, { initPageTheme } from './theme-listener.js';
import debounce from 'debounce-script'; 

initPageTheme(window.ThemeManager);


/* ----------------------------------------------------
    START: CHECK SIGNUP FORM
------------------------------------------------------- */

let forgotPwFormString = `form[action='${FORGOT_PASSWORD_ACTION}']`;

let submitForgotPassword = `#submitForgotPassword`;

const forgotPasswordBool = {
    isValidEmail: false,
}

function validateForgotPwForm(forgotPwFormString = `form[action='${FORGOT_PASSWORD_ACTION}']`) {

    let submitForgotPassword = document.querySelector(`#submitForgotPassword`);

    submitForgotPassword.disabled = true;


    forgotPasswordBool.isValidEmail = validateEmail(`${forgotPwFormString} input[name="forgotPwEmail"]`);

    if (!forgotPasswordBool.isValidEmail) {
        submitForgotPassword.disabled = true;
    } else {

        submitForgotPassword.disabled = false;
        return true;
    }

}

initEmailValidation(`${forgotPwFormString} input[name="forgotPwEmail"]`, () => {
    validateForgotPwForm(forgotPwFormString);
}, forgotPasswordBool);


function validateOnSignUp(event) {
    setFormDirty(event);

    validateForgotPwForm();
}

const signUpEvent = new GlobalListener('click', document, `${forgotPwFormString} ${submitForgotPassword}`, validateOnSignUp);


/* ----------------------------------------------------
    END: CHECK SIGNUP FORM
------------------------------------------------------- */
