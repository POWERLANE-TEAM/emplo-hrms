@props(['label', 'header', 'message', 'type'])

<div class="modal fade" {{ $attributes->merge(['class' => '',]) }} tabindex="-1" aria-label="{{ $label }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-md-5">
            <div class="px-md-3 py-md-3 ms-auto mt-md-n4 me-md-n4">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 mt-md-n3">
                <div class="d-flex justify-content-center flex-column">
                    <div class="mx-auto">
                        @if($type === 'success')
                            <x-illustration.success />
                        @else
                            <x-illustration.error />
                        @endif
                    </div>
                    <hgroup class="mx-auto fw-medium" role="alert">
                        <div
                            class="fs-2 fw-bold text-center {{ $type === 'success' ? 'text-primary' : 'text-danger' }} mb-md-3">
                            {{ $header }}
                        </div>
                        <span class="fs-5 mb-md-4">{{ $message }}</span>
                    </hgroup>
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