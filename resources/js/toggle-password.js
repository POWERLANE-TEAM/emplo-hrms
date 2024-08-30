import addGlobalListener, { GlobalListener } from './global-event-listener.js';

export default function togglePassword(parent, inputSelector, toggleSelector) {
    const togglePassword = new GlobalListener('input', parent, toggleSelector, event => {
        let passwordInput = parent.querySelector(inputSelector)

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }

    });
}
