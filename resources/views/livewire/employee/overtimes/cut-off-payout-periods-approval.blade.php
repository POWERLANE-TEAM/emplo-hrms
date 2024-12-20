@props([
    'collapsibleId' => 'otSummaryApproversCollapse',
])

<section class="row">
    <style>
        .right-col {
            display: grid;
            grid-template-columns: max-content 1fr;
            column-gap: 20px;
            row-gap:5px;
        }
    </style>

    <div class="col-6">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                <div class="d-flex align-items-center">
                    <div wire:ignore>
                        <span class="pe-3">
                            <i class="icon text-success d-inline" data-lucide="badge-check"></i>
                        </span>
                    </div>
                    <img 
                        class="rounded-circle me-3" 
                        width="50" 
                        height="50"
                        src="{{ $employee->account->photo }}" 
                        alt="Employee photo"
                    >                        
                    <div>
                        <div class="d-flex align-items-center">
                            <div wire:ignore class="me-3">{{ $employee->full_name }}</div>
                            <x-status-badge 
                                color="{{ $status === 'Approved' ? 'success' : 'info' }}"
                            >
                                {{ $status }}
                            </x-status-badge>    
                        </div>
                        <div class="text-muted fs-6">{{ __("Employee Id: {$employee->employee_id}") }}</div>
                    </div>
                </div>
            </x-slot:heading>

            <x-slot:description>
                <div class="mt-3"></div>
                <div class="right-col">
                    <div class="text-secondary-emphasis fw-semibold">
                        {{ __('Cut Off Period: ') }}
                    </div>
                    <div>{{ $overtime->first()->payrollApproval->payroll->cut_off }}</div>
                    <div class="text-secondary-emphasis fw-semibold">
                        {{ __('Pay-Out Date: ') }}
                    </div>
                    <div>{{ $overtime->first()->payrollApproval->payroll->payout }}</div>
                </div>
                <div class="list-group">
                    <div wire:ignore>
                        <a
                            type="button" 
                            class="me-2 mb-2 mt-3"
                            data-bs-toggle="collapse" 
                            data-bs-target="#{{ $collapsibleId }}" 
                            aria-expanded="false" 
                            aria-controls="collapseControls"
                        >
                            {{ __('Overtime Summary Approvers') }}
                            <i data-lucide="chevron-down" class="icon icon-large me-2"></i>
                        </a>
                    </div>
                    <div class="collapse" id="{{ $collapsibleId }}">
                        <div class="list-group-item border-0 ps-0">
                            <h6 class="fw-semibold text-secondary-emphasis d-inline">
                                {{ __('Initial: ') }}
                            </h6>
                            <span>{{ $otSummary->initialApprover }}</span>
                            <div class="d-flex justify-content-start">
                                <span class="text-muted small">
                                    {{ $otSummary->initialApprovalDate ?? __('(Awaiting Approval)') }}
                                </span>
                            </div>
                        </div> 

                        <div class="list-group-item border-0 ps-0">
                            <h6 class="fw-semibold text-secondary-emphasis d-inline">
                                {{ __('Second: ') }}
                            </h6>
                            <span>{{ $otSummary->secondApprover }}</span>
                            <div class="d-flex justify-content-start">
                                <span class="text-muted small">
                                    {{ $otSummary->secondApprovalDate ?? __('(Awaiting Approval)') }}
                                </span>
                            </div>
                        </div>
                    
                        <div class="list-group-item border-0 ps-0">
                            <h6 class="fw-semibold text-secondary-emphasis d-inline">
                                {{ __('Third: ') }}
                            </h6>
                            <span>{{ $otSummary->thirdApprover }}</span>
                            <div class="d-flex justify-content-start">
                                <span class="text-muted small">
                                    {{ $otSummary->thirdApprovalDate ?? __('(Awaiting Approval)') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>                                   
            </x-slot:description>
        </x-headings.main-heading>
    </div>

    <div class="col-6 pt-2 text-end">
        <div class="card border-0 align-items-end">
            <div class="text-center">
                <div class="card-body px-5 py-2 rounded-2 bg-secondary-subtle">
                    <div class="fs-3 fw-bold text-primary">
                        {{ $totalOtHours }}      
                    </div>
                    <div class="fw-semibold text-secondary-emphasis">
                        {{ __('Total OT Hours') }}
                    </div>
                </div>

                @if (in_array('initial', $remainingApprovers))
                    @can('approveOvertimeSummaryInitial', auth()->user())
                        <div class="pt-2">
                            <x-buttons.main-btn 
                                id="approveOtSummaryInitial" 
                                label="{{ __('Approve OT Summary') }}" 
                                wire:click="approveOtSummaryInitial" 
                                target="approveOtSummaryInitial"
                                :nonce="$nonce"
                                :disabled="false" 
                                class="w-100" 
                            />    
                        </div>    
                    @endcan

                @elseif (in_array('secondary', $remainingApprovers))
                    @can('approveOvertimeSummarySecondary', auth()->user())
                        <div class="pt-2">
                            <x-buttons.main-btn 
                                id="approveOtSummarySecondary" 
                                label="{{ __('Approve OT Summary') }}" 
                                wire:click="approveOtSummarySecondary" 
                                target="approveOtSummarySecondary"
                                :nonce="$nonce"
                                :disabled="false" 
                                class="w-100" 
                            />    
                        </div>
                    @endcan

                @elseif (in_array('tertiary', $remainingApprovers))
                    @can('approveOvertimeSummaryTertiary', auth()->user())
                        <div class="pt-2">
                            <x-buttons.main-btn 
                                id="approveOtSummaryTertiary" 
                                label="{{ __('Approve OT Summary') }}" 
                                wire:click="approveOtSummaryTertiary" 
                                target="approveOtSummaryTertiary"
                                :nonce="$nonce"
                                :disabled="false" 
                                class="w-100" 
                            />    
                        </div>             
                    @endcan
                @endif
            </div>
        </div>
    </div>
</section>