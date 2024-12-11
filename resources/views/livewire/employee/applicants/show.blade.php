@use('App\Enums\ApplicationStatus')
<div>
    <div
        class="d-flex justify-content-xl-between justify-content-xxl-start flex-wrap flex-md-nowrap px-md-1 px-lg-3 mb-4 mb-lg-5 min-w-100" style="min-height: 62dvh;">
        <div class="container d-flex flex-column mx-0 col-12 col-md-5 px-0 pe-md-3 min-h-md-inherit ">
            <div class="bg-body-secondary rounded-3 p-3 px-lg-5 py-md-4 mb-4 flex-grow-1">
                <hgroup class="mb-4 mb-md-5">
                    <span class="h3 text-primary ">ID:
                        <span class="h3 text-primary" role="heading" aria-level="2">
                            APL-{{ $application->application_id }}</span>
                    </span>
                    <p class="fs-4">{{ $application->vacancy->jobTitle->job_title }}</p>
                </hgroup>

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
                    <div id="applicant-contact-no">Contanct Number</div>
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
            </div>

            <div class="d-flex  column-gap-1 column-gap-md-3 w-100 px-2">

                @livewire('form.employee.applicant.intial.decline', ['application' => $application])

                <button class="btn btn-lg btn-success flex-grow-1-25"  data-bs-toggle="modal"
                    data-bs-target="#{{ $modalId }}">Approve Resume</button>
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

    <div class="d-flex justify-content-between" style="max-width:2170px;">
        <button class="btn btn-outline-primary"><span>Prev</span>ious Applicant</button>
        <button class="btn btn-primary">Next Applicant</button>
    </div>
</div>
