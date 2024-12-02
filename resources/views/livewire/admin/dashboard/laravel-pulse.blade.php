<div class="col-md-6 d-flex">
    <a target="_blank" href="{{ route('admin.system.pulse') }}" class="unstyled w-100">
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
    </a>
</div>