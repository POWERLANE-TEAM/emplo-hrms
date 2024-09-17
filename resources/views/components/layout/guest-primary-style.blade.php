@section('critical-styles')
    <style nonce="{{ csp_nonce() }}" wire:key="guest-primary-internal-style-1">
        section.top-vector {
            position: absolute;
            width: 100vw;
            height: 100vh;
            contain: layout;

            img.green-wave {
                position: absolute;
                min-width: 83svw;
                width: 83svw;
                height: auto;
                left: 17%;
                transform: scaleX(1.30) scaleY(1.1);
            }

            svg.right-circle {
                position: absolute;
                right: calc(-96px + 1vw);
                top: 87vh;
                z-index: -10;
            }

            svg.left-circle {
                position: absolute;
                left: calc(-32px + 1vw);
                top: 47vh;
                z-index: -10;
            }
        }
    </style>
@endsection
