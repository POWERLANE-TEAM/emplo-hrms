<section class="top-vector z-n1" draggable="false" ondragstart="return;">
    <picture class="green-wave-container">
        <source media="(min-width:2560px)"
            srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xl.webp') }}">
        <source media="(min-width:1200px)"
            srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-lg.webp') }}">
        <source media="(min-width:768px)"
            srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.webp') }}">
        <source media="(min-width:576px)"
            srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-sm.webp') }}">

        <img class="green-wave no-drag"
            src="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.webp') }}" width="600px"
            height="300px" alt="">
    </picture>

    <svg class="left-circle" width="171" height="251" viewBox="0 0 171 251" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
            d="M156 125.5C156 186.527 106.527 236 45.5 236C-15.5275 236 -65 186.527 -65 125.5C-65 64.4725 -15.5275 15 45.5 15C106.527 15 156 64.4725 156 125.5Z"
            stroke="#404040" stroke-opacity="0.05" stroke-width="30" />
    </svg>

    <svg class="right-circle" width="145" height="145" viewBox="0 0 145 145" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
            d="M137.5 72.5C137.5 108.399 108.399 137.5 72.5 137.5C36.6015 137.5 7.5 108.399 7.5 72.5C7.5 36.6015 36.6015 7.5 72.5 7.5C108.399 7.5 137.5 36.6015 137.5 72.5Z"
            stroke="#61B000" stroke-width="15" />
    </svg>
</section>


<style>

.green-wave {
    top: 0;
    left: 0; 
    position: absolute;
    opacity: 0;
    transition: opacity 2s ease-out, transform 2s ease-out;
    width: 100vw;
    max-width: 100vw;
    transform: translateX(100vw);
    will-change: transform, opacity;
}

.left-circle,
.right-circle {
    opacity: 0;
    transform: translateX(-100vw); /* Initially off-screen */
    transition: opacity 2s ease-out, transform 2s ease-out;
}

.animate {
    opacity: 1;
    transform: translateX(0); /* Move to original position */
}

.left-circle {
    transition-delay: 0.6s;
}

.right-circle {
    transition-delay: 0.9s;
}

@keyframes slideInFromRight {
    0% {
        transform: translateX(100vw); /* Start off-screen to the right */
        opacity: 0;
    }
    100% {
        transform: translateX(0); /* Move to original position */
        opacity: 1;
    }
}

</style>

<script>
window.addEventListener('load', () => {

    // Seperate animation of the green wave.
    const greenWave = document.querySelector('.green-wave');
    greenWave.style.transform = 'translateX(100vw)';
    greenWave.style.opacity = '0';

    // Trigger the animation after a brief delay
    setTimeout(() => {
        greenWave.style.transition = 'transform 2s ease-out, opacity 2s ease-out';
        greenWave.style.transform = 'translateX(0)';
        greenWave.style.opacity = '1';
        greenWave.style.animation = 'slideInFromRight 2s forwards';
    }, 10); // Small delay to ensure proper layout rendering. jepoy d


    // Add 'animate' class to the circles to trigger their transition
    const elements = document.querySelectorAll('.left-circle, .right-circle');
    elements.forEach(element => {
        element.classList.add('animate');
    });
});


</script>