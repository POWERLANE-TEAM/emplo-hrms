<div class="activity-logs-container">
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">

                <div>

                    @foreach ($events as $event)
                        <div class="timeline-data">
                            <div class="date-header">
                                <x-status-badge color="success">{{ $event['formatted_date'] }}</x-status-badge>
                            </div>
                            <ul class="timeline">
                                @foreach ($event['logs'] as $log)
                                    <li class="event" data-date="{{ $log['time'] }}">
                                        <h3 style="margin-bottom:0">{{ $log['title'] }}</h3>

                                        @if (!empty($log['previous_value']))
                                            <p class="mb-0 mt-2 change-detail">
                                                <span class="change-label">Previous Value:</span>
                                                <span class="change-value previous">{{ $log['previous_value'] }}</span>
                                            </p>
                                        @endif

                                        @if (!empty($log['new_value']))
                                            <p class="mb-0 change-detail">
                                                <span class="change-label">New Value:</span>
                                                <span class="change-value new">{{ $log['new_value'] }}</span>
                                            </p>
                                        @endif

                                        <div class="mt-2">
                                            <small class="text-muted">{{ $log['platform'] }} | {{ $log['ip_address'] }}</small>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>