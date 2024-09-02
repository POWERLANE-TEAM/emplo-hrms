<?php
return [
    'origin' => env('RECAPTCHAV3_ORIGIN', 'https://www.google.com/recaptcha'),
    'sitekey' => env('RECAPTCHAV3_SITE_KEY', ''),
    'secret' => env('RECAPTCHAV3_SECRET_KEY', ''),
    'locale' => env('RECAPTCHAV3_LOCALE', '')
];
