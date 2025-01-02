@use('App\Enums\ApplicationStatus')

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
                @elseif ($this->isInitAssessment)
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
            @if($isInitAssessment)

            <div class="d-flex gap-4 min-w-100 ">
                <div class="bg-body-secondary rounded-3 col p-3 px-lg-5 py-md-4 text-center position-relative">
                    <button class="btn position-absolute text-primary top-0 end-0 m-1" type="button">
                        <i class="icon icon-large" data-lucide="pencil-line"></i>
                    </button>
                    <label for="applicant-exam-date" class="d-block text-uppercase text-primary fw-medium mt-2">Examination</label>
                    <strong id="applicant-exam-date" class="applicant-exam-date fs-4 fw-bold">
                        {{ $examSchedF }}
                    </strong>

                </div>

                <div class="bg-body-secondary rounded-3 col p-3 px-lg-5 py-md-4 text-center position-relative">
                    <button class="btn position-absolute text-primary top-0 end-0 m-1" type="button">
                        <i class="icon icon-large" data-lucide="pencil-line"></i>
                    </button>
                    <label for="applicant-interview-date" class="d-block text-uppercase text-primary fw-medium mt-2">Initial
                        Interview</label>
                    <strong id="applicant-interview-date" class="applicant-interview-date fs-4 fw-bold">
                        {{ $initialInterviewSchedF }}
                    </strong>

                </div>
            </div>

            <div class="d-flex gap-4 min-w-100 ">
                <div class="bg-body-tertiary rounded-3 col p-3 position-relative">
                    <label for="applicant-exam-result" class="d-block text-uppercase text-primary fw-medium mb-2">Examination Result</label>
                    <div id="applicant-exam-result" class="applicant-exam-result d-flex align-items-center fw-bold">
                        <span class="flex-1">No Result</span>
                        <button class="btn btn-sm btn-outline-secondary px-3 px-md-4" type="button"  {!! when($notYetExam, 'disabled') !!}>
                            Assign
                        </button>
                    </div>

                </div>

                <div class="bg-body-tertiary rounded-3 col p-3 position-relative">
                    <label for="applicant-exam-result" class="d-block text-uppercase text-primary fw-medium mb-2">Initial Interview</label>
                    <div id="applicant-exam-result" class="applicant-exam-result d-flex align-items-center fw-bold">
                        <span class="flex-1 text-capitalize">{{ optional($application->initialInterview)->is_init_interview_passed == null ? 'No Result' : ($application->initialInterview->is_init_interview_passed ? 'passed' : 'failed') }}</span>
                        <button class="btn btn-sm btn-outline-secondary px-3 px-md-4" type="button"  {!! when($notYetInterview, 'disabled') !!}>
                            Assign
                        </button>
                    </div>

                </div>

            </div>
            @endif

            @isset($evaluationNotice)
            <p><span class="text-primary fw-bold">Note</span> {{$evaluationNotice}}</p>
            @endisset

            {{-- TODO add check if has result in exam --}}
            @if ($isInitAssessment && (!$notYetExam || !$notYetInterview && false || $application->initialInterview->is_init_interview_passed == null))
                <p><i class="icon icon-xl text-info mx-2" data-lucide="badge-check"></i> No final result yet. Please assign all the result. </p>
            @endif

            <div class="d-flex  column-gap-2 column-gap-md-3 w-100 px-2">

                @livewire('form.employee.applicant.intial.decline', ['application' => $application])

                @if ($isPending)
                <button class="btn btn-lg btn-success flex-grow-1-25" data-bs-toggle="modal"
                data-bs-target="#{{ $modalId }}">Approve Resume</button>

                @elseif ($application->application_status_id == ApplicationStatus::ASSESSMENT_SCHEDULED->value)
                <button class="btn btn-lg btn-secondary flex-grow-1-25" {!! when(!$isReadyForInitEvaluation, 'disabled') !!} data-bs-toggle="modal"
                data-bs-target="#{{ $modalId }}">Proceed</button>
                @endif
            </div>


        </div>


        <div class="container d-flex mx-0 col-12 col-md-7 px-0 mt-3 mt-md-n1">
            <div class="flex-grow-1 border border-1 rounded-3 ">
                <div class="flex-grow-1 px-4 position-relative">
                    <button type="button" aria-controls="iframe-applicant-resume"
                        class="btn text-dark shadow rounded-circle btn-full-screen"><i class="icon-medium"
                            data-lucide="expand"></i></button>
                </div>
                <iframe id="iframe-applicant-resume" name="applicant-resume" class="rounded-3 " allowfullscreen='yes'
                    src="{{ Storage::url('hardware-and-software-components.pdf') }}" height="100.5%" width="100%"
                    frameborder="0" allowpaymentrequest="false" loading="lazy"></iframe>
            </div>
        </div>
    </div>

    @include('livewire.employee.applicants.show-nav-btn')

</div>
