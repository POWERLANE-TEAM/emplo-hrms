@props(['message'])

@guest

    <x-modals.status-modal type="success" label="Account Creation Succesful" header="Account Creation Successful"
        id="modal-sign-up-success">
        <x-slot:message>
            An email with the next steps will be sent shortly.
        </x-slot:message>
    </x-modals.status-modal>

@endguest
