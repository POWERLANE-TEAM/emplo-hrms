<div wire:ignore.self>
    <section class="px-4">
        <div class="row">
            <div class="col-6">
                <div class="text-primary fs-3 fw-bold d-flex align-items-center"">
                    {{ __('Attendance') }}
                </div>
            </div>

            <div class=" col-6">
                <div class="row">
                    <div class="col-4 d-flex align-items-center justify-content-end">
                        <label class="fw-semibold text-secondary-emphasis">{{ __('Payroll Period') }}</label>
                    </div>

                    <div class="col-8">
                        <select id="period" class="form-select" style="flex: 1;">
                            @foreach ($this->periods as $payroll)
                                <option value="{{ $payroll->payroll_id }}">
                                    {{ $payroll->cut_off }}
                                </option>
                            @endforeach
                        </select>
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
                    <b class="text-primary">{{ $totalPresentWorkingDays }}</b>
                </div>

                <!-- Metrics -->
                <div class="mt-4 px-5">
                    <!-- Regular Schedule -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-primary me-2"></i>
                        <b>{{ $totalPresentWorkingDays }}</b> day(s) worked on
                        <span class="fw-bold text-primary">regular schedule</span>
                    </p>

                    <!-- Overtime -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-info me-2"></i>
                        <b>{{ $overtimeDaysCount }}</b> day(s) of
                        <span class="fw-bold text-info">overtime</span> worked
                    </p>

                    <!-- Documented Leave -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-teal me-2"></i>
                        <b>{{ $documentLeavesCount }}</b> day(s) documented
                        <span class="fw-bold text-teal">leave</span> day
                    </p>

                    <!-- Absents -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-danger me-2"></i>
                        <b>{{ $totalAbsents }}</b>
                        <span class="fw-bold text-danger">absents </span>(undocumented leave day)
                    </p>

                    <!-- Tardy -->
                    <p class="pb-3">
                        <i class="bi bi-circle-fill fs-5 text-warning me-2"></i>
                        <b>{{ $tardyDaysCount }}</b>
                        <span class="fw-bold text-warning">tardy </span>(late)
                    </p>
                </div>
            </div>

            <div class="col-8">
                <div class="mt-3 ms-3" wire:ignore>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </section>
</div>

@script
<script>
    Livewire.hook('morphed', (component) => {
        const events = @json($events);
        initCalendar(events);
        const periodDropdown = document.getElementById('period');

        periodDropdown.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex].text;

            const startDateMatch = selectedOption.match(/([A-Za-z]+ \d{1,2}, \d{4})/);
            if (startDateMatch) {
                const startDate = startDateMatch[0];

                const event = new CustomEvent("periodChanged", {
                    detail: {
                        startDate,
                        fullText: selectedOption,
                    },
                });
                document.dispatchEvent(event);
            }
        });
    });
</script>
@endscript