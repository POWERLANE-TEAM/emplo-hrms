<div>
    <x-modals.dialog id="forgotPasswordModal">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Forgot Password') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="my-3">
                @include('auth.forgot-password', ['livewire' => true])
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button type="button" class="btn btn-primary submit" x-init="$el.disabled = true" id="submitForgotPassword"
                wire:click="forgotPassword">{{ __('Submit') }} </button>
        </x-slot:footer>
    </x-modals.dialog>

    @if (session('status'))
        <x-modals.email-sent label="Forgot password email sent" id="modal-forgot-password-email-success"
            header="Email Sent" :message="session('status')" />
    @endif
</div>


