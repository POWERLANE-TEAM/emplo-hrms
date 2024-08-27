@if (empty($position))
    <?php $content = 'No jobs Selected'; ?>
@else
    <?php
    $content = <<<HTML
        <header>
            <hgroup>
                <h4 class="card-title text-primary fw-bold mb-0">Job Position</h4>
                <p class="fs-6 text-black ">Job Location</p>
            </hgroup>
            <a href="" hreflang="en-PH" class="btn btn-primary mt-1 mb-4" role="navigation" aria-label="Apply">Apply
                <span><i data-lucide="external-link"></i></span></a>
    
            <p class="job-descr card-text">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis
                possimus expedita ipsum atque magni laboriosam vel veritatis, suscipit eum
                quam quaerat cupiditate veniam voluptatem. Cum pariatur quisquam totam vero
                natus?
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis
                possimus expedita ipsum atque magni laboriosam vel veritatis, suscipit eum
                quam quaerat cupiditate veniam voluptatem. Cum pariatur quisquam totam vero
                natus?
            </p>
            <button href="" class="bg-transparent border border-0 text-decoration-underline text-black ps-0">
                Show More <span><i data-lucide="chevron-down"></i></span>
            </button>
        </header>
        <div>
            <button class="bg-transparent border border-0">
                <i data-lucide="more-vertical"></i>
            </button>
        </div>
    HTML;
    ?>
@endif

<article class="job-view tab-content col-12 col-md-6">
    <div class="job-content tab-pane fade show active card border-0 bg-secondary w-100 " id="#1-tab-pane" role="tabpanel"
        aria-labelledby="-tab">
        <div class="d-flex">
            {{ $content }}
        </div>
    </div>
</article>
