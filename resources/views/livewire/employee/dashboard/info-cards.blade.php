<section role="navigation" aria-label="Key Metrics" class="mb-5">
    <div class="row">
        <!-- SECTION: Hours Worked Card -->
        <div class="col-md-4 mb-3">
            <div class="card bg-body-secondary border-0 py-4 px-5 h-100">
                <div>
                    <div class="d-flex align-items-center">
                        <img class="img-size-13 img-responsive"
                            src="{{ Vite::asset('resources/images/icons/sidebar/green-attendance.webp') }}" alt="">
                        <span class="ps-2 fs-3 fw-bold">Hours Worked
                    </div>
                </div>
                <div class="pt-3">
                    <span><b>Previous Payroll:</b> {{ __("{$workedHoursPrevious} hours") }}</span><br>
                    <span><b>Current Payroll:</b> {{ __("{$workedHoursCurrent} hours (so far)") }}</span>
                </div>
            </div>
        </div>

        <!-- SECTION: Leave Balance Card -->
        {{-- <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white border-0 py-4 px-5 h-100">
                <div>
                    <div class="d-flex align-items-center">
                        <img class="img-size-13 img-responsive"
                            src="{{ Vite::asset('resources/images/icons/sidebar/white-leaves.webp') }}" alt="">
                        <span class="ps-2 fs-3 fw-bold">Leave Balance
                    </div>
                </div>
                <div class="pt-3">
                    <!-- BACK-END REPLACE: Vacation + Sick Leave Balance -->
                    <span><b>Vacation Leave:</b> 4 days left (12 total)</span><br>
                    <span><b>Sick Leave:</b> 3 days left (13 total)</span>
                </div>
            </div>
        </div> --}}

        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white border-0 py-4 px-5 h-100">
                <div>
                    <div class="d-flex align-items-center">
                        <img class="img-size-13 img-responsive"
                            src="{{ Vite::asset('resources/images/icons/sidebar/white-leaves.webp') }}" alt="">
                        <span class="ps-2 fs-3 fw-bold">
                            {{ __('Overtimes') }}
                    </div>
                </div>
                <div class="pt-3">
                    <span><b>Total Hours:</b> {{ __("{$workedHoursCurrent} hours") }}</span>
                </div>
            </div>
        </div>

        <!-- SECTION: Next Payslips Card -->
        <div class="col-md-4 mb-3">
            <div class="card bg-body-secondary border-0 py-4 px-5 h-100">
                <div>
                    <div class="d-flex align-items-center">
                        <img class="img-size-13 img-responsive"
                            src="{{ Vite::asset('resources/images/icons/sidebar/green-payslips.webp') }}" alt="">
                        <span class="ps-2 fs-3 fw-bold">Payslip
                    </div>
                </div>
                <div class="pt-3">
                    <span><strong>{{ __('Next Pay Date:') }}</strong><br></span>
                    <span>{{ $nextPayout }}</span> 
                </div>
            </div>
        </div>
    </div>
</section>