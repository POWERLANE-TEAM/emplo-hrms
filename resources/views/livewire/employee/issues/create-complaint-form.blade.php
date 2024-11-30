<div class="mx-3">
    <form>
        <!-- SECTION: Type(s) of Complaints -->
        <div class="row">
            <div class="col">
                <!-- BACK-END REPLACE: All types of complaints options -->
                <x-form.multi-select-dropdown x-cloak id="type_complaint" label="Type of Issue/Complaint"
                    :nonce="$nonce" :required="true" :options="['workplace_harassment' => 'Workplace Harassment', 'safety_concerns' => 'Safety Concerns']">
                </x-form.multi-select-dropdown>
            </div>
        </div>

        <!-- SECTION: Date Issue Occured, Confidentiality Preferences -->
        <div class="row pt-2">
            <div class="col">
                <!-- BACK-END REPLACE: Date of the issue. -->
                <x-form.boxed-date id="issue_date" label="{{ __('Date Issue Occured') }}" :nonce="$nonce"
                    :required="true" placeholder="Resolution Date">
                </x-form.boxed-date>
            </div>

            <div class="col">
                <!-- BACK-END REPLACE: Confidentiality Preference options  -->
                <x-form.boxed-dropdown id="conf_pref" label="{{ __('Confidentiality Preference') }}" :nonce="$nonce"
                    :required="true" :options="['share' => 'Share with Relevant Parties', 'internal' => 'Internal Use Only', 'public' => 'Public Summary', 'discreet' => 'Discreet Handling', 'anonymous' => 'Anonymous']"
                    placeholder="Select confidentiality preference" :tooltip="['modalId' => 'aboutConfidentialityPref']">
                </x-form.boxed-dropdown>
            </div>
        </div>

        <!-- SECTION: Issue Detailed Description -->
        <div class="row pt-2">
            <div class="col">
                <!-- BACK-END REPLACE: Processing of the issue's detailed description. -->
                <x-form.boxed-textarea id="detailed_desc" label="Detailed Description" :nonce="$nonce"
                    :required="true" :rows="6" 
                    description="Kindly provide a detailed description of the issue or complaint.">
                </x-form.boxed-textarea>
            </div>
        </div>

        <!-- SECTION: Supporting Information -->
        <div class="row pt-2">
            <div class="col">
                <!-- BACK-END REPLACE: Processing of supporting information and attachments. -->
                <x-form.boxed-textarea-attachment id="supporting_info" label="Supporting Information" :nonce="$nonce"
                description="Include any relevant information or supporting evidence related to your complaint.<br><span class='fw-medium text-info'>For attachments:</span> Please ensure that the title of the attached file(s) clearly reflects its content for easy reference.">
                </x-form.boxed-textarea-attachment>
            </div>
        </div>

        <!-- SECTION: Desired Resolution -->
        <div class="row pt-2">
            <div class="col">
                <!-- BACK-END REPLACE: Processing of the issue's detailed description. -->
                <x-form.boxed-textarea id="desired_resolution" label="Detailed Description" :nonce="$nonce"
                    :required="true" :rows="6" 
                    description="Kindly provide the desire resolution to the complaint.">
                </x-form.boxed-textarea>
            </div>
        </div>

        <!-- Create Button -->
        <div class="pt-3">
            <x-buttons.main-btn id="create_complaint" label="{{ __('Submit Complaint') }}" wire:click="save" target="save" :nonce="$nonce"
            :disabled="false" class="w-25" loading="Creating...">
            </x-buttons.main-btn>
        </div>
    </form>
</div>