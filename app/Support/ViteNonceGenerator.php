<?php

namespace App\Support;

use Illuminate\Support\Facades\Vite;
use Spatie\Csp\Nonce\NonceGenerator;

class ViteNonceGenerator implements NonceGenerator
{
    public function generate(): string
    {

        // $session_id = session()->getId();
        // $custom_hash = 'nonce-' . hash('sha512', $session_id);
        // return Vite::useCspNonce($custom_hash);
        return Vite::useCspNonce();
    }
}
