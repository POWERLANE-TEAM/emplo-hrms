<div class="my-3">

    @php
        $isEmpty = true;
        // BACK-END REPLACE: Toogle the boolean if there's submitted resignation letter.
    @endphp

    <!-- Empty State -->
    @if ($isEmpty)
        <section>
            <div class="container">
                <!-- Row for the Image -->
                <div class="row justify-content-center mb-4">
                    <div class="col-12 text-center">
                        <img class="img-size-20 img-responsive"
                            src="{{ Vite::asset('resources/images/illus/empty-states/files-and-folder.webp') }}" alt="">
                    </div>
                </div>

                <!-- Row for the Text -->
                <div class="row justify-content-center mb-4">
                    <div class="col-12 text-center">
                        <p class="fs-3 fw-bold mb-0 pb-1">You haven’t submitted a resignation yet.</p>
                        <p class="fs-6 fw-medium">If you’re considering ending your employment, you can submit your resignation letter here.</p>
                    </div>
                </div>

                <!-- Row for Buttons -->
                <div class="row justify-content-center">
                    <div class="col-12 text-center mb-3">
                        <a href="file-resignation"
                            class="btn btn-primary btn-lg w-25 d-flex align-items-center justify-content-center mx-auto">
                            <i data-lucide="file-plus-2" class="icon icon-large me-2"></i>
                            Submit Resignation Letter
                        </a>
                    </div>

                    <!-- REPLACE STATIC PAGE LINK: separation Process Policy -->
                    <div class="col-12 text-center">
                        <a href="#" id="toggle-information" class="text-link-blue text-decoration-underline fs-7">
                            Learn more about the separation process
                        </a>
                    </div>
                </div>
            </div>
        </section>

    @endif

    <!-- Presence of Resignation Letter -->
    @if (!$isEmpty)
        <section>
            Active State Here
        </section>
    @endif
</div>