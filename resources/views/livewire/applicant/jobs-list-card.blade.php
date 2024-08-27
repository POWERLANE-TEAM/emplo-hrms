<sidebar class="nav nav-tabs col-12 col-md-5 " id="jobs-list" role="tablist">

    @if ($positions->isEmpty())
        No jobs Available
    @endif

    @foreach ($positions as $position)
        <li class="card nav-item ps-0 " role="presentation">
            <button wire:click="$set('showSignUp', {{ $position->position_id }})"
                class="nav-link d-flex flex-row tw-gap-x-6" id="{{ $position->position_id }}-tab" data-bs-toggle="tab"
                data-bs-target="#{{ $position->position_id }}-tab-pane" role="tab">
                <div class="col-4 pt-3 px-2 ">
                    <img src="http://placehold.it/74/74" alt="">
                </div>
                <div class="col-7 text-start">
                    <header>
                        <hgroup>
                            <h4 class="card-title text-black mb-0">{{ $position->title }}</h4>
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
