<section role="navigation" aria-label="Key Metrics" class="mb-5 row" x-data>
    <h3 class="mb-3 fw-bold">{{ __('Key Metrics') }}</h3>

    <div class="col">
        <div class="card bg-body-secondary border-0 py-4 card-start-border-teal">
            <div class="row">
                <div class="col-md-3 icons-container d-flex align-items-center">
                    <img class="icons-row-card"
                        src="{{ Vite::asset('resources/images/illus/reports/incidents.webp') }}" 
                        alt="Incidents icon">
                </div>
                <div class="col-md-7">
                    <p class="fw-semibold fs-3">{{ __('Incidents') }}</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25">
                        <strong class="text-teal">
                            {{ "{$metrics->incidents->percentage}%" }}
                        </strong>
                            {{ __('Resolved Incidents') }}
                    </p>
                    <p class="fw-medium fs-7 text-opacity-25">
                        {{ __("{$metrics->incidents->completed} out of {$metrics->incidents->total} cases") }}
                    </p>
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
                    <p class="fw-semibold fs-3">{{ __('Issues') }}</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25">
                        <strong class="text-primary">
                            {{ "{$metrics->issues->percentage}%" }}
                        </strong>
                            {{ __('Resolved Issues') }}
                    </p>
                    <p class="fw-medium fs-7 text-opacity-25">
                        {{ __("{$metrics->issues->completed} out of {$metrics->issues->total} cases") }}
                    </p>
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
                    <p class="fw-semibold fs-3">{{ __('Training') }}</p>
                    <p class="pt-1 fw-medium fs-7 text-opacity-25">
                        <strong class="text-purple">
                            {{ "{$metrics->trainings->percentage}%" }}
                        </strong>
                            {{ __('Completion') }}
                    </p>
                    <p class="fw-medium fs-7 text-opacity-25">
                        {{ __("{$metrics->trainings->completed} out of {$metrics->trainings->total} trainings") }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>