<div class="col-md-7">
    <div class="card h-100">
        <div class="card-header w-100 px-4 pt-3 pb-2">
            <header class="fs-4 fw-bold text-secondary-emphasis">
                {{ __('Recent Activity Logs') }}
            </header>
            <div>
                {{ __('Logs for the past 24 hours.') }}
            </div>
            {{-- <div class="d-flex justify-content-end">
                <x-buttons.view-link-btn link="#" text="View All" />
            </div> --}}
        </div>

        <div class="card-body px-4">
            <ul class="list-unstyled">
                @foreach ($this->logs as $log)
                    <div class="d-flex">
                        <div>
                            <li class="fs-6">{{ $log->description }}</li>
                        </div>
                        <div class="ms-auto">
                            <small class="text-muted">{{ $log->created_at }}</small>
                        </div>
                    </div>
                    <hr />
                @endforeach
            </ul>
            <div>
                {{ $this->logs->links() }} 
            </div>
        </div>        
    </div>
</div>