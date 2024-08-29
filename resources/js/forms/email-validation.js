import addGlobalListener from '../global-event-listener.js';
import InputValidator, { setInvalidMessage } from './input-validator.js';
import debounce from '../debounce-fn.js';

let validEmailDomains;
let emailDomainResouurces = `api/json/email-domain-list`;

fetch(emailDomainResouurces)
    .then(function (response) {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(function (data) {

        // JSON.stringify
        console.log(data);
        EMAIL_VALIDATION.attributes.valid_emails = data.valid_email;
        // console.log(validEmailDomains);
        console.log(EMAIL_VALIDATION.attributes.valid_emails);

    })
    .catch(function (error) {
        // console.error('GET request error:', error);
    });


const EMAIL_VALIDATION = {
    clear_invalid: false,
    trailing: {
        '-+': '-',    // Replace consecutive dashes with a single dash
        '\\.+': '.',  // Replace consecutive periods with a single period
        // ' +': ' ',    // Replace consecutive spaces with a single space
        // '(\\w)\\1{2,}': '$1$1' //remove trailing character if three consecutive
    },
    attributes: {
        type: 'email',
        pattern: /^[a-zA-Z0-9._\-]+@[a-z0-9.-]+\.[a-z]{2,4}$/,
        required: true,
        max_length: 255,
        valid_emails: validEmailDomains,
    },
    // customMsg: {
    //     required: true,
    //     max_length: '',
    // },
    errorFeedback: {
        required: 'Email is required.',
        max_length: 'Email cannot be more than 255 characters.',
        pattern: 'Invalid email.',
        typeMismatch: 'Invalid email.',
        trailing: 'Consecutive repeating characters not allowed.',
        valid_emails: 'Email domain not allowed.',
    }
}

let emailValidator = new InputValidator(EMAIL_VALIDATION);

export default function initEmailValidation(inputSelector) {
    const debouncedValidation = debounce(function (event) {
        if (!emailValidator.validate(event.target, setInvalidMessage)) {
            event.target.classList.add('is-invalid');
        } else {
            event.target.classList.remove('is-invalid');
        }
    }, 500);

    addGlobalListener('input', document, inputSelector, debouncedValidation);
}
