@php
    $heading = match (true) {
        request()->routeIs("{$routePrefix}.overtimes.recents") => __('Recent Overtime Requests'),
        request()->routeIs("{$routePrefix}.overtimes.archive") => __('All Overtime Requests'),
    };
@endphp

<section class="row">
    <div class="col-6">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                {{ $heading }}
            </x-slot:heading>

            <x-slot:description>
                <div class="text-secondary-emphasis">
                    <strong>{{ __('Cut Off Period:') }}</strong>
                    {{ $cutOff }} 
                </div>
                <div class="text-secondary-emphasis">
                    <strong>{{ __('Pay-Out Date:') }}</strong> {{ $payout }}
                </div>
                     
            </x-slot:description>
        </x-headings.main-heading>
    </div>
    <div class="col-6 pt-2 text-end">
        <button onclick="openModal('requestOvertimeModal')" class="btn btn-primary">
        <i data-lucide="plus-circle" class="icon icon-large me-2"></i>{{ __('Request Overtime') }}</button>

        <!-- BACK-END REPLACE NOTE: This button should not appear if the OT Summary Form being viewed is history/not the current payroll period. -->
    </div>
    <livewire:employee.overtimes.basic.request-overtime />
</section>