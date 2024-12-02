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
                    <!-- BACK-END REPLACE: Hours of worked last month + this month-->
                    <span><b>Last Month:</b> 160 hours (so far)</span><br>
                    <span><b>This Month:</b> 20 hours (so far)</span>
                </div>
            </div>
        </div>

        <!-- SECTION: Leave Balance Card -->
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white border-0 py-4 px-5 h-100">
                <div>
                    <div class="d-flex align-items-center">
                        <img class="img-size-13 img-responsive"
                            src="{{ Vite::asset('resources/images/icons/sidebar/white-leaves.webp') }}" alt="">
                        <span class="ps-2 fs-3 fw-bold">Leave Balance
                    </div>
                </div>
                <div class="pt-3">
                    <!-- BACK-END REPLACE: Remaining balance + Total spent leaves. 20 is fixed, according to PH labor law -->
                    <span><b>Remaining Days:</b> 4 days<br></span>
                    <span><b>16</b> out of 20 days used</span>
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
                    <!-- BACK-END REPLACE: Next Pay Date -->
                    <span><b>Next Pay Date:</b><br></span>
                    <span>September 25, 2024</span> 
                </div>
            </div>
        </div>
    </div>
</section>