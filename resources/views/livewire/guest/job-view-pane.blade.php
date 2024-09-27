@if (empty($job_vacancy))
    <?php $content = 'No jobs Selected'; ?>
@else
    @php
        $content =
            '
<header>
    <hgroup>
        <h4 class="card-title text-primary fw-bold mb-0">' .
            $job_vacancy['jobDetails']['jobTitle'][0]['job_title'] .
            '</h4>
        <p class="fs-6 text-black ">' .
            $job_vacancy['jobDetails']['specificArea'][0]['area_name'] .
            '</p>
    </hgroup>
    <a href="' .
            (auth()->check() ? url('/apply') : '') .
            '"
         class="btn btn-primary mt-1 mb-4" role="navigation" aria-label="Apply"
        ' .
            (auth()->guest() ? 'aria-controls="signUpForm" data-bs-toggle="modal" data-bs-target="#signUpForm"' : '') .
            '
        wire:ignore>
        Apply
        <span><i data-lucide="external-link"></i></span>
    </a>

    <label for="job-descr-panel" class="job-descr card-text">
        ' .
            $job_vacancy['jobDetails']['jobTitle'][0]['job_desc'] .
            '
    </label>
    <input type="checkbox" class="showMoreToggle" name="" id="job-descr-panel">
</header>
<div>
    <button class="bg-transparent border border-0">
        <i data-lucide="more-vertical"></i>
    </button>
</div>';
    @endphp
@endif

<article class="job-view tab-content col-12 col-md-6 me-auto" id="job-view-pane" nonce="{{ csp_nonce() }}">
    <div class="job-content tab-pane fade show active card border-0 bg-body-secondary w-100 " id="#1-tab-pane"
        role="tabpanel" aria-labelledby="tab">
        <div class="d-flex">
            {!! $content !!}

        </div>
    </div>
</article>
