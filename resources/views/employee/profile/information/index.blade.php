@extends('components.layout.employee.layout', ['nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>Profile</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('scripts')
    @vite(['resources/js/employee/hr/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/main.css'])
@endPushOnce

@section('content')

<div class="row pb-3">
    <div class="col-md-8">
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'ps-2 pt-2 pb-2 ms-n1'])" :overrideContainerClass="true" class="fs-2 fw-bold mb-2 text-secondary-emphasis">
            <x-slot:heading>
                {{ __('Profile Information') }}
            </x-slot:heading>
        </x-headings.main-heading>
    </div>

    <div class="col-md-4 text-end d-flex justify-content-end align-items-center">
        <div>
            <!-- BACK-END REPLACE: Replace with Employee ID while redirecting to Edit Profile -->
            <a href="{{ route($routePrefix . '.profile.edit') }}" class="btn btn-lg btn-outline-primary">
                <i data-lucide="pen-line" class="icon icon-large me-2"></i>
                Edit Profile
            </a>
        </div>
    </div>
</div>

<section>
    <div class="row">
        <!-- Left Column: Work / Personal Information -->
        <div class="col-md-6">
            <!-- SECTION: Image & Name, Job Title, Level -->
            <section class="text-center">
                <!-- BACK-END REPLACE: Replace with Default Avatar / Employee photo -->
                <img class="img-size-30 img-responsive rounded-circle w-100 h-auto object-fit-cover aspect-ratio--square"
                    src="https://ui-avatars.com/api/?name=Blackwell+Kelly" alt="">

                <div class="pt-2">
                    <!-- BACK-END REPLACE: Name, Job Title, Level -->
                    <p class="fw-bold fs-3">Blackwell, Kelly Princess J.</p>
                    <p class="fs-5">Associate / Assistant Manager</p>
                    <p class="fs-6 fw-normal text-primary">Level 2: Associate</p>
                </div>
            </section>

            <!-- SECTION: Work Details -->
            <section class="pt-4 px-4">
                <div>
                    <!-- BACK-END REPLACE: Department, Employment Status, Shift, Hired Date -->
                    <p class="pb-2"><b>Department:</b> Human Resources</p>
                    <p class="pb-2"><b>Employment Status:</b> Regular</p>
                    <p class="pb-2"><b>Shift:</b> Day Shift</p>
                </div>

                <div class="mt-3">
                    <x-headings.section-title title="{{ __('Personal Details') }}" />

                    <!-- BACK-END REPLACE: Birthday, Sex, Cvil Status, Educational Attaintment -->
                    <p class="pt-2 pb-2"><b>Birthdate:</b> December 29, 1998</p>
                    <p class="pb-2"><b>Sex at birth:</b> Female</p>
                    <p class="pb-2"><b>Civil Status:</b> Married</p>
                    <p class="pb-2"><b>Educational Attainment:</b> College</p>
                </div>

            </section>
        </div>

        <!-- Right Column: Contact, Address, IDs -->
        <div class="col-md-6">

            <!-- SECTION: Contact Information -->
            <section class="row">
                <div class="col mb-3">
                    <div class="card bg-body-secondary border-0 py-4 px-5">
                        <x-headings.section-title title="{{ __('Contact Information') }}" />

                        <div class="px-2">
                            <!-- BACK-END REPLACE: Email, Contact Number -->
                            <p class="pt-3 pb-2"><b>Email:</b> blackwell.kpj@gmail.com</p>
                            <p class="pb-2"><b>Contact Number:</b> +63 912 345 6789</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: Addresses -->
            <section class="row">
                <div class="col mb-3">
                    <div class="card bg-body-secondary border-0 py-4 px-5">
                        <x-headings.section-title title="{{ __('Addresses') }}" />

                        <div class="px-2">
                            <!-- BACK-END REPLACE: Preset & Permanent Address -->
                            <p class="pt-3 pb-2"><b>Present Address:</b> Block 7, Lot 14, Barangay Mabuhay, Taguig City,
                                Metro Manila</p>
                            <p class="pb-2"><b>Permanent Address:</b> Block 15, Lot 7, Barangay Moonwalk, Parañaque
                                City, Metro Manila</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: Identification Numbers -->
            <section class="row">
                <div class="col mb-3">
                    <div class="card bg-body-secondary border-0 py-4 px-5">
                        <x-headings.section-title title="{{ __('Identification Numbers') }}" />

                        <div class="px-2">
                            <!-- BACK-END REPLACE: SSS, Cedula/CTC, PhilHealth, TIN, Pag-IBIG -->
                            <p class="pt-3 pb-2"><b>SSS:</b> 43-1294876-1</p>
                            <p class="pb-2"><b>Cedula/CTC: </b> 87654321</p>
                            <p class="pb-2"><b>PhilHealth: </b> 11-234567890-3</p>
                            <p class="pb-2"><b>TIN: </b> 954-672-809-321</p>
                            <p class="pb-2"><b>Pag-IBIG: </b> 987654321098</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
@endsection