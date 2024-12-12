<div class="card border-0 px-4 py-3 notification-container visible-gray-scrollbar show">
    <section>
        <div class="row">
            <div class="col-md-10">
                <h4 class="mb-0 fw-bold">Notifications</h4>
                <p class="text-muted">You have 12 new notifications.</p>
            </div>
            <div class="col-md-2 text-end">
                <a wire:navigate href="{{ route($routePrefix . '.notifications') }}" class="icon-link"
                    data-bs-toggle="tooltip" title="See all notifications">
                    <div class="icon-container">
                        <i data-lucide="plus" class="icon-with-border"></i>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <div class="seperator mt-1 mb-3">
        <div class="wavy-line"></div>
    </div>

    <div>
        <div>
            @include('components.includes.tab_navs.notifs-navs')
        </div>

        <!-- General Notifications -->
        <section id="general" class="tab-section">
            <ul class="list-unstyled">
                <li class="d-flex align-items-start mb-3">

                    <!-- Profile Icon -->
                    <img class="img-size-10 img-responsive rounded-circle w-100 h-auto object-fit-cover aspect-ratio--square"
                        src="https://ui-avatars.com/api/?name=Peter+Wilrexe" alt="">

                    <!-- Notification Content -->
                    <div class=" ms-3 flex-grow-1">
                        <p class="mb-0 fs-7">Peter Wilrexe requested a new leave.</p>

                        <span class="text-muted small">2 hours ago</span>
                    </div>
                </li>
            </ul>
        </section>

        <!-- Urgent Notifications -->
        <section id="urgent" class="tab-section">
            <ul class="list-unstyled">
                <li class="d-flex align-items-start mb-3">

                    <!-- Profile Icon -->
                    <img class="img-size-10 img-responsive rounded-circle w-100 h-auto object-fit-cover aspect-ratio--square"
                        src="https://ui-avatars.com/api/?name=Peter+Wilrexe" alt="">

                    <!-- Notification Content -->
                    <div class=" ms-3 flex-grow-1">
                        <p class="mb-0 fs-7">Peter Wilrexe requested a new leave. Luh??</p>

                        <span class="text-muted small">2 hours ago</span>
                    </div>
                </li>
            </ul>
        </section>
    </div>
</div>