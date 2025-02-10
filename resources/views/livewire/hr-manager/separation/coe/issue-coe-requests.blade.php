@use('Carbon\Carbon')

<d>


    <!-- BACK-END REPLACE: Placeholder data, x-header, information -->
    @php
        $certificateStatus = $coe->emp_coe_doc_id ? 'issued' : 'pending';
        // Status of the COE issuance.
        // DEADLINE OF 15 DAYS OF PROCESSING.
        // Possible values: 'pending' (is being processed),
        // 'issued' (COE has been issued to the employee).

        $statusConfig = [
            'pending' => ['color' => 'info', 'badge' => 'Pending'],
            'issued' => ['color' => 'success', 'badge' => 'Issued'],
        ];

        $color = $statusConfig[$certificateStatus]['color'] ?? 'secondary'; // Default to 'secondary' if status doesn't match
$badge = $statusConfig[$certificateStatus]['badge'] ?? 'Unknown';

    @endphp


    <div>
        <x-headings.header-with-status :title="$coe->requestor->fullname" color="{{ $color }}" badge="{{ $badge }}">
            <!-- Attributes are dynamically replaced based on $certificateStatus -->
        </x-headings.header-with-status>
    </div>

    <div class="mt-2">
        <header>
            <p>The following details will be included in the certificate:</p>
        </header>

        <div class="card border-primary mt-4 py-4 px-5 w-100">
            <!-- Employee Details -->
            <div class="row">
                <div class="col-md-6">
                    <section>
                        <p class="fw-bold fs-5 letter-spacing-2 text-primary text-uppercase">Employee Details</p>
                        <p class="pb-2 fs-6"><b>Name:</b> {{ $coe->requestor->fullname }}</p>
                        <p class="pb-2 fs-6"><b>Department:</b>
                            {{ $coe->requestor->jobTitle->department->department_name }}</p>
                        <p class="pb-2 fs-6"><b>Position/Job Title:</b>
                            {{ $coe->requestor->jobTitle->jobLevel->job_level_name }} /
                            {{ $coe->requestor->jobTitle->job_title }}</p>
                    </section>
                </div>

                @php

                    $startDate = $coe->requestor->lifecycle->started_at ?? 'Error';
                    $endDate = $coe->requestor->lifecycle->separated_at ?? 'N/A';

                    if ($startDate !== 'Error') {
                        $startDate = Carbon::parse($startDate);
                    }

                    if ($endDate instanceof Carbon) {
                        $endDate = Carbon::parse($endDate);
                    }

                    $employmentDuration =
    $startDate instanceof Carbon && $endDate instanceof Carbon
        ? $startDate->diff($endDate)->format('%y years, %m months, %d days')
        : ($startDate instanceof Carbon ? $startDate->diff(Carbon::now())->format('%y years, %m months, %d days') : 'N/A');
                @endphp

                <!-- Employment Details -->
                <div class="col-md-6">
                    <section>
                        <p class="fw-bold fs-5 letter-spacing-2 text-primary text-uppercase">Employment Details</p>
                        <p class="pb-2 fs-6"><b>Start Date:</b>
                            {{ $startDate instanceof \Carbon\Carbon ? $startDate->format('F j, Y') : 'Error' }}</p>
                        <p class="pb-2 fs-6"><b>End Date:</b> {{ $endDate instanceof Carbon ? $endDate->format('F j, Y') : 'N/A' }}</p>
                        <p class="pb-2 fs-6"><b>Employment Duration: </b> {{ $employmentDuration }}</p>
                    </section>
                </div>
            </div>
        </div>

        @if ($certificateStatus === 'pending')
            <x-info_panels.note
                note="{{ __('Please ensure that the certificate is issued within') }} <span class='text-danger fw-bold'>{{ __('15 days') }}</span> {{ __('after the employee has completed their clearance rendering.') }}" />

            <x-buttons.main-btn id="generate_certificate" label="Issue Certificate" wire:click="save" :nonce="$nonce"
                :disabled="false" class="w-25" :loading="'Generating...'" />
        @else
            <div class="mt-3 mb-2">
                <small class="">Generated on <b>
                        {{ $coe->updated_at ? Carbon::parse($coe->updated_at)->format('F j, Y') : 'N/A' }}</b></small>

            </div>

            <button class="btn btn-primary btn-lg w-25 mt-3" wire:click="download">
                <i data-lucide="download" class="icon icon-large me-2"></i>
                Download the Generated Certificate
            </button>
        @endif
    </div>
    </div>
