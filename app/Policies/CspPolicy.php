<?php

namespace App\Policies;

use Spatie\Csp\Directive;

class CspPolicy extends CustomSpatiePolicy
{
    public function configure()
    {
        parent::configure();

        if (app()->environment() === 'local') {
            /*
            Resources to use
            https://github.com/vitejs/vite/issues/11862
            https://github.com/spatie/laravel-csp/discussions/101
            https://laracasts.com/discuss/channels/laravel/how-to-set-content-security-policy-for-laravel-bundle-assets
            https://laracasts.com/discuss/channels/vite/laravel-vite-with-laravel-valet-on-local-network
            https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/connect-src

            */

            $server_ip = $this->getActiveIpAddress();

            $this
                ->addDirective(Directive::DEFAULT, 'localhost:*')
                ->addDirective(Directive::DEFAULT, "$server_ip:*")
                ->addDirective(Directive::BASE, 'localhost:*')
                ->addDirective(Directive::BASE, "$server_ip:*");

            $this
                ->addDirective(Directive::CONNECT, 'localhost:*')
                ->addDirective(Directive::CONNECT, 'ws://localhost:*') /* websocket */
                ->addDirective(Directive::CONNECT, "ws://$server_ip:*"); /* websocket */

            $this
                ->addDirective(Directive::SCRIPT, 'localhost:*')
                ->addDirective(Directive::SCRIPT, "$server_ip:*")
                ->addDirective(Directive::SCRIPT, 'unsafe-inline');
            $this->addDirective(Directive::SCRIPT, 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js');
            $this->addDirective(Directive::SCRIPT, 'https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js');

            $this
                ->addDirective(Directive::STYLE, 'localhost:*')
                ->addDirective(Directive::STYLE, "$server_ip:*");
            $this->addDirective(Directive::STYLE, 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css');
            $this->addDirective(Directive::STYLE, 'https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css');

            $this
                ->addDirective(Directive::IMG, 'localhost:*')
                ->addDirective(Directive::IMG, "$server_ip:*")
                ->addDirective(Directive::IMG, 'www.placeholder.com')
                ->addDirective(Directive::IMG, 'via.placeholder.com')
                ->addDirective(Directive::IMG, 'https://dummyimage.com')
                ->addDirective(Directive::IMG, 'placehold.it')
                ->addDirective(Directive::MEDIA, 'localhost:*')
                ->addDirective(Directive::OBJECT, 'localhost:*');

            $this
                ->addDirective(Directive::FONT, 'data:');

            $this->reportOnly();
        }

        // $this
        //     ->addDirective(Directive::CONNECT, Scheme::WSS);
        //     ->addDirective(Directive::IMG, Scheme::DATA);

        $this
            ->addDirective(Directive::CONNECT, 'https://accounts.google.com');

        $this->addDirective(Directive::STYLE, 'unsafe-inline');
        $this->addDirective(Directive::STYLE, 'https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css');
        $this->addDirective(Directive::STYLE, 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
        $this->addDirective(Directive::STYLE, 'fonts.bunny.net');
        $this->addDirective(Directive::STYLE, 'https://accounts.google.com');

        $this->addDirective(Directive::FONT, 'data:');
        $this->addDirective(Directive::FONT, 'fonts.bunny.net');

        $this->addDirective(Directive::IMG, 'data:');

        $this->addDirective(Directive::SCRIPT, 'unsafe-eval'); /* Di ko talaga mapagana livewire without this */
        // $this->addDirective(Directive::SCRIPT, 'strict-dynamic');
        $this->addDirective(Directive::SCRIPT, 'https://unpkg.com/lucide@latest');
        $this->addDirective(Directive::SCRIPT, 'https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js');
        $this->addDirective(Directive::SCRIPT, 'https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js');
        $this->addDirective(Directive::SCRIPT, 'https://kit.fontawesome.com/your-fontawesome-kit-id.js');
        $this->addDirective(Directive::SCRIPT, 'https://kit.fontawesome.com/a076d05399.js');
        $this->addDirective(Directive::SCRIPT, 'https://accounts.google.com/gsi/client');
        $this->addDirective(Directive::SCRIPT, 'https://www.google.com');
        $this->addDirective(Directive::SCRIPT, 'https://www.gstatic.com');
        $this->addDirective(Directive::SCRIPT, 'https://cdn.jsdelivr.net/npm/chart.js');

        $this->addDirective(Directive::FRAME, 'self');
        $this->addDirective(Directive::FRAME_ANCESTORS, 'self');
        $this->addDirective(Directive::FRAME, 'https://www.google.com');
        $this->addDirective(Directive::FRAME_ANCESTORS, 'https://www.google.com');
    }

    private function getActiveIpAddress()
    {
        $interfaces = net_get_interfaces();
        $wifiKeywords = ['wi-fi', 'wlan', 'wifi'];

        // First, try to find an active Wi-Fi interface
        foreach ($interfaces as $name => $details) {
            if (isset($details['description']) && isset($details['unicast'])) {
                foreach ($wifiKeywords as $keyword) {
                    if (stripos($details['description'], $keyword) !== false) {
                        foreach ($details['unicast'] as $unicast) {
                            if ($unicast['family'] === AF_INET && isset($details['up']) && $details['up']) {
                                return $unicast['address'];
                            }
                        }
                    }
                }
            }
        }

        // If no Wi-Fi interface is found, fallback to the first active non-internal IPv4 address
        foreach ($interfaces as $details) {
            if (isset($details['unicast'])) {
                foreach ($details['unicast'] as $unicast) {
                    if ($unicast['family'] === AF_INET && isset($details['up']) && $details['up']) {
                        return $unicast['address'];
                    }
                }
            }
        }

        return null;
    }
}
