<div>
    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb :href="route($this->routePrefix.'.relations.incidents.index')">
                {{ __('Incidents') }}
            </x-breadcrumb>
            <x-breadcrumb :active="request()->routeIs($this->routePrefix.'.relations.incidents.show')">
                {{ "{$incident->reportedBy->last_name}'s Incident Report" }}
            </x-breadcrumb>
        </x-slot:breadcrumbs>
    </x-breadcrumbs>
    
    <div class="row">
        <div class="col-7">
            <x-headings.main-heading :isHeading="true">
                <x-slot:heading>
                    {{ __("Review or Update Incident Report") }}
                </x-slot:heading>
            
                <x-slot:description>
                    @if ($incident->created_at->eq($incident->updated_at))
                        {{ $incident->created_at->format('F d, Y g:i A') }}
                    @else
                        {{ $incident->updated_at->format('F d, Y g:i A') }}
                        <span class="text-muted">
                            {{ __('(Edited)') }}
                        </span>
                    @endif
                </x-slot:description>
            </x-headings.main-heading>        
        </div>

        <div class="col-5 mt-2 d-flex align-items-start justify-content-end">
            <livewire:hr-manager.incidents.manage-collaborators :$routePrefix :$incident />
        </div>        
    </div>

    <div class="row mb-3">
        <div class="col">
            <span class="pe-2">{{ __('Tags: ') }}</span>
            @foreach ($incident->types as $type)
                <span class="badge rounded-pill bg-primary fs-6 fw-light px-3 py-2 my-1">{{ $type->issue_type_name }}</span>
            @endforeach
        </div>
    </div>
    
    <section class="mb-5">
        <form wire:submit="saveChanges" enctype="multipart/form-data">
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

                        @forelse ($incident->attachments as $attachment)
                            <div class="attachment-item d-inline-flex align-items-center me-2">
                                <a 
                                    href="{{ route("{$this->routePrefix}.relations.incidents.attachments.show", ['attachment' => $attachment->attachment]) }}" 
                                    target="__blank" 
                                    class="text-info text-decoration-underline me-1" 
                                    title="File Name">{{ $attachment->attachment_name }}
                            
                                </a>
                                <a 
                                    href="{{ route("{$this->routePrefix}.relations.incidents.download", ['attachment' => $attachment->attachment]) }}"
                                    target="__blank"
                                >
                                    <button
                                        type="button"
                                        class="btn btn-sm py-0 px-1 no-hover-border hover-opacity"
                                        data-bs-toggle="tooltip" title="Download">
                                        <i class="icon icon-large text-info" data-lucide="download"></i>
                                    </button>
                                </a>                                
                            </div>
                        @empty
                            <div class="text-muted mb-2">{{ __('No attachments provided.') }}</div>
                        @endforelse
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
    
            @can('updateIncidentReport', $incident)
                <div>
                
                    <x-buttons.main-btn 
                        id="create_incident_report" 
                        label="{{ __('Save Changes') }}" 
                        target="save" 
                        :nonce="$nonce"
                        :disabled="false" 
                        class="w-25 fw-light" 
                        loading="Creating..."
                    ></x-buttons.main-btn>                    
                </div>
            @endcan
        </form>    
    </section>
</div>

@script
<script>
    Livewire.on('updatedIncidentReport', (event) => {
        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });
</script>
@endscript
