<!-- BACK-END REPLACE: Entire Form & Submit Button -->

<div class="modal fade" id="addTrainingRecord" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-2">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add New Training</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Training Title Input Field -->
                <div class="mb-3">
                    <x-form.boxed-input-text id="work_performed" label="{{ __('Training Title') }}" :nonce="$nonce"
                        :required="true" placeholder="Training Title" />
                </div>

                <!-- Trainer. Not sure if I'll make this a dropdown (if the trainers are employee themselves or if it's external) -->
                <div class="mb-3">
                    <x-form.boxed-input-text id="trainer" label="{{ __('Trainer') }}" :nonce="$nonce"
                        :required="true" placeholder="Amanda Lee" />
                </div>

                <!-- Training Provider -->
                <div class="mb-3">
                    <x-form.boxed-input-text id="training_provider" label="{{ __('Training Title') }}" :nonce="$nonce"
                        placeholder="Amanda Lee" />
                </div>

                <div class="mb-3">
                <x-form.boxed-textarea id="announcement_desc" label="Description" :nonce="$nonce" :rows="6" :required="true" />
                </div>

                <!-- Date & Hours of OT -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="row">
                        <label class="mb-1 fw-semibold text-secondary-emphasis"> Duration </label>
                            <div class="col-md-6">

                            <!-- Opted for dropdown in the numbers for less errors on the dumbass user's side. Populate with numbers. -->
                            <x-form.boxed-dropdown id="count" name="leave_type"
                            :nonce="$nonce" :required="true" :options="['1' => '1', '2' => '2', '3' => '3']" placeholder="Select count" />
                            </div>

                            <div class="col-md-6">
                            <x-form.boxed-dropdown id="time_unit" name="leave_type"
                            :nonce="$nonce" :required="true" :options="['days' => 'days', 'weeks' => 'weeks', 'months' => 'months']" placeholder="Select time unit" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-form.boxed-date id="expiry_date" label="{{ __('Date') }}" :nonce="$nonce" :required="true"
                            placeholder="Date of Overtime" />
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <!-- Submit Button -->
                <button onclick="" class="btn btn-primary">Submit Request</button>
            </div>
        </div>
    </div>
</div>