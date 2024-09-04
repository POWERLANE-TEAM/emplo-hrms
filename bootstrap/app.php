<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->append(\Spatie\Csp\AddCspHeaders::class);

        if (app()->environment() === 'local') {
            $middleware->trustProxies(at: [
                'http://127.0.0.1:8000/',
            ]);
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
