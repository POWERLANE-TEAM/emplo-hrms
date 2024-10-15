import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
                'node_modules/chartjs-plugin-annotation/dist/chartjs-plugin-annotation.min.js',
                // 'resources/js/app.js',
                'resources/js/hiring.js',
                'resources/js/applicant/login.js',
                'resources/js/employee/login.js',
                'resources/js/admin/login.js',
                'resources/js/unverified-email.js',
                'resources/js/applicant/dashboard.js',
                'resources/js/employee/standard/dashboard.js',
                'resources/js/employee/hr/dashboard.js',
                'resources/js/admin/dashboard.js',
                'resources/js/employee/pre-employment.js',
                'resources/js/forms/nbp.min.js',
                'resources/js/applicant/apply.js',
                'resources/css/hiring.css',
                'resources/css/login.css',
                'resources/css/unverified-email.css',
                'resources/css/guest/primary-bg.css',
                'resources/css/guest/secondary-bg.css',
                'resources/css/applicant/apply.css',
                'resources/css/employee/pre-employment.css',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'resources/js/pasword-list/collections/mostcommon_1000000',
                    dest: 'assets/pasword-list/collections'
                },
                {
                    src: 'resources/js/email-domain-list.json',
                    dest: 'assets/'
                }
            ]
        })
    ],
    resolve: {
        alias: {
            'laravel-echo': '/node_modules/laravel-echo/dist/echo.js',
            'pusher-js': '/node_modules/pusher-js/dist/web/pusher.js',
        },
    },
    server: {
        host: process.env.APP_URL,
        hmr: {
            host: 'localhost',
        },
    },
});
