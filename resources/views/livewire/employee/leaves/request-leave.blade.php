@php
    $validDate = today()->addDay()->format('Y-m-d');
@endphp

<div>
    <div class="row px-3 mb-4">

        <div class="callout {{ $this->vacationLeaveCredits > 0 ? 'callout-info bg-body-tertiary' : 'callout-danger' }}">
            <div class="fs-5 px-2">{{ __('Vacation Leave Credit(s)') }}:
                <span class="fw-bold text-primary">{{ $this->vacationLeaveCredits }}</span>
            </div>
        </div>

        <div class="callout my-3 {{ $this->sickLeaveCredits > 0 ? 'callout-info bg-body-tertiary' : 'callout-danger' }}">
            <div class="fs-5 px-2">{{ __('Sick Leave Credit(s)') }}:
                <span class="fw-bold text-primary">{{ $this->sickLeaveCredits }}</span>
            </div>
        </div>

        <!-- Input Field: Type of Leave -->
        <div class="row">
            <div class="col">
                <div class="input-group mb-3 position-relative">
                    <label for="dropdown-id" class="mb-1 fw-semibold text-secondary-emphasis">
                        {{ __('Type of Leave') }}
                        <span class="text-danger">*</span>
                    </label>
                
                    <select
                        wire:model.live="state.leaveType"
                        id="dropdown-id"
                        class="form-control form-select border ps-3 rounded pe-5"
                        autocomplete="off"
                        aria-owns="dropdown-id-feedback"
                        required
                    >
                        <option value="">{{ __('Select an option') }}</option>
                        @foreach ($this->leaveCategories as $category)
                            <option value="{{ $category->leave_category_id }}">
                                {{ $category->leave_category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('state.leaveType')
                        <div class="invalid-feedback" role="alert"> {{ $message }} </div>
                    @enderror
                </div>
            </div>
        </div>   
        
        @if ($showLeaveDescription)
            <div class="row mb-3">
                <div class="col">
                    <textarea class="form-control gray-custom-scrollbar" rows="5" readonly>{{ 
                        trim(optional($this->leaveCategories
                        ->where('leave_category_id', $state['leaveType'])
                        ->first())->leave_category_desc) 
                    }}</textarea>
                </div>
            </div>
        @endif

        <!-- Calendar Pick: Start Date & End Date -->
        <div class="row">
            <div class="col">
                <x-form.boxed-date id="start_date" label="{{ __('Start Date') }}" wire:model.live="state.startDate" :nonce="$nonce"
                    :required="true" placeholder="Start Date" min="{{ $validDate }}" />
                @error('state.startDate')
                    <div class="invalid-feedback" role="alert"> {{ $message }} </div>
                @enderror
            </div>

            <div class="col">
                <x-form.boxed-date id="end_date" label="{{ __('End Date') }}" wire:model.live="state.endDate" :nonce="$nonce"
                    :required="true" placeholder="End Date" min="{{ $validDate }}" :disabled="$disabledEndDate" />
                @error('state.endDate')
                    <div class="invalid-feedback" role="alert"> {{ $message }} </div>
                @enderror
            </div>
        </div>

        <!-- Textarea Field: Reason for Leave -->
        <div class="row">
            <div class="col">
                <x-form.boxed-textarea id="reason_leave" label="Reason for leave" name="state.reason" :nonce="$nonce"
                    :rows="7" :required="true" />
                @error('state.reason')
                    <div class="invalid-feedback" role="alert"> {{ $message }} </div>
                @enderror
            </div>
        </div>

        <div class="row pt-2">
            <div class="col">
                <x-form.boxed-textarea-attachment
                    name="attachments"
                    id="detailed_desc" 
                    label="" 
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

        <!-- Reason for Leave -->
        <div class="pe-4 my-2">
            <div class="col-md-12 pe-2">
                <div class="callout callout-success bg-body-tertiary">
                    <div class="fs-5 px-2">{{ __('Total leave days requested') }}:
                        <span class="fw-bold text-primary">{{ $totalDaysLeave }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pe-4 mt-2">
            <div class="col-md-12 pe-2 d-flex align-items-center text-end">
                <x-buttons.main-btn label="Submit Request" wire:click.prevent="save" :nonce="$nonce"
                    :disabled="false" class="w-25" :loading="'Submitting...'" />
            </div>
        </div>
    </div>
</div>

@script
<script>
    Livewire.on('showSuccessToast', (event) => {
        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });
</script>
@endscript