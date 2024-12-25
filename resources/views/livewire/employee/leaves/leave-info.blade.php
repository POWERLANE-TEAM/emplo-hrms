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
            </div>
        </div>

        @if (is_null($leave->denied_at) && $destructiveBtnsEnabled)
            <div class="container pe-4 mt-4">
                <div class="row">
                    <div class="col-6 pe-2">
                        <button 
                            type="submit" 
                            wire:click="denyLeaveRequest" 
                            wire:loading.attr="disabled"
                            wire:target="approveLeaveRequest, rejectLeaveRequest"
                            class="btn btn-lg btn-danger w-100"
                        >
                            {{ __('Deny') }}
                        </button>
                    </div>
                    <div class="col-6">
                        <button 
                            type="submit" 
                            wire:click="approveLeaveRequest" 
                            wire:loading.attr="disabled"
                            wire:target="approveLeaveRequest, rejectLeaveRequest"
                            class="btn btn-lg btn-primary w-100"
                        >
                            {{ __('Approve') }}
                        </button>
                    </div>
                </div>
            </div>              
        @endif
    </div>
</div>