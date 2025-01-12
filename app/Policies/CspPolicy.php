<?php

namespace App\Policies;

use Illuminate\Support\Facades\Cache;
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

            $serverIp = $this->getActiveIpAddress();

            $this
                ->addDirective(Directive::DEFAULT, 'localhost:*')
                ->addDirective(Directive::DEFAULT, "$serverIp:*")
                ->addDirective(Directive::BASE, 'localhost:*')
                ->addDirective(Directive::BASE, "$serverIp:*");

            $this
                ->addDirective(Directive::CONNECT, 'localhost:*')
                ->addDirective(Directive::CONNECT, 'ws://localhost:*') /* websocket */
                ->addDirective(Directive::CONNECT, "ws://$serverIp:*"); /* websocket */

            $this
                ->addDirective(Directive::SCRIPT, 'localhost:*')
                ->addDirective(Directive::SCRIPT, "$serverIp:*")
                ->addDirective(Directive::SCRIPT, 'unsafe-inline');
            $this->addDirective(Directive::SCRIPT, 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js');
            $this->addDirective(Directive::SCRIPT, 'https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js');
            $this->addDirective(Directive::SCRIPT, 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js');

            $this
                ->addDirective(Directive::STYLE, 'localhost:*')
                ->addDirective(Directive::STYLE, "$serverIp:*");
            $this->addDirective(Directive::STYLE, 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css');
            $this->addDirective(Directive::STYLE, 'https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css');

            $this
                ->addDirective(Directive::IMG, 'localhost:*')
                ->addDirective(Directive::IMG, "$serverIp:*")
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
        $this->addDirective(Directive::STYLE, 'fonts.bunny.net');
        $this->addDirective(Directive::STYLE, 'https://accounts.google.com');
        $this->addDirective(Directive::STYLE, 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css');
        $this->addDirective(Directive::STYLE, 'https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css');
        $this->addDirective(Directive::STYLE, 'https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');

        $this->addDirective(Directive::FONT, 'data:');
        $this->addDirective(Directive::FONT, 'fonts.bunny.net');
        $this->addDirective(Directive::FONT, 'https://cdn.jsdelivr.net/npm/bootstrap-icons/font/fonts/bootstrap-icons.woff');
        $this->addDirective(Directive::FONT, 'https://cdn.jsdelivr.net/npm/bootstrap-icons/font/fonts/bootstrap-icons.woff2');

        $this->addDirective(Directive::IMG, 'data:');
        $this->addDirective(Directive::IMG, 'https://ui-avatars.com');

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
        $this->addDirective(Directive::SCRIPT, 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js');
        $this->addDirective(Directive::SCRIPT, 'https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js');

        $this->addDirective(Directive::FRAME, 'self');
        $this->addDirective(Directive::FRAME_ANCESTORS, 'self');
        $this->addDirective(Directive::FRAME, 'https://www.google.com');
        $this->addDirective(Directive::FRAME_ANCESTORS, 'https://www.google.com');
    }

    private function getActiveIpAddress()
    {
        $cacheKey = 'active_ip_address';
        $cacheDuration = 60; // Cache duration in seconds

        // Check if the result is cached
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey); // Return the cached IP address directly
        }

        $interfaces = net_get_interfaces();
        $wifiKeywords = ['wi-fi', 'wlan', 'wifi'];
        $activeIps = [];

        // First loop: Check for Wi-Fi interfaces with internet connectivity and store active IPs
        foreach ($interfaces as $name => $details) {
            if (isset($details['description']) && isset($details['unicast']) && isset($details['up']) && $details['up']) {
                foreach ($details['unicast'] as $unicast) {
                    if ($unicast['family'] === AF_INET) {
                        foreach ($wifiKeywords as $keyword) {
                            if (stripos($details['description'], $keyword) !== false) {
                                if ($this->hasInternetConnection($unicast['address']) && ! str_ends_with($unicast['address'], '.1')) {
                                    Cache::put($cacheKey, $unicast['address'], $cacheDuration);

                                    return $unicast['address'];
                                }
                                $activeIps[] = ['type' => 'wifi', 'address' => $unicast['address']];
                            }
                        }
                    }
                }
            }
        }

        // Second loop: Check for any interfaces with internet connectivity and store active IPs
        foreach ($interfaces as $name => $details) {
            if (isset($details['description']) && isset($details['unicast']) && isset($details['up']) && $details['up']) {
                foreach ($details['unicast'] as $unicast) {
                    if ($unicast['family'] === AF_INET) {
                        if ($this->hasInternetConnection($unicast['address']) && ! str_ends_with($unicast['address'], '.1')) {
                            Cache::put($cacheKey, $unicast['address'], $cacheDuration);

                            return $unicast['address'];
                        }
                        $activeIps[] = ['type' => 'other', 'address' => $unicast['address']];
                    }
                }
            }
        }

        // If no IP address with internet connectivity is found, fallback to any active IP address, prioritizing Wi-Fi
        foreach ($activeIps as $ip) {
            if ($ip['type'] === 'wifi' && ! str_ends_with($ip['address'], '.1')) {
                Cache::put($cacheKey, $ip['address'], $cacheDuration);

                return $ip['address'];
            }
        }

        // If no Wi-Fi IP is found, return any active IP
        $fallbackIp = $activeIps[0]['address'] ?? 'localhost';
        Cache::put($cacheKey, $fallbackIp, $cacheDuration);

        return $fallbackIp;
    }

    private function hasInternetConnection($ipAddress)
    {
        $cacheKey = 'internet_connection_check';
        $cacheDuration = 60; // Cache duration in seconds

        // Check if the result is cached
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $servers = ['8.8.8.8', '1.1.1.1', '208.67.222.222']; // Google's DNS, Cloudflare's DNS, OpenDNS
        $connected = false;

        foreach ($servers as $server) {
            $connected = @fsockopen($server, 53); // Attempt to connect to the DNS server
            if ($connected) {
                fclose($connected);
                break;
            }
        }

        // Cache the result
        Cache::put($cacheKey, $connected, $cacheDuration);

        return $connected;
    }
}
