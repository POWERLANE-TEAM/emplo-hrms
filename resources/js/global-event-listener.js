/**
 * Attaches a global scroll listener to the specified reference element.
 *
 * @param {string} type - The event type (e.g., "click", "scroll").
 * @param {HTMLElement} ref - The reference element to attach the listener to (default is document).
 * @param {string} selector - The CSS selector for the target elements.
 * @param {Function} callback - The callback function to execute when the event occurs.
 */

export default function addGlobalListener(type, ref = document, selector, callback) {
    try {
        ref.addEventListener(type, e => {
            if (e.target.matches(selector)) {
                callback(e);
            }
        })
    } catch (error) {
        console.trace(error);
    }
}
