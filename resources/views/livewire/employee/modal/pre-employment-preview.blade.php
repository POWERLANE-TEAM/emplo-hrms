@php
    $doc_id = $pre_employment_req->preemp_req_id;
    $doc_name = $pre_employment_req->preemp_req_name;
@endphp

{{--
    Livewire class
        file:///./../../../../../app/Livewire/Employee/Modal/PreEmploymentPreview.php
--}}

<div x-data="modal_preemp_req('{{ $doc_id }}')" wire:ignore.self class="modal fade" id="preemp-doc-{{ $doc_id }}-attachment"
    tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">


    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-6" id="modalLabel">File Preview for <small>{{ $doc_name }}</small></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <template x-if="preemp_file">
                    <div>
                        <span x-text="'File name: ' +  preemp_file.name">File name</span>
                        <iframe id="pdfIframe-{{ $doc_id }}" width="100%" height="500px"
                            style="border: none;
       "></iframe>
                    </div>
                </template>
                <template x-if="!preemp_file">
                    <div>No file selected.</div>
                </template>

                <span class="text-danger" x-text="errorFeedback">File name</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit" @click="submitFile()">Submit</button>
            </div>
        </div>
    </div>
</div>
