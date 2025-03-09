<?php

namespace App\Livewire\Dialogues;

use App\Rules\EmailRule;
use Illuminate\Support\Facades\Password;
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

        switch ($status) {
            case Password::RESET_LINK_SENT:
                session()->flash('status', __($status));
                $this->js("switchModal('forgotPasswordModal', 'modal-forgot-password-email-success')");
                break;

            case Password::RESET_THROTTLED:
                $this->dispatch('show-toast', [
                    'type' => 'danger',
                    'message' => 'Too many requests. Please try again later.',
                ]);
                break;

            case Password::INVALID_USER:
                $this->dispatch('show-toast', [
                    'type' => 'danger',
                    'message' => 'The email address is not registered.',
                ]);
                break;

            case Password::INVALID_TOKEN:
                $this->dispatch('show-toast', [
                    'type' => 'danger',
                    'message' => 'The password reset token is invalid.',
                ]);
                break;

            default:
                report($status);
                $this->dispatch('show-toast', [
                    'type' => 'danger',
                    'message' => 'An error occurred while sending the password reset link.',
                ]);
                break;
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
        return [
            'forgotPwEmail' => 'required|'.(new EmailRule(false))->getRule(),
        ];
    }

    public function render()
    {
        return view('livewire.dialogues.forgot-password');
    }
}
