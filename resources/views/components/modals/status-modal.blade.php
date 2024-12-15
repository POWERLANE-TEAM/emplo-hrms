@props(['label', 'Email sent', 'header' => 'Email Sent', 'message' => 'Please check your inbox.'])

<div class="modal fade" {{ $attributes->merge([
    'class' => '',
]) }} tabindex="-1" aria-label="{{ $label }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-md-5 ">
            <div class="px-md-3 py-md-3 ms-auto mt-md-n4 me-md-n4">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 mt-md-n3 ">
                <div class="d-flex justify-content-center flex-column gap-3">
                    <div class="">

                        @isset($illustration)
                            {{ $illustration }}

                        @endisset
                    </div>
                    <hgroup class=" fw-medium" role="alert">
                        <div class="fs-2 fw-bold text-center text-primary mb-md-3">
                            {{ $header }}
                        </div>
                        <span class="fs-5 mb-md-4">{{ $message }}</span>

                    </hgroup>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal {
            width: 50% !important;
        }
    </style>

</div>