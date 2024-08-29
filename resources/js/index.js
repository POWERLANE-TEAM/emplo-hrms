
import "../css/index.css";
import initLucideIcons from './icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from './global-scroll-fn.js';
import addGlobalListener from './global-event-listener.js';
import togglePassword from './toggle-password.js';
import InputValidator from './input-validator.js';
import './applicant/top-bar.js'
// import './livewire.js'

document.addEventListener("DOMContentLoaded", (event) => {
    initLucideIcons();
});

togglePassword(`form[action='applicant/sign-up']`, `#signUp-password`, `input.toggle-password`);

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
        pattern: 'Invalid Password.',
    }
}

let emailValidator = new InputValidator(EMAIL_VALIDATION);
let passwordValidator = new InputValidator(PASSWORD_VALIDATION);

// let signUpForm = document.querySelector(parent);

console.log(document.querySelector(`form[action='applicant/sign-up'] input[name="email"]`))

function setInvalidMessage(element, feedbackMsg) {
    let feedbackElement = document.querySelector(`[aria-owns="${element.id}"]`);
    try {
        feedbackElement.textContent = feedbackMsg;
    } catch (error) {
        console.error('Feedback message not set.');
    }

}

addGlobalListener('input', document, `form[action='applicant/sign-up'] input[name="email"]`, event => {
    console.log(event.target);
    if (!emailValidator.validate(event.target, setInvalidMessage)) {
        event.target.classList.add('is-invalid');
    } else {
        event.target.classList.remove('is-invalid');
    }

});

addGlobalListener('input', document, `form[action='applicant/sign-up'] input[name="password"]`, event => {
    console.log(event.target);
    if (!passwordValidator.validate(event.target, setInvalidMessage)) {
        event.target.classList.add('is-invalid');
    } else {
        event.target.classList.remove('is-invalid');
    }
});


// $("#form_id").trigger("reset");


