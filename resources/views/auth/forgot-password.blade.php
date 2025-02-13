<script>
    const FORGOT_PASSWORD_ACTION = "{{ route('password.email') }}";
</script>
@vite(['resources/js/forgot-password.js'])
<script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>

<form method="POST" @if ($livewire)  wire:submit.prevent="forgotPassword"   @endif action="{{ route('password.email') }}">
    @csrf
    <p class="fs-6 fw-medium">Enter your email address to receive a password reset link.</p>
    <x-form.boxed-email id="reset-pw-email" label="{{ __('Email Address') }}" name="forgotPwEmail" autocomplete="email"
        :nonce="$nonce" class=" {{ $errors->has('forgotPwEmail') ? 'is-invalid' : '' }}" required
        placeholder="johndoe@gmail.com">

        <x-slot:feedback>
            @include('components.form.input-feedback', [
                'feedback_id' => 'reset-pw-email-feedback',
                'message' => $errors->first('forgotPwEmail'),
            ])
        </x-slot:feedback>
    </x-form.boxed-email>

    @if (!$livewire)
        <button type="submit" id="submitForgotPassword" class="btn btn-primary">{{ __('Submit') }}</button>
    @endif
</form>

@if (!$livewire)
    @if (session('status'))
        <script nonce="{{ $nonce }}">
            switchModal('forgotPasswordModal', 'modal-forgot-password-email-success');
        </script>

        <x-modals.email-sent label="Forgot password email sent" id="modal-forgot-password-email-success"
            header="Email Sent" :message="session('status')" />
    @endif
@endif



