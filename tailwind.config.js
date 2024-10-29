/** @type {import('tailwindcss').Config} */
export default {
    presets: [
        //
    ],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    prefix: 'tw-', /* Added to differentiate tailwind css */
    theme: {
        extend: {},
    },
    corePlugins: {
        preflight: false, /* Added to prevent normalize conflict as mentioned in https://stackoverflow.com/questions/62688037/can-use-both-tailwind-css-and-bootstrap-4-at-the-same-time#:~:text=BUT%2C%20most%20likely%20you%20will%20have%20a%20problem%20with%20normalize.css%2C%20which%20used%20in%20%40tailwind%20base */
    },
    plugins: [
    ],
    darkMode: 'class',
}
