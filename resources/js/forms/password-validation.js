import addGlobalListener from '../global-event-listener.js';
import InputValidator, { setInvalidMessage } from './input-validator.js';
import debounce from '../debounce-fn.js';

const PASSWORD_VALIDATION = {
    clear_invalid: false,
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

export default function initPasswordValidation(inputSelector) {
    const debouncedValidation = debounce(function (event) {
        console.log(event.target);
        if (!passwordValidator.validate(event.target, setInvalidMessage)) {
            event.target.classList.add('is-invalid');
        } else {
            event.target.classList.remove('is-invalid');
        }
    }, 500);

    addGlobalListener('input', document, inputSelector, debouncedValidation);
}
