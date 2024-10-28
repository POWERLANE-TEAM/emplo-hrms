<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Traits\GoogleCallback;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;

class GoogleOAuth extends Component
{
    use GoogleCallback;

    public function googleOauth()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {

                Auth::login($user);

                return redirect('/hiring');
            }

            DB::beginTransaction();

            $newUser = $this->saveGooglePayload($googleUser->user);

            DB::commit();

            if (! $newUser) {

                session()->flash('error', 'Something went wrong.');

                return redirect('/');
            }

            Auth::login($newUser);

            return redirect('/hiring');

        } catch (Exception $e) {

            DB::rollBack();

            report($e);

            return redirect()->intended('/');
        }
    }

    public function render()
    {
        return view('livewire.auth.google-sign-up');
    }
}
