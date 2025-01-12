@props([
    'modalId' => 'addTrainingModal',
])

<div>
    <x-modals.dialog :id="$modalId">
        <x-slot:title>
            <h1 class="modal-title fs-5 fw-bold text-secondary-emphasis" id="{{ $modalId }}">{{ __('Add New Training') }}</h1>
            <button wire:click="$dispatchSelf('close-{{ $modalId }}')" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        <x-slot:content>
            <div class="mb-3">
                <x-form.boxed-input-text id="work_performed" label="{{ __('Training Title') }}" :nonce="$nonce"
                    :required="true" placeholder="Whatever" />
            </div>

            <div class="mb-3">
                <x-form.boxed-input-text wire:model="provider" id="trainingProvider" label="{{ __('Training Provider') }}" :nonce="$nonce"
                    placeholder="Amanda Lee" />
            </div>

            <!-- Trainer. Not sure if I'll make this a dropdown (if the trainers are employee themselves or if it's external) -->
            <div class="mb-3">
                <x-form.boxed-input-text wire:model="trainer" id="trainer" label="{{ __('Trainer') }}" :nonce="$nonce"
                    :required="true" placeholder="Amanda Lee" />
            </div>

            <div class="mb-3">
                <x-form.boxed-textarea wire:model="description" id="description" label="Description" :nonce="$nonce" :rows="6" :required="true" />
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="row">
                    <label class="mb-1 fw-semibold text-secondary-emphasis"> Duration </label>
                        <div class="col-md-6">

                        <!-- Opted for dropdown in the numbers for less errors on the dumbass user's side. Populate with numbers. -->
                        <x-form.boxed-dropdown id="count" name="leave_type"
                        :nonce="$nonce" :required="true" :options="['1' => '1', '2' => '2', '3' => '3']" placeholder="Select count" />
                        </div>

                        <div class="col-md-6">
                        <x-form.boxed-dropdown id="time_unit" name="leave_type"
                        :nonce="$nonce" :required="true" :options="['days' => 'days', 'weeks' => 'weeks', 'months' => 'months']" placeholder="Select time unit" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <x-form.boxed-date id="expiry_date" label="{{ __('Date') }}" :nonce="$nonce" :required="true"
                        placeholder="Date of Overtime" />
                </div>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button class="btn btn-primary">{{ __('Submit') }}</button>
        </x-slot:footer>
    </x-modals.dialog>

    <section class="row pt-4">
        <div class="col-6">
            <label class="ps-1 mb-2 fw-semibold text-primary fs-5"> Employee Name </label>

            <div class="row">
                <div class="col-md-8">
                    <!-- BACK-END REPLACE: All Employees -->
                    <x-form.boxed-selectpicker id="incident_type" :nonce="$nonce" :required="true"
                        :options="['employee_1' => 'Cristian Manalang', 'employee_2' => 'Jobert Owen']"
                        placeholder="Select employee">
                    </x-form.boxed-selectpicker>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="hover-opacity pe-auto">
                        <i data-lucide="user-2" class="icon icon-slarge ms-2 text-blue-info"></i>
                        <!-- BACK-END REPLACE: Link to the current employee's profile -->
                        <a href="#" class="text-link-blue text-decoration-underline fs-5">
                            View Information
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 pt-2 d-flex align-items-center justify-content-end">
            <button onclick="openModal('addTrainingModal')" class="btn btn-primary">
                <i data-lucide="plus-circle" class="icon icon-large me-2"></i> Add New Training</button>
        </div>
    </section>

    <section class="my-2">
        <livewire:employee.tables.individual-employee-trainings-table :$routePrefix :$employee />
    </section>
</div>

@script
<script>
    $wire.on('close-{{ $modalId }}', () => {
        hideModal('{{ $modalId }}');
    });
</script>
@endscript