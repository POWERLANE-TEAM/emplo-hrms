import addGlobalListener, { GlobalListener } from './global-event-listener.js';

export default function togglePassword(parent, inputSelector, toggleSelector) {
    const togglePasswordVisibility = () => {
        let passwordInput = document.querySelector(`${parent} ${inputSelector}`);
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    };

    const togglePassword = new GlobalListener('input', document, toggleSelector, togglePasswordVisibility);

    document.addEventListener('keyup', function (event) {
        let passwordInput = document.querySelector(inputSelector);
        if (event.altKey && event.key === 'F8' && document.activeElement === passwordInput) {
            togglePasswordVisibility();
        }
    });
}

