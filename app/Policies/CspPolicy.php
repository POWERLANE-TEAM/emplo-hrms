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

            $this
                ->addDirective(Directive::BASE, 'localhost:*')
                ->addDirective(Directive::CONNECT, 'localhost:*')
                ->addDirective(Directive::CONNECT, 'ws://localhost:*') /* websocket */
                ->addDirective(Directive::DEFAULT, 'localhost:*')
                ->addDirective(Directive::IMG, 'localhost:*')
                ->addDirective(Directive::MEDIA, 'localhost:*')
                ->addDirective(Directive::OBJECT, 'localhost:*')
                ->addDirective(Directive::SCRIPT, 'localhost:*')
                ->addDirective(Directive::STYLE, 'localhost:*')
                ->addDirective(Directive::IMG, 'www.placeholder.com')
                ->addDirective(Directive::IMG, 'via.placeholder.com')
                ->addDirective(Directive::IMG, 'placehold.it');
        }

        // $this
        //     ->addDirective(Directive::CONNECT, Scheme::WSS)
        //     ->addDirective(Directive::IMG, Scheme::DATA);

        $this->addDirective(Directive::STYLE, 'unsafe-inline');
        $this->removeDirective(Directive::STYLE, 'nonce-', 'starts_with');
        $this->addDirective(Directive::DEFAULT, 'fonts.bunny.net');
        $this->addDirective(Directive::STYLE, 'fonts.bunny.net');
        $this->addDirective(Directive::SCRIPT, 'https://unpkg.com/lucide@latest');
        $this->addDirective(Directive::SCRIPT, 'https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js');
        $this->addDirective(Directive::IMG, 'data:');
    }
}
