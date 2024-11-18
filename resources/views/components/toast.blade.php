@props(['id', 'status'])

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="{{ $id }}" class="toast text-white {{ $status ?? 'text-bg-success' }} top-25 end-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            {{ $content }}
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>