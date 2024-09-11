<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;

class GoogleOAuth extends Component
{
    public function googleOauth()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback()
    {
        $google_user = Socialite::driver('google')->stateless()->user();

        // authenticate if user exists, else create the user
        $user = User::where('google_id', $google_user->id)->first();

        if ($user) {
            Auth::login($user);

            return redirect('/');
        } else {
            User::create([
                'email' => $google_user->email,
                'password' => null,
                'google_id' => $google_user->id,
                'role' => 'USER',
            ]);

            return redirect('/');
        }
    }
}
