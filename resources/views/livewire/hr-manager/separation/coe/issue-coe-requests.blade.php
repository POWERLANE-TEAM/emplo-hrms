<d>

    <!-- BACK-END REPLACE: Placeholder data, x-header, information -->
    @php
        $requestCertificateStatus = 'issued';
    @endphp


    <div>
        <x-headings.header-with-status title="Clark, Avery Mendiola" color="success" badge="Issued">
            </x-profile-header>
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

        <x-info_panels.note
            note="{{ __('Under the Labor Code of the Philippines, Article 291, employers are required to issue a Certificate of Employment (COE) upon request of an employee.') }}" />

        @if ($requestCertificateStatus === 'pending')
            <x-buttons.main-btn id="generate_certificate" label="Issue Certificate" wire:click="save" :nonce="$nonce"
                :disabled="false" class="w-25" :loading="'Generating...'" />
        @else
            <button class="btn btn-outline-primary btn-lg w-25">
                <i data-lucide="download" class="icon icon-large me-2"></i>
                Download the Generated Certificate
            </button>
        @endif
    </div>
</div>