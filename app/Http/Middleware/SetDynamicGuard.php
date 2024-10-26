<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ChooseGuard;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SetDynamicGuard
{
    /**
     * Checks incoming request and choose appropriate guard.
     *
     * Uses referer to determine the guard.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // This could handle the guard switching e.g. livewire/update requests
        $guard = ChooseGuard::getByReferrer($request);
        if ($guard) Auth::shouldUse($guard);

        return $next($request);
    }
}
