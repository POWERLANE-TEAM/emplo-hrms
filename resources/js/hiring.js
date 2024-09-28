
import './script.js';
import initLucideIcons from './icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from './global-scroll-fn.js';
import addGlobalListener, { GlobalListener } from './global-event-listener.js';
import togglePassword from './toggle-password.js';
import { initPasswordEvaluator, evalPassword } from './forms/eval-password.js';
import InputValidator, { setInvalidMessage, setFormDirty } from './forms/input-validator.js';
import initEmailValidation, { validateEmail } from './forms/email-validation.js';
import PasswordValidator, { DEFAULT_PASSWORD_VALIDATION } from './forms/password-validation.js';
import ConsentValidator from './forms/consent-validation.js';
import initPasswordConfirmValidation, { validateConfirmPassword } from './forms/password-confirm-validation.js';
import ThemeManager, { initPageTheme } from './theme-listener.js';
import debounce from './debounce-fn.js';
import './applicant/top-bar.js'
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

    Livewire.on('guest-new-user-registered', (event) => {
        bootstrap.Modal.getOrCreateInstance('#signUpForm').hide();
        bootstrap.Modal.getOrCreateInstance('#register-email-alert').show();
    });

});


/* ----------------------------------------------------
    START: CHECK SIGNUP FORM
------------------------------------------------------- */

/* ----------------------------------------------------
  TODO:
  1. Validate other form fields only if is touched
  2. Track for unsaved or unfinished form
  3. Reset form on confirmed discard signup (on modal dismiss)
------------------------------------------------------- */

togglePassword(`form[action='applicant/sign-up']`, `#signUp-password`, `#toggle-psw`);
togglePassword(`form[action='applicant/sign-up']`, `#signUp-password-confirm`, `#toggle-psw-confirm`);

let sigUpFormString = `form[action='applicant/sign-up']`;

let signUpBtn = `#signUpBtn`;

const FIRST_NAME_VALIDATION = {
    clear_invalid: true,
    trailing: {
        '-+': '-',    // Replace consecutive dashes with a single dash
        '\\.+': '.',  // Replace consecutive periods with a single period
        ' +': ' ',    // Replace consecutive spaces with a single space
        '\\\'+': '\'',    // Replace consecutive aposthrophe with a single aposthrophe
    },
    attributes: {
        type: 'text',
        // pattern: /^[\p{L} \'-]+$/u,
        pattern: /^[A-Za-zÑñ '\-]+$/,
        required: true,
        max_length: 191,
    },
    // customMsg: {
    //     required: true,
    //     max_length: '',
    // },
    errorFeedback: {
        required: 'First name is required.',
        max_length: 'First name cannot be more than 255 characters.',
        pattern: 'Invalid first name.',
        typeMismatch: 'Invalid first name.',
        trailing: 'Consecutive repeating characters not allowed.',
    }
}

const MIDDLE_NAME_VALIDATION = { ...FIRST_NAME_VALIDATION };
const LAST_NAME_VALIDATION = { ...FIRST_NAME_VALIDATION };

MIDDLE_NAME_VALIDATION.attributes = {
    type: 'text',
    // pattern: /^[\p{L} \'-]+$/u,
    pattern: /^[A-Za-zÑñ '\-]+$/,
    max_length: 191,
};

MIDDLE_NAME_VALIDATION.errorFeedback = {
    max_length: 'Middle name cannot be more than 255 characters.',
    pattern: 'Invalid middle name.',
    typeMismatch: 'Invalid middle name.',
    trailing: 'Consecutive repeating characters not allowed.',
};

LAST_NAME_VALIDATION.errorFeedback = {
    required: 'Last name is required.',
    max_length: 'Last name cannot be more than 255 characters.',
    pattern: 'Invalid last name.',
    typeMismatch: 'Invalid last name.',
    trailing: 'Consecutive repeating characters not allowed.',
};

class NameValidator {
    constructor(inputSelector, validator, parent = document) {
        this.inputSelector = inputSelector;
        this.validator = validator;
        this.parent = parent;
    }

    // Validate individual input element
    validateElement(element) {
        const isValid = this.validator.validate(element, setInvalidMessage);
        if (!isValid) {
            element.classList.add('is-invalid');
        } else {
            element.classList.remove('is-invalid');
        }
        return isValid;
    }

    validateName(inputSelector, parent = document) {
        const element = parent.querySelector(this.inputSelector);
        return this.validateElement(element);
    }

    // Initialize debounced validation on input
    initValidation(callback, resultRef) {
        const debouncedValidation = debounce((event) => {

            event.target.classList.add('is-dirty');

            const isValid = this.validateElement(event.target);
            try {
                resultRef = isValid;
            } catch (error) {

            }
            callback();
        }, 500);

        addGlobalListener('input', this.parent, this.inputSelector, debouncedValidation);
    }

}

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

console.log(`${sigUpFormString} input[name="consent"]`)

const termsPrivacyValidator = new ConsentValidator(`${sigUpFormString} input[name="consent"]`,
    new InputValidator(TERMS_AND_PRIVACY_VALIDATION),
    signUpBool.consentAgreed,
    () => validateSignUpForm(sigUpFormString)
);

// const signUpConsentEvent = new GlobalListener('input', document, `${sigUpFormString} input[name="consent"]`, function (event) {
//     validateSignUpForm(sigUpFormString);
// });

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

    // console.log(sigUpFormString)
    // console.log(signUpBool)
    // console.log(consentAgreed)
    // console.log(isWeakPassword)
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

// console.log(document.querySelector(`form[action='applicant/sign-up'] #signUpBtn`))


// $("#form_id").trigger("reset");


