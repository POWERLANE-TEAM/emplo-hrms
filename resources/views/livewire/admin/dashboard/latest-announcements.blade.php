<div class="col-md-5 flex">
    <div class="indiv-grid-container-1 overflow-auto thin-custom-scrollbar">
        <!-- Header -->
        <div class="px-4 pb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img class="img-size-10 img-responsive"
                        src="{{ Vite::asset('resources/images/illus/dashboard/megaphone.png') }}" alt="">

                    <span class="ms-3 green-highlight">
                        {{ __('Latest Announcements') }}
                    </span>
                </div>

                <!-- Button Link to Create Announcement -->
                <a wire:navigate href="{{ route('admin.announcement.create') }}" class="icon-link"
                    data-bs-toggle="tooltip" title="Post an announcement">
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

        @if ($this->announcements)
            @foreach ($this->announcements as $announcement)
                <div class="card mb-3 bg-body-secondary border-0">
                    <div class="w-100 p-4">
                        <div>
                            <header class="fs-5 fw-bold d-inline-block me-2">{{ $announcement->title }}
                                @if ($announcement->offices)
                                    @foreach ($announcement->offices as $office)
                                        <x-status-badge :color="$colorMapping[$office->name] ?? $colorMapping['default']">{{ $office->name }}</x-status-badge>
                                    @endforeach
                                @endif
                            </header>
                            <p class="fs-7 pt-2">{{ $announcement->description }}</p>
                        </div>

                    </div>
                    <div class="card-footer px-4">
                        <small class="fw-regular pe-2">
                            {{ __('Published by ') . $announcement->publisher }}
                        </small>
                        <small class="text-muted">
                            {{ $announcement->published_at }}
                        </small>
                        @if ($announcement->modified_at)
                            @if ($announcement->modified_at > $announcement->published_at)
                                <small class="text-muted">
                                    {{ __('Modified ') . $announcement->modified_at }}
                                </small>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        @endif


        <div class="d-flex justify-content-end">
            {{ $this->announcements->links() }}
        </div>
    </div>
</div>