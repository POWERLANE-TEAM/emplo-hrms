@use('App\Enums\ApplicationStatus')
@use('Illuminate\View\ComponentAttributeBag')
@use('App\Enums\BasicEvalStatus')
@use('App\Enums\InterviewRating')

<div>
    <div class="d-flex justify-content-xl-between justify-content-xxl-start flex-wrap flex-md-nowrap px-md-1 px-lg-3 mb-4 mb-lg-5 min-w-100"
        style="min-height: 62dvh;">
        <div class="container d-flex flex-column mx-0 col-12 col-md-5 px-0 pe-md-3 row-gap-4 {!! when($isPending, 'min-h-md-inherit') !!} ">
            <div class="bg-body-secondary rounded-3 p-3 px-lg-5 py-md-4  {!! when($isPending, 'flex-grow-1') !!}">

                @if ($isPending)
                    <hgroup class="mb-4 mb-md-5">
                        <span class="h3 text-primary ">ID:
                            <span class="h3 text-primary" role="heading" aria-level="2">
                                {{ $applicationId }}
                            </span>
                        </span>
                        <p class="fs-4">{{ $application->vacancy->jobTitle->job_title }}</p>
                    </hgroup>
                @elseif ($this->isInitAssessment || $application->application_status_id == ApplicationStatus::FINAL_INTERVIEW_SCHEDULED->value)
                    <hgroup class="p-3 p-md-4 text-center ">
                        <div class="h2 text-primary fw-bold">ID:
                            <span class="h2 text-primary fw-bold" role="heading" aria-level="2">
                                {{ $applicationId }}
                            </span>
                        </div>
                        <div aria-label="Applicant Name" class="fs-4">
                            {{ $application->applicant->fullname }}
                        </div>
                    </hgroup>
                @endif

                @if ($isPending)
                    <div class="mb-3 mb-md-4">
                        <div id="applicant-email">Email Address</div>
                        <Address aria-labelledby="applicant-email">
                            <x-mail-link class="d-block text-truncate fw-bold unstyled" :email="$application->applicant->account->email ?? ''"></x-mail-link>
                        </Address>
                    </div>

                    <div class="mb-3 mb-md-4">
                        <div id="applicant-name">Full Name</div>
                        <div aria-labelledby="applicant-name" class=" text-truncate fw-bold">
                            {{ $application->applicant->fullname }}</div>
                    </div>

                    <div class="mb-3 mb-md-4">
                        <div id="applicant-contact-no">Contact Number</div>
                        <Address aria-labelledby="applicant-contact-no" class="d-block">
                            <x-phone-link class="d-inline-block fw-bold unstyled" :phone="$application->applicant->contact_number"
                                separator=" / "></x-phone-link>
                        </Address>
                    </div>

                    <div class="mb-3 mb-md-4">
                        <div id="applicant-bdate">Birthdate</div>
                        <time aria-labelledby="applicant-bdate" datetime="{{ now()->toDateString() }}"
                            class="d-block fw-bold">{{ \Carbon\Carbon::parse($application->applicant->date_of_birth)->format('F j, Y') }}</time>
                    </div>

                    <div>
                        <div id="applicant-bio-sex">Sex in Birth</div>
                        <div aria-labelledby="applicant-bio-sex" class=" text-truncate fw-bold">
                            {{ $application->applicant->sex }}</div>
                    </div>
                @endif



            </div>

            {{-- TODO add checks if atleast one of the schedule is set or what --}}
            @if ($isInitAssessment ||
            $application->application_status_id == ApplicationStatus::FINAL_INTERVIEW_SCHEDULED->value
            )
                <div class="d-flex gap-4 min-w-100 ">

                    <x-employee.applicants.examination-card :application="$application" :isInitAssessment="$isInitAssessment">
                        {{ $examSchedF }}
                    </x-employee.applicants.examination-card>

                    <x-employee.applicants.init-interview-card :application="$application" :isInitAssessment="$isInitAssessment">
                        {{ $initialInterviewSchedF }}
                    </x-employee.applicants.init-interview-card>
                </div>

                {{-- SET EXAM RESULT MODAL --}}
                <x-modals.dialog id="modal-assign-exam-result">
                    <x-slot:title>
                        <h1 class="modal-title fs-5"></h1>
                        <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
                    </x-slot:title>
                    <x-slot:content>
                        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'text-center fs-5'])" :overrideClass="true"
                            class="text-primary fs-3 fw-bold mb-2">
                            <x-slot:heading>
                                {{ __('Assign Result ') }}
                            </x-slot:heading>

                            <x-slot:description>
                                <label for="exam-result">{{ __('Select if the candidate passed or not') }}</label>
                            </x-slot:description>
                        </x-headings.main-heading>
                        <livewire:employee.applicants.set-exam-result :application="$application" />

                    </x-slot:content>
                    <x-slot:footer>

                    </x-slot:footer>
                </x-modals.dialog>

                {{-- ASSIGN EXAM RESULT BUTTON --}}

                <div class="d-flex gap-4 min-w-100 ">
                    <div class="bg-body-tertiary rounded-3 col p-3 position-relative">
                        <label for="applicant-exam-result"
                            class="d-block text-uppercase text-primary fw-medium mb-2">Examination Result</label>
                        <div id="applicant-exam-result" class="applicant-exam-result d-flex align-items-center fw-bold">
                            <span
                                class="flex-1">{{ BasicEvalStatus::labelForValue($application->exam->passed) }}</span>
                            <button class="btn btn-sm btn-outline-secondary px-3 px-md-4" type="button"
                                id="toggle-assign-exam-modal" {!! when($notYetExam, 'disabled') !!}>
                                {{ is_null($application->exam->passed) ? 'Assign' : 'Edit' }}
                            </button>
                        </div>

                    </div>

                    {{-- SET INTERVIEW RESULT  MODAL --}}

                    <x-modals.dialog id="modal-assign-init-interview-result">
                        <x-slot:title>
                            <h1 class="modal-title fs-5"></h1>
                            <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
                        </x-slot:title>
                        <x-slot:content>
                            <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'text-center fs-5'])" :overrideClass="true"
                                class="text-primary fs-3 fw-bold mb-2">
                                <x-slot:heading>
                                    {{ __('Assign Results') }}
                                </x-slot:heading>

                                <x-slot:description>
                                    {{ __('Enter initial interview results of the applicant') }}
                                </x-slot:description>
                                {{-- INSERT notice --}}
                            </x-headings.main-heading>
                            <div class="d-grid mx-auto px-md-5 row-gap-4 row-gap-md-3 overflow-y-auto"
                                style="max-height: 50cqh;">

                                @if ($isReadyForInitEvaluation)
                                    <livewire:employee.applicants.set-init-interview-result :initInterview="$application->initialInterview"
                                        :interviewParameterItems="$interviewParameters" />
                                @endif

                            </div>
                        </x-slot:content>
                        <x-slot:footer>

                        </x-slot:footer>
                    </x-modals.dialog>

                    {{-- ASSIGN INTERVIEW RESULT BUTTON --}}

                    <div class="bg-body-tertiary rounded-3 col p-3 position-relative">
                        <label for="applicant-init-interview-result"
                            class="d-block text-uppercase text-primary fw-medium mb-2">Initial Interview</label>
                        <div id="applicant-init-interview-result" class="applicant-init-interview-result d-flex align-items-center fw-bold">
                            <span
                                class="flex-1 text-capitalize">{{ BasicEvalStatus::labelForValue(optional($application->initialInterview)->is_init_interview_passed) }}</span>
                            <button class="btn btn-sm btn-outline-secondary px-3 px-md-4" type="button"
                                id="toggle-assign-init-interview-modal" {!! when(!$isReadyForInitEvaluation, 'disabled') !!}>
                                {{ is_null(optional($application->initialInterview)->is_init_interview_passed) ? 'Assign' : 'Edit' }}
                            </button>
                        </div>

                    </div>

                </div>
            @endif

            {{-- Modal to set/update final interview schedule --}}
            @if (optional($application->initialInterview)->is_init_interview_passed )
                @php

                    $inputGroupAttributes = ['class' => 'input-group gap-1 min-w-100 px-5'];
                    $inputWrapperClasses = ['class' => 'col-12 text-start'];
                    $isInitAssessment = false;
                @endphp
                <x-modals.dialog id="edit-final-interview">
                    <x-slot:title>
                        <h1 class="modal-title fs-5">{{ __('Set Schedule') }}</h1>
                        <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
                    </x-slot:title>

                    <x-slot:content>

                        @livewire('employee.applicants.set-final-interview-date', ['application' => $application, 'routePrefix' => $routePrefix, 'inputGroupAttributes' => $inputGroupAttributes, 'dateWrapAttributes' => $inputWrapperClasses, 'timeWrapAttributes' => $inputWrapperClasses, 'overrideInputContainerClass' => true, 'overrideDateWrapper' => true, 'overrideTimeWrapper' => true])

                        <button type="button" class="btn btn-success submit ndsbl"
                            wire:click="dispatch('submit-final-interview-sched-form')">Schedule</button>
                    </x-slot:content>

                    <x-slot:footer>

                    </x-slot:footer>

                </x-modals.dialog>
            @endif

            @if ($this->isFinalAssessment)
                <hr>

                <div class="d-flex gap-4 min-w-100 ">
                    {{-- FINAL INTERVIEW  SET SCHEDULE CARD --}}
                    @if ($isReadyForInitEvaluation)
                        <section>
                            <x-employee.applicants.final-interview-card :application="$application" :isFinalAssessment="$isFinalAssessment">
                                {{ $finalInterviewSchedF ?? null }}
                            </x-employee.applicants.final-interview-card>
                        </section>
                    @endif

                    {{-- FINAL INTERVIEW ASSIGN RESULT BUTTON --}}
                    @if (!$notYetInitInterview && !$notYetFinalInterview)
                        <div class="bg-body-tertiary rounded-3 col p-3 position-relative">
                            <label for="applicant-final-interview-result"
                                class="d-block text-uppercase text-primary fw-medium mb-2">Final
                                Interview Result</label>
                            <div id="applicant-final-interview-result"
                                class="applicant-final-interview-result d-flex align-items-center fw-bold">
                                <span
                                    class="flex-1">{{ BasicEvalStatus::labelForValue(optional($application->finalInterview)->is_final_interview_passed) }}</span>
                                <button class="btn btn-sm btn-outline-secondary px-3 px-md-4" type="button"
                                    id="toggle-assign-final-interview-modal" {!! when( $notYetFinalInterview, 'disabled') !!}>
                                    Assign
                                </button>
                            </div>

                        </div>
                    @endif

                </div>

                {{-- SET FINAL INTERVIEW RESULT  MODAL --}}

                <x-modals.dialog id="modal-assign-final-interview-result">
                    <x-slot:title>
                        <h1 class="modal-title fs-5"></h1>
                        <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
                    </x-slot:title>
                    <x-slot:content>
                        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'text-center fs-5'])" :overrideClass="true"
                            class="text-primary fs-3 fw-bold mb-2">
                            <x-slot:heading>
                                {{ __('Assign Results') }}
                            </x-slot:heading>

                            <x-slot:description>
                                {{ __('Enter Final interview results of the applicant') }}
                            </x-slot:description>
                            {{-- INSERT notice --}}
                        </x-headings.main-heading>
                        <div class="d-grid mx-auto px-md-5 row-gap-4 row-gap-md-3 overflow-y-auto"
                            style="max-height: 50cqh;">

                            {{-- SET FINAL INTERVIEW RESULT FORM --}}
                            @if (!$notYetInitInterview && !$notYetFinalInterview)
                                <livewire:employee.applicants.set-final-interview-result :finalInterview="$application->finalInterview"
                                    :interviewParameterItems="$interviewParameters" />
                            @endif

                        </div>
                    </x-slot:content>
                    <x-slot:footer>

                    </x-slot:footer>
                </x-modals.dialog>
            @endif



            @isset($evaluationNotice)
                <p><span class="text-primary fw-bold">Note</span> {{ $evaluationNotice }}</p>
            @endisset

            {{-- TODO add check if has result in exam --}}
            @if (
                $isInitAssessment &&
                    ($notYetExam ||
                        false ||
                        ($notYetInitInterview || optional($application->initialInterview)->is_init_interview_passed == null)))
                <p><i class="icon icon-xl text-info mx-2" data-lucide="badge-check"></i> No final result yet. Please
                    assign all the result. </p>
            @endif

            {{-- DECLINE PROCEED BUTTONS --}}
            <div class="d-flex  column-gap-2 column-gap-md-3 w-100 px-2">

                @livewire('form.employee.applicant.intial.decline', ['application' => $application])

                @if ($isPending)
                    {{-- BUTTON TO SCHEDULE EXAM AND INITAL INTERVIEW --}}
                    <button class="btn btn-lg btn-success flex-grow-1-25" data-bs-toggle="modal"
                        data-bs-target="#{{ $modalId }}">Approve Resume</button>
                @elseif ($application->application_status_id == ApplicationStatus::ASSESSMENT_SCHEDULED->value)
                    {{-- BUTTON TO PROCEED TO FINAL INTERVIEW BY SETTING SCHEDULE --}}
                    <button class="btn btn-lg flex-grow-1-25 ndsbl {!! $isReadyForInitEvaluation && optional($this->application->initialInterview)->is_init_interview_passed
                        ? 'btn-primary'
                        : 'btn-secondary' !!} " {!! when(!optional($this->application->initialInterview)->is_init_interview_passed, 'disabled') !!}
                        data-bs-toggle="modal" data-bs-target="#edit-final-interview"
                        {{-- wire:click="setFinalInterview" --}}>Proceed</button>
                @elseif ($application->application_status_id == ApplicationStatus::FINAL_INTERVIEW_SCHEDULED->value)
                    {{-- BUTTON TO  --}}
                    <button class="btn btn-lg flex-grow-1-25 ndsbl {!! optional($this->application->finalInterview)->is_final_interview_passed
                        ? 'btn-primary'
                        : 'btn-secondary' !!} " {!! when(!optional($this->application->finalInterview)->is_final_interview_passed, 'disabled') !!}
                        data-bs-toggle="modal"
                        wire:click="makeEmployeeInst">Approve Candidate</button>
                @endif
            </div>


        </div>


        <div class="container d-flex mx-0 col-12 col-md-7 px-0 mt-3 mt-md-n1">
            {{-- @if (!is_null($resume) && Storage::exists($resume)) --}}
            <div class="flex-grow-1 border border-1 rounded-3 ">
                <div class="flex-grow-1 px-4 position-relative">
                    <button type="button" aria-controls="iframe-applicant-resume"
                        class="btn text-dark shadow rounded-circle btn-full-screen"><i class="icon-medium"
                            data-lucide="expand"></i></button>
                </div>

                <iframe id="iframe-applicant-resume" name="applicant-resume" class="rounded-3 "
                    allowfullscreen='yes' src="{{ Storage::url($resume) }}" height="100.5%" width="100%"
                    frameborder="0" allowpaymentrequest="false" loading="lazy"></iframe>
            </div>
            {{-- @endif --}}
        </div>


    </div>

    <x-employee.applicants.show-nav-btn :nextApplicant="$nextApplicant" :previousApplicant="$previousApplicant" />

</div>
