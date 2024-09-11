<!-- Google one-tap login -->
<div id="g_id_onload"
    data-client_id="{{ config('services.google.client_id') }}"
    data-login_uri="{{ config('services.google.one_tap_redirect') }}"
    data-context="signup"
    data-_token="{{ csrf_token() }}"
    data-auto_prompt="true"
    data-auto_select="true"
    data-itp_support="true"
    {{-- data-use_fedcm_for_prompt="true" --}}
    data-cancel_on_tap_outside="true"
>
</div>    

<script src="https://accounts.google.com/gsi/client" async defer></script>