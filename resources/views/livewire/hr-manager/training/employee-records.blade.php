@props([
    'modalId' => 'addTrainingModal',
])

<div>
    <x-modals.dialog :id="$modalId" data-bs-backdrop="static" data-bs-keyboard="false">
        <x-slot:title>
            <h1 class="modal-title fs-5 fw-bold text-secondary-emphasis" id="{{ $modalId }}">{{ __('Add New Training') }}</h1>
            <button wire:click="$dispatchSelf('close-{{ $modalId }}')" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        <x-slot:content>
            <div class="row">
                <div class="mb-3 col">
                    <x-form.boxed-input-text wire:model="title" id="work_performed" label="{{ __('Training Title') }}" :nonce="$nonce"
                        :required="true" placeholder="Interpersonal Communication" />
                    @error('title')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col">
                    <x-form.boxed-input-text 
                        wire:model="provider" 
                        id="trainingProvider" 
                        label="{{ __('Training Provider') }}" 
                        :nonce="$nonce"
                        :disabled="! $isTrainerOutsourced"
                        placeholder="{{ $isTrainerOutsourced ? __('Microsoft Philippines') : __('In-house training') }}" 
                    />
                    @error('provider')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror

                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            id="isTrainerOutsourced"
                            wire:model.live="isTrainerOutsourced"
                            value="true"
                        >
                        <label class="form-check-label" for="isTrainerOutsourced">
                            {{ __('Is trainer outsourced?') }}
                        </label>
                    </div>                        
                </div>

                <div class="mb-3 col">
                    @if (! $isTrainerOutsourced)
                        <x-form.boxed-dropdown
                            wire:model="trainer"
                            id="completion" 
                            label="{{ __('Employee Trainer') }}" 
                            :nonce="$nonce" 
                            :required="true" 
                            :options="$this->employeeOptions" 
                            placeholder="Select option" />
                            @error('trainer')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                    @else
                        <x-form.boxed-input-text 
                            wire:model="trainer" 
                            id="trainer" 
                            label="{{ __('Outsourced Trainer') }}" 
                            :nonce="$nonce"
                            :required="true" 
                            placeholder="Amanda Lee" />  
                            @error('trainer')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror                  
                    @endif
                </div>              
            </div>

            <div class="row">
                <label class="mb-3 fw-semibold text-secondary-emphasis">{{ __('Duration') }}</label>

                @php $validDate = today()->format('Y-m-d'); @endphp

                <div class="col">
                    <x-form.boxed-date wire:model="startDate" id="startDate" label="{{ __('Start Date') }}" :nonce="$nonce" :required="true"
                    placeholder="Date" />
                    @error('startDate')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <x-form.boxed-date wire:model="endDate" id="endDate" label="{{ __('End Date') }}" :nonce="$nonce" :required="true"
                    placeholder="Date" />
                    @error('endDate')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <x-form.boxed-date wire:model="expiryDate" id="expiryDate" label="{{ __('Expiry Date') }}" :nonce="$nonce" :required="true"
                        placeholder="Date" />
                    @error('expiryDate')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <x-form.boxed-dropdown id="completion" label="{{ __('Completion Status') }}" name="completion"
                    :nonce="$nonce" :required="true" :options="$this->completionStatuses" placeholder="Select option" />
                    @error('completion')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <x-form.boxed-textarea wire:model="description" id="description" label="Description" :nonce="$nonce" :rows="6" :required="true" />
                @error('description')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button wire:loading.attr="disabled" wire:click="save" class="btn btn-primary">{{ __('Submit') }}</button>
        </x-slot:footer>
    </x-modals.dialog>

    <section class="row pt-2 pb-4">
        <div class="col-6 d-flex align-items-center">
            <div wire:ignore>
                <span class="pe-3">
                    <i class="icon text-success d-inline" data-lucide="badge-check"></i>
                </span>
            </div>
            <img 
                class="rounded-circle me-3" 
                width="50" 
                height="50"
                src="{{ $employee->account->photo }}" 
                alt="Employee photo"
            >                        
            <div>
                <div class="d-flex align-items-center">
                    <div wire:ignore class="me-3">{{ $employee->full_name }}</div>   
                </div>
                <div class="text-muted fs-6">{{ __("Employee Id: {$employee->employee_id}") }}</div>
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

    Livewire.on('trainingRecordCreated', (event) => {
        hideModal('{{ $modalId }}');

        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });
</script>
@endscript