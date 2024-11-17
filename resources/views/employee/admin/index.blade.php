@php
    $nonce = csp_nonce();
@endphp

@extends('components.layout.employee.layout', ['description' => 'Admin Dashboard', 'nonce' => $nonce])

@section('head')
<title>Home</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@pushOnce('pre-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/dashboard.js'])

    <!-- Adds the Core Table Styles -->
    @rappasoftTableStyles

    <!-- Adds any relevant Third-Party Styles (Used for DateRangeFilter (Flatpickr) and NumberRangeFilter) -->
    @rappasoftTableThirdPartyStyles

    <!-- Adds the Core Table Scripts -->
    @rappasoftTableScripts

    <!-- Adds any relevant Third-Party Scripts (e.g. Flatpickr) -->
    @rappasoftTableThirdPartyScripts
@endPushOnce

@pushOnce('styles')

    @vite(['resources/css/employee/admin/dashboard.css'])
@endPushOnce

@section('content')
<hgroup class="mb-5 ms-n1">
    <div class="fs-2 pt-3 fw-bold mb-2">Good afternoon, {{ auth()->user()->account->first_name }}!</div>
    <p>It is <time datetime="{{ now() }}"> {{ \Carbon\Carbon::now()->format('l, d F') }}</time></p>
</hgroup>

<!-- SECTION: Key Metrics -->
<section role="navigation" aria-label="Key Metrics" class="mb-5 row">
    <div class="col-md-3">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-teal">
            <div class="row">
                <div class="col-md-3 icons-container">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/active-accs.webp') }}" alt="">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-medium fs-7 text-opacity-25">Active Accounts</p>
                    <p class="fw-semibold fs-3">40</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-green" role="none">
            <div class="row">
                <div class="col-md-3 icons-container">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/online-users.webp') }}" alt="">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-medium fs-7 text-opacity-25">Online Users</p>
                    <p class="fw-semibold fs-3">12</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-blue" role="none"
            aria-describedby="attendance-nav-desc">
            <div class="row">
                <div class="col-md-3 icons-container">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/total-users.webp') }}" alt="">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-medium fs-7 text-opacity-25">Total Users</p>
                    <p class="fw-semibold fs-3">52</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-purple" role="none"
            aria-describedby="attendance-nav-desc">
            <div class="row">
                <div class="col-md-3 icons-container">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/last-24-hours.webp') }}" alt="">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-medium fs-7 text-opacity-25">Logins Within 24h</p>
                    <p class="fw-semibold fs-3">40</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: Laravel Pulse & Recent Activity Logs -->
<section class="mb-5">
    <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
        Key Metrics & Logs
    </header>

    <div class="d-flex mb-5 row">

        <!-- Laravel Pulse -->
        <div class="col-md-6 d-flex">
            <x-nav-link href="{{ route('admin.system.pulse') }}" class="unstyled w-100">
                <div class="card p-4 pulse-card h-100">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="px-3 py-2">
                                <div class="fs-2 fw-bold text-primary card-cont-green-hover">Laravel Pulse</div>
                                <div class="fs-5 pt-2 fw-regular card-cont-green-hover">Check the system’s performance
                                    and usage via Laravel Pulse.</div>
                            </div>
                        </div>
                        <div class="col-md-5 image-container">
                            <!-- Static Image -->
                            <img class="static-image"
                                src="{{ Vite::asset('resources/images/illus/dashboard/pulse-static.webp') }}" alt="">
                            <!-- Animated Image -->
                            <img class="animated-image"
                                src="{{ Vite::asset('resources/images/illus/dashboard/pulse-animated.gif') }}" alt="">
                        </div>
                    </div>
                </div>
            </x-nav-link>
        </div>

        <!-- Recent Activity Logs -->
        <div class="col-md-6 d-flex">
            <div class="card border-primary p-4 h-100 w-100">
                <div class="px-3">
                    <div class="row">
                        <div class="col-9">
                            <div class="fs-3 fw-bold mb-3">Recent Activity Logs</div>
                        </div>

                        <div class="col-3">
                            <div class="d-flex justify-content-end">
                                <x-buttons.view-link-btn link="#" text="View All" />
                            </div>
                        </div>
                    </div>
                    <div class="w-100">

                        <!-- BACK-END REPLACE: Recent Activity Logs. Limit to 2. -->
                        <ul>
                            <li>You deleted a <b>qualification</b> from Accountant.
                            <li>You addeda <b>new open job position</b>: Janitor.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: Announcements & Daily Time Record -->
<section class="mb-5">
    <div class="d-flex mb-5 row">

        <!-- Announcement -->
        <div class="col-md-7 flex announcement-box">
            <!-- Header -->
            <div class="px-4 pb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img class="img-size-10 img-responsive"
                            src="{{ Vite::asset('resources/images/illus/dashboard/megaphone.png') }}" alt="">

                        <span class="ms-3 green-highlight">
                            Latest Announcements
                        </span>
                    </div>

                    <!-- Button Link to Create Announcement -->
                    <a href="{{ route('admin.announcement.create') }}" class="icon-link" data-bs-toggle="tooltip"
                        title="Post an announcement">
                        <div class="icon-container">
                            <i data-lucide="plus" class="icon-with-border"></i>
                        </div>
                    </a>
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
                    [
                        'title' => 'Work Anniversary',
                        'description' => 'Happy 5th work anniversary to John Smith! Thank you for your dedication and hard work over the years.',
                        'roles' => ['Accountant', 'Employee']
                    ],
                    [
                        'title' => 'Company Picnic',
                        'description' => 'Join us for the annual company picnic on July 15th at Central Park. Food, games, and fun for the whole family!',
                        'roles' => ['Relations']
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

            <!-- The fetching section -->
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

        <!-- Daily Time Record -->
        <div class="col-md-5 flex">
            <div class="card p-4">
                <div class="table-attendance-cont">
                    <header class="fs-4 fw-bold" role="heading" aria-level="2">
                        <i class="bi bi-circle-fill text-danger fs-4"></i>
                        <span class="text-danger text-uppercase fw-bold">Live</span>
                        Daily Time Record
                    </header>

                    <table class="table table-borderless table-attendance-list">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                            </tr>
                        </thead>

                        <!-- BACK-END REPLACE: DTI from Database. Limit to 5. -->
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td>{{ fake()->name() }}</td>
                                    <td>{{ fake()->time() }}</td>
                                    <td>{{ fake()->time() }}</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>

                    <!-- Redirect Link: To Attendance -->
                    <div class="col-12 px-5">
                        <x-buttons.view-link-btn link="#" text="View All" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: High Level View of Users Table -->
<section class="mb-5">
    <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
        Users
    </header>

    <div class="d-flex mb-5">
        <div class="col-md-12">
            <div class="card p-4 h-100">
                <!-- Insert here the Users table. Its supposed width is 100vw. -->
                List of Users Table
            </div>
        </div>
    </div>
</section>

<!-- <livewire:admin.dashboard.online-users /> -->
@endsection