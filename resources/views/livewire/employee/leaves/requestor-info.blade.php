@php
    $activeRoutes = [];
    $ownedRequest = "{$this->routePrefix}.leaves.show";
    $otherRequest = "{$this->routePrefix}.leaves.employee.requests";

    array_push($activeRoutes, $ownedRequest, $otherRequest);

    $leaveBalance = $leave->employee->jobDetail->leave_balance;
@endphp

<section>
    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb :href="route($this->routePrefix.'.leaves.index')">
                {{ __('Leaves') }}
            </x-breadcrumb>
            <x-breadcrumb :active="request()->routeIs($activeRoutes)">
                {{ __('Request Leave') }}
            </x-breadcrumb>
        </x-slot:breadcrumbs>
    </x-breadcrumbs>
    
    <x-headings.main-heading :isHeading="true">
        <x-slot:heading>
            <div class="row d-flex align-items-start">
                <div class="col-7">
                    <span class="me-2">
                        {{ $leave->category->leave_category_name }}
                    </span>
                    <x-status-badge 
                        color="{{ $status->getColor() }}"
                    >
                        {{ $status->getLabel() }}
                    </x-status-badge>                    
                </div>

                <div class="col-5 text-end">
                    <a wire:navigate.hover 
                        @isset ($previousLeaveId)
                            href="{{ route("{$this->routePrefix}.leaves.employee.requests", ['leave' => $previousLeaveId]) }}">
                        @endif
                        <button 
                            class="btn btn-outline-primary text-center h-100 px-4"
                            @if (is_null($previousLeaveId)) 
                                disabled
                            @endif
                        >
                            <i class="icon icon-large mx-1" data-lucide="arrow-left"></i>
                            {{ __('Previous') }}
                        </button>
                    </a>

                    <a wire:navigate.hover 
                        @isset ($nextLeaveId)
                            href="{{ route("{$this->routePrefix}.leaves.employee.requests", ['leave' => $nextLeaveId]) }}">
                        @endif
                        <button 
                            class="btn btn-outline-primary text-center h-100 px-4" 
                            @if (is_null($nextLeaveId)) 
                                disabled 
                            @endif
                        >
                            {{ __('Next') }}
                            <i class="icon icon-large mx-1" data-lucide="arrow-right"></i>
                        </button>
                    </a>
                </div>
            </div>
        </x-slot:heading>

        <x-slot:description>
            <div class="row d-flex align-items-center">
                @if ($leave->employee_id !== $this->user->account->employee_id)
                    <div class="col-3">
                        <div class="d-flex align-items-center">
                            <div wire:ignore>
                                <span class="pe-3">
                                    <i class="icon text-success icon-slarge d-inline" data-lucide="badge-check"></i>
                                </span>
                            </div>
                            <img 
                                class="rounded-circle me-3" 
                                width="40" 
                                height="40"
                                src="{{ $leave->employee->account->photo }}" 
                                alt="Employee photo"
                            >                        
                            <div class="text-truncate" style="max-width: 200px;">
                                <div class="d-flex align-items-center">
                                    <div wire:ignore class="fw-medium fs-5 text-truncate">{{ $leave->employee->full_name }}</div>
                                </div>
                                <div class="text-muted fs-6">{{ __("Employee Id: {$leave->employee->employee_id}") }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="">
                            <span class="fw-semibold text-secondary-emphasis">
                                {{ __('Leave Balance: ') }}
                            </span>
                            <span class="fw-bold {{ $leaveBalance > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $leaveBalance }}
                            </span>
                        </div>
                        <div class="">
                            <span class="fw-semibold text-secondary-emphasis">
                                {{ __('Filed On: ') }}
                            </span>
                            {{ $leave->filed_at }}
                        </div>  
                    </div>
                @endif   
            </div>
        </x-slot:description>
    </x-headings.main-heading>
</section>
