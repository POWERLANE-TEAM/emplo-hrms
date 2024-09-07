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
                ->addDirective(Directive::DEFAULT, 'localhost:*')
                ->addDirective(Directive::BASE, 'localhost:*');

            $this
                ->addDirective(Directive::CONNECT, 'localhost:*')
                ->addDirective(Directive::CONNECT, 'ws://localhost:*'); /* websocket */

            $this
                ->addDirective(Directive::SCRIPT, 'localhost:*')
                ->addDirective(Directive::SCRIPT, 'unsafe-inline');

            $this
                ->addDirective(Directive::STYLE, 'localhost:*');

            $this
                ->addDirective(Directive::IMG, 'localhost:*')
                ->addDirective(Directive::IMG, 'www.placeholder.com')
                ->addDirective(Directive::IMG, 'via.placeholder.com')
                ->addDirective(Directive::IMG, 'https://dummyimage.com')
                ->addDirective(Directive::IMG, 'placehold.it')
                ->addDirective(Directive::MEDIA, 'localhost:*')
                ->addDirective(Directive::OBJECT, 'localhost:*');

            $this
                ->addDirective(Directive::FONT, 'data:');
        }

        // $this
        //     ->addDirective(Directive::CONNECT, Scheme::WSS)
        //     ->addDirective(Directive::IMG, Scheme::DATA);

        $this->addDirective(Directive::STYLE, 'unsafe-inline');
        $this->addDirective(Directive::STYLE, 'https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css');
        $this->addDirective(Directive::STYLE, 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
        $this->addDirective(Directive::STYLE, 'fonts.bunny.net');

        $this->addDirective(Directive::FONT, 'data:');
        $this->addDirective(Directive::FONT, 'fonts.bunny.net');

        $this->addDirective(Directive::IMG, 'data:');

        $this->addDirective(Directive::SCRIPT, 'unsafe-eval'); /* Di ko talaga mapagana livewire without this */
        // $this->addDirective(Directive::SCRIPT, 'strict-dynamic');
        $this->addDirective(Directive::SCRIPT, 'https://unpkg.com/lucide@latest');
        $this->addDirective(Directive::SCRIPT, 'https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js');
        $this->addDirective(Directive::SCRIPT, 'https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js');
        $this->addDirective(Directive::SCRIPT, 'https://kit.fontawesome.com/your-fontawesome-kit-id.js');
        $this->addDirective(Directive::SCRIPT, 'https://accounts.google.com/gsi/client');
        $this->addDirective(Directive::SCRIPT, 'https://www.google.com');
        $this->addDirective(Directive::SCRIPT, 'https://www.gstatic.com');
        // $this->addDirective(Directive::SCRIPT, 'http://localhost:5173/resources/js/livewire.js');

        $this->addDirective(Directive::FRAME, 'https://www.google.com');
    }
}
