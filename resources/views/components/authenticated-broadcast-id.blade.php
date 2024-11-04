<script nonce="{{ $nonce }}">
@php
if (Auth::guard()->check()) {
    $user_session = session()->getId();
    $authBroadcastId = hash('sha512', $user_session . Auth::guard()->user()->email . $user_session);
@endphp

    @once
        var AUTH_BROADCAST_ID = "{{ $authBroadcastId }}";
    @endonce

@php
}
@endphp

</script>
