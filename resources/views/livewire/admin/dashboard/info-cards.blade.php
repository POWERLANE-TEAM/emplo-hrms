<section role="navigation" aria-label="Key Metrics" class="mb-5 row" x-data>
    <div class="col-md-3">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-teal">
            <div class="row">
                <div class="col-md-3 icons-container">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/active-accs.webp') }}" alt="">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-medium fs-7 text-opacity-25">{{ __('Active Users') }}</p>
                    <p class="fw-semibold fs-3">{{ $activeUsersCount }}</p>
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
                    <p class="fw-semibold fs-3" x-text="$store.onlineUsers.list.length"></p>
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
                    <p class="fw-medium fs-7 text-opacity-25">{{ __('Total Users') }}</p>
                    <p class="fw-semibold fs-3">{{ $totalUsersCount }}</p>
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
                    <p class="fw-medium fs-7 text-opacity-25">{{ __('Logins in 24h') }}</p>
                    <p class="fw-semibold fs-3">40</p>
                </div>
            </div>
        </div>
    </div>
</section>