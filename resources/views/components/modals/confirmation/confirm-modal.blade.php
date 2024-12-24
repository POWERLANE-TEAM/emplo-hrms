@props(['label', 'header', 'message', 'type', 'actionButtonTitle' => 'Confirm'])

<div class="modal fade" {{ $attributes->merge(['class' => '',]) }} tabindex="-1" aria-label="{{ $label }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content pt-md-5 pl-md-5 pr-md-5 pb-md-4">
            <div class="modal-body pt-0 mt-md-n3">
                <div class="d-flex justify-content-center flex-column">
                    <div class="mx-auto">
                        @if($type === 'delete')
                        <img class="img-size-100 img-responsive"
                        src="{{ Vite::asset('resources/images/illus/statuses/trashbin.png') }}" alt="">
                        @else
                        <img class="img-size-100 img-responsive"
                        src="{{ Vite::asset('resources/images/illus/statuses/caution.png') }}" alt="">
                        @endif
                    </div>
                    <hgroup class="mx-auto fw-medium pt-3 pb-4" role="alert">
                        <div
                            class="fs-2 fw-bold text-center {{ $type === 'delete' ? 'text-danger' : 'text-warning' }} mb-md-3">
                            {{ $header }}
                        </div>
                        <div class="fs-5 mb-md-2 text-center">{{ $message }}</div>
                    </hgroup>

                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        <button onclick="" class="btn {{ $type === 'delete' ? 'btn-danger' : 'btn-warning' }}">{{ $actionButtonTitle }}</button>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<style>
    .modal-lg {
        max-width: 40%;
    }

    @media (max-width: 768px) {
        .modal-lg {
            max-width: 100% !important;
        }

        .modal-body hgroup {
            padding: 1rem 0rem;
        }

        .modal-content .px-md-3 {
            padding: 1rem 1rem 0rem 0rem;
        }
    }

    @media (max-width: 1024px) {
        .modal-lg {
            max-width: 90%;
        }
    }
</style>