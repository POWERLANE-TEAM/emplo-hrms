@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])

@section('head')
<title>Home Page</title>

<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/basic/dashboard.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/basic/style.css'])
@endPushOnce

@section('content')
<hgroup class="mb-5 ms-n1">
    <div class="fs-2 pt-3 fw-bold">{{ ('Good afternoon, ') . auth()->user()->account->first_name }}!</div>
    <p>{{ __('It is') }} <time datetime="{{ now() }}"> {{ \Carbon\Carbon::now()->format('l, d F') }}</time></p>
</hgroup>

{{-- Key Info Cards --}}
<livewire:employee.dashboard.info-cards />

<!-- DTR & Announcements -->
<section>
    <div class="row px-3">

        <!-- SECTION: Daily Time Record -->
        <div class="col-md-5">
            <div class="h-100">
                <div class="text-center">
                    <!-- BACK-END REPLACE: Current Date -->
                    <span class="text-primary letter-spacing-3 text-uppercase fw-bold fs-5">August 17, 2024</span>
                    <p class="fs-2 fw-bold">Daily Time Record</p>
                </div>

                <!-- Corrected Row and Columns -->
                <div class="row">
                    <!-- Clock In Section -->
                    <div class="col-5">
                        <div class="d-flex flex-column align-items-center text-end">
                            <span class="text-primary fw-bold fs-7">Clock In</span>
                            <!-- BACK-END REPLACE: Clock In for the day -->
                            <span class="fs-4 fw-bold">8:06 AM</span>
                        </div>
                    </div>

                    <!-- Vertical Divider -->
                    <div class="col-2 d-flex justify-content-center align-items-center">
                        <div class="vertical-line" style="height: 4.5em;"> </div>
                    </div>
                    <!-- Clock Out -->
                    <div class="col-5">
                        <div class="d-flex flex-column align-items-center justify-content-start">
                            <span class="text-primary fw-bold fs-7">Clock In</span>
                            <!-- BACK-END REPLACE: Clock In for the day -->
                            <span class="fs-4 fw-bold">8:06 AM</span>
                        </div>
                    </div>
                </div>

                <div class="text-center pt-3">
                    <x-buttons.link-btn label="View Attendance" href="#"
                        class="btn-primary" />
                </div>
            </div>
        </div>


        <!-- SECTION: Announcements -->
        <div class="col-md-7 border">
            <div class="h-100">
                Section 2
            </div>
        </div>
    </div>
</section>

@endsection