<div class="col-md-12 pt-3 leaves-info">
    <section class="row">
        <div class="col-md-3 mb-3">
            <div class="card bg-body-secondary border-0 py-4 px-5 h-100">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <p class="fs-3 fw-bold">{{ $awaitingFinalApprovals }}</p>
                    <p class="fs-5 text-center">{{ __('Awaiting Final Approval/s') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white border-0 py-4 px-5 h-100">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <p class="fs-3 fw-bold">{{ $leaveBalance }}</p>
                    <p class="fs-5 text-center">{{ __('Leave Balance') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-body-secondary border-0 py-4 px-5 h-100">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <p class="fs-3 fw-bold">{{ $daysTaken }}</p>
                    <p class="fs-5 text-center">{{ __('Days Taken') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-body-secondary border-0 py-4 px-5 h-100">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <p class="fs-3 fw-bold">{{ $deniedLeaveRequests }}</p>
                    <p class="fs-5 text-center">{{ __('Denied Leave Requests') }}</p>
                </div>
            </div>
        </div>
    </section>
</div>