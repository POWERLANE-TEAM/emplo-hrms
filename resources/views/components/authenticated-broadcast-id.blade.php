<script nonce="{{ $nonce }}">
@php
if (Auth::check()) {
    $user_session = session()->getId();
    $authBroadcastId = hash('sha512', $user_session . Auth::user()->email . $user_session);
@endphp

    @once
        var AUTH_BROADCAST_ID = "{{ $authBroadcastId }}";
    @endonce

@php
}
@endphp

</script>
