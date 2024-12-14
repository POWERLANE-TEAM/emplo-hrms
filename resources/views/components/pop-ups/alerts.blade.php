<!-- resources/views/components/alerts/toasts.blade.php -->

<div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
    <i data-lucide="{{ $icon }}" class="me-2"></i> <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
