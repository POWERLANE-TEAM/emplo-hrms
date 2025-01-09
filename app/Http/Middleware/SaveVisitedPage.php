<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SaveVisitedPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Auth::check()) {
            // Save the current URL to the session
            session(['url.intended' => $request->url()]);
        }

        $response = $next($request);

        // Ensure the response is a valid Response object
        if ($response instanceof Response) {
            return $response;
        }

        // Handle cases where the response is not a valid Response object
        return response($response);
    }
}
