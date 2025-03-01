<section role="navigation" aria-label="Key Metrics" class="mb-5 row">
    <h3 class="mb-3 fw-bold">{{ __('Employee Metrics') }}</h3>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-purple">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/reports/tenure.webp') }}" 
                        alt="Incidents icon">
                </div>
                <div class="col-md-7">
                    <p class="fw-semibold fs-3">{{ __('Tenure') }}</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25">
                        <strong class="text-purple">
                            {{ $metrics->employee_tenure }}
                        </strong> years
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-blue" role="none">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/reports/new-hires.webp') }}" 
                        alt="Issues icon">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-semibold fs-3">{{ __('New Hires') }}</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25">
                        <strong class="text-blue">
                            {{ $metrics->new_hires->hires }}
                        </strong> hired out of {{ $metrics->new_hires->applicants }} applicants
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-teal" role="none">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/reports/evaluation.webp') }}" 
                        alt="Training icon">
                </div>
                <div class="col-md-7 mx-2">
                    <p class="fw-semibold fs-3">Evaluations</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25">
                        <strong class="text-teal">
                            {{ "{$metrics->evaluation_success}%" }}
                        </strong> Passing Rate
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>