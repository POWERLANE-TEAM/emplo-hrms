import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
                'vendor/node_modules/jquery/dist/jquery.min.js',
                'vendor/node_modules/jquery/dist/jquery.slim.min.js',
                'vendor/node_modules/chartjs-plugin-annotation/dist/chartjs-plugin-annotation.min.js',
                // 'resources/js/app.js',
                'resources/js/hiring.js',
                'resources/js/applicant/login.js',
                'resources/js/employee/login.js',
                'resources/js/admin/login.js',
                'resources/js/unverified-email.js',
                'resources/js/email-domain-list.json',
                'resources/js/applicant/dashboard.js',
                'resources/js/employee/standard/dashboard.js',
                'resources/js/employee/hr/dashboard.js',
                'resources/js/employee/head-admin/dashboard.js',
                'resources/js/employee/pre-employment.js',
                'resources/js/forms/nbp.min.js',
                'resources/css/hiring.css',
                'resources/css/login.css',
                'resources/css/unverified-email.css',
                'resources/css/guest/primary-bg.css',
                'resources/css/guest/secondary-bg.css',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'resources/js/pasword-list/collections/mostcommon_1000000',
                    dest: 'assets/pasword-list/collections'
                }
            ]
        })
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
    // build: {
    //     rollupOptions: {
    //         external: [
    //             'resources/js/pasword-list/collections/mostcommon_1000000'
    //         ]
    //     }
    // },

});
