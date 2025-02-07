@props([
    'collapsibleId' => 'otSummaryApproversCollapse',
])

@php
    $page = [
        'heading' => null,
        'description' => null,
    ];
    
    $summaryRoute = null;

    if (request()->routeIs("{$routePrefix}.overtimes.recents")) {
        $page = [
            'heading' => __('Recent Overtime Requests'),
            'description' => __('You can view and manage overtime requests filed less than a week ago here.'),
        ];
    }

    if (request()->routeIs("{$routePrefix}.overtimes.archive")) {
        $page = [
            'heading' => __('Archived Overtime Requests'),
            'description' => __('You can view overtime requests that are authorized or filed more than a week ago here.'),
        ];
    }

    if (request()->routeIs("{$routePrefix}.overtimes.summaries")) {
        $page = [
            'heading' => __('Overtime Summary'),
        ];

        $summaryRoute = request()->routeIs("{$routePrefix}.overtimes.summaries");
    }
@endphp

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
                <span class="me-3"> {{ $page['heading'] }} </span>

                @if ($summaryRoute)
                    <x-status-badge 
                        color="{{ $status === 'Approved' ? 'success' : 'info' }}"
                    >
                        {{ $status }}
                    </x-status-badge>      
                @endif
            </x-slot:heading>

            <x-slot:description>
                @if ($summaryRoute)
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
                
                    <div class="mt-3 list-group">
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
                @else
                    <div>
                        <div> {{ $page['description'] }} </div>
                    </div>                
                @endif
            </x-slot:description>
        </x-headings.main-heading>
    </div>

    <div class="col-6 text-end">
        <div class="card border-0 align-items-end">
            <div class="text-center">
                <div class="mb-3">
                    <button onclick="openModal('requestOvertimeModal')" class="btn btn-primary">
                        <i data-lucide="plus-circle" class="icon icon-large me-2"></i>{{ __('Request Overtime') }}
                    </button>                    
                </div>
                
                @if ($summaryRoute)
                    <div class="card-body px-5 py-2 rounded-2 bg-secondary-subtle">
                        <div class="fs-3 fw-bold text-primary">
                            {{ $totalOtHours }}      
                        </div>
                        <div class="fw-semibold text-secondary-emphasis">
                            {{ __('Total OT Hours') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <livewire:employee.overtimes.basic.request-overtime />
    <livewire:employee.overtimes.basic.edit-overtime-request />
</section>