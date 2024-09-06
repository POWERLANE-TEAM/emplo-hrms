<!-- Google one-tap login -->
<div id="g_id_onload"
    data-client_id="{{ config('services.google.client_id') }}"
    data-login_uri="{{ config('services.google.one_tap_redirect') }}"
    {{-- data-_token="{{ csrf_token() }}" --}}
    data-cancel_on_tap_outside="false"
    {{-- data-debug ="true" --}}
>
</div>    

<script src="https://accounts.google.com/gsi/client" async defer></script>