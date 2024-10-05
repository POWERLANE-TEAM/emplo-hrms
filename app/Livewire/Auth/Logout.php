<?php

namespace App\Livewire\Auth;

use App\Http\Helpers\ChooseGuard;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Livewire\Component;

class Logout extends Component
{
    protected $class;

    protected $auth_broadcast_id;

    protected $nonce;
    protected $use_guard;

    public function mount($class = 'border-0 bg-transparent')
    {
        $this->class = $class;

        $this->nonce = csp_nonce();

        $this->use_guard = ChooseGuard::getByRequest();

        $user_session = session()->getId();
        $this->auth_broadcast_id =   hash('sha512', $user_session . Auth::guard($this->use_guard)->user()->email . $user_session);
    }

    public function render()
    {

        return <<<'HTML'
        <form action="/{{ $this->use_guard }}/logout" method="POST" nonce="{{ $this->nonce }}">
            @csrf
            <input type="hidden" name="auth_broadcast_id" value="{{$this->auth_broadcast_id}}">
            <button type="submit"  nonce="{{ $this->nonce }}" class="{{$this->class}}">
                Logout
            </button>

        </form>

        HTML;
    }
    public function destroy(AuthenticatedSessionController $session_controller)
    {
        // dump(request());
        // dd($session_controller->guard);
        $response =  $session_controller->destroy(request());
        return $response->toResponse(request());
    }
}
