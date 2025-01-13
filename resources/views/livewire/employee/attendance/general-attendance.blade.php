<div>
    <div class="row">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <p class="mb-0 text-primary fw-bold me-3" style="min-width: 100px;">
                    {{ __('Payroll Period:') }}
                </p>
                <select wire:model.live="period" id="period" class="form-select" style="flex: 1;">
                    @foreach ($this->periods as $payroll)
                        <option value="{{ $payroll->payroll_id }}">
                            {{ $payroll->cut_off }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <p class="mb-0 text-primary fw-bold me-3">
                    View:
                </p>
                <select wire:model.live="selectedView" class="form-select" style="flex: 1;">
                    <option value="summary">Summary</option>
                    <option value="logs">Workday Logs</option>
                </select>
            </div>
        </div>
    </div>

    @if($selectedView === 'summary')
        <livewire:employee.attendance.summary :$period :employee="$this->userEmployee" />
    @else
        <livewire:employee.attendance.workday-logs :$period :employee="$this->userEmployee" />
    @endif
</div>

@script
<script>
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
</script>
@endscript