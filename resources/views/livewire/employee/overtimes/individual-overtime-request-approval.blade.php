@props([
    'modalId' => 'overtimeRequestApprovalInfo',
])

<div>
    <style>
        .left-col {
            display: grid;
            grid-template-columns: 130px 1fr;
            column-gap: 20px;
            row-gap:5px;
        }
    
        .right-col {
            display: grid;
            grid-template-columns: max-content 1fr;
            column-gap: 20px;
            row-gap: 5px;
        }
    </style>

    <x-modals.dialog data-bs-backdrop="static" data-bs-keyboard="false" :id="$modalId">
        <x-slot:title class="">
            <h1 class="modal-title fs-5 fw-bold text-secondary-emphasis" id="{{ $modalId }}">{{ __('Overtime Request Information') }}</h1>
            <button wire:click="$dispatchSelf('close-{{ $modalId }}')" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        <x-slot:content>
            @if (! $loading)
                <div class="row mb-3">
                    <div class="col-5">
                        <div class="d-flex flex-column align-items-center text-secondary-emphasis">
                            <div class="text-center">
                                <img 
                                    src="{{ $otRequest?->requestor->photo }}" 
                                    alt="Employee photo" 
                                    class="rounded-circle mb-2"
                                    height="70"
                                    width="70" 
                                    style="object-fit: cover;"
                                />
                                <div class="fw-semibold fs-5">
                                    {{ $otRequest?->requestor->name }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ $otRequest?->requestor->job_title }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ "Level {$otRequest?->requestor->job_level}: {$otRequest?->requestor->job_level_name}" }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ "Employee ID: {$otRequest?->requestor->employee_id}" }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ $otRequest?->requestor->employment }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ "{$otRequest?->requestor->shift} ({$otRequest?->requestor->shift_schedule}) "}}
                                </div>        
                            </div>
                        </div>
                    </div>
                
                    <div class="col-7 mt-3 mt-md-0 text-secondary-emphasis">
                        <div class="mb-3">
                            <label for="work_performed" class="fw-medium form-label">{{ __('Work To Perform') }}</label>
                            <div class="form-control bg-body-secondary p-2 rounded-3" id="work_performed">
                                {{ $otRequest?->overtime_details->work_performed }}
                            </div>
                        </div>
                
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_ovt" class="fw-medium form-label">{{ __('Requested Date') }}</label>
                                <div class="form-control bg-body-secondary p-2 rounded-3" id="date_ovt">
                                    {{ $otRequest?->overtime_details->date }}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="hours_ot" class="fw-medium form-label">{{ __('Start Time') }}</label>
                                <div class="form-control bg-body-secondary p-2 rounded-3" id="hours_ot">
                                    {{ $otRequest?->overtime_details->start_time }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="hours_ot_end" class="fw-medium form-label">{{ __('End Time') }}</label>
                                <div class="form-control bg-body-secondary p-2 rounded-3" id="hours_ot_end">
                                    {{ $otRequest?->overtime_details->end_time }}
                                </div>
                            </div>
                        </div>

                        <div class="right-col fs-6 fw-medium">
                            <div class="">{{ __('Requested Hours: ') }}</div>
                            <div>{{ $otRequest?->overtime_details->hours_requested }}</div>
                            <div class="">{{ __('Date Filed: ') }}</div>
                            <div>{{ $otRequest?->overtime_details->filed_at }}</div>
                        </div>

                        <div class="list-group">
                            @if ($otRequest->overtime_details->authorizer_signed_at)
                                <hr>
                                <div class="list-group-item border-0 ps-0">
                                    <h6 class="fw-semibold text-primary">{{ __('Authorized') }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary-emphasis">{{ __('by: ') }}
                                            <span class="fw-semibold">
                                                {{ $otRequest?->overtime_details->authorizer }}
                                            </span>
                                        </span>
                                        <span class="text-muted small">{{ $otRequest?->overtime_details->authorizer_signed_at }}</span>
                                    </div>
                                </div>
                            @endif
            
                            @if ($otRequest->overtime_details->denied_at)
                                <hr>
                                <div class="list-group-item border-0 ps-0">
                                    <h6 class="fw-semibold text-danger">{{ __('Request Denied') }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary-emphasis">{{ __('by: ') }}
                                            <span class="fw-semibold">
                                                {{ $otRequest?->overtime_details->denier }}
                                            </span>
                                        </span>
                                        <span class="text-muted small">{{ $otRequest?->overtime_details->denied_at }}</span>
                                    </div>
                                    <div class="mt-3">
                                        <label for="feedback" class="form-label text-muted">{{ __('Reason:') }}</label>
                                        <textarea id="feedback" class="form-control bg-body-secondary" rows="3" readonly>{{ $otRequest?->overtime_details->feedback }}</textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>                         
                </div>
            @else
                <div wire:loading class="col-12 my-4">
                    @include('livewire.placeholder.overtime-request-content')
                </div>  
            @endif                             
        </x-slot:content>        
        <x-slot:footer>
        </x-slot:footer>                
    </x-modals.dialog>
</div>

@script
<script>
    $wire.on('showOvertimeRequestApprovalInfo', () => {
        openModal('{{ $modalId }}');
    });

    $wire.on('close-{{ $modalId }}', () => {
        hideModal('{{ $modalId }}');
        $wire.set('loading', true);
    });
</script>
@endscript
