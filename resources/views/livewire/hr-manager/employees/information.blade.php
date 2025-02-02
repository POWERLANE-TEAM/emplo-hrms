@use(Illuminate\Support\Carbon)

<section id="information" class="tab-section-employee">
    <div class="row">
        <!-- Left Column: Work / Personal Information -->
        <div class="col-md-6">
            <!-- SECTION: Image & Name, Job Title, Level -->
            <section class="text-center">
                <img class="img-size-30 img-responsive rounded-circle w-100 h-auto object-fit-cover aspect-ratio--square"
                    src="{{ $employee->photo }}" alt="">

                <div class="pt-2">
                    <p class="fw-bold fs-3">{{ $employee->name }}</p>
                    <p class="fs-5">{{ $employee->jobTitle }}</p>
                    <p class="fs-6 fw-medium text-primary">{{ 'Level '.$employee->jobLevel.': '.$employee->jobLevelName }}</p>
                </div>
            </section>

            <style>
                .left-col {
                    display: grid;
                    grid-template-columns: 130px 1fr;
                    column-gap: 20px;
                    row-gap:5px;
                }

                .right-col {
                    display: grid;
                    grid-template-columns: max-content 1fr;
                    column-gap: 20px;
                    row-gap: 5px;
                }
            </style>
            <!-- SECTION: Work Details -->
            <section class="pt-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="icon icon-slarge text-primary me-2" data-lucide="briefcase-business"></i>
                    <x-headings.section-title title="{{ __('Work Information') }}" />
                </div>              

                <div class="left-col pt-2 align-items-center">
                    <div class="fw-bold">{{ __('Job Family: ') }}</div>
                    <p>{{ $employee->jobFamily }}</p>
                    <div class="fw-bold">{{ __('Employment Status: ') }}</div>
                    <p>{{ $employee->employmentStatus }}</p>
                    <div class="fw-bold">{{ __('Shift Schedule: ') }}</div>
                    <p>{{ $employee->shift.' ('.$employee->shiftSched.')' }}</p>
                    <div class="fw-bold">{{ __('Rest Day: ') }}</div>
                    <p>{{ $employee->restDay ?? __('No record found.') }}</p>
                    <div class="fw-bold">{{ __('Date Hired: ') }}</div>
                    <p>{{ Carbon::parse($employee->hiredAt)->format('F d, Y') ?? __('No record found.') }}</p>
                </div>

                <div class="mt-3">
                    <div class="d-flex align-items-center">
                        <i class="icon icon-slarge text-primary me-2" data-lucide="user-cog"></i>
                        <x-headings.section-title title="{{ __('Personal Information') }}" />
                    </div>
                    <div class="pt-2 left-col align-items-center">
                        <div class="fw-bold">{{ __('Date of Birth: ') }}</div>
                        <p>{{ Carbon::parse($employee->dob)->format('F d, Y') ?? __('Not provided') }}</p>
                        <div class="fw-bold">{{ __('Sex: ') }}</div>
                        <p>{{ \App\Enums\Sex::from($employee->sex)->label() }}</p>
                        <div class="fw-bold">{{ __('Civil Status: ') }}</div>
                        <p>{{ $employee->civilStatus }}</p>
                    </div>
                </div>

            </section>
        </div>

        <!-- Right Column: Contact, Address, IDs -->
        <div class="col-md-6">

            <!-- SECTION: Contact Information -->
            <section class="row">
                <div class="col mb-3">
                    <div class="card bg-body-secondary border-0 py-4 px-5 text-secondary-emphasis">
                        <div class="d-flex align-items-center">
                            <i class="icon icon-slarge text-primary me-2" data-lucide="send"></i>
                            <x-headings.section-title title="{{ __('Contact Information') }}" />
                        </div>

                        <div class="right-col pt-3 px-2">
                            <div class="fw-bold">{{ __('Email Address: ') }}</div>
                            <a class="text-decoration-none" href="mailto:{{ $employee->email }}">{{ $employee->email }}</a>
                            <div class="fw-bold">{{ __('Contact No: ') }}</div>
                            <a class="text-decoration-none" href="tel:{{ $employee->contactNo }}">{{ $employee->contactNo }}</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: Addresses -->
            <section class="row">
                <div class="col mb-3">
                    <div class="card bg-body-secondary border-0 py-4 px-5 text-secondary-emphasis">
                        <div class="d-flex align-items-center">
                            <i class="icon icon-slarge text-primary me-2" data-lucide="map-pin"></i>
                            <x-headings.section-title title="{{ __('Address') }}" />
                        </div>

                        <div class="right-col px-2 pt-3">
                            <div class="fw-bold">{{ __('Present: ') }}</div>
                            <p>{{ $employee->fullPresentAddress }}</p>
                            <div class="fw-bold">{{ __('Permanent: ') }}</div>
                            <p>{{ $employee->fullPermanentAddress }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: Identification Numbers -->
            <section class="row">
                <div class="col mb-3">
                    <div class="card bg-body-secondary border-0 py-4 px-5 text-secondary-emphasis">
                        <div class="d-flex align-items-center">
                            <i class="icon icon-slarge text-primary me-2" data-lucide="id-card"></i>
                            <x-headings.section-title title="{{ __('Mandatory Contribution Ids') }}" />
                        </div>

                        <div class="right-col pt-3 px-2 align-items-center">
                            <div class="fw-bold">{{ __('SS Number: ') }}</div>
                            <p>{{ $employee->sss }}</p>
                            <div class="fw-bold">{{ __('PhilHealth ID No: ') }}</div>
                            <p>{{ $employee->philHealth }}</p>
                            <div class="fw-bold">{{ __('Taxpayer ID No: ') }}</div>
                            <p>{{ $employee->tin }}</p>
                            <div class="fw-bold">{{ __('Pag-IBIG MID No: ') }}</div>
                            <p>{{ $employee->pagIbig }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="row">
                <div class="col d-flex align-items-center justify-content-end">
                    <div class="hover-opacity pe-auto">
                        <!-- BACK-END REPLACE: Link to the current employee's profile -->
                        <a href="#" id="toggle-documents" class="text-link-blue text-decoration-underline fs-5">
                            View Documents
                        </a>

                        <i data-lucide="arrow-right" class="icon icon-slarge ms-2 text-blue-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>