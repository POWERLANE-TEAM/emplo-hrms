<div>

    <!-- SECTION: Metrics -->
    <section class="mt-5 px-4">
        <div class="row pb-4">

            <!-- Attendance -->
            <div class="col-md-6">

                <div class="d-flex justify-content-center">
                    <h2 class="text-primary fw-bold">Attendance</h2>
                </div>

                <!-- Total Working Days -->
                <div class="mt-2 border-0 rounded-4 bg-body-secondary p-3 fw-bold fs-5">
                    <span class="col-2 px-2">
                        <i data-lucide="badge-check" class="icon icon-slarge text-success"></i>
                    </span>
                    <span>Present Working Days - </span>
                    <b class="text-primary">4 days</b> <!-- BACK-END REPLACE: Total present working days -->
                </div>

                <!-- Absents -->
                <div class="mt-2 border-0 rounded-4 bg-body-secondary p-3 fs-5">
                    <span class="ps-3">Absents: </span>
                    <b class="fw-bold">1 day</b> <!-- BACK-END REPLACE: Total absents -->
                </div>

            </div>

            <!-- Hours Worked -->
            <div class="col-md-6">

                <div class="d-flex justify-content-center">
                    <h2 class="text-primary fw-bold">Hours Worked</h2>
                </div>

                <!-- Total Working Days -->
                <div class="mt-2 border-0 rounded-4 bg-body-secondary p-3 fw-bold fs-5">
                    <span class="col-2 px-2">
                        <i data-lucide="badge-check" class="icon icon-slarge text-success"></i>
                    </span>
                    <span>Total Tracked Hours - </span>
                    <b class="text-primary">149:55:03</b> <!-- BACK-END REPLACE: Total hours worked -->
                </div>

                <!-- Absents -->
                <div class="mt-2 border-0 rounded-4 bg-body-secondary p-3 fs-5">
                    <span class="ps-3">Overtime Hours: </span>
                    <b class="fw-bold">8:52:26</b> <!-- BACK-END REPLACE: Total present working days -->
                </div>
            </div>
        </div>
    </section>

    <hr>

    <!-- SECTION: Monthly Calendar & Attendance Breakdown -->
    <section class="mt-4 px-4">
        <div class="row">
            <div class="col-8">
                <div class="mt-3 ms-3" wire:ignore>
                    <div id="calendar"></div>
                </div>
            </div>

            <div class="col-4">

                <div class="d-flex justify-content-center pt-4">
                    <h2 class="fw-bold">Attendance Breakdown</h>

                        <!-- BACK-END REPLACE NOTE:
                        This is monthly breakdown.
                        It should also be updated if the user
                        navigates to other months in the calendar. -->

                </div>

                <!-- Metrics -->
                <div class="mt-4 px-5">
                    <!-- Regular Schedule -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-primary me-2"></i>
                        <b>11</b> day(s) worked on <!-- BACK-END REPLACE: Total count of regular schedule work days -->
                        <span class="fw-bold text-primary">regular schedule</span>
                    </p>

                    <!-- Overtime -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-info me-2"></i>
                        <b>2</b> day(s) of <!-- BACK-END REPLACE: Total count of days worked in overtime -->
                        <span class="fw-bold text-info">overtime</span> worked
                    </p>

                    <!-- Documented Leave -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-teal me-2"></i>
                        <b>1</b> day(s) documented <!-- BACK-END REPLACE: Total count of documented leave. -->
                        <span class="fw-bold text-teal">leave</span> day
                    </p>

                    <!-- Absents -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-danger me-2"></i>
                        <b>1</b> <!-- BACK-END REPLACE: Total count of absents. -->
                        <span class="fw-bold text-danger">absents </span>(undocumented leave day)
                    </p>

                    <!-- Tardy -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-warning me-2"></i>
                        <b>1</b> <!-- BACK-END REPLACE: Total count of absents. -->
                        <span class="fw-bold text-warning">tardy </span>(late)
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

@script
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('refreshCalendar', () => {
            initCalendar();
        });
    });
</script>
@endscript