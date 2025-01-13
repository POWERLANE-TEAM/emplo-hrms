@php
    $accepted = [
        'application' => ['pdf'],
    ];
    $uploadLabel = <<<HTML
    Drag & Drop or <span class="filepond--label-action"> Browse</span> your resignation letter.
    HTML;
@endphp

<section class="mx-4">
    <form action=""  wire:submit.prevent="save">

        <div class="mt-1 pt-1 pb-3 py-4 w-100">
            <div class="filepond-wrapper custom-filepond-style">
                {{-- <input multiple type="file" class="filepond" name="file" accept="application/pdf"> --}}
                <!-- BACK-END REPLACE: Replace thiS input with the x-filepond::upload component. Use search -->

                <div class="">
                    <x-filepond::upload id="display-profile" label-idle="{!! $uploadLabel !!}"
                        wire:model="resignationFile" :accept="$accepted" instant-upload="true" :required="true" />
                    @include('components.form.input-feedback', [
                        'feedback_id' => 'display-profile-feedback',
                        'message' => $errors->first('displayProfile'),
                    ])
                </div>


            </div>
        </div>

        <!-- REPLACE STATIC PAGE LINK: Separation Process Policy & Company Policies -->
        <div class="pt-2 px-3">
            <x-info_panels.note :note="'Ensure your resignation letter is complete before submission. Review the
                                    <a href=\'#\' class=\'text-link-blue hover-opacity\'>separation process</a> and
                                    <a href=\'#\' class=\'text-link-blue hover-opacity\'>company policies</a>
                                    to understand the legal implications of separation. Once submitted, this will initiate the resignation process.'" />
        </div>

        <div class="pt-4  d-flex align-items-center text-end">
            <x-buttons.main-btn label="File Resignation Letter" :nonce="$nonce"
                :disabled="false" class="w-25" :loading="'Submitting...'" />
        </div>
    </form>
</section>
