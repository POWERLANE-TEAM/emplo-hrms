@use('App\Enums\UserRole')
<section>

    @php
        $profileUploadLabel = <<<HTML
        Drag & Drop or <span class="filepond--label-action"> Browse</span> a Profile Picture
        HTML;

        $accepted = [
            'image' => ['png', 'jpeg', 'webp'],
        ];
    @endphp

    {{-- <div class="container d-flex mx-0 col-12 col-md-6 px-0 pe-md-3 mt-3 mt-md-n1">
            <x-iframe src="{{ $doc['file'] }}" :srcIsUrl="true" id="apply-resume-preview">
            </x-iframe>
    </div>
--}}

    @foreach ($preemploymentDocs as $index => $doc)
        @php
            $req = $preemploymentReqs[$index] ?? null;
        @endphp
        <x-modals.dialog id="preemp-form-{{ $index }}" {{-- x-ref="preemp-form-{{ $index }}" --}}{{-- x-data="{ edit: false, hasFile: {{ $doc['file'] ? 'true' : 'false' }}, doc: {{ json_encode($doc) }} }" --}}>
            <x-slot:title>
                <h1 class="modal-title fs-5">{{ __($req->preemp_req_name) }}</h1>
                <button data-bs-toggle="modal" class="btn-close" aria-label="Close"></button>
            </x-slot:title>
            <x-slot:content>
                <x-filepond::upload id="display-profile" label-idle="{!! $profileUploadLabel !!}"
                    image-validate-size-min-width="160" image-validate-size-min-height="160"
                    image-validate-size-max-width="2160" image-validate-size-max-height="2160"
                    allow-image-validate-size="true" image-validate-size-max-resolution="2160"
                    wire:model="preemploymentDocs.{{ $index }}.file" :accept="$accepted" :allow-image-preview="true"
                    instant-upload="true" :required="true" />
            </x-slot:content>
            <x-slot:footer>

                @if (auth()->user()->hasRole(UserRole::BASIC))
                    {{-- <button class="btn btn-secondary" x-show="!edit && hasFile" @click="edit = true">{{ __('Re-upload') }} </button>
                 <button class="btn btn-secondary-outline" x-show="edit" @click="edit = false">{{ __('Cancel') }} </button> --}}
                    <button class="btn btn-primary" {{-- x-show="edit" --}}
                        {{ when($doc['file'] == $doc['oldFile'] && isset($doc['file']), 'disabled') }}
                        wire:click="save('{{ $index }}')">{{ __('Save') }} </button>
                @endif
            </x-slot:footer>
        </x-modals.dialog>
    @endforeach


</section>
