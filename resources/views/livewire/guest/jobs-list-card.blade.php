<sidebar class="nav nav-tabs col-12 col-md-4 " id="jobs-list" role="tablist" nonce="csp_nonce()">

    @if (empty($job_vacancies))
        No jobs Available
    @endif

    @foreach ($job_vacancies as $job_vacancy)
        <li class="card nav-item ps-0 " role="presentation">
            <button value="{{ $job_vacancy->jobDetails->jobTitle->job_title_id }}"
                x-on:click.debounce.10ms="$dispatch('job-selected', { job_vacancy: [{{ $job_vacancy }}] })"
                class="nav-link d-flex flex-row px-md-5 py-md-4"
                id="{{ $job_vacancy->jobDetails->jobTitle->job_title_id }}-tab" data-bs-toggle="tab" role="tab"
                aria-controls="job-view-pane" aria-label="{{ $job_vacancy->jobDetails->jobTitle->job_title }}">
                <div class="col-12 text-start">
                    <header>
                        <hgroup>
                            <div class="card-title fs-3 fw-bold text-black mb-0">
                                {{ $job_vacancy->jobDetails->jobTitle->job_title }}</div>
                            <p class="fs-4 text-primary">
                                {{ $job_vacancy->jobDetails->jobFamily->job_family_name }}</p>
                        </hgroup>
                    </header>
                </div>
            </button>
        </li>
    @endforeach
</sidebar>
