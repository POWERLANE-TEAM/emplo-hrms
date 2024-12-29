<div class="mx-3">
    <form wire:submit="save" enctype="multipart/form-data">
        <div class="row">
            <div class="col">
                <x-form.multi-select-dropdown 
                    name="types" 
                    x-cloak 
                    id="type_complaint" 
                    label="Type of Issue/Complaint"
                    :nonce="$nonce" 
                    :required="true" 
                    :options="$this->issueTypes"
                >
                </x-form.multi-select-dropdown>
                @error('types')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row pt-2">
            <div class="col">
                <x-form.boxed-date
                    name="occuredAt"
                    type="datetime-local"
                    id="issue_date"
                    label="{{ __('Date Issue Occured') }}"
                    :nonce="$nonce"
                    :required="true"
                    placeholder="Resolution Date"
                    max="{{ today() }}"
                >
                </x-form.boxed-date>
                @error('occuredAt')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <x-form.boxed-dropdown 
                    name="confidentiality"
                    id="conf_pref" 
                    label="{{ __('Confidentiality Preference') }}"
                    :nonce="$nonce"
                    :required="true" 
                    :options="$this->confidentialityPreferences"
                    placeholder="Select confidentiality preference" 
                    :tooltip="['modalId' => 'aboutConfidentialityPref']"
                >
                </x-form.boxed-dropdown>
                @error('confidentiality')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row pt-2">
            <div class="col">
                <x-form.boxed-textarea
                    name="description"
                    id="detailed_desc" 
                    label="Detailed Description" 
                    :nonce="$nonce"
                    :required="true" :rows="6" 
                    description="Kindly provide a detailed description of the issue or complaint."
                >
                </x-form.boxed-textarea>
                @error('description')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row pt-2">
            <div class="col">
                <x-form.boxed-textarea-attachment
                    name="attachments"
                    id="supporting_info" 
                    label="Supporting Information" 
                    :nonce="$nonce"
                    description="Include any relevant information or supporting evidence related to your complaint.<br><span class='fw-medium text-info'>For attachments:</span> Please ensure that the title of the attached file(s) clearly reflects its content for easy reference."
                >
                <x-slot:preview>
                    @if ($attachments)
                        @foreach ($attachments as $index => $attachment)
                            <div class="attachment-item d-inline-flex align-items-center me-2">
                                <a 
                                    href="#" 
                                    target="__blank" 
                                    class="text-info text-decoration-underline me-1" 
                                    title="File Name">{{ $attachment->getClientOriginalName() }}
                                </a>
                                <button 
                                    type="button"
                                    wire:click="removeAttachment({{ $index }})" 
                                    class="btn btn-sm py-0 px-1 no-hover-border hover-opacity"
                                    data-bs-toggle="tooltip" title="Remove attachment">✖
                                </button>
                            </div>
                        @endforeach
                    @endif
                </x-slot:preview>
                </x-form.boxed-textarea-attachment>
                @error('attachments')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row pt-2">
            <div class="col">
                <x-form.boxed-textarea 
                    name="desiredResolution"
                    id="desired_resolution" 
                    label="Detailed Description" 
                    :nonce="$nonce"
                    :required="true" 
                    :rows="6" 
                    description="Kindly provide the desire resolution to the complaint."
                >
                </x-form.boxed-textarea>
                @error('desiredResolution')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="pt-3" wire:ignore>
            <x-buttons.main-btn
                id="submit_report"
                label="{{ __('Submit Report') }}"
                target="save" 
                :nonce="$nonce"
                :disabled="false"
                class="w-25" 
                loading="Creating..."
            >
            </x-buttons.main-btn>
        </div>
    </form>
</div>

@script
<script>
    Livewire.on('showSuccessToast', (event) => {
        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });
</script>
@endscript