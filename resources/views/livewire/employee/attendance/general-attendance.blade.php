<!-- resources/views/livewire/employee/attendance/attendance-controller.blade.php -->
<div>
    <div class="row">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <p class="mb-0 text-primary fw-bold me-3" style="min-width: 100px;">
                    Payroll Period:
                </p>
                <select wire:model.live="selectedPeriod" class="form-select" style="flex: 1;">
                    <option value="1">Sep 02, 2024 - Sep 27, 2024</option>
                    <option value="2">Oct 28, 2024 - Nov 27, 2024</option>
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
        <livewire:employee.attendance.summary :period="$selectedPeriod" />
    @else
        <livewire:employee.attendance.workday-logs :period="$selectedPeriod" />
    @endif
</div>

@script
<script>

    Livewire.hook('morph.added', ({ el }) => {
        initCalendar();
    });
</script>

@endscript