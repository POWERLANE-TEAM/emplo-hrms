<div>
    <div class="row px-3 mb-4">

        <div class=" col-8 d-flex align-items-center fw-bold">
            <i class="icon p-1 mx-2 text-primary" data-lucide="baseline"></i>
            {{ __('Performance Category') }}
        </div>

        <div class="col-4 d-flex align-items-center">
            <div class="text-center fw-bold text-primary justify-content-center">
                {{ __('Annual Rating') }}
            </div>
        </div>

    </div>

    <div class="scrollable-container visible-gray-scrollbar">
        @foreach($previews as $preview)
            <div class="card p-4 mb-4 d-flex">
                <div class="row px-3">
                    <div class="col-7">
                        <p class="fw-bold fs-5 text-primary">
                            {{ "{$loop->iteration}. {$preview->category}" }}
                        </p>
                        <p>{{ $preview->categoryDesc }}</p>
                    </div>

                    <div class="col-2 d-flex justify-content-center">
                        <div class="vertical-line"></div>
                    </div>

                    <div class="col-3 px-2 d-flex align-items-center">
                        <div class="fw-bold text-primary justify-content-center">
                            {{ "{$preview->ratingScale} - {$preview->ratingName}" }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>