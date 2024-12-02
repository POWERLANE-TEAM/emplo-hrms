{{--
* |--------------------------------------------------------------------------
* | Incident Report Form
* | BACK-END REPLACE NOTE:
* | ▸ HR can create a form whether an incident is ongoing or already resolved.
* | ▸ The Resolution Details textarea will only show up if the selected "Status" = Resolved.

* | ▸ DO NOT CHANGE THE ID OF STATUS AND RESOLUTION DETAILS TEXTFIELD.
* |   This is used to show/hide the Resolution Details based on the status of the incidents.
* |--------------------------------------------------------------------------
--}}


<div class="mx-3">
    <form>
        <!-- SECTION: Incident Type, Reported By, Assigned HR -->
        <div class="row mb-3">
            <div class="col">
                <!-- BACK-END REPLACE: Incident Type options -->
                <x-form.boxed-selectpicker id="incident_type" label="{{ __('Incident Type') }}" :nonce="$nonce"
                    :required="true" :options="['equipment_malfunction' => 'Equipment Malfunction', 'issue' => 'Issue']"
                    placeholder="Select type">
                </x-form.boxed-selectpicker>
            </div>

            <div class="col">
                <!-- BACK-END REPLACE: Reported By who. The dropdown items are the employees. -->
                <x-form.boxed-selectpicker id="reported_by" label="{{ __('Reported By') }}" :nonce="$nonce" :required="true"
                    :options="['employee_1' => 'Employee 1', 'employee_2' => 'Employee 2']" placeholder="Select employee">
                </x-form.boxed-selectpicker>
            </div>

            <div class="col">
                <!-- BACK-END REPLACE: Assigned to in HR. The dropdown items are the members of the HR job family. -->
                <x-form.boxed-selectpicker id="assigned_hr" label="{{ __('Assigned HR') }}" :nonce="$nonce" :required="true"
                    :options="['hr_1' => 'HR Staff 1', 'hr_2' => 'HR Staff 2']" placeholder="Select HR member">
                </x-form.boxed-selectpicker>
            </div>
        </div>

        <!-- SECTION: Priority, Status, Resolution Date -->
        <div class="row pt-2">
            <div class="col">
                <!-- BACK-END REPLACE: Priority options -->
                <x-form.boxed-dropdown id="priority" label="{{ __('Priority') }}" :nonce="$nonce"
                    :required="true" :options="['low' => 'Low', 'medium' => 'Medium', 'high' => 'High']" placeholder="Select type">
                </x-form.boxed-dropdown>
            </div>

            <div class="col">
                <!-- BACK-END REPLACE: Status options  -->
                <x-form.boxed-dropdown id="status" label="{{ __('Status') }}" :nonce="$nonce" :required="true"
                    :options="['ongoing' => 'Ongoing', 'resolved' => 'Resolved']" placeholder="Select priority">
                </x-form.boxed-dropdown>
            </div>

            <div class="col">
                <!-- BACK-END REPLACE: Assigned to in HR. The dropdown items are the members of the HR job family. -->
                <x-form.boxed-date id="resolution_datr" label="{{ __('Resolution Date') }}" :nonce="$nonce"
                    placeholder="Resolution Date" />
            </div>
        </div>

        <!-- SECTION: Detailed Description -->
        <div class="row pt-2">
            <div class="col">
                <!-- BACK-END REPLACE: Processing of description and attachments. -->
                <x-form.boxed-textarea-attachment id="detailed_desc" label="Detailed Description" :nonce="$nonce" :required="true"
                description="Kindly provide a detailed description and details of the incident report.<br><span class='fw-medium text-info'>For attachments:</span> Please ensure that the title of the attached file(s) clearly reflects its content for easy reference.">
                </x-form.boxed-textarea-attachment>
            </div>
        </div>

        <!-- SECTION: Resolution Details -->
        <div class="row pt-2" id="resolutionDetailsField">
            <div class="col">
                <!-- BACK-END REPLACE: Processing of description and attachments. -->
                <x-form.boxed-textarea-attachment id="resolution_details" label="Resolution Details" :nonce="$nonce" :required="true"
                description="If the incident has already been resolved, kindly provide the details.<br><span class='fw-medium text-info'>For attachments:</span> Please ensure that the title of the attached file(s) clearly reflects its content for easy reference.">
                </x-form.boxed-textarea-attachment>
            </div>
        </div>

        <!-- Create Button -->
        <div>
            <x-buttons.main-btn id="create_incident_report" label="{{ __('Create Incident Report') }}" wire:click="save" target="save" :nonce="$nonce"
            :disabled="false" class="w-25" loading="Creating...">
            </x-buttons.main-btn>
        </div>

        </div>
    </form>
</div>