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