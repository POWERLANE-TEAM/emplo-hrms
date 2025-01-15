<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait NeedsAuthBroadcastId
{
    /**
     * Generate a unique authentication broadcast ID of the current user session.
     *
     * @return string The generated authentication broadcast ID.
     */
    public static function generateAuthId()
    {
        if (Auth::check()) {
            $userSession = session()->getId();
            $userIdentity = Auth::user()->email ?? Auth::id();

            return hash('sha512', $userSession.$userIdentity.$userSession);
        } else {
            abort(401);
        }
    }
}
