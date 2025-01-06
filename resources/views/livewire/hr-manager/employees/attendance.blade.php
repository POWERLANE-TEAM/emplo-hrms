<section id="attendance" class="tab-section-employee">

    <!-- SECTION: Payroll Period Dropdown -->
    <section class="px-4">
        <div class="row">
            <div class="col-6">
                <div class="text-primary fs-3 fw-bold d-flex align-items-center"">
                    Attendance
                </div>
            </div>

            <div class=" col-6">
                    <div class="row">
                        <div class="col-4 d-flex align-items-center justify-content-end">
                            <label class="fw-semibold text-secondary-emphasis"> Payroll Period </label>
                        </div>

                        <div class="col-8">
                            <!-- BACK-END Replace: Payroll periods. Default is the current period. -->
                            <x-form.boxed-dropdown :nonce="$nonce" :required="true" :options="['1' => 'Sep 02, 2024 - Sep 27, 2024', '2' => 'Oct 28, 2024 - Nov 27, 2024']" placeholder="Select payroll period" />
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- SECTION: Summary & Calendar -->

    <section class="mt-3 px-4">
        <div class="row">
            <div class="col-4">
                <!-- Total Working Days -->
                <div class="mt-2 border-0 rounded-4 bg-body-secondary p-3 fw-bold fs-5">
                    <span class="col-2 px-2">
                        <i data-lucide="badge-check" class="icon icon-slarge text-success"></i>
                    </span>
                    <span>Present Working Days - </span>
                    <b class="text-primary">4 days</b> <!-- BACK-END REPLACE: Total present working days -->
                </div>

                <!-- Metrics -->
                <div class="mt-4 px-5">
                    <!-- Regular Schedule -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-primary me-2"></i>
                        <b>11</b> day(s) worked on <!-- BACK-END REPLACE: Total count of regular schedule work days -->
                        <span class="fw-bold text-primary">regular schedule</span>
                    </p>

                    <!-- Overtime -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-info me-2"></i>
                        <b>2</b> day(s) of <!-- BACK-END REPLACE: Total count of days worked in overtime -->
                        <span class="fw-bold text-info">overtime</span> worked
                    </p>

                    <!-- Documented Leave -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-teal me-2"></i>
                        <b>1</b> day(s) documented <!-- BACK-END REPLACE: Total count of documented leave. -->
                        <span class="fw-bold text-teal">leave</span> day
                    </p>

                    <!-- Absents -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-danger me-2"></i>
                        <b>1</b> <!-- BACK-END REPLACE: Total count of absents. -->
                        <span class="fw-bold text-danger">absents </span>(undocumented leave day)
                    </p>

                    <!-- Tardy -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-warning me-2"></i>
                        <b>1</b> <!-- BACK-END REPLACE: Total count of absents. -->
                        <span class="fw-bold text-warning">tardy </span>(late)
                    </p>
                </div>
            </div>

            <div class="col-8">
                <div class="mt-3 ms-3">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </section>
</section>

@script
<script>
initCalendar();
</script>
@endscript