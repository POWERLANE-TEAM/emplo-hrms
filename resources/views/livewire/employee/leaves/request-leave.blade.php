@php
    $validDate = today()->addDay()->format('Y-m-d');
    $leaveBalance = auth()->user()->account->jobDetail->leave_balance;
@endphp

<div>
    <div class="row px-3 mb-4">

        <!-- Input Field: Type of Leave -->
        <div class="row">
            <div class="col">
                <x-form.boxed-dropdown id="leave_type" label="{{ __('Type of Leave') }}" name="state.leaveType"
                    :nonce="$nonce" :required="true" :options="$this->leaveCategories" placeholder="Select type" />
                @error('state.leaveType')
                    <div class="invalid-feedback" role="alert"> {{ $message }} </div>
                @enderror
            </div>
        </div>

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
                    :required="true" placeholder="End Date" min="{{ $validDate }}" />
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

        <!-- Reason for Leave -->
        <div class="pe-4 my-2">
            <div class="col-md-12 pe-2">
                <div class="callout callout-success bg-body-tertiary">
                    <div class="fs-5 px-2">{{ __('Total leave days requested') }}:
                        <span class="fw-bold text-primary">{{ $totalDaysLeave }}</span>
                    </div>
                </div>
                <div class="callout callout-info bg-body-{{ $leaveBalance > 0 ? 'info' : 'danger' }} mt-3">
                    <div class="fs-5 px-2">{{ __('Remaining Leave Balance') }}:
                        <span class="fw-bold text-primary">{{ $leaveBalance }}</span>
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