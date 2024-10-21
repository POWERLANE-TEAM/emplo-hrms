@php

    $use_guard = \App\Http\Helpers\ChooseGuard::getByRequest();
@endphp

<script nonce="{{ $nonce }}">
@php
if (Auth::guard($use_guard)->check()) {
    $user_session = session()->getId();
    $auth_broadcast_id = hash('sha512', $user_session . Auth::guard($use_guard)->user()->email . $user_session);
@endphp

    @once
        var AUTH_BROADCAST_ID = "{{ $auth_broadcast_id }}";
    @endonce

@php
}
@endphp

</script>
