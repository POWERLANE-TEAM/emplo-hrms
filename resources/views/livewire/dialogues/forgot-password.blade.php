<div>
    <x-modals.dialog id="forgotPassword">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Forgot Password') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>
        <x-slot:content>
            <div class="my-3"">

                <p class="fs-6 fw-medium">Enter your email address to receive a password reset link.</p>
                <x-form.boxed-input-text id=" trainer" label="{{ __('Email Address') }}" :nonce="$nonce" :required="true"
                placeholder="johndoe@gmail.com" />
            </div>
</x-slot:content>
<x-slot:footer>
    <button class="btn btn-primary">{{ __('Submit') }}</button>

    <!-- Use a status-modal here or x-email-sent once email has been sent -->
</x-slot:footer>
</x-modals.dialog>
</div>