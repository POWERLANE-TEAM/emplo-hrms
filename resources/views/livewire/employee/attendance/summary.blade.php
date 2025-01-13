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
                    <span>{{ __('Present Working Days: ')}}</span>
                    <strong class="text-primary">{{ $totalPresentWorkingDays }}</strong>
                </div>

                <!-- Absents -->
                <div class="mt-2 border-0 rounded-4 bg-body-secondary p-3 fs-5">
                    <span class="ps-3">{{ __('Absents: ') }}</span>
                    <strong class="fw-bold">{{ "{$totalAbsents} day(s)" }}</strong>
                </div>

            </div>

            <!-- Hours Worked -->
            <div class="col-md-6">

                <div class="d-flex justify-content-center">
                    <h2 class="text-primary fw-bold">{{ __('Hours Worked') }}</h2>
                </div>

                <!-- Total Working Days -->
                <div class="mt-2 border-0 rounded-4 bg-body-secondary p-3 fw-bold fs-5">
                    <span class="col-2 px-2">
                        <i data-lucide="badge-check" class="icon icon-slarge text-success"></i>
                    </span>
                    <span>{{ __('Total Tracked Hours: ') }}</span>
                    <strong class="text-primary">{{ $totalHours }} </strong>
                </div>

                <!-- Absents -->
                <div class="mt-2 border-0 rounded-4 bg-body-secondary p-3 fs-5">
                    <span class="ps-3">{{ __('Overtime Hours: ') }}</span>
                    <strong class="fw-bold">{{ $totalOtHours }}</strong>
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
                    <h2 class="fw-bold">{{ __('Attendance Breakdown') }}</h>
                </div>

                <!-- Metrics -->
                <div class="mt-4 px-5">
                    <!-- Regular Schedule -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-primary me-2"></i>
                        <strong>{{ $totalPresentWorkingDays }}</strong> day(s) worked on
                        <span class="fw-bold text-primary">regular schedule</span>
                    </p>

                    <!-- Overtime -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-info me-2"></i>
                        <strong>{{ $overtimeDaysCount }}</strong> day(s) of
                        <span class="fw-bold text-info">overtime</span> worked
                    </p>

                    <!-- Documented Leave -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-teal me-2"></i>
                        <strong>{{ $documentLeavesCount }}</strong> day(s) documented
                        <span class="fw-bold text-teal">leave</span> day
                    </p>

                    <!-- Absents -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-danger me-2"></i>
                        <strong>{{ $totalAbsents }}</strong>
                        <span class="fw-bold text-danger">absents </span>(undocumented leave day)
                    </p>

                    <!-- Tardy -->
                    <p class="pb-1">
                        <i class="bi bi-circle-fill fs-5 text-warning me-2"></i>
                        <strong>{{ $tardyDaysCount }}</strong>
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