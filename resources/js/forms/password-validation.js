import addGlobalListener from '../global-event-listener.js';
import InputValidator, { setInvalidMessage } from './input-validator.js';
import debounce from '../debounce-fn.js';

const PASSWORD_VALIDATION = {
    clear_invalid: false,
    clear_trailing: true,
    trailing: {
        ' ': '',    // Replace consecutive spaces with a single space
    },
    attributes: {
        pattern: /^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])[^\s]{8,72}$/,
        required: true,
        max_length: 72,
    },
    // customMsg: {
    //     required: true,
    //     max_length: '',
    // },
    errorFeedback: {
        required: 'Password is required.',
        max_length: 'Maximum password length is 72 characters.',
        pattern: 'Password must be atleast 8 characters with a number, an uppercase letter, a lowercase letter, and a special character.',
    }
}


let passwordValidator = new InputValidator(PASSWORD_VALIDATION);

function validatePasswordElement(passwordElement) {
    let isValid = passwordValidator.validate(passwordElement, setInvalidMessage);
    if (!isValid) {
        passwordElement.classList.add('is-invalid');
    } else {
        passwordElement.classList.remove('is-invalid');
    }
    return isValid;
}

export function validatePassword(inputSelector, parent = document) {
    const passwordElement = parent.querySelector(inputSelector);
    return validatePasswordElement(passwordElement);
}

export default function initPasswordValidation(inputSelector, callback) {
    const debouncedValidation = debounce(function (event) {
        validatePasswordElement(event.target);
        callback();
    }, 500);

    addGlobalListener('input', document, inputSelector, debouncedValidation);
}
