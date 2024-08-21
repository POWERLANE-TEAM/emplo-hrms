import tailwindcss from './vendor/node_modules/tailwindcss/lib/index.js';
import autoprefixer from './vendor/node_modules/autoprefixer/lib/autoprefixer.js';

export default {
    plugins: [
        tailwindcss({
            config: './vendor/tailwind.config.js',
        }),
        autoprefixer,
    ],
};
