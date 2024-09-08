import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/index.js',
                'resources/js/login.js',
                'resources/js/employee/dashboard.js',
                'resources/js/email-domain-list.json',
                'vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
                'vendor/node_modules/jquery/dist/jquery.min.js',
                'vendor/node_modules/jquery/dist/jquery.slim.min.js',
                // 'resources/js/forms/nbp.min.js',
                // 'resources/js/forms/eval-password.js',
                // 'resources/js/pasword-listcollections/mostcommon_1000000',
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
