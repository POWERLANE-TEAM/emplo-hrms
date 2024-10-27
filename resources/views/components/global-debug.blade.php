<script nonce="{{ $nonce }}">
    @env('local')
        console.time("DOMContentLoaded");
        console.time("loading");
        console.time("interactive");
        console.time("complete");
        document.addEventListener("DOMContentLoaded", () => {
            console.timeEnd("DOMContentLoaded");
        });

        document.addEventListener("readystatechange", (event) => {
            if (event.target.readyState === "loading") {

                console.timeEnd("loading");
            } else if (event.target.readyState === "interactive") {

                console.timeEnd("interactive");
            } else if (event.target.readyState === "complete") {

                console.timeEnd("complete");
            }
        });
    @endenv
</script>
