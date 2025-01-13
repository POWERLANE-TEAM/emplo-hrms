@php

    try {
        $resumePreviewSrc = $formState['form.applicant.resume-upload-step']['resumePath'] ?? null;

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
            $applicantEmail = $applicationForm['user']['email'] ?? $user->email;
            if (empty($applicantEmail) && !$user->facebook_id) {
                throw new \Exception('User email is missing.');
            }
        } catch (\Exception $e) {
            $applicantEmail = $e->getMessage();
            report("User Id of $user->user_id", $e);
        }

        try {
            $applicantName = ucwords(
                ($applicationForm['lastName'] ?? '') . ', ' . ($applicationForm['firstName'] ?? ''),
            );

            $applicantName .= ' ' . ($applicationForm['middleName'] ?? '');

            if (empty($applicantName) || trim($applicantName) == ',') {
                throw new \Exception('Name information is missing');
            }
        } catch (\Exception $e) {
            $applicantName = $e->getMessage();
        }

        try {
            $applicantBirthDate = $applicationForm['dateOfBirth'] ?? 'Birth date missing';
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
            $applicantSexAtBirth = $applicationForm['sex'] ?? 'Sex at birth information is missing';
        } catch (\Exception $e) {
            $applicantSexAtBirth = 'Sex at birth information is missing';
        }

        try {
            $contactNumberString = $applicationForm['contactNumber'] ?? 'Contact number information is missing';

            // Check if the contact number string is not the default message
            if ($contactNumberString !== 'Contact number information is missing') {
                $contactNumbers = explode(',', $contactNumberString);
            }
        } catch (\Exception $e) {
            $contactNumbers = 'Contact number information is missing';
        }

        try {
            $civilStatus = $applicationForm['civilStatus'] ?? 'Civil status information is missing';
        } catch (\Exception $e) {
            $applicantSexAtBirth = 'Civil status information is missing';
        }

        try {
            if (!($education = $applicationForm['education'] ?? null)) {
                throw new \Exception('Education information is missing');
            }
        } catch (\Throwable $th) {
            $education = [$th->getMessage()];
        }

        try {
            if (!($experience = $applicationForm['experience'] ?? null)) {
                throw new \Exception('Experience information is missing');
            }
        } catch (\Throwable $th) {
            $experience = [$th->getMessage()];
        }

        try {
            if (!($skills = $applicationForm['skills'] ?? null)) {
                throw new \Exception('Skills information is missing');
            }
        } catch (\Throwable $th) {
            $skills = [$th->getMessage()];
        }

        try {
            if (!($skills = $applicationForm['skills'] ?? null)) {
                throw new \Exception('Skills information is missing');
            }
        } catch (\Throwable $th) {
            $skills = [$th->getMessage()];
        }

        try {
            if (!($presentAddress = $this->getAddress('present') ?? null)) {
                throw new \Exception('Present address information is missing');
            }
        } catch (\Throwable $th) {
            $presentAddress = $th->getMessage();
        }
        try {
            if (!($permanentAddress = $this->getAddress('permanent') ?? null)) {
                throw new \Exception('Permanent address information is missing');
            }
        } catch (\Throwable $th) {
            $permanentAddress = $th->getMessage();
        }
    } catch (\Throwable $th) {
        throw $th;
    }

    // // Dummy data for modifying front end
    // $applicantEmail = 'dummy@example.com';
    // $applicantName = 'Doe, John';
    // $applicantBirthDate = '1990-01-01';
    // $applicantBirthDateF = \Carbon\Carbon::parse($applicantBirthDate)->format('F j, Y');
    // $applicantSexAtBirth = 'Male';
    // $civilStatus = 'Single';
    // $education = [
    //     'Bachelor of Science in Computer Science - XYZ University',
    //     'Master of Science in Information Technology - ABC University',
    // ];

    // $experience = [
    //     'Software Developer at Tech Solutions Inc. (2015-2018)',
    //     'Senior Software Engineer at Innovatech (2018-2021)',
    //     'Lead Developer at CodeCrafters (2021-Present)',
    // ];

    // $skills = [
    //     'Proficient in PHP, JavaScript, and Python',
    //     'Experience with Laravel and Vue.js frameworks',
    //     'Strong understanding of database design and management',
    //     'Excellent problem-solving and analytical skills',
    //     'Effective communication and teamwork abilities',
    // ];
    // $presentAddress = '123 Main St, Brgy. San Isidro, Makati, Metro Manila, NCR';
    // $permanentAddress = '456 Elm St, Brgy. San Jose, Cebu City, Cebu, Region VII (Central Visayas)';

