import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
                'vendor/node_modules/jquery/dist/jquery.min.js',
                'vendor/node_modules/jquery/dist/jquery.slim.min.js',
                'vendor/node_modules/chartjs-plugin-annotation/dist/chartjs-plugin-annotation.min.js',
                // 'vendor/node_modules/laravel-echo/dist/echo.js',
                // 'vendor/node_modules/pusher-js',
                'resources/js/app.js',
                'resources/js/index.js',
                'resources/js/login.js',
                'resources/js/email-domain-list.json',
                'resources/js/applicant/dashboard.js',
                'resources/js/employee/dashboard.js',
                'resources/js/employee/pre-employment.js',
                // 'resources/js/forms/nbp.min.js',
                // 'resources/js/forms/eval-password.js',
                // 'resources/js/pasword-listcollections/mostcommon_1000000',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            'laravel-echo': '/vendor/node_modules/laravel-echo/dist/echo.js',
            'pusher-js': '/vendor/node_modules/pusher-js/dist/web/pusher.js',
        },
    },
    server: {
        host: process.env.APP_URL,
        hmr: {
            host: 'localhost',
        },
    },

});
