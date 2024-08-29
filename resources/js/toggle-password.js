import addGlobalListener from './global-event-listener.js';

export default function togglePassword(parent, inputSelector, toggleSelector) {
    let signUpForm = document.querySelector(parent);

    addGlobalListener('click', signUpForm, toggleSelector, event => {
        let passwordInput = signUpForm.querySelector(inputSelector)

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }

    });
}
