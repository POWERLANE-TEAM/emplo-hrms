<div class="col-md-7 flex">
    <div class="card h-100">
        <div class="card-header w-100 d-flex justify-content-between align-items-center">
            {{-- DTR Total --}}
            <div>
                <span class="fs-1 fw-bold text-danger">{{ $totalDtr }}</span>
            </div>
            {{-- Daily Time Record Header --}}
            <div class="ms-3 text-start">
                <header class="fs-4 fw-bold text-secondary-emphasis">
                    {{ __('Daily Time Record') }}
                </header>
                <div class="text-muted">{{ $dtrDate }}</div>
            </div>
            {{-- Refresh Button --}}
            <div wire:ignore class="ms-auto">
                <button 
                    wire:click="$refresh" 
                    type="button" 
                    wire:loading.attr="disabled" 
                    class="btn btn-sm btn-outline-primary rounded-5" 
                    data-bs-toggle="tooltip"
                    title="{{ __('Sync to ZKTeco') }}">
                    <i data-lucide="refresh-cw"></i>
                </button>
            </div>
        </div>
        
        <table class="table table-sm table-striped px-3">
            <thead class="">
                <tr class="">
                    <th>{{ __('Employee') }}</th>
                    <th>{{ __('Check-In') }}</th>
                    <th>{{ __('Check-Out') }}</th>
                    <th>{{ __('Overtime-In') }}</th>
                    <th>{{ __('Overtime-Out') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($dtrLogs as $dtrLog)
                    <tr>
                        <td class="d-flex align-items-center">
                            <img src="{{ $dtrLog->employee->photo }}" alt="User Picture" width="30" height="30" class="rounded-circle me-3">
                            <div>
                                <div>{{ $dtrLog->employee->name ?? __('Unknown Employee') }}</div>
                                <small class="text-muted">
                                    <span class="fw-bold">{{ __('Employee Id:') }}</span>
                                    {{ $dtrLog->employee->id }}
                                </small>
                            </div>                                
                        </td>
                        <td>{{ $dtrLog->checkIn ?? '-' }}</td>
                        <td>{{ $dtrLog->checkOut ?? '-' }}</td>
                        <td>{{ $dtrLog->overtimeIn ?? '-' }}</td>
                        <td>{{ $dtrLog->overtimeOut ?? '-' }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        {{-- Issue: Throws an error if a component is refresh, by click the Refresh button --}}
        {{-- <div class="col-12 mb-4">
            <x-buttons.view-link-btn 
                wire:navigate 
                link="{{ route($routePrefix.'.attendance.logs') }}" 
                text="View All" />
        </div> --}}
    </div>
</div>