@php

    $inputGroupAttributes = ['class' => 'input-group gap-1 min-w-100 px-5'];
    $inputWrapperClasses = ['class' => 'col-12 text-start'];
    $isInitAssessment = false;
@endphp

<div class="bg-body-secondary rounded-3 col p-3 px-lg-5 py-md-4 text-center position-relative">
    @if ($isInitAssessment)
        <button class="btn position-absolute text-primary top-0 end-0 m-1" type="button" data-bs-toggle="modal"
            data-bs-target="#edit-init-interview">
            <i class="icon icon-large" data-lucide="pencil-line"></i>
        </button>
    @endif

    <label for="applicant-interview-date" class="d-block text-uppercase text-primary fw-medium mt-2">{{-- Initial --}}
        Interview</label>
    <strong id="applicant-interview-date" class="applicant-interview-date fs-4 fw-bold">
        {{ $slot }}
    </strong>

    <x-modals.dialog id="edit-init-interview">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Set Schedule') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>

        <x-slot:content>
            @livewire('employee.applicants.set-init-interview-date', ['application' => $application, 'routePrefix' => $routePrefix, 'inputGroupAttributes' => $inputGroupAttributes, 'dateWrapAttributes' => $inputWrapperClasses, 'timeWrapAttributes' => $inputWrapperClasses, 'overrideInputContainerClass' => true, 'overrideDateWrapper' => true, 'overrideTimeWrapper' => true])

            <button type="button" class="btn btn-success"
                wire:click="dispatch('submit-init-interview-sched-form')">Reschedule</button>
        </x-slot:content>

        <x-slot:footer>

        </x-slot:footer>

    </x-modals.dialog>

</div>
