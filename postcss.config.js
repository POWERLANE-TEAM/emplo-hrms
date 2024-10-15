import tailwindcss from './node_modules/tailwindcss/lib/index.js';
import autoprefixer from './node_modules/autoprefixer/lib/autoprefixer.js';

export default {
    plugins: [
        tailwindcss({
            config: './tailwind.config.js',
        }),
        autoprefixer,
    ],
};
