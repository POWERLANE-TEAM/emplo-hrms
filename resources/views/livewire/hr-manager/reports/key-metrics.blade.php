<section role="navigation" aria-label="Key Metrics" class="mb-5 row" x-data>
    <h3 class="mb-3 fw-bold">Key Metrics</h3>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-teal">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/reports/incidents.webp') }}" 
                        alt="Incidents icon">
                </div>
                <div class="col-md-7">
                    <p class="fw-semibold fs-3">{{ $metrics['incidents']['type'] }}</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25"><b class="text-teal">{{ $metrics['incidents']['percentage'] }}%</b> Resolved Incidents</p>
                    <p class="fw-medium fs-7 text-opacity-25">{{ $metrics['incidents']['completed'] }} out of {{ $metrics['incidents']['total'] }} cases</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-green" role="none">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/reports/issues.webp') }}" 
                        alt="Issues icon">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-semibold fs-3">{{ $metrics['issues']['type'] }}</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25"><b class="text-primary">{{ $metrics['issues']['percentage'] }}%</b> Resolved Issues</p>
                    <p class="fw-medium fs-7 text-opacity-25">{{ $metrics['issues']['completed'] }} out of {{ $metrics['issues']['total'] }} cases</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-purple" role="none">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/reports/training.webp') }}" 
                        alt="Training icon">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-semibold fs-3">{{ $metrics['training']['type'] }}</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25"><b class="text-purple">{{ $metrics['training']['percentage'] }}%</b> Completion</p>
                    <p class="fw-medium fs-7 text-opacity-25">{{ $metrics['training']['completed'] }} out of {{ $metrics['training']['total'] }} trainings</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- @script
<script>
    window.addEventListener('year-changed', event => {
        console.log('Year changed event detected:', event.detail);
        @this.set('selectedYear', event.detail);
    });
</script>
@endscript --}}