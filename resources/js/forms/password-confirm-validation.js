import addGlobalListener from '../global-event-listener.js';
import InputValidator, { setInvalidMessage } from './input-validator.js';
import debounce from '../debounce-fn.js';

const PASSWORD_CONFIRM_VALIDATION = {
    clear_invalid: false,
    isConfirmation: true,
    attributes: {
        required: true,
        max_length: 72,
    },
    errorFeedback: {
        required: 'Password confirmation is required.',
        max_length: 'Maximum password length is 72 characters.',
    }
}


let passwordConfirmValidator = new InputValidator(PASSWORD_CONFIRM_VALIDATION);

function setConfirmPassword(element) {
    let passwordElement = document.querySelector(`[aria-owns~="${element.id}"]`);
    let passwordVal = passwordElement.value;
    let passwordConfirmVal = element.value;

    let isMatchPassword = passwordVal == passwordConfirmVal;

    if (!isMatchPassword) {
        setInvalidMessage(element, "Password mismatch.");
    } else {
        setInvalidMessage(element, "");
    }

    return isMatchPassword;

}

function validatePasswordConfirmElement(passwordElement) {
    let isValid = passwordConfirmValidator.validate(passwordElement, setConfirmPassword);
    if (!isValid) {
        passwordElement.classList.add('is-invalid');
    } else {
        passwordElement.classList.remove('is-invalid');
    }
    return isValid;
}

export function validateConfirmPassword(inputSelector, parent = document) {
    const passwordElement = parent.querySelector(inputSelector);
    return validatePasswordConfirmElement(passwordElement);
}

export default function initPasswordConfirmValidation(inputSelector, callback, result) {
    const debouncedValidation = debounce(function (event) {
        let isValid = validatePasswordConfirmElement(event.target);
        try {
            result.isPasswordMatch = isValid;
        } catch (error) {

        }
        callback();
    }, 500);

    addGlobalListener('input', document, inputSelector, debouncedValidation);
}
