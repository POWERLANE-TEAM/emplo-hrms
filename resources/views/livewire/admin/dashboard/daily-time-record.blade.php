<div class="col-md-7 flex">
    <div class="card h-100 indiv-grid-container-1">
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
                <div class="text-muted">{{ today()->format('F d, Y') }}</div>
            </div>
            {{-- Refresh Button --}}
            <div wire:ignore class="ms-auto">
                <button wire:click="$refresh" type="button" wire:loading.attr="disabled"
                    class="btn btn-sm btn-outline-primary rounded-5" data-bs-toggle="tooltip"
                    title="{{ __('Sync to ZKTeco') }}">
                    <i data-lucide="refresh-cw"></i>
                </button>
            </div>
        </div>

        <div class="dtr-table overflow-auto thin-custom-scrollbar">
            <table class="w-100">
                <thead class="">
                    <tr class="">
                        <th class="ps-5 pt-4 pb-2">{{ __('Employee') }}</th>
                        <th class="text-center pt-4 pb-2">{{ __('Check-In') }}</th>
                        <th class="text-center pt-4 pb-2">{{ __('Check-Out') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @if ($dtrLogs->isEmpty())
                        {{-- some empty state here --}}
                    @else
                        @foreach ($dtrLogs as $dtrLog)
                            <tr class="px-5">
                                <td class="d-flex align-items-center px-4 py-3">
                                    <img src="{{ $dtrLog->employee->photo }}" alt="User Picture" width="45" height="45"
                                        class="rounded-circle me-3">
                                    <div>
                                        <div>{{ $dtrLog->employee->name ?? __('Unknown Employee') }}</div>
                                        <small class="text-muted">
                                            <span class="fw-bold">{{ __('Employee Id:') }}</span>
                                            {{ $dtrLog->employee->id }}
                                        </small>
                                    </div>
                                </td>
                                <td class="text-center">{{ $dtrLog->checkIn ?? '-' }}</td>
                                <td class="text-center">{{ $dtrLog->checkOut ?? '-' }}</td>
                            </tr>
                        @endforeach                    
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Issue: Throws an error if a component is refresh, by click the Refresh button --}}
        {{-- <div class="col-12 mb-4">
            <x-buttons.view-link-btn wire:navigate link="{{ route($routePrefix.'.attendance.logs') }}"
                text="View All" />
        </div> --}}
    </div>
</div>