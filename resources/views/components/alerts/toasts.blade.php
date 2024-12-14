<!-- resources/views/components/alerts/toasts.blade.php -->

<div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
    <i data-lucide="{{ $icon }}"></i> <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
