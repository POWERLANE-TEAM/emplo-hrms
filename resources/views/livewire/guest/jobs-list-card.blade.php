<sidebar class="nav nav-tabs col-12 col-md-5 " id="jobs-list" role="tablist" nonce="csp_nonce()">

    @if (empty($positions))
        No jobs Available
    @endif

    @foreach ($positions as $position)
        <li class="card nav-item ps-0 " role="presentation">
            <button value="{{ $position->position_id }}"
                x-on:click.debounce.10ms="$dispatch('job-selected', { position: [{{ $position }}] })"
                class="nav-link d-flex flex-row column-gap-4" id="{{ $position->position_id }}-tab" data-bs-toggle="tab"
                role="tab" aria-controls="job-view-pane" aria-label="{{ $position->title }}">
                <div class="col-4 pt-3 px-2 ">
                    <img src="http://placehold.it/74/74" alt="" loading="lazy">
                </div>
                <div class="col-7 text-start">
                    <header>
                        <hgroup>
                            <div class="card-title fs-3 fw-bold text-black mb-0">{{ $position->title }}</div>
                            <p class="fs-4 text-primary">Card title</p>
                        </hgroup>
                    </header>
                    <div class="">

                        <div class="card-text text-black">content.</div>
                        <div class="card-text text-black">content.</div>
                    </div>
                </div>
            </button>
        </li>
    @endforeach
</sidebar>
