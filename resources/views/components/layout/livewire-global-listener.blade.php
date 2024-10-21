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
                    // preventDefault();
                    /* Inert custom page expired propmt */

                }

                @stack('livewire-failed-request-script')
            })
        })

        Livewire.hook('morph.updated', ({
            el,
            component
        }) => {
            setTimeout(() => {
                try {

                    lucide.createIcons();

                    @stack('livewire-component-update-script')
                } catch (error) {
                    console.log(error)
                }
            }, 0);
        })
    });
</script>
