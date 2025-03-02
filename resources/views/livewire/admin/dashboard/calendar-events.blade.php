@use(Illuminate\Support\Carbon)

<div class="col-md-6 d-flex">
    <div class="card border-primary p-4 h-100 w-100">
        <div class="px-3">
            <div class="row">
                <div class="col-9">
                    <div class="fs-3 fw-bold mb-3">Upcoming Dates & Events</div>
                </div>

                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <x-buttons.view-link-btn link="#" text="See Calendar" />
                    </div>
                </div>
            </div>
            <div class="w-100">

                @if ($holidays->isEmpty())
                    <small>{{ __('No upcoming events.') }}</small>
                @else
                    <ul>
                        @foreach ($holidays as $holiday)
                            <li>
                                {{ sprintf("%s - %s",   
                                    Carbon::createFromFormat('m-d', $holiday->date)->format('F d Y'),
                                    $holiday->event)
                                }}
                            </li>
                        @endforeach                        
                    </ul>        
                @endif

            </div>
        </div>
    </div>
</div>