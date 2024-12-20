@php
    $heading = match (true) {
        request()->routeIs("{$routePrefix}.overtimes.recents") => __('Recent Overtime Requests'),
        request()->routeIs("{$routePrefix}.overtimes.archive") => __('All Overtime Requests'),
    };
@endphp

<section class="row">
    <div class="col-6">
        <x-headings.main-heading :isHeading="true">
            <x-slot:heading>
                @if ($employee)

                    <div class="d-flex align-items-center">
                        <img 
                            class="rounded-circle me-3" 
                            width="50" 
                            height="50"
                            src="{{ $employee->account->photo }}" 
                            alt="Employee photo"
                        >                        
                        <div>
                            <div>{{ $heading }}</div>
                            <div class="text-muted fs-6">{{ __("Employee Id: {$employee->employee_id}") }}</div>
                        </div>
                    </div>
                @else               
                    {{ $heading }}
                @endif
            </x-slot:heading>

            {{-- <x-slot:description>
                <div class="text-secondary-emphasis">
                    <strong>{{ __('Cut Off Period:') }}</strong>
                    {{ $cutOff }} 
                </div>
                <div class="text-secondary-emphasis">
                    <strong>{{ __('Pay-Out Date:') }}</strong> {{ $payout }}
                </div>
                     
            </x-slot:description> --}}
        </x-headings.main-heading>
    </div>

    @if(request()->routeIs("{$routePrefix}.overtimes.requests.employee.summaries"))
        <div class="col-6 pt-2 text-end">
            {{ __("{$totalOtHours} Total OT Hours") }}
        </div>
    @else
        <div class="col-6 pt-2 text-end">
            <button onclick="openModal('requestOvertimeModal')" class="btn btn-primary">
            <i data-lucide="plus-circle" class="icon icon-large me-2"></i>{{ __('Request Overtime') }}</button>
        </div>

        <livewire:employee.overtimes.basic.request-overtime />
        <livewire:employee.overtimes.basic.edit-overtime-request />
    @endif
</section>