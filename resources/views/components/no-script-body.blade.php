<noscript>
    <style nonce="{{ $nonce }}">
        .no-script {
            width: 100%;
            width: 100svw;
            height: calc(3rem + 1vw + 2vh);
            font-size: calc(1rem + 0.1vw + 0.2vh);
            z-index: 10000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .top-vector {
            z-index: -1;
        }

        div:has(.btn-close) {
            top: calc(0.25rem + 0.5vw);
            bottom: 0;
            right: 3rem;
        }
    </style>
    <section class="no-script alert alert-info  alert-dismissible px-5 ,-0 position-relative" aria-live="polite"
        aria-label="JavaScript Disabled Notice">
        <div>
            For the best experience, please enable JavaScript in your browser settings.
        </div>
        <div class=" position-absolute">
            <button type="button" class="btn-close " data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    </section>
</noscript>
