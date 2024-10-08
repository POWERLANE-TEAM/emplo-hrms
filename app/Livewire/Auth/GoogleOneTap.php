<?php

namespace App\Livewire\Auth;

use Exception;
use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Traits\GoogleCallback;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GoogleOneTap extends Component
{
    use GoogleCallback;

    public function render()
    {
        return view('livewire.auth.google-one-tap');
    }

    public function handleCallback(Request $request)
    {
        try {

            $client = new \Google_Client(['client_id' => config('services.google.client_id')]);

            $credential = $request->input('credential');
            
            $payload = $client->verifyIdToken($credential);
    
            if ($payload) {
    
                $user = User::where('google_id', $payload['sub'])
                    ->orWhere('email', $payload['email'])
                    ->first();
    
                if ($user) {
    
                    Auth::login($user);
    
                    return redirect('hiring');
                }

                DB::beginTransaction();
                
                $new_user = $this->saveGooglePayload($payload);

                DB::commit();

                if(! $new_user) {

                    session()->flash('error', 'Something went wrong.');

                    return redirect('/');
                }

                Auth::login($new_user);

                return redirect('hiring');
            }    

        } catch (Exception $e) {

            DB::rollBack();

            report($e);

            return redirect()->intended('/hiring');
        }
    }
}
