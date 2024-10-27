import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import os from 'os';

function getLocalIpAddress() {
    const interfaces = os.networkInterfaces();

    // Prioritize Wi-Fi adapters based on common names
    const wifiNames = ['Wi-Fi', 'wlan', 'WiFi'];

    // Check Wi-Fi interfaces first
    for (const name of Object.keys(interfaces)) {
        if (wifiNames.some(wifiName => name.toLowerCase().includes(wifiName.toLowerCase()))) {
            for (const iface of interfaces[name]) {
                if (iface.family === 'IPv4' && !iface.internal && iface.address !== '127.0.0.1') {
                    return iface.address;
                }
            }
        }
    }

    // If no Wi-Fi interface is found, fallback to the first NIC
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            if (iface.family === 'IPv4' && !iface.internal && iface.address !== '127.0.0.1') {
                return iface.address;
            }
        }
    }

    return 'localhost';
}



const localIpAddress = getLocalIpAddress();

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
                'resources/js/employee/basic/dashboard.js',
                'resources/js/employee/hr-manager/dashboard.js',
                'resources/js/employee/supervisor/dashboard.js',
                'resources/js/admin/dashboard.js',
                'resources/js/employee/pre-employment.js',
                'resources/js/forms/nbp.min.js',
                'resources/js/applicant/apply.js',
                'resources/css/style.css',
                'resources/css/hiring.css',
                'resources/css/login.css',
                'resources/css/unverified-email.css',
                'resources/css/guest/primary-bg.css',
                'resources/css/guest/secondary-bg.css',
                'resources/css/applicant/apply.css',
                'resources/css/employee/pre-employment.css',
                'resources/css/employee/hr-manager/dashboard.css',
                'resources/css/employee/supervisor/dashboard.css',
                'resources/css/employee/basic/dashboard.css',
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
    server: {
        host: localIpAddress,
        hmr: {
            host: localIpAddress,
        },
    },
});
