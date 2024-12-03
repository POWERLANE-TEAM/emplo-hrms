import './websocket.js';

let hasUnsavedChanges = false;
let logoutCallback = null;

try {
    Echo.private(`user-auth.${AUTH_BROADCAST_ID}`).listen('UserLoggedout', async (event) => {

        if (!hasUnsavedChanges) {
            window.location.href = event.redirectUrl;
        } else {
            if (await logoutCallback == true) {
                return window.location.href = event.redirectUrl;
            }
        }
    })
} catch (error) {

}
