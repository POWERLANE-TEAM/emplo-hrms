<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    protected $class;
    protected $auth_broadcast_id;
    protected $nonce;

    public function mount($class = 'border-0 bg-transparent')
    {
        $this->class = $class;

        $this->nonce = csp_nonce();

        $user_session = session()->getId();
        $this->auth_broadcast_id =   hash('sha512', $user_session . Auth::user()->email . $user_session);
    }

    public function render()
    {

        return <<<'HTML'
        <form action="/logout" method="POST" nonce="{{ $this->nonce }}">
            @csrf
            <input type="hidden" name="auth_broadcast_id" value="{{$this->auth_broadcast_id}}">
            <button type="submit"  nonce="{{ $this->nonce }}" class="{{$this->class}}">
                Logout
            </button>

        </form>

        HTML;
    }
}
