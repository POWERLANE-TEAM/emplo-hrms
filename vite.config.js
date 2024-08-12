import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/*',
                'resources/css/*',
                'resources/scss/*',
            ],
            refresh: true,
        }),
    ],
});
