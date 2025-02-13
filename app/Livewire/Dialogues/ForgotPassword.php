<?php

namespace App\Livewire\Dialogues;

use App\Rules\EmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
    #[Validate]
    public $forgotPwEmail;

    public function forgotPassword()
    {
        $this->validate();

        config(['fortify.prefix' => '']);

        $status = Password::sendResetLink(['email' => $this->forgotPwEmail]);

        if ($status == Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));
            $this->js("switchModal('forgotPasswordModal', 'modal-forgot-password-email-success')");
        } else {
            report($status);
            $this->dispatch('show-toast', [
                'type' => 'danger',
                'message' => 'An error occurred while sending the password reset link.',
            ]);
        }
    }

    protected function validationAttributes()
    {
        return [
            'forgotPwEmail' => 'email address',
        ];
    }

    protected function rules()
    {
        return  [
            'forgotPwEmail' => 'required|' . (new EmailRule(false))->getRule(),
        ];
    }

    public function render()
    {
        return view('livewire.dialogues.forgot-password');
    }
}
