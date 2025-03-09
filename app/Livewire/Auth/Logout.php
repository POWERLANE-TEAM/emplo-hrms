<?php

namespace App\Livewire\Auth;

use App\Enums\ActivityLogName;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Livewire\Component;

class Logout extends Component
{
    protected ComponentAttributeBag $buttonAttributes;

    protected ComponentAttributeBag $formAttributes;

    protected $authBroadcastId;

    protected $nonce;

    protected $use_guard;

    public function mount(?ComponentAttributeBag $buttonAttributes = null, ?ComponentAttributeBag $formAttributes = null)
    {
        $buttonAttributes ??= new ComponentAttributeBag;
        $formAttributes ??= new ComponentAttributeBag;

        $nonce = csp_nonce();

        $this->formAttributes = $formAttributes->merge(['nonce' => $nonce]);
        $this->buttonAttributes = $buttonAttributes->merge(['class' => 'border-0 px-0 w-100 text-start bg-transparent', 'nonce' => $nonce]);

        $user_session = session()->getId();
        $this->authBroadcastId = hash('sha512', $user_session.Auth::user()->email.$user_session);
    }

    public function render()
    {

        return <<<'HTML'
        <form  action="/logout" method="POST" {{$this->formAttributes}}>
            @csrf
            <input type="hidden" name="authBroadcastId" value="{{$this->authBroadcastId}}">
            <button type="submit"  {{$this->buttonAttributes}}>Logout
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
