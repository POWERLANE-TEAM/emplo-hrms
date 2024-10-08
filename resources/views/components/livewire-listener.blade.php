@php
    $nonce = csp_nonce();
@endphp

<script nonce="{{ $nonce }}">
    document.addEventListener('livewire:init', () => {
        Livewire.hook('request', ({
            fail
        }) => {
            fail(({
                status,
                preventDefault
            }) => {
                if (status === 419) {
                    preventDefault();
                    /* Inert custom page expired propmt */
                    Livewire.navigate('/419')
                }

                @stack('livewire-failed-request-script')
            })
        })
    });
</script>
