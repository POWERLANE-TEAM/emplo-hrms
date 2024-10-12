@use(App\Enums\PreEmploymentReqStatus as EnumsPreEmploymentReqStatus)
<section class="">
    <div class="bg-body-secondary d-flex flex-md-nowrap border-0 rounded-3 px-5 py-4">
        <span class="fw-bold fs-5">
            <span class=" text-primary">
                11
                <span>requirements</span>
            </span>
            <span>
                out of
                <span>
                    18
                </span>
                submitted copy
            </span>
        </span>

        <div class="ms-md-auto d-none d-sm-block" wire:ignore>
            <button class=" bg-transparent border-0"><i class="icon p-1 d-inline text-info"
                    data-lucide="arrow-down-wide-narrow"></i>Sort By</button>
            <button class="bg-transparent border-0"><i class="icon p-1 d-inline text-info"
                    data-lucide="list-filter"></i>Filters</button>
        </div>
        <div class="dropdown px-2 d-none d-sm-inline-block" wire:ignore>
            <button class=" bg-transparent border-0 dropdown-toggle d-flex align-content-center"
                data-bs-toggle="dropdown">
                <i class="icon p-1 d-inline text-info" data-lucide="download"></i>
                Download as
            </button>
            <ul class="dropdown-menu">
                <li class="dropdown-item">PDF</li>

            </ul>
        </div>
    </div>

    <table class="table table-hover table-borderless text-center position-relative "
        aria-label="Pre-employment documents" aria-description="List of Pre-employment documents to be submitted.">
        <thead wire:ignore>
            <tr>
                <th class="col-5"><i class="icon p-1 d-inline text-primary" data-lucide="file-text"></i>Requirement
                </th>
                <th class="col-1"><i class="icon p-1 d-inline text-primary" data-lucide="check-circle"></i>Status
                </th>
                <th class="col-3"><i class="icon p-1 d-inline text-primary" data-lucide="paperclip"></i>Attachment
                </th>
                <th class="col-3"><i class="icon p-1 d-inline text-primary" data-lucide="upload"></i>Upload</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($pre_employment_reqs as $pre_employment_req)
                @livewire('employee.pre-employment-doc', ['pre_employment_req' => $pre_employment_req], key('preemp_req-' . $pre_employment_req->preemp_req_id))
                {{-- @livewire('employee.pre-employment-modal', ['pre_employment_req' => $pre_employment_req], key('preemp_req_modal-' . $pre_employment_req->preemp_req_id)) --}}

                @php
                    $doc_id = $pre_employment_req->preemp_req_id;
                    $doc_name = $pre_employment_req->preemp_req_name;
                @endphp

                <div x-data="modal_preemp_req('{{ $doc_id }}')" wire:ignore.self {{-- Pota wire:ignore lng pla fix sa bug --}} class="modal fade"
                    id="preemp-doc-{{ $doc_id }}-attachment" tabindex="-1" aria-labelledby="modalLabel"
                    aria-hidden="true">


                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-6" id="modalLabel">File Preview for
                                    <small>{{ $doc_name }}</small>
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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
                                <button type="button" class="btn btn-primary submit"
                                    @click="submitFile()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <tr class="opacity-0" x-data="{ isVisible: true }" x-show="isVisible"
                x-on:pre-employment-docs-loaded.window="$nextTick(() => { isVisible = false })"
                x-intersect.full="$wire.loadMore()">
                <td colspan="4">
                </td>
            </tr>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('file_preemp_req', (docId) => ({
                        dropingFile: false,
                        preemp_file: null,
                        docId: docId,
                        uuid: null,
                        progress: 0,
                        hasError: false,
                        outlineColor: null,

                        onUpload() {
                            this.hasError = false;
                        },

                        updateProgress(event) {
                            this.progress = Math.min(event.detail.progress);
                            console.log(this.$el)
                            // if (CSS && CSS.registerProperty) {
                            //     CSS.registerProperty({
                            //         name: '--data-upload-progress',
                            //         syntax: '<percentage>',
                            //         inherits: false,
                            //         initialValue: '0%'
                            //     });
                            // }
                            this.$el.style.setProperty('--data-upload-progress', `${this.progress}%`);

                        },

                        handleUploadErorr(event) {
                            if (event.detail[0].docId == this.docId) {
                                this.hasError = true;
                                this.$el.style.setProperty('--bs-table-border-color', `var(--bs-danger)`);
                                this.$dispatch('preemp-file-error', {
                                    docId: this.docId,
                                    message: event.detail[0].message,
                                });
                            }
                        },

                        // Handle file drop event
                        handleFileDrop(event) {
                            this.dropingFile = false; // Reset drag state
                            if (event.dataTransfer.files.length > 0) {
                                this.queueFile(event.dataTransfer.files[0]); // Queue only the first file
                            }
                        },

                        // Handle file selection via file input
                        handleFileChange(event) {
                            this.queueFile(this.$refs.fileInput.files[0]); // Queue only the first file
                        },

                        // Open file picker
                        openFilePicker() {
                            this.$refs.fileInput.click(); // Trigger file input
                        },

                        // Queue a single file for preview
                        queueFile(file) {
                            this.preemp_file = file; // Replace any previously queued file with the new one
                            this.$nextTick(() => {
                                console.log(this.$refs.statusBadge)
                                const classList = this.$refs.statusBadge.classList;
                                [...classList].forEach(className => {
                                    if (className.startsWith('bg-') || className.startsWith(
                                            'text-')) {
                                        classList.remove(className);
                                    }
                                });
                                this.$refs.statusBadge.classList.add('bg-orange-subtle', 'text-orange');
                                this.$refs.statusBadge.textContent =
                                    {{ EnumsPreEmploymentReqStatus::PENDING->label() }};
                            });
                            console.log('Queued file:', this.preemp_file);

                            // Emit custom event with queued file information
                            this.$dispatch('file-queued', {
                                file: this.preemp_file,
                                docId: this.docId
                            }, {
                                bubbles: true
                            });
                        },

                        handleSubmitFile(event) {
                            if (event.detail.docId == this.docId) {
                                this.progress = 0;
                                this.$el.style.setProperty('--data-upload-progress', `${this.progress}%`);

                                if (this.preemp_file.type === 'application/pdf') {

                                    this.$refs.uploadForm.dispatchEvent(new Event('submit', {
                                        bubbles: true,
                                        cancelable: true
                                    }));

                                }
                            }
                        },

                        init() {
                            this.uuid = this.$el.getAttribute('wire:id');
                            this.outlineColor = getComputedStyle(this.$el).getPropertyValue(
                                '--bs-table-border-color');
                            window.addEventListener('submit-preemp-file', this.handleSubmitFile.bind(this));
                            window.addEventListener(`${this.uuid}:uploadError`, this.handleUploadErorr.bind(
                                this));

                        }
                    }));

                    Alpine.data('modal_preemp_req', (docId) => ({
                        preemp_file: null,
                        docId: docId,
                        errorFeedback: null,

                        handleFileQueued(event) {
                            this.errorFeedback = null;
                            if (event.detail.docId === this.docId) {

                                this.preemp_file = event.detail.file;

                                if (this.preemp_file.type === 'application/pdf') {
                                    const fileURL = URL.createObjectURL(this.preemp_file);
                                    let modalSelector = `#preemp-doc-${this.docId}-attachment`;

                                    // Show the corresponding modal
                                    const previewModal = bootstrap.Modal.getOrCreateInstance(modalSelector);
                                    previewModal.show();

                                    // Update iframe source after modal is shown
                                    this.$nextTick(() => {
                                        let iframe = document.querySelector(`${modalSelector} iframe`);
                                        if (iframe) {
                                            iframe.src = fileURL;
                                        }
                                    });
                                }
                            }
                        },

                        submitFile() {

                            this.$dispatch('submit-preemp-file', {
                                docId: this.docId
                            }, {
                                bubbles: true
                            });
                        },

                        handleUploadErorr(event) {
                            console.log(event.detail)
                            if (event.detail.docId == this.docId) {
                                if (event.detail.message) {
                                    this.errorFeedback = event.detail.message
                                } else {
                                    this.errorFeedback = ''
                                }
                            }
                        },

                        // Initialize the component and register event listener
                        init() {
                            window.addEventListener('file-queued', this.handleFileQueued.bind(this));
                            window.addEventListener(`preemp-file-error`, this.handleUploadErorr.bind(
                                this));
                        }

                    }));
                });
            </script>

        </tbody>

        <tbody class="text-start">
            <tr class="no-hover">
                <td colspan="4">
                    <section class="d-flex  px-4">
                        <div class="text-primary text-wrap ">For diplomas(s), please select
                            attainment:
                        </div>
                        <div class=" ms-auto d-flex gap-4 text-nowrap align-items-center">
                            <div class="position-relative d-flex">
                                <input type="checkbox" id="cbox-diploma-highschool" name=""
                                    class="checkbox checkbox-primary">
                                <label for="cbox-diploma-highschool" class="checkbox-label">JR./SR./HS</label>
                            </div>
                            <div class="position-relative d-inline-flex">
                                <input type="checkbox" id="cbox-diploma-undergrad" name=""
                                    class="checkbox checkbox-primary">
                                <label for="cbox-diploma-undergrad" class="checkbox-label">College Undergrad</label>
                            </div>
                            <div class="position-relative d-inline-flex">

                                <input type="checkbox" id="cbox-diploma-voc-grad" name=""
                                    class="checkbox checkbox-primary">
                                <label for="cbox-diploma-voc-grad" class="checkbox-label">Vocational Grad</label>
                            </div>
                            <div class="position-relative d-inline-flex">
                                <input type="checkbox" id="cbox-diploma-graduate" name=""
                                    class="checkbox checkbox-primary">
                                <label for="cbox-diploma-graduate" class="checkbox-label">College Graduate</label>
                            </div>
                        </div>
                    </section>
                </td>
            </tr>
        </tbody>

        <tbody>
            <tr class="border-2 rounded-2 outline" style="height: 100px; vertical-align: middle;">
                <td class="">
                    <div class="fw-bold">Document</div>
                </td>
                <td>
                    <x-status-badge color="danger">Invalid</x-status-badge>
                </td>
                <td><button class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                        Attachment</button></td>
                <td><button class="btn btn-primary">Upload</button></td>
            </tr>

            <tr class="border-2 rounded-2 outline" style="height: 100px; vertical-align: middle;">
                <td class="">
                    <div class="fw-bold">Document</div>
                </td>
                <td>
                    <x-status-badge color="danger">Invalid</x-status-badge>
                </td>
                <td><button class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                        Attachment</button></td>
                <td><button class="btn btn-primary">Upload</button></td>
            </tr>
        </tbody>

    </table>
</section>
