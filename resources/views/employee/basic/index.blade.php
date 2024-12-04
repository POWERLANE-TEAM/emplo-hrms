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
                    <x-buttons.link-btn label="View Attendance" href="#" class="btn-primary" />
                </div>
            </div>
        </div>

        <!-- SECTION: Latest Announcement -->
        <div class="col-md-7 ">
            <div class="h-100">
                <div class="flex announcement-box">
                    <!-- Header -->
                    <div class="px-4 pb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img class="img-size-10 img-responsive"
                                    src="{{ Vite::asset('resources/images/illus/dashboard/megaphone.png') }}" alt="">

                                <span class="ms-3 green-highlight">
                                    Latest Announcement
                                </span>
                            </div>

                            <!-- Button Link to Create Announcement -->
                            <div class="ps-1 me-1">
                                <x-buttons.view-link-btn link="#" text="View All Announcements" />
                            </div>
                        </div>
                    </div>

                    <!-- Mock Data Only for color mapping. Remove once data is fetched dynamically. -->
                    @php
                        $announcements = [
                            [
                                'title' => 'New Policy Implementation',
                                'description' => 'Effective next month, we will be implementing a new remote work policy. Please review the details in the policy section of the portal!',
                                'roles' => ['Technical', 'Employee']
                            ],

                        ];

                        // Bound to change.
                        $colorMapping = [
                            'HR' => 'blue',
                            'Employee' => 'teal',
                            'Accountant' => 'green',
                            'Relations' => 'purple',
                            'Technical' => 'orange',
                            'default' => 'purple',
                        ];
                    @endphp

                    <!-- The fetching section. ONLY SHOW THE LATEST ANNOUNCEMENT!-->
                    @foreach ($announcements as $announcement)
                        <div class="card mb-3 bg-body-secondary border-0 p-4">
                            <div class="w-100">
                                <div>
                                    <header class="fs-5 fw-bold d-inline-block me-2">{{ $announcement['title'] }}
                                        @foreach ($announcement['roles'] as $role)
                                            <x-status-badge :color="$colorMapping[$role] ?? $colorMapping['default']">{{ $role }}</x-status-badge>
                                        @endforeach
                                    </header>

                                    <p class="fs-7">{{ $announcement['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>

@endsection