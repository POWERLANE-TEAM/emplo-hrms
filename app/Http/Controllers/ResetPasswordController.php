<?php

namespace App\Http\Controllers;

use App\Http\Helpers\RouteHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function index(Request $request)
    {
        $throttleKey = Str::lower($request->input('email')).'|'.$request->ip();
        RateLimiter::hit($throttleKey, 2 * 60);

        if (RateLimiter::tooManyAttempts($throttleKey, 3) && ! app()->isLocal()) {
            abort(429, 'Too many attempts');
        }

        try {
            $passwordResetor = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'The email address is not registered.');

            return redirect()->route('login');
            // return redirect('/');
        }

        // tokenExist handle both non existing and expired checks
        if (! $passwordResetor || ! Password::tokenExists($passwordResetor, $request->token)) {

            $routePrefix = RouteHelper::getByUser($passwordResetor);

            session()->flash('error', 'The password reset token is invalid or has expired.');

            return redirect()->route($routePrefix.'login');
        }

        return view('auth.reset-password', ['request' => $request]);
    }
}
