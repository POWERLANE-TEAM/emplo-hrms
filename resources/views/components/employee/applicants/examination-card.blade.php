@php

    $inputGroupAttributes = ['class' => 'input-group gap-1 min-w-100 px-5'];
    $inputWrapperClasses = ['class' => 'col-12 text-start'];
    $isInitAssessment = false;
@endphp

<div class="bg-body-secondary rounded-3 col p-3 px-lg-5 py-md-4 text-center position-relative">
    @if ($isInitAssessment)
        <button class="btn position-absolute text-primary top-0 end-0 m-1" type="button" data-bs-toggle="modal" data-bs-target="#edit-exam-sched">
            <i class="icon icon-large" data-lucide="pencil-line"></i>
        </button>
    @endif
    <label for="applicant-exam-date" class="d-block text-uppercase text-primary fw-medium mt-2">Examination</label>
    <strong id="applicant-exam-date" class="applicant-exam-date fs-4 fw-bold">
    {{$slot}}
    </strong>


    <x-modals.dialog id="edit-exam-sched">
        <x-slot:title>
            <h1 class="modal-title fs-5">{{ __('Set Schedule') }}</h1>
            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
        </x-slot:title>

        <x-slot:content>
            <div class="mx-auto">
                @livewire('employee.applicants.set-examination-date', ['application' => $application, 'routePrefix' => $routePrefix, 'postMethod' => 'PATCH',
                'inputGroupAttributes'=> $inputGroupAttributes ,
                'dateWrapAttributes'=> $inputWrapperClasses ,
                'timeWrapAttributes'=> $inputWrapperClasses,
                'overrideInputContainerClass' => true,
                'overrideDateWrapper' => true,
                'overrideTimeWrapper' => true
                ])
            </div>

            <button type="button" class="btn btn-success" wire:click="dispatch('submit-exam-sched-form')">Reschedule</button>

            {{-- @livewire('employee.applicants.set-init-interview-date', ['application' => $application, 'routePrefix' => $routePrefix]) --}}
        </x-slot:content>

        <x-slot:footer>

        </x-slot:footer>

    </x-modals.dialog>

</div>
