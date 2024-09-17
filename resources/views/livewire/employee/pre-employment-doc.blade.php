@php
    $doc_parts = explode('(', $pre_employment_doc->document_name, 2);
    $doc_name = trim($doc_parts[0]);
    $doc_hint = isset($doc_parts[1]) ? '(' . rtrim($doc_parts[1], ')') . ')' : null;
    $doc_id = $pre_employment_doc->document_control_id;
@endphp

<tr class="rounded-2 outline dropzone position-relative" style="height: 100px; vertical-align: middle;"
    {{--  wire:key="{{ $pre_employment_doc->document_control_id }}" --}} id="preemp-doc-{{ $doc_id }}" wire:ignore>
    <form action="" wire:model="document_id" name="{{ $doc_id }}">
        <td class="dz-message">
            @csrf
            <div class="fw-bold">{{ $doc_name }}

                @isset($doc_hint)
                    <div class="small">
                        {{ $doc_hint }}
                    </div>
                @endisset
            </div>
        </td>
        <td class="dz-message">
            <x-status-badge color="danger">Invalid</x-status-badge>
        </td>
        <td class="dz-message"><button type="button" data-bs-toggle="modal"
                data-bs-target="#preemp-doc-{{ $doc_id }}-attachment"
                class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                Attachment</button></td>
        <td class="dz-message">
            <button type="button" class="btn btn-primary"> <i class="icon p-1  d-inline" data-lucide="plus-circle"></i>
                Upload</button>
        </td>
    </form>

    <div class="modal fade" id="preemp-doc-{{ $doc_id }}-attachment" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-fullscreen-sm-down modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class=" dropzone-previews dropzone" id="preemp-doc-{{ $doc_id }}-preview">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</tr>

@script
    <script>
        let preEmpDoc{{ $doc_id }} = new Dropzone(
            "#preemp-doc-{{ $doc_id }}", {
                url: "/preemploy",
                withCredentials: true,
                paramName: "pre_emp_doc",
                maxFilesize: 2, // MB
                // autoQueue: false,
                autoProcessQueue: false,
                // createImageThumbnails: false,
                clickable: '#preemp-doc-{{ $doc_id }} .btn.btn-primary',
                // acceptedFiles: 'application/pdf',
                // disablePreviews: true,
                parallelUploads: 1,
                maxFiles: 1,
                thumbnailWidth: null,
                thumbnailHeight: null,
                thumbnailMethod: 'contain',
                // previewTemplate: , /* Customize preview element look */
                previewsContainer: '#preemp-doc-{{ $doc_id }}-preview',
                addRemoveLinks: true,
                maxfilesexceeded: function(file) {
                    this.removeFile(this.files[0])
                    console.log(file);
                },
                maxfilesreached: function(file) {
                    // this.removeFile(this.files[0])
                    console.log(file);
                },
                addedfiles: function(file) {
                    let modalSelector = `#preemp-doc-{{ $doc_id }}-attachment`;

                    console.log(file)
                    $(`${modalSelector} button.submit`).off('click');
                    $(`${modalSelector} button.submit`).on('click', () => {
                        this.processQueue(file);
                    });

                    const previewModal = bootstrap.Modal.getOrCreateInstance(modalSelector);
                    previewModal.show();
                    // alert(file)
                },
                sending: function(file, xhr, formData) {

                    formData.append("doc_id", {{ $doc_id }});
                },
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                // uploadprogress: function(file, progress, bytesSent) { /* Ovverrides default handler  https://github.com/dropzone/dropzone/blob/main/src/options.js#L574 */
                //     this.element.style.setProperty('--data-upload-progress', `${progress}%`);
                //     this.element.setAttribute('data-upload-progress', `${progress}`);

                //     console.log(this.element)
                //     console.log(file)
                //     console.log(progress)
                // },
                totaluploadprogress: function(progress, totalBytesSent) {
                    this.element.style.setProperty('--data-upload-progress', `${progress}%`);
                    this.element.setAttribute('data-upload-progress', `${progress}`);
                    console.log('totaluploadprogress')
                    console.log(progress)
                    console.log(totalBytesSent)

                },
                queuecomplete: function() {
                    console.log('complete')

                },
                errormultiple: function() {

                    console.log('error')

                },
                successmultiple: function() {

                    console.log('success')
                },


            });
    </script>
@endscript
