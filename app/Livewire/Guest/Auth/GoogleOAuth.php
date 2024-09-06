<?php

namespace App\Livewire\Guest\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleOAuth extends Component
{
    public function googleOauth()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $google_user = Socialite::driver('google')->user();

        // authenticate if user exists, else create the user
        $user = User::where('google_id', $google_user->id)->first();

        if ($user) {
            Auth::login($user);
            return redirect('/');
        } else {
            User::create([
                'email' => $google_user->email,
                'password' => NULL,
                'google_id' => $google_user->id,
                'role' => 'USER',
            ]);

            return redirect('/');
        }
    }
}
