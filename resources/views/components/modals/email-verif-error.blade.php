@props(['message'])

@guest

    <x-modals.status-modal type="error" label="Error Sending Email Verification" header="Verification Email Not Sent"
        id="modal-verification-email-error">
        <x-slot:message>
            {{ session('verification-email-error') ?? 'An error occurred while sending the verification email. Please try again later.' }}
        </x-slot:message>
    </x-modals.status-modal>

@endguest
