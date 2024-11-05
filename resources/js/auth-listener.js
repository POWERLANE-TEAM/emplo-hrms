import './websocket.js';

let hasUnsavedChanges = false;
let logoutCallback = null;

try {
    Echo.private(`user_auth.${AUTH_BROADCAST_ID}`).listen('UserLoggedout', async (event) => {

        if (!hasUnsavedChanges) {
            if (await logoutCallback == true) {
                return window.location.href = '/';
            }

            window.location.href = event.redirectUrl;
        }
    })
} catch (error) {

}
