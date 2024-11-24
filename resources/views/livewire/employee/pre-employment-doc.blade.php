@php
    use App\Enums\PreEmploymentReqStatus;
    $doc_parts = explode('(', $pre_employment_req->preemp_req_name, 2);
    $doc_name = trim($doc_parts[0]);
    $doc_hint = isset($doc_parts[1]) ? '(' . rtrim($doc_parts[1], ')') . ')' : null;
    $doc_id = $pre_employment_req->preemp_req_id;

@endphp

{{--
    Livewire class
        file:///./../../../../app/Livewire/Employee/PreEmploymentDoc.php
--}}

<tr class="rounded-2 outline  position-relative" style="height: 100px; vertical-align: middle; " x-data="file_preemp_req('{{ $doc_id }}')"
    x-bind:class="dropingFile ? 'bg-light border-primary' : 'border-secondary'"
    x-bind:style="hasError ? { '--bs-table-border-color': 'var(--bs-danger)' } : { '--bs-table-border-color': outlineColor }"
    x-on:drop.prevent="handleFileDrop($event)" x-on:dragover.prevent="dropingFile = true"
    x-on:dragleave.prevent="dropingFile = false" x-on:livewire-upload-progress="updateProgress"
    x-on:livewire-upload-start="onUpload" x-on:livewire-upload-error="hasError = true"
    x-bind:data-upload-progress="progress">

    <td>
        <div class="fw-bold">{{ $doc_name }}
            @isset($doc_hint)
                <div class="small">{{ $doc_hint }}</div>
            @endisset
        </div>
    </td>

    <td>
        <x-status-badge x-ref="statusBadge"
            color="{{ $errors->has('preemp_file') ? 'danger' : '' }}">{{ $errors->has('preemp_file') ? PreEmploymentReqStatus::INVALID : '' }}</x-status-badge>
    </td>

    <td>
        <button type="button" data-bs-toggle="modal" data-bs-target="#preemp-doc-{{ $doc_id }}-attachment"
            class="btn bg-transparent border-0 text-decoration-underline text-body text-capitalize text-nowrap"
            x-bind:class="preemp_file ? '' : 'opacity-50'">
            View Attachment
        </button>
    </td>

    <td>
        <button type="button" class="btn btn-primary" @click="openFilePicker">
            <i class="icon p-1 d-inline" data-lucide="plus-circle"></i> Upload
        </button>
        <form wire:submit.prevent="save" x-ref="uploadForm">
            <input type="file" wire:model="preemp_file" hidden x-ref="fileInput" @change="handleFileChange"
                wire:ignore />
        </form>
    </td>


</tr>

@error('preemp_file')
    @script
        <script>
            $dispatch('preemp-file-error', {
                docId: {{ $doc_id }},
                message: '{{ $message }}',
            });
        </script>
    @endscript
@enderror