@endphp

<div>

    @include('livewire.applicant.application.application-wizard-progress-bar')
    <section aria-labelledby="review-label">
        <div class="d-flex justify-content-xl-between justify-content-xxl-start flex-wrap flex-md-nowrap px-md-1 px-lg-3 mb-4 mb-lg-5 mx-auto col-9  "
            style="min-height: 55dvh; max-height: 55dvh">

            <div class="container d-flex mx-0 col-12 col-md-6 px-0 pe-md-3 mt-3 mt-md-n1">
                <x-iframe src="{{ $base64Resume ?? '' }}" :srcIsUrl="true" id="apply-resume-preview">
                </x-iframe>
            </div>

            <div class="container d-flex flex-column mx-0 col-12 col-md-6 px-0 ps-md-3 min-h-md-inherit">
                <div
                    class="border rounded-3 p-3 px-lg-5 py-md-4 flex-grow-1 overflow-y-auto mh-100 thin-gray-scrollbar ">

                    <h3 class="fs-5 text-primary fw-bold mb-4" id="review-label">Review your information.</h3>

                    <address class="d-contents">

                        <div class="mb-3 mb-md-4">
                            <div id="applicant-email">Email Address</div>
                            <div aria-labelledby="applicant-email">
                                <x-mail-link class="d-block text-truncate fw-bold unstyled"
                                    :email="$applicantEmail"></x-mail-link>
                            </div>
                        </div>

                        <div class="mb-3 mb-md-4">
                            <div id="applicant-mobile-num">Contact Number</div>
                            <div aria-labelledby="applicant-mobile-num">
                                <x-phone-link class="d-inline-block text-truncate fw-bold unstyled" :phone="$contactNumbers"
                                    separator=" / ">
                                </x-phone-link>
                            </div>
                        </div>

                        <div class="mb-3 mb-md-4">
                            <div id="applicant-name">Full Name</div>
                            <div aria-labelledby="applicant-name" class=" text-truncate fw-bold">
                                {{ $applicantName }}</div>
                        </div>

                    </address>


                    <div class="mb-3 mb-md-4">
                        <div id="applicant-education">Education</div>
                        <div aria-labelledby="applicant-education" class=" fw-bold">
                            <ul>
                                @foreach ($education as $edu)
                                    <li>{{ $edu }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="mb-3 mb-md-4">
                        <div id="applicant-experience">Experience</div>
                        <div aria-labelledby="applicant-experience" class=" fw-bold">
                            <ul>
                                @foreach ($experience as $exp)
                                    <li>{{ $exp }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="mb-3 mb-md-4">
                        <div id="applicant-skills">Skills</div>
                        <div aria-labelledby="applicant-skills" class=" fw-bold">
                            <ul>
                                @foreach ($skills as $skill)
                                    <li>{{ $skill }}</li>
                                @endforeach
                            </ul>
                        </div>
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


                    <div class="mb-3 mb-md-4">
                        <div id="applicant-civil-status">Civil Status</div>
                        <div aria-labelledby="applicant-civil-status" class="  fw-bold text-capitalize">
                            {{ $civilStatus }}</div>
                    </div>

                    <div class="mb-3 mb-md-4">
                        <div id="applicant-present-address">Present Address</div>
                        <div aria-labelledby="applicant-present-address" class=" fw-bold">
                            {{ $presentAddress }}
                        </div>
                    </div>
                    <div class="mb-3 mb-md-4">
                        <div id="applicant-permanent-address">Permanent Address</div>
                        <div aria-labelledby="applicant-permanent-address" class="fw-bold">
                            {{ $permanentAddress }}
                        </div>
                    </div>


                </div>

            </div>


        </div>

        @include('livewire.applicant.application.application-wizard-nav-btn')
    </section>
</div>
