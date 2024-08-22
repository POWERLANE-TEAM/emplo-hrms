import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/index.js',
                'resources/js/employee/dashboard.js',
                'vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: process.env.APP_URL,
        hmr: {
            host: 'localhost',
        },
    },

});
