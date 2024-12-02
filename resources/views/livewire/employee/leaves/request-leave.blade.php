<!-- BACK-END REPLACE: Entire form -->
<div>
    <div class="row px-3 mb-4">

        <!-- Input Field: Type of Leave -->
        <div class="row">
            <div class="col">
                <x-form.boxed-dropdown id="leave_type" label="{{ __('Type of Leave') }}" name="leave_type"
                    :nonce="$nonce" :required="true" :options="['Sick' => 'Sick', 'Annual' => 'Annual', 'Weekly' => 'Weekly']" placeholder="Select type" />
            </div>
        </div>

        <!-- Calendar Pick: Start Date & End Date -->
        <div class="row">
            <div class="col">
                <x-form.boxed-date id="start_date" label="{{ __('Start Date') }}" name="start_date" :nonce="$nonce"
                    :required="true" placeholder="Start Date" />
            </div>

            <div class="col">
                <x-form.boxed-date id="end_date" label="{{ __('End Date') }}" name="end_date" :nonce="$nonce"
                    :required="true" placeholder="End Date" />
            </div>
        </div>

        <!-- Textarea Field: Reason for Leave -->
        <div class="row">
            <div class="col">
                <x-form.boxed-textarea id="reason_leave" label="Reason for leave" name="reason_leave" :nonce="$nonce"
                    :rows="7" :required="true" />
            </div>
        </div>

        <!-- Reason for Leave -->
        <div class="pe-4 my-2">
            <div class="col-md-12 pe-2">
                <div class="callout callout-success bg-body-tertiary">
                    <div class="fs-5 px-2">Total leave days requested:
                        <span class="fw-bold text-primary">10</span> <!-- Back-end Replace: Total count. This should be a client-side live response. -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pe-4 mt-2">
            <div class="col-md-12 pe-2 d-flex align-items-center text-end">
                <x-buttons.main-btn label="Submit Evaluation" wire:click.prevent="save" :nonce="$nonce"
                    :disabled="false" class="w-25" :loading="'Submitting...'" />
            </div>
        </div>
    </div>
</div>