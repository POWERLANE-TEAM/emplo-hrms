<div class="mx-3">
    <form wire:submit="save" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col">
                <x-form.multi-select-dropdown
                    name="types"
                    x-cloak
                    id="incident_type" 
                    label="{{ __('Incident Type') }}" 
                    :nonce="$nonce"
                    :required="true" 
                    :options="$this->incidentTypes"
                ></x-form.multi-select-dropdown>
                @error('types')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row pt-2">
            <div class="col">
                <x-form.boxed-dropdown
                    name="initiator"
                    id="initiated_by" 
                    label="{{ __('Initiated By') }}" 
                    :nonce="$nonce" 
                    :required="true"
                    :options="$this->employees" 
                    placeholder="Select employee"
                ></x-form.boxed-dropdown>
                @error('initiator')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <x-form.boxed-dropdown
                    name="priority"
                    id="priority" 
                    label="{{ __('Priority') }}" 
                    :nonce="$nonce"
                    :required="true" 
                    :options="$this->priorityLevels" 
                    placeholder="Select type"
                ></x-form.boxed-dropdown>
                @error('priority')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <x-form.boxed-dropdown
                    name="status"
                    id="status" 
                    label="{{ __('Status') }}" 
                    :nonce="$nonce" 
                    :required="true"
                    :options="$this->statuses" 
                    placeholder="Select priority"
                ></x-form.boxed-dropdown>
                @error('status')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <x-form.boxed-date
                    name="resolutionDate"
                    type="datetime-local" 
                    id="resolution_datr" 
                    label="{{ __('Resolution Date') }}" 
                    :nonce="$nonce"
                    placeholder="Resolution Date and Time"
                    max="{{ today() }}"
                />
                @error('resolutionDate')
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
                    :required="true" 
                    :rows="6" 
                    description="{{ __('Kindly provide a detailed description and details of the incident report.') }}">
                ></x-form.boxed-textarea>
                @error('description')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row pt-2">
            <div class="col">
                <x-form.boxed-textarea-attachment
                    name="attachments"
                    id="detailed_desc" 
                    label="Attachments" 
                    :nonce="$nonce" 
                    description="<span class='fw-medium text-info'>For attachments:</span> Please ensure that the title of the attached file(s) clearly reflects its content for easy reference.">
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

        <div wire:ignore class="row pt-2" id="resolutionDetailsField">
            <div class="col">
                <x-form.boxed-textarea
                    name="resolutionDetails" 
                    id="resolution_details" 
                    label="Resolution Details" 
                    :nonce="$nonce" 
                    :required="true"
                    :rows="6"
                    description="If the incident has already been resolved, kindly provide the details.">
                </x-form.boxed-textarea>
                @error('resolutionDetails')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <x-buttons.main-btn 
                id="create_incident_report" 
                label="{{ __('Create Incident Report') }}" 
                target="save" 
                :nonce="$nonce"
                :disabled="false" 
                class="w-25 fw-light" 
                loading="Creating..."
            ></x-buttons.main-btn>
        </div>

        </div>
    </form>
</div>

@script
<script>
    Livewire.on('storedIncidentReport', (event) => {
        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });
</script>
@endscript