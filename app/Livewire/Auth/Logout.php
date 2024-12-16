<?php

namespace App\Livewire\Auth;

use App\Enums\ActivityLogName;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Livewire\Component;

class Logout extends Component
{
    protected $class;

    protected $authBroadcastId;

    protected $nonce;

    protected $use_guard;

    public function mount($class = 'border-0 bg-transparent p-0')
    {
        $this->class = $class;

        $this->nonce = csp_nonce();

        $user_session = session()->getId();
        $this->authBroadcastId = hash('sha512', $user_session.Auth::user()->email.$user_session);
    }

    public function render()
    {

        return <<<'HTML'
        <form action="/logout" method="POST" nonce="{{ $this->nonce }}">
            @csrf
            <input type="hidden" name="authBroadcastId" value="{{$this->authBroadcastId}}">
            <button type="submit"  nonce="{{ $this->nonce }}" class="{{$this->class}}">
                Logout
            </button>

        </form>

        HTML;
    }

    public function destroy(AuthenticatedSessionController $session_controller)
    {
        activity()
            ->by(Auth::user())
            ->useLog(ActivityLogName::AUTHENTICATION->value)
            ->log(Str::ucfirst(Auth::user()->account->first_name).' logged out');

        $response = $session_controller->destroy(request());

        return $response->toResponse(request());
    }
}
