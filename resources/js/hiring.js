
import './script.js';
import './animations/texts-effect.js';
import initLucideIcons from './icons/lucide.js';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import togglePassword from './toggle-password.js';
import { initPasswordEvaluator, evalPassword } from './forms/eval-password.js';
import InputValidator, { setInvalidMessage, setFormDirty } from './forms/input-validator.js';
import initEmailValidation, { validateEmail } from './forms/email-validation.js';
import PasswordValidator, { DEFAULT_PASSWORD_VALIDATION } from './forms/password-validation.js';
import ConsentValidator from './forms/consent-validation.js';
import initPasswordConfirmValidation, { validateConfirmPassword } from './forms/password-confirm-validation.js';
import ThemeManager, { initPageTheme } from './theme-listener.js';
import debounce from 'debounce-script';
import './applicant/top-bar.js'
import NameValidator from 'name-validator-script';
import { LAST_NAME_VALIDATION, MIDDLE_NAME_VALIDATION, FIRST_NAME_VALIDATION } from 'name-validate-rule';
// import './livewire.js'

const themeManager = new ThemeManager();

initPageTheme(themeManager);

document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();
});

document.addEventListener('livewire:init', () => {
    let loadSignUp = Livewire.on('guest-sign-up-load', (event) => {
        initPasswordEvaluator();
        loadSignUp();
    });

    Livewire.on('verification-email-error', (event) => {
        window.openModal('modal-verification-email-error');
    });

    Livewire.on('sign-up-error', (event) => {
        window.openModal('modal-sign-up-error');
    });


    Livewire.on('guest-job-view-pane-rendered', (event) => {
    });
    Livewire.on('guest-sign-up-rendered', (event) => {
        let formCheckbox = document.querySelectorAll('form input[type="checkbox"]');

        for (let i = 0; i < formCheckbox.length; i++) {
            if (formCheckbox[i].type == 'checkbox') {
                formCheckbox[i].checked = false;
            }
        }
    });

    Livewire.on('sign-up-successful', (event) => {
        bootstrap.Modal.getOrCreateInstance('#signUpForm').hide();
        window.openModal('modal-sign-up-success');

        setTimeout(() => {
            bootstrap.Modal.getOrCreateInstance('#signUpForm').hide();
            bootstrap.Modal.getOrCreateInstance('#modal-sign-up-success').hide();
            window.openModal('modal-verification-email-success');
        }, 5000); // 5 seconds timeout
    });


    Livewire.on('verification-email-success', (event) => {
        bootstrap.Modal.getOrCreateInstance('#signUpForm').hide();
        window.openModal('"modal-verification-email-success');
    });

});


/* ----------------------------------------------------
    START: CHECK SIGNUP FORM
------------------------------------------------------- */

/* ----------------------------------------------------
  TODO:
  1. Track for unsaved or unfinished form
  2. Reset form on confirmed discard signup (on modal dismiss)
------------------------------------------------------- */

togglePassword(`form[action='applicant/sign-up']`, `#signUp-password`, `#toggle-psw`);
togglePassword(`form[action='applicant/sign-up']`, `#signUp-password-confirm`, `#toggle-psw-confirm`);

let sigUpFormString = `form[action='applicant/sign-up']`;

let signUpBtn = `#signUpBtn`;



const firstNameValidator = new NameValidator(`${sigUpFormString} input[name="first_name"]`, new InputValidator(FIRST_NAME_VALIDATION));
const middleNameValidator = new NameValidator(`${sigUpFormString} input[name="middle_name"]`, new InputValidator(MIDDLE_NAME_VALIDATION));
const lastNameValidator = new NameValidator(`${sigUpFormString} input[name="last_name"]`, new InputValidator(LAST_NAME_VALIDATION));


const TERMS_AND_PRIVACY_VALIDATION = {
    attributes: {
        type: 'checkbox',
        required: true,
    },
    customMsg: {
        required: 'Consent to the terms and conditions and privacy policy is required.',
    },
    errorFeedback: {
        required: 'Consent to the terms and conditions and privacy policy is required.',
    }
}

