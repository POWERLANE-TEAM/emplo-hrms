@if (empty($position))
    <?php $content = 'No jobs Selected'; ?>
@else
    <?php
    $content = <<<HTML
        <header>
            <hgroup>
                <h4 class="card-title text-primary fw-bold mb-0">$position->title</h4>
                <p class="fs-6 text-black ">Job Location</p>
            </hgroup>
            <a href="" hreflang="en-PH" class="btn btn-primary mt-1 mb-4" role="navigation" aria-label="Apply" data-bs-toggle="modal" data-bs-target="#signUp">Apply
                <span><i data-lucide="external-link"></i></span></a>
    
            <label for="job-descr-panel" class="job-descr card-text">
                $position->description
    
    
            </label>
            <input type="checkbox" class="showMoreToggle" name="" id="job-descr-panel">
    
    
        </header>
        <div>
            <button class="bg-transparent border border-0">
                <i data-lucide="more-vertical"></i>
            </button>
        </div>
    HTML;
    ?>
@endif

<article class="job-view tab-content col-12 col-md-6" nonce="csp_nonce()">
    <div class="job-content tab-pane fade show active card border-0 bg-secondary-subtle w-100 " id="#1-tab-pane"
        role="tabpanel" aria-labelledby="-tab">
        <div class="d-flex">
            <?php echo $content; ?>

        </div>
    </div>
</article>
