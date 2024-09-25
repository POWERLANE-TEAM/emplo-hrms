<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GoogleOneTap extends Component
{
    public function render()
    {
        return view('livewire.auth.google-one-tap');
    }

    public function handleCallback()
    {
        $client = new \Google_Client(['client_id' => config('services.google.client_id')]);
        $payload = $client->verifyIdToken($_POST['credential']);

        if ($payload) {
            $findUser = User::where('google_id', $payload['sub'])->first();

            if ($findUser) {
                return Auth::login($findUser);
            } else {
                $newUser = User::updateOrCreate([
                    'email' => $payload['email'],
                    'password' => null,
                    'google_id' => $payload['sub'],
                    'role' => 'USER',
                ]);

                return Auth::login($newUser);
            }
        }
    }
}
