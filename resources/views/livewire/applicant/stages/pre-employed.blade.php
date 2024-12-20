<div>
    <section class="mb-5">
        <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
            <span class="fs-2 fw-bold ps-1 pe-3">Pre-Employment Requirements</span>
            <x-status-badge color="danger">Incomplete</x-status-badge>
        </header>

        <div class="row flex-md-nowrap gap-5">
            <div class="col-md-6 p-3">
                <div class="position-relative mx-auto">
                    <canvas id="chartProgress" class=""></canvas>
                </div>

            </div>
            <section class="d-flex flex-column col-md-6 px-5 gap-4">
                <header class="fw-semibold fs-5 ">
                    Status Metric
                </header>
                <div class=" d-flex flex-column gap-3">
                    <div class="col-md-12 border-0 rounded-4 bg-body-secondary p-3 ">
                        <span class="col-2 px-2">
                            <i class="icon p-1 mx-2 text-info  d-inline" data-lucide="badge-info"></i>
                        </span>
                        <span>Pending for review: </span>
                        <b>{{ $pendingDocuments->count() }}</b>
                    </div>
                    <div class="col-md-12 border-0 rounded-4 bg-body-secondary p-3 ">
                        <span class="col-2 px-2">
                            <i class="icon p-1 mx-2 text-success  d-inline" data-lucide="badge-check"></i>
                        </span>
                        <span>Verified documents: </span>
                        <b>{{ $verifiedDocuments->count() }}</b>
                    </div>
                    <div class="col-md-12 border-0 rounded-4 bg-body-secondary p-3 ">
                        <span class="col-2 px-2">
                            <i class="icon p-1 mx-2 text-danger  d-inline" data-lucide="badge-alert"></i>
                        </span>
                        <span>Awaiting Resubmission: </span>
                        <b>{{ $rejectedDocuments->count() }}</b>
                    </div>

                </div>
                <small>
                    <i><b>Note: </b>Status updates will be provided periodically. Review of pending documents may take
                        1-3
                        days.</i>
                </small>
            </section>
        </div>
    </section>

    <nav class="w-100 d-flex mb-5">
        <a href="/preemploy" class="btn btn-primary btn-lg mx-auto px-5 text-capitalize"> <span><i
                    class="icon p-1 mx-2 d-inline" data-lucide="plus-circle"></i></span>Go to submission
            page</a>
    </nav>
</div>
