@props([
    'modalId' => 'overtimeSummaryApprovalInfoModal',
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

    <x-modals.dialog :id="$modalId">
        <x-slot:title class="">
            <h1 class="modal-title fs-5 fw-bold text-secondary-emphasis" id="{{ $modalId }}">{{ __('Overtime Request Information') }}</h1>
            <button wire:click="$dispatchSelf('close-{{ $modalId }}')" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        <x-slot:content>
            @if (! $loading)
                <div class="row mb-3">
                    <div class="col-12 mt-3 mt-md-0 text-secondary-emphasis">
                        <div class="mb-3">
                            <label for="work_performed" class="fw-medium form-label">{{ __('Work To Perform') }}</label>
                            <div class="form-control bg-body-secondary p-2 rounded-3" id="work_performed">
                                {{ $otSummaryInfo?->work_performed }}
                            </div>
                        </div>
                
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hours_ot" class="fw-medium form-label">{{ __('Start Time') }}</label>
                                <div class="form-control bg-body-secondary p-2 rounded-3" id="hours_ot">
                                    {{ $otSummaryInfo?->start_time }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="hours_ot_end" class="fw-medium form-label">{{ __('End Time') }}</label>
                                <div class="form-control bg-body-secondary p-2 rounded-3" id="hours_ot_end">
                                    {{ $otSummaryInfo?->end_time }}
                                </div>
                            </div>
                        </div>

                        <div class="right-col fs-6 fw-medium">
                            <div class="">{{ __('Requested Hours: ') }}</div>
                            <div>{{ $otSummaryInfo?->hours_requested }}</div>
                            <div class="">{{ __('Date Filed: ') }}</div>
                            <div>{{ $otSummaryInfo?->filed_at }}</div>
                        </div>

                        <div class="list-group">
                            @if ($otSummaryInfo?->authorizer_signed_at)
                                <hr>
                                <div class="list-group-item border-0 ps-0">
                                    <h6 class="fw-semibold text-primary">{{ __('Request Authorized') }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary-emphasis">{{ __('by: ') }}
                                            <span class="fw-semibold">
                                                {{ $otSummaryInfo?->authorizer }}
                                            </span>
                                        </span>
                                        <span class="text-muted small">{{ $otSummaryInfo?->authorizer_signed_at }}</span>
                                    </div>
                                </div>
                            @endif
            
                            @if ($otSummaryInfo?->denied_at)
                                <hr>
                                <div class="list-group-item border-0 ps-0">
                                    <h6 class="fw-semibold text-danger">{{ __('Request Denied') }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary-emphasis">{{ __('by: ') }}
                                            <span class="fw-semibold">
                                                {{ $otSummaryInfo?->denier }}
                                            </span>
                                        </span>
                                        <span class="text-muted small">{{ $otSummaryInfo?->denied_at }}</span>
                                    </div>
                                    <div class="mt-3">
                                        <label for="feedback" class="form-label text-muted">{{ __('Reason:') }}</label>
                                        <textarea id="feedback" class="form-control bg-body-secondary" rows="3" readonly>{{ $otSummaryInfo?->feedback }}</textarea>
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
            <div class="d-flex justify-content-between align-items-center w-100 text-secondary-emphasis">
                <div class="me-auto">
                    <p class="fs-6 fw-medium mb-0">
                        <strong>Note: </strong>{{ __('Overtime requests cannot be updated after a week or once authorized.') }}
                    </p>
                </div>
            </div>
        </x-slot:footer>             
    </x-modals.dialog>
</div>

@script
<script>
    $wire.on('showOvertimeSummaryApproval', () => {
        openModal('{{ $modalId }}');
    });

    $wire.on('close-{{ $modalId }}', () => {
        hideModal('{{ $modalId }}');
        $wire.set('loading', true);
    });
</script>
@endscript
