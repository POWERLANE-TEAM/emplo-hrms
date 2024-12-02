<!-- BACK-END REPLACE: Entire Form & Submit Button -->

<div class="modal fade" id="requestOvertime" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addCategoryModalLabel">Request Overtime</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Work To Perform Input Field -->
                <div class="mb-3">
                    <x-form.boxed-input-text id="work_performed" label="{{ __('Work To Perform') }}" :nonce="$nonce"
                        :required="true" placeholder="Detail of the work to be done" />
                </div>

                <!-- Date & Hours of OT -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-form.boxed-date id="date_ovt" label="{{ __('Date') }}" :nonce="$nonce" :required="true"
                            placeholder="Date of Overtime" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-form.boxed-time id="hours_ot" label="{{ __('Hours of OT') }}" :nonce="$nonce" :required="true"
                        placeholder="Date of Overtime" />
                    </div>
                </div>

                <p class="fs-6 fw-medium">
                    <b>Note</b>: Overtime submissions require a minimum of 30 minutes.
                </p>
            </div>

            <div class="modal-footer">
                <!-- Submit Button -->
                <button onclick="" class="btn btn-primary">Submit Request</button>
            </div>
        </div>
    </div>
</div>