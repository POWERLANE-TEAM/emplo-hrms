import addGlobalListener from 'globalListener-script';
import InputValidator, { setInvalidMessage } from './input-validator.js';
import debounce from 'debounce-script';

let validEmailDomains;
let emailDomainResouurces = `/build/assets/email-domain-list.json`;


fetch(emailDomainResouurces)
    .then(function (response) {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(function (data) {

        EMAIL_VALIDATION.attributes.valid_emails = data.valid_email;

    })
    .catch(function (error) {
        console.warn('Failed to fetch email dictionary.');
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
        max_length: 191,
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
        valid_emails: 'Email service provider is not allowed.',
    }
}

let emailValidator = new InputValidator(EMAIL_VALIDATION);

function validateEmailElement(emailElem) {
    let isValid = emailValidator.validate(emailElem, setInvalidMessage);
    if (!isValid) {
        emailElem.classList.add('is-invalid');
    } else {
        emailElem.classList.remove('is-invalid');
    }
    return isValid;
}

export function validateEmail(inputSelector, parent = document) {
    const emailElem = parent.querySelector(inputSelector);
    return validateEmailElement(emailElem);
}

// Exported function for debounced validation
export default function initEmailValidation(inputSelector, callback, result) {
    const debouncedValidation = debounce(function (event) {
        event.target.classList.add('is-dirty');
        let isValid = validateEmailElement(event.target);
        try {
            result.isValidEmail = isValid;
        } catch (error) {

        }

        callback();
    }, 500);

    addGlobalListener('input', document, inputSelector, debouncedValidation);
}

