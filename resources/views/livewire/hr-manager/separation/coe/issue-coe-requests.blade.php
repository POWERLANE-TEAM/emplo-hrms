<d>

    <!-- BACK-END REPLACE: Placeholder data, x-header, information -->
    @php
        $certificateStatus = 'issued';
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
        <x-headings.header-with-status title="Clark, Avery Mendiola" color="{{ $color }}" badge="{{ $badge }}">
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
                        <p class="pb-2 fs-6"><b>Name:</b> Avery Mendiola, Clark</p>
                        <p class="pb-2 fs-6"><b>Department:</b> Human Resources</p>
                        <p class="pb-2 fs-6"><b>Position/Job Title:</b> Associate / Assistant Manager</p>
                        <p class="pb-2 fs-6"><b>Employment:</b> Permanent</p>
                    </section>
                </div>

                <!-- Employment Details -->
                <div class="col-md-6">
                    <section>
                        <p class="fw-bold fs-5 letter-spacing-2 text-primary text-uppercase">Employment Details</p>
                        <p class="pb-2 fs-6"><b>Start Date:</b> January 5, 2015</p>
                        <p class="pb-2 fs-6"><b>End Date:</b> April 20, 2019</p>
                        <p class="pb-2 fs-6"><b>Employment Duration: </b> 4 years, 5 days</p>
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
                <small class="">Generated on <b>January 10, 2024</b></small>
                <!-- BACK-END REPLACE: Date when HR issued/generated the Certificate -->
            </div>

            <button class="btn btn-primary btn-lg w-25 mt-3">
                <i data-lucide="download" class="icon icon-large me-2"></i>
                Download the Generated Certificate
            </button>
        @endif
    </div>
    </div>