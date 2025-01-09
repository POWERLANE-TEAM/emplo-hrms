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
                }

                @stack('livewire-failed-request-script')
            })
        })
    });
    document.addEventListener('livewire:navigated', () => {
        try {
            lucide.createIcons();
        } catch (error) {

        }
        @stack('livewire-navigated-script')

    });
</script>
