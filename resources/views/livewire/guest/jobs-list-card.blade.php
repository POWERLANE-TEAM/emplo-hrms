<sidebar class="nav nav-tabs col-12 col-md-4 ms-auto" id="jobs-list" role="tablist" nonce="csp_nonce()">

    @if (empty($job_vacancies))
        No jobs Available
    @endif

    @foreach ($job_vacancies as $job_vacancy)
        <li class="card nav-item ps-0 " role="presentation">
            <button value="{{ $job_vacancy->jobTitle->job_title_id }}"
                x-on:click.debounce.10ms="$dispatch('job-hiring-selected', { job_vacancy: {
                jobDetail: {
                    jobTitle: [
                        {{ $job_vacancy->jobTitle }}
                    ],
                    jobFamilies: [
                       {{ $job_vacancy->jobTitle->jobFamilies->first() }}
                    ],
                    specificAreas: [
                        {{ $job_vacancy->jobTitle->specificAreas->first() }}
            ],
            }
            } })"
                class="nav-link d-flex flex-row px-md-5 py-md-4" id="{{ $job_vacancy->jobTitle->job_title_id }}-tab"
                data-bs-toggle="tab" role="tab" aria-controls="job-view-pane"
                aria-label="{{ strip_tags($job_vacancy->jobTitle->job_title) }}">
                <div class="col-12 text-start">
                    <header>
                        <hgroup>
                            <div class="card-title fs-3 fw-bold text-body mb-0">
                                {!! $job_vacancy->jobTitle->job_title !!}</div>
                            <p class="fs-4 text-primary">
                                {!! $job_vacancy->jobTitle->jobFamilies->first()->job_family_name !!}</p>
                        </hgroup>
                    </header>
                </div>
            </button>
        </li>
    @endforeach
</sidebar>
