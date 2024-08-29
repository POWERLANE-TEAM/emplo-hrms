export default function debounce(func, delay = 300) {
    let timeoutId;
    console.log('Debounce function called.');
    return function (...args) {
        clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}
