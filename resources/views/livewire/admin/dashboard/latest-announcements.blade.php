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

    @php
        $colorMapping = [
            'Operations' => 'blue',
            'Administrative' => 'teal',
            'General Affairs-Support' => 'green',
            'Human Resources Operations' => 'purple',
            'Accounting' => 'orange',
            'default' => 'purple',
        ];
    @endphp

    @foreach ($this->announcements as $announcement)
        <div class="card mb-3 bg-body-secondary border-0 p-4">
            <div class="w-100">
                <div>
                    <header class="fs-5 fw-bold d-inline-block me-2">{{ $announcement->title }}
                        @foreach ($announcement->offices as $office)
                            <x-status-badge :color="$colorMapping[$office->name] ?? $colorMapping['default']">{{ $office->name }}</x-status-badge>
                        @endforeach
                    </header>

                    <p class="fs-7">{{ $announcement->description }}</p>
                </div>
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-end">
        {{ $this->announcements->links() }}
    </div>

</div>