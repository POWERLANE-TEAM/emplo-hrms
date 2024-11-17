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

        <!-- Upcoming Dates in Calendar Manager -->
        <div class="col-md-6 d-flex">
            <div class="card border-primary p-4 h-100 w-100">
                <div class="px-3">
                    <div class="row">
                        <div class="col-9">
                            <div class="fs-3 fw-bold mb-3">Upcoming Dates & Events</div>
                        </div>

                        <div class="col-3">
                            <div class="d-flex justify-content-end">
                                <x-buttons.view-link-btn link="#" text="See Calendar" />
                            </div>
                        </div>
                    </div>
                    <div class="w-100">

                        <!-- BACK-END REPLACE: Recent Activity Logs. Limit to 2. -->
                        <ul>
                            <li>February 14, 2024 — Valentine's Day</li>
                            <li>March 8, 2024 — International Women's Day</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>