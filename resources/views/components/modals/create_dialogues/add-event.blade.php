<x-modals.dialog id="addEventModal">
    <x-slot:title>
        <h1 class="modal-title fs-5">{{ __('Add Event') }}</h1>
        <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
    </x-slot:title>
    <x-slot:content>
        <div class="my-3"">
            <x-form.boxed-input-text id="trainer" label="{{ __('Event Name') }}" :nonce="$nonce"
                :required="true" placeholder="National Hero's Day" />
        </div>
        <div class="mb-3">
            <x-form.boxed-dropdown id="priority" label="{{ __('Select Type') }}"  :required="true" :nonce="$nonce" :options="['1' => 'Regular Holiday', '2' => 'Special Non-working Holiday', '3' => 'Company event']" placeholder="Select type of event" />
        </div>
    </x-slot:content>
    <x-slot:footer>
        <button class="btn btn-primary">{{ __('Add Event') }}</button>
    </x-slot:footer>
</x-modals.dialog>