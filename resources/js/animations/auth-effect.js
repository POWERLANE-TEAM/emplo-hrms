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
