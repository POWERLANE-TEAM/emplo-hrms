<section role="navigation" aria-label="Key Metrics" class="mb-5 row">
    <h3 class="mb-3 fw-bold">Employee Metrics</h3>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-purple">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/active-accs.webp') }}" 
                        alt="Incidents icon">
                </div>
                <div class="col-md-7">
                    <p class="fw-semibold fs-3">Tenure</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25"><b class="text-teal">{{ $metrics['employee_tenure'] }}</b> years</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-teal" role="none">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/online-users.webp') }}" 
                        alt="Issues icon">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-semibold fs-3">New Hires</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25"><b class="text-primary">{{ $metrics['new_hires']['hires'] }}</b> hired out of {{ $metrics['new_hires']['applicants'] }} applicants</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-green" role="none">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/dashboard/total-users.webp') }}" 
                        alt="Training icon">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-semibold fs-3">Evaluation Rate</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25"><b class="text-purple">{{ $metrics['evaluation_success'] }}%</b> Passing Rate</p>
                </div>
            </div>
        </div>
    </div>
</section>

@script
<script>
    window.addEventListener('year-changed', event => {
        console.log('Year changed event detected:', event.detail);
        @this.set('selectedYear', event.detail);
    });
</script>
@endscript