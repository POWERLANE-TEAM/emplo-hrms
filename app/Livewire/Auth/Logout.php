<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    protected $class;

    public function mount($class = 'border-0 bg-transparent')
    {
        $this->class = $class;
    }

    public function render()
    {
        $nonce = csp_nonce();

        return <<<'HTML'
        <form action="/logout" method="POST" nonce="{ $nonce }">
            @csrf

            <button type="submit"  nonce="{ $nonce }" class="{{$this->class}}">
                Logout
            </button>

        </form>

        HTML;
    }
}
