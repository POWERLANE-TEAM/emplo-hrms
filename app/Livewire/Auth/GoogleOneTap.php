<?php

namespace App\Livewire\Auth;

use Exception;
use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Traits\GoogleCallback;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse;

class GoogleOneTap extends Component
{
    use GoogleCallback;

    public function render()
    {
        return view('livewire.auth.google-one-tap');
    }

    public function handleCallback(Request $request)
    {

        $client = new \Google_Client(['client_id' => config('services.google.client_id')]);
        $credential = $request->input('credential');
        $payload = $client->verifyIdToken($credential);
        if ($payload) {
            $user = User::where('google_id', $payload['sub'])
                ->orWhere('email', $payload['email'])
                ->first();

            if ($user) {
                Auth::login($user);
                
                return app(LoginResponse::class);
            }

            $newUser = $this->saveGooglePayload($payload);

            if (! $newUser) {
                session()->flash('error', 'Something went wrong.');

                return redirect('/');
            }
            Auth::login($newUser);

            return app(LoginResponse::class);
        }
    }
}
