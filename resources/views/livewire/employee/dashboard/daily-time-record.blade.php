@props([
    'dtr' => $dtr
])

<div class="col-md-5">
    <div class="h-100">
        <div class="text-center">
            <span class="text-primary letter-spacing-3 text-uppercase fw-bold fs-5">
                {{ today()->format('F, d Y') }}
            </span>
            <p class="fs-2 fw-bold">{{ __('Daily Time Record') }}</p>
        </div>

        <div class="row">
            <div class="col-5">
                <div class="d-flex flex-column align-items-center text-end">
                    <span class="text-primary fw-bold fs-7">{{ __('Check In') }}</span>
                    <span class="fs-4 fw-bold">{{ $dtr->check_in ?? '-' }}</span>
                </div>
            </div>

            <div class="col-2 d-flex justify-content-center align-items-center">
                <div class="vertical-line" style="height: 4.5em;"> </div>
            </div>
            <div class="col-5">
                <div class="d-flex flex-column align-items-center justify-content-start">
                    <span class="text-primary fw-bold fs-7">{{ __('Check Out') }}</span>
                    <span class="fs-4 fw-bold">{{ $dtr->check_out ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="text-center pt-3">
            <x-buttons.link-btn label="View Attendance" href="{{ route($routePrefix.'.attendance') }}" class="btn-primary" />
        </div>
    </div>
</div>