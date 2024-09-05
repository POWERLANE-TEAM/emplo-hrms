import addGlobalListener from '../global-event-listener.js';
import InputValidator, { setInvalidMessage } from './input-validator.js';
import debounce from '../debounce-fn.js';

export const DEFAULT_PASSWORD_VALIDATION = {
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


// export var passwordValidator = new InputValidator(passwordValidation);

// function validatePasswordElement(passwordElement) {
//     let isValid = passwordValidator.validate(passwordElement, setInvalidMessage);
//     if (!isValid) {
//         passwordElement.classList.add('is-invalid');
//     } else {
//         passwordElement.classList.remove('is-invalid');
//     }
//     return isValid;
// }

// export function validatePassword(inputSelector, parent = document) {
//     const passwordElement = parent.querySelector(inputSelector);
//     return validatePasswordElement(passwordElement);
// }

// export default function initPasswordValidation(inputSelector, callback, result) {
//     const debouncedValidation = debounce(function (event) {
//         let isValid = validatePasswordElement(event.target);
//         try {
//             result.isValidPassword = isValid;
//         } catch (error) {

//         }
//         callback();
//     }, 500);

//     addGlobalListener('input', document, inputSelector, debouncedValidation);
// }


export default class PasswordValidator {
    constructor(passwordValidation) {
        this.passwordValidation = passwordValidation;
        this.passwordValidator = new InputValidator(this.passwordValidation);
    }

    validatePasswordElement(passwordElement) {
        let isValid = this.passwordValidator.validate(passwordElement, setInvalidMessage);
        if (!isValid) {
            passwordElement.classList.add('is-invalid');
        } else {
            passwordElement.classList.remove('is-invalid');
        }
        return isValid;
    }

    validatePassword(inputSelector, parent = document) {
        const passwordElement = parent.querySelector(inputSelector);
        return this.validatePasswordElement(passwordElement);
    }

    init(inputSelector, callback, result) {
        const debouncedValidation = debounce((event) => {
            let isValid = this.validatePasswordElement(event.target);
            try {
                result.isValidPassword = isValid;
            } catch (error) {

            }
            callback();
        }, 500);

        addGlobalListener('input', document, inputSelector, debouncedValidation);
    }
}


