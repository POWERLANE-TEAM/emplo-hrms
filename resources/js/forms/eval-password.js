export function initPasswordEvaluator() {
    try {
        /* The resource script does not comply to es6 standard */
        NBP.init('mostcommon_1000000', 'build/assets/pasword-list/collections', true);
    } catch (error) {
        console.trace();
    }
}


/**
 * Evaluates whether a password is common or not.
 *
 * @param {string} passwordValue - The password to evaluate.
 * @returns {boolean} - `true` if the password is common, otherwise `false`.
 */
export function evalPassword(passwordValue) {
    /* The password evaluator or the dictionary is last updated 7 yrs ago */
    let isCommonPassword;
    try {
        isCommonPassword = NBP.isCommonPassword(passwordValue);
    } catch (error) {
        console.warn('Password evaluator not available.')
    }

    return isCommonPassword;
}
