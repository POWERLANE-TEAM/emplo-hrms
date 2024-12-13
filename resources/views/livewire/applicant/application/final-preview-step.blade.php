@php

    try {
        $resumePreviewSrc = $formState['form.applicant.resume-upload-step']['resumePath'] ?? null;
        $personalDetail = collect($formState['form.applicant.personal-details-step'] ?? []);

        // I got unauthorized error when I tried to access the file directly
        $base64Resume = null;

        if (file_exists($resumePreviewSrc)) {
            try {
                $fileContent = file_get_contents($resumePreviewSrc);
                $base64Resume = 'data:application/pdf;base64,' . base64_encode($fileContent);
            } catch (\Throwable $th) {
                report($th);
            }
        }

        $user = auth()->user() ?? abort(401);

        try {
            $applicantEmail = $personalDetail['applicant']['email'] ?? $user->email;
            if (empty($applicantEmail) && !$user->facebook_id) {
                throw new \Exception('User email is missing.');
            }
        } catch (\Exception $e) {
            $applicantEmail = $e->getMessage();
            report("User Id of $user->user_id", $e);
        }

        try {
            $applicantName = ucwords(
                (data_get($personalDetail, 'applicant.name.lastName') ?? '') .
                    ', ' .
                    (data_get($personalDetail, 'applicant.name.firstName') ?? ''),
            );

            $applicantName .= ' ' . (data_get($personalDetail, 'applicant.name.middleName') ?? '');

            if (empty($applicantName) || trim($applicantName) == ',') {
                throw new \Exception('Name information is missing');
            }
        } catch (\Exception $e) {
            $applicantName = $e->getMessage();
        }

        try {
            $applicantBirthDate = data_get($personalDetail, 'applicant.birth') ?? 'Birth date missing';
            if ($applicantBirthDate) {
                $applicantBirthDateF = \Carbon\Carbon::parse($applicantBirthDate)->format('F j, Y');
            } else {
                throw new \Exception('Birth date information is missing');
            }
        } catch (\Exception $e) {
            $applicantBirthDate = '';
            $applicantBirthDateF = $e->getMessage();
        }

        try {
            $applicantSexAtBirth = $personalDetail->get('sexAtBirth') ?? 'Sex at birth missing';
        } catch (\Exception $e) {
            $applicantSexAtBirth = 'Sex at birth information is missing';
        }

        // // Dummy data for modifying front end
        // $applicantEmail = 'dummy@example.com';
        // $applicantName = 'Doe, John';
        // $applicantBirthDate = '1990-01-01';
        // $applicantBirthDateF = \Carbon\Carbon::parse($applicantBirthDate)->format('F j, Y');
        // $applicantSexAtBirth = 'Male';
    } catch (\Throwable $th) {
        throw $th;
    }
@endphp

<div>
    @include('livewire.applicant.application.application-wizard-progress-bar')
    <section aria-labelledby="review-label">
        <div class="d-flex justify-content-xl-between justify-content-xxl-start flex-wrap flex-md-nowrap px-md-1 px-lg-3 mb-4 mb-lg-5 mx-auto col-9  "
            style="min-height: 55dvh">

            <div class="container d-flex mx-0 col-12 col-md-7 px-0 pe-md-3 mt-3 mt-md-n1">
                <x-iframe src="{{ $base64Resume ?? '' }}" :srcIsUrl="true" id="apply-resume-preview">
                </x-iframe>
            </div>

            <div class="container d-flex flex-column mx-0 col-12 col-md-5 px-0 ps-md-3 min-h-md-inherit">
                <div class="border rounded-3 p-3 px-lg-5 py-md-4 flex-grow-1">

                    <h3 class="fs-5 text-primary fw-bold mb-4" id="review-label">Review your information.</h3>

                    <div class="mb-3 mb-md-4">
                        <div id="applicant-email">Email Address</div>
                        <Address aria-labelledby="applicant-email">
                            <x-mail-link class="d-block text-truncate fw-bold unstyled" :email="$applicantEmail"></x-mail-link>
                        </Address>
                    </div>

                    <div class="mb-3 mb-md-4">
                        <div id="applicant-name">Full Name</div>
                        <div aria-labelledby="applicant-name" class=" text-truncate fw-bold">
                            {{ $applicantName }}</div>
                    </div>


                    <div class="mb-3 mb-md-4">
                        <div id="applicant-bdate">Birthdate</div>
                        <time aria-labelledby="applicant-bdate" datetime="{{ $applicantBirthDate }}"
                            class="d-block fw-bold">{{ $applicantBirthDateF }}</time>
                    </div>

                    <div>
                        <div id="applicant-bio-sex">Sex at Birth</div>
                        <div aria-labelledby="applicant-bio-sex" class=" text-truncate fw-bold text-capitalize">
                            {{ $applicantSexAtBirth }}</div>
                    </div>
                </div>

            </div>


        </div>

        @include('livewire.applicant.application.application-wizard-nav-btn')
    </section>
</div>
