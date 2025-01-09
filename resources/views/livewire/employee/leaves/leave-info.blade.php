@props([
    'collapsibleId' => 'denyLeaveRequestCollapse',
])

@php
    $leaveBalance = $leave->employee->jobDetail->leave_balance;
@endphp

<div>
    <div class="row px-3 mb-4">
        <div class="row">
            <div class="col">

                <div class="mb-3">
                    <x-form.display.boxed-input-display label="{{ __('Type of Leave') }}" data="{{ $leave->category->leave_category_name }}" />
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-form.display.boxed-input-display label="{{ __('Start Date') }}" data="{{ $leave->start_date }}" />
            </div>

            <div class="col">
                <x-form.display.boxed-input-display label="{{ __('End Date') }}" data="{{ $leave->end_date }}" />
            </div>
        </div>

        <div class="row">
            <div class="col">
                <x-form.display.boxed-input-display label="{{ __('Reason for leave') }}" data="{{ $leave->reason }}"/>
            </div>
        </div>

        <div class="pe-4 my-2">
            <div class="col-md-12 pe-2">
                <div class="callout callout-success bg-body-tertiary">
                    <div class="fs-5 px-2">{{ __('Total leave days requested: ') }}
                        <span class="fw-bold text-primary"> {{ $leave->total_days_requested }} </span>
                    </div>
                </div>
                @if ($this->user->account->employee_id === $leave->employee_id)
                    <div class="callout mt-2 callout-{{ $leaveBalance > 0 ? 'success' : 'danger' }} bg-body-tertiary">
                        <div class="fs-5 px-2">{{ __('Remaining Leave Balance: ') }}
                            <span class="fw-bold text-{{ $leaveBalance > 0 ? 'success' : 'danger' }}"> 
                                {{ $leaveBalance }} 
                            </span>
                        </div>
                    </div> 
                @endif
            </div>
        </div>

        <div wire:ignore.self class="mb-3 mt-2 px-3 collapse" id="{{ $collapsibleId }}" aria-expanded="false">
            <div class="">
                <label for="textarea-id" class="mb-1 fw-medium mb-3">
                    {{ __("Why? Tell {$leave->employee->last_name} here.") }}
                    <span class="text-danger" style="display: none;">*</span>
                </label>
                <div class="input-group mb-3 position-relative">
                    <textarea
                        id="denyFeedback" 
                        wire:model="feedback" 
                        class="form-control border ps-3 rounded" 
                        autocomplete="off" 
                        placeholder="{{ __('Enter your reason here') }}"
                        aria-owns="denyFeedback"
                        rows=4
                        @error('feedback') is-invalid @enderror
                    >
                    </textarea>
                    @error('feedback')
                        <div class="invalid-feedback" role="alert"> {{ $message }} </div>
                    @enderror
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center w-100 text-secondary-emphasis">
                <div id="collapsibleBtns" class="ms-auto">
                    <button 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#{{ $collapsibleId }}" 
                        aria-expanded="false" 
                        aria-controls="collapseControls" 
                        class="btn btn-lg btn-secondary me-2 px-5"
                        wire:loading.attr="disabled"
                        wire:target="approveLeaveRequest, denyLeaveRequest">
                        {{ __('Cancel') }}
                    </button>
                    <button 
                        type="button" 
                        wire:click="denyLeaveRequest" 
                        class="btn btn-lg btn-danger px-5"
                        wire:loading.attr="disabled"
                        wire:target="approveLeaveRequest, denyLeaveRequest">
                        {{ __('Confirm Deny') }}
                    </button>
                </div>
            </div>
        </div>  

        @if (is_null($leave->denied_at) && $destructiveBtnsEnabled)
            <div x-cloak id="destructiveBtns" class="container pe-4 mt-4">
                <div class="row">
                    <div class="col-6 pe-2">
                        <button
                            id="denyLeaveRequest" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#{{ $collapsibleId }}" 
                            aria-expanded="false" 
                            aria-controls="collapseControls" 
                            class="btn btn-lg btn-danger w-100"
                            wire:loading.attr="disabled"
                            wire:target="approveLeaveRequest, denyLeaveRequest"
                        >
                            {{ __('Deny Request') }}
                        </button>
                    </div>
                    <div class="col-6">
                        <button 
                            type="submit" 
                            wire:click="approveLeaveRequest" 
                            wire:loading.attr="disabled"
                            wire:target="approveLeaveRequest, denyLeaveRequest"
                            class="btn btn-lg btn-primary w-100"
                        >
                            {{ __('Approve Request') }}
                        </button>
                    </div>
                </div>
            </div>              
        @endif
    </div>
</div>

@script
<script>
    const collapseId = document.getElementById('{{ $collapsibleId }}');
    const collapsible = bootstrap.Collapse.getOrCreateInstance(collapseId, { toggle: false });
    const destructiveBtns = document.getElementById('destructiveBtns');
    const denyButton = document.getElementById('denyLeaveRequest');
    const denyFeedback = document.getElementById('denyFeedback');
    const collapsibleBtns = document.getElementById('collapsibleBtns');

    Livewire.on('showSuccessToast', (event) => {
        collapsible.hide();
        const eventPayload = event[0];
        showToast(eventPayload.type, eventPayload.message);
    });

    const isElementInViewport = (el) => {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    const scrollToFeedback = () => {
        if (! isElementInViewport(collapsibleBtns)) {
            collapsibleBtns.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    if (denyButton && denyFeedback) {
        denyButton.addEventListener('click', () => {
            if (collapsible) {
                collapsible.show();
            }

            setTimeout(scrollToFeedback, 300);
        });
    }

    if (collapsible && destructiveBtns) {
        collapseId.addEventListener('show.bs.collapse', () => {
            destructiveBtns.style.display = 'none';
        });

        collapseId.addEventListener('hidden.bs.collapse', () => {
            destructiveBtns.style.display = 'block';
        });
    }
</script>
@endscript