import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import { sync } from 'glob';
import os from 'os';

function getLocalIpAddress() {
    const interfaces = os.networkInterfaces();

    // Prioritize Wi-Fi adapters based on common names
    const wifiNames = ['Wi-Fi', 'wlan', 'WiFi'];

    // Check Wi-Fi interfaces first
    for (const name of Object.keys(interfaces)) {
        if (wifiNames.some(wifiName => name.toLowerCase().includes(wifiName.toLowerCase()))) {
            for (const iface of interfaces[name]) {
                if (iface.family === 'IPv4' && !iface.internal && iface.address !== '127.0.0.1' && !iface.address.endsWith('.1')) {
                    return iface.address;
                }
            }
        }
    }

    // If no Wi-Fi interface is found, fallback to the first NIC
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            if (iface.family === 'IPv4' && !iface.internal && iface.address !== '127.0.0.1' && !iface.address.endsWith('.1')) {
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
                ...sync('./resources/js/**/*.js',),
                ...sync('./resources/css/**/*.css'),
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
            'emp-sidebar-script': "/resources/js/employee/side-top-bar.js",
            'theme-listener-script': "/resources/js/theme-listener.js",
            'employee-page-script': "/resources/js/employee/employee.js",
            'globalListener-script': "/resources/js/utils/global-event-listener.js",
            'iframe-full-screener-script': "/resources/js/utils/iframe-full-screener.js",
            'websocket-script': "/resources/js/websocket.js",
            'datatable': "/vendor/rappasoft/laravel-livewire-tables/resources/imports/laravel-livewire-tables-all.js",
        },
    },
    server: {
        host: localIpAddress,
        hmr: {
            host: localIpAddress,
        },
    },
});
