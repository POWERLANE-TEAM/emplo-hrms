@props(['message'])

@guest

    <x-modals.status-modal type="error" label="Error Creating Account" header="Account Creation Failed"
        id="modal-sign-up-error">
        <x-slot:message>
            An error occurred while creating your account. Please try again later.
        </x-slot:message>
    </x-modals.status-modal>

@endguest
