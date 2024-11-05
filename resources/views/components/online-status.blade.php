@props(['status'])

@php
    $isOnline = $status === 'online' ? 'bg-success' : 'bg-danger'
@endphp

<div class="position-relative d-inline-block">
  {{ $slot }}
  <span class="{{ $isOnline }} position-absolute top-100 start-100 ms-n1 mt-n1 translate-middle p-1 border border-light rounded-circle">
    <span class="visually-hidden">Active Status</span>
  </span>  
</div>
