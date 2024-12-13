<!-- Notifications -->
<div>
    <div class="px-4">
        @include('components.includes.tab_navs.notifs-navs')
    </div>

    <!-- General Notifications -->
    <section id="general" class="tab-section">
        <ul class="list-unstyled">
            @foreach ($generalNotifications as $notification)
                <a href="">
                    <li class="mx-3 d-flex px-3 py-3 align-items-start">

                        <!-- Profile Icon -->
                        <img class="img-size-10 img-responsive rounded-circle w-100 h-auto object-fit-cover aspect-ratio--square"
                            src="{{ $notification['profile'] }}" alt="Profile Picture">

                        <!-- Notification Content -->
                        <div class="ms-3 flex-grow-1">
                            <p class="mb-0">
                                {!! $notification['message'] !!}
                            </p>

                            <!-- Labels -->
                            <div class="pt-1">
                                @foreach ($notification['label'] as $label)
                                    <x-status-badge color="info">{{ $label }}</x-status-badge>
                                @endforeach
                                <small class="text-muted fs-8 ps-1">{{ $notification['time'] }}</small>
                            </div>
                        </div>
                    </li>
                </a>
            @endforeach
        </ul>
    </section>

    <!-- Urgent Notifications -->
    <section id="urgent" class="tab-section">
        <ul class="list-unstyled">
            @foreach ($urgentNotifications as $notification)
                <a href="">
                    <li class="mx-3 d-flex px-3 py-3 align-items-start">

                        <!-- Profile Icon -->
                        <img class="img-size-10 img-responsive rounded-circle w-100 h-auto object-fit-cover aspect-ratio--square"
                            src="{{ $notification['profile'] }}" alt="Profile Picture">

                        <!-- Notification Content -->
                        <div class="ms-3 flex-grow-1">
                            <p class="mb-0">
                                {!! $notification['message'] !!}
                            </p>

                            <!-- Labels -->
                            <div class="pt-1">
                                @foreach ($notification['label'] as $label)
                                    <x-status-badge color="warning">{{ $label }}</x-status-badge>
                                @endforeach
                                <small class="text-muted fs-8 ps-1">{{ $notification['time'] }}</small>
                            </div>
                        </div>
                    </li>
                </a>
            @endforeach
        </ul>
    </section>
</div>