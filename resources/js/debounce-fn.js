export default function debounce(func, delay = 300) {
    let timeoutId;
    console.trace();
    return function (...args) {
        clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}
