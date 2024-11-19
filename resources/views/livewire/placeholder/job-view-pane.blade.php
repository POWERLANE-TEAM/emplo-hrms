<article class="job-view tab-content col-12 col-md-6 me-auto">
    <div class="job-content tab-pane fade show active card border-0 bg-secondary-subtle w-100 " role="tabpanel"
        aria-label="Loading job details">
        <header>
            <hgroup class="placeholder-glow d-flex flex-column mb-1">
                <div class=" text-primary placeholder-glow mb-1">
                    <span class="placeholder py-3  col-8"></span>
                </div>
                <p class="text-black placeholder-glow">
                    <span class="placeholder py-2  col-4"></span>
                </p>
            </hgroup>
        </header>

        <p class="mt-3 mb-4 placeholder-glow "><span class=" placeholder text-primary py-3  col-2 "></span></p>

        <div class="pe-2 overflow-hidden">
            @for ($i = 0; $i < 3; $i++)
                <p class="d-flex card-text placeholder-glow gap-1 mb-3">
                    <span class="placeholder py-2 col-12"></span>
                </p>
            @endfor

            @for ($i = 0; $i < 4; $i++)
                <p class="d-flex card-text placeholder-glow gap-1 mb-3">
                    @php
                        $randomNumber = rand(0, 1);
                        $firstColClass = $randomNumber ? 'col-11' : 'col-12';
                    @endphp

                    <span class="placeholder py-2 {{ $firstColClass }}"></span>
                </p>
            @endfor
            <p class="d-flex card-text placeholder-glow gap-1 mb-3">
                <span class="placeholder py-2 col-6"></span>
                @php
                    $randomNumber = rand(0, 1);
                    $firstColClass = $randomNumber ? 'col-4' : 'col-3';
                    $lastColClass = $randomNumber ? 'col-2' : 'col-3';
                @endphp

                <span class="placeholder py-2 {{ $firstColClass }}"></span>
                <span class="placeholder py-2 {{ $lastColClass }}"></span>
            </p>
        </div>

    </div>
</article>
