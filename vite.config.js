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
                'resources/js/app.js',
                'resources/js/hiring.js',
                'resources/js/login.js',
                'resources/js/unverified-email.js',
                'resources/js/email-domain-list.json',
                'resources/js/applicant/dashboard.js',
                'resources/js/employee/standard/dashboard.js',
                'resources/js/employee/hr/dashboard.js',
                'resources/js/employee/head-admin/dashboard.js',
                'resources/js/employee/pre-employment.js',
                'resources/js/forms/nbp.min.js',
                'resources/js/pasword-list/collections/mostcommon_1000000',
                'resources/css/hiring.css',
                'resources/css/login.css',
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