function checkConsent(consentForm) {
    return consentForm.checked;
}

const signUpBool = {
    isValidFirstName: false,
    isValidMiddleName: false,
    isValidLastName: false,
    isValidEmail: false,
    isValidPassword: false,
    isPasswordMatch: false,
    consentAgreed: false
}

// Initialize validation with callback and result object
firstNameValidator.initValidation(() => validateSignUpForm(sigUpFormString), signUpBool.isValidFirstName);
middleNameValidator.initValidation(() => validateSignUpForm(sigUpFormString), signUpBool.isValidMiddleName);
lastNameValidator.initValidation(() => validateSignUpForm(sigUpFormString), signUpBool.isValidLastName);

const passwordValidator = new PasswordValidator(DEFAULT_PASSWORD_VALIDATION);

const termsPrivacyValidator = new ConsentValidator(`${sigUpFormString} input[name="consent"]`,
    new InputValidator(TERMS_AND_PRIVACY_VALIDATION),
    signUpBool.consentAgreed,
    () => validateSignUpForm(sigUpFormString)
);

function validateSignUpForm(sigUpFormString = `form[action='applicant/sign-up']`) {
    const stack = new Error().stack;

    let signUpBtn = document.querySelector(`${sigUpFormString} #signUpBtn`);
    let passwordInput = document.querySelector(`${sigUpFormString} input[name="password"]`);

    signUpBtn.disabled = true;

    signUpBool.consentAgreed = checkConsent(document.querySelector(`${sigUpFormString} input[name="consent"]`));

    let isWeakPassword;
    try {
        isWeakPassword = evalPassword(passwordInput.value);
    } catch (error) {
        console.warn('Password evaluator not available.')
    }

    signUpBool.isValidFirstName = firstNameValidator.validateName(`${sigUpFormString} input[name="first_name"]`);
    signUpBool.isValidMiddleName = middleNameValidator.validateName(`${sigUpFormString} input[name="middle_name"]`);
    signUpBool.isValidLastName = lastNameValidator.validateName(`${sigUpFormString} input[name="last_name"]`);

    if (!stack.includes('email-validation.js')) {
        signUpBool.isValidEmail = validateEmail(`${sigUpFormString} input[name="email"]`);
    }

    if (!stack.includes('password-validation.js')) {
        signUpBool.isValidPassword = passwordValidator.validatePassword(`${sigUpFormString} input[name="password"]`);
    }

    if (!stack.includes('password-confirm-validation.js')) {
        signUpBool.isPasswordMatch = validateConfirmPassword(`${sigUpFormString} input[name="password_confirmation"]`);
    }

    if (!signUpBool.isValidEmail || !signUpBool.isValidPassword || !signUpBool.isValidFirstName || !signUpBool.isValidMiddleName || !signUpBool.isValidLastName) {
        signUpBtn.disabled = true;
    } else
        if (isWeakPassword?.valueOf() == true) {
            signUpBtn.disabled = true;
            passwordInput.classList.add('is-invalid');
            setInvalidMessage(passwordInput, 'Password is weak.');
        } else if (!signUpBool.isPasswordMatch || !signUpBool.consentAgreed) {
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

passwordValidator.init(`${sigUpFormString} input[name="password"]`, () => {
    validateSignUpForm(sigUpFormString);
}, signUpBool);

initPasswordConfirmValidation(`${sigUpFormString} input[name="password_confirmation"]`, () => {
    validateSignUpForm(sigUpFormString);
}, signUpBool);

const passwordConfirmPaste = new GlobalListener('paste', document, `${sigUpFormString} input[name="password_confirmation"]`, e => e.preventDefault());
const passwordConfirmDrop = new GlobalListener('drop', document, `${sigUpFormString} input[name="password_confirmation"]`, e => e.preventDefault());

function validateOnSignUp(event) {
    setFormDirty(event);

    validateSignUpForm();
}

const signUpEvent = new GlobalListener('click', document, `${sigUpFormString} ${signUpBtn}`, validateOnSignUp);


/* ----------------------------------------------------
    END: CHECK SIGNUP FORM
------------------------------------------------------- */



