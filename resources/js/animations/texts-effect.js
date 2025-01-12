document.addEventListener("DOMContentLoaded", function() {
    // Apply animations
    applyTypewriterEffect('.typewriter-text'); // Apply typewriter effect
    applyFadeInEffect('.fadein-text', 1, 1); // Apply fade-in effect with 1s duration and 1s delay
    applySlideUpEffect('.slide-up-text', 2); // Apply slide-up effect with 2s duration
    applyBounceEffect('.bounce-text', 1); // Apply bounce effect with 1s duration
    applyZoomInEffect('.zoom-in-text', 1); // Apply zoom-in effect with 1s duration
    applyTextColorChange('.color-change-text', '#ff0000'); // Apply color change effect
    applyTextRotationEffect('.rotate-text', 2); // Apply text rotation effect with 2s duration
    applyShakeEffect('.shake-text', 0.5, 10); // Apply shake effect with 0.5s duration and 10px shake distance
    applyLetterByLetterFadeInEffect('.letter-by-letter-text'); // Apply letter-by-letter fade-in effect
    applyWiggleEffect('.wiggle-text', 1); // Apply wiggle effect with 1s duration
    applyTextLoop('.text-loop', 1, 0.5);
    applyTypingLoop('.typing-loop', 0.1, 1);
});

function applyTypewriterEffect(targetClass) {
    const elements = document.querySelectorAll(targetClass);

    // Check if elements exist before applying animation
    if (elements.length > 0) {
        elements.forEach(element => {
            element.innerHTML = element.textContent.replace(/./g, "<span>$&</span>");
            gsap.from(`${targetClass} span`, {
                opacity: 0,
                duration: 0.05,
                stagger: 0.1,
            });
        });
    }
}

// Fade-In Effect: Fades in the text from opacity 0 to 1
function applyFadeInEffect(targetClass, duration = 2, delay = 0) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        gsap.from(targetClass, {
            opacity: 0,
            duration: duration,
            delay: delay,
        });
    }
}

// Slide-Up Effect: Slides the text up from an offset and fades it in
function applySlideUpEffect(targetClass, duration = 2, delay = 0) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        gsap.from(targetClass, {
            y: 20,
            opacity: 0,
            duration: duration,
            delay: delay,
        });
    }
}

// Bounce Effect: Bounces the text into view with an easing effect
function applyBounceEffect(targetClass, duration = 1, delay = 0) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        gsap.from(targetClass, {
            scale: 0,
            opacity: 0,
            duration: duration,
            ease: "bounce.out",
            delay: delay,
        });
    }
}

// Zoom-In Effect: Zooms the text from a smaller scale to its original size
function applyZoomInEffect(targetClass, duration = 1, delay = 0) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        gsap.from(targetClass, {
            scale: 0.5,
            opacity: 0,
            duration: duration,
            delay: delay,
        });
    }
}

// Text Color Change: Animates a color change for the text
function applyTextColorChange(targetClass, color, duration = 2, delay = 0) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        gsap.to(targetClass, {
            color: color,
            duration: duration,
            delay: delay,
        });
    }
}

// Text Rotation Effect: Rotates the text from a rotated state
function applyTextRotationEffect(targetClass, duration = 2, delay = 0) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        gsap.from(targetClass, {
            rotation: 180,
            opacity: 0,
            duration: duration,
            delay: delay,
        });
    }
}

// Shake Effect: Makes the text shake (useful for error messages)
function applyShakeEffect(targetClass, duration = 0.5, shakeDistance = 10) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        gsap.fromTo(
            targetClass,
            {
                x: -shakeDistance,
            },
            {
                x: shakeDistance,
                duration: duration,
                ease: "power2.inOut",
                repeat: 5,
                yoyo: true,
            }
        );
    }
}

// Letter-by-Letter Fade-In Effect: Animates the letters one by one
function applyLetterByLetterFadeInEffect(targetClass, duration = 0.05, staggerTime = 0.1) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        elements.forEach(element => {
            element.innerHTML = element.textContent.replace(/./g, "<span>$&</span>");
            gsap.from(`${targetClass} span`, {
                opacity: 0,
                duration: duration,
                stagger: staggerTime,
            });
        });
    }
}

// Wiggle Effect: Makes the text wiggle back and forth
function applyWiggleEffect(targetClass, duration = 1, delay = 0) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        gsap.to(targetClass, {
            rotation: 5,
            x: 5,
            y: 5,
            repeat: -1, // Repeat indefinitely
            yoyo: true,
            duration: duration,
            delay: delay,
            ease: "power1.inOut",
        });
    }
}

function applyTextLoop(targetClass, duration = 2, delay = 0) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        elements.forEach(element => {
            const originalText = element.textContent;
            const texts = [
                originalText,
                "Welcome Back!",
                "Ready to Work?",
                "Let's Get Started!"
            ];
            
            let currentIndex = 0;

            // Create the timeline with initial delay
            const tl = gsap.timeline({
                repeat: -1,
                repeatDelay: 1,
                delay: delay // Add initial delay
            });

            // Function to create animation for each text
            function createTextAnimation() {
                tl.to(element, {
                    duration: duration * 0.3, // Fade out duration
                    opacity: 0,
                    y: -20,
                    ease: "power2.in",
                    onComplete: () => {
                        currentIndex = (currentIndex + 1) % texts.length;
                        element.textContent = texts[currentIndex];
                    }
                })
                .to(element, {
                    duration: duration * 0.3, // Fade in duration
                    opacity: 1,
                    y: 0,
                    ease: "power2.out"
                })
                .to(element, {
                    duration: duration * 0.4, // Display duration
                    ease: "none"
                });
            }

            // Create animations for each text
            texts.forEach(() => {
                createTextAnimation();
            });
        });
    }
}

function applyTypingLoop(targetClass, typingSpeed = 0.1, delayBetweenLoops = 1) {
    const elements = document.querySelectorAll(targetClass);

    if (elements.length > 0) {
        elements.forEach(element => {
            const originalText = element.textContent.trim(); // Get the original text
            let currentIndex = 0;
            let isTyping = true; // State to toggle between typing and backspacing

            // Wrap content and add a cursor
            element.innerHTML = `<span class="text"></span><span class="cursor">|</span>`;
            const textSpan = element.querySelector('.text');
            const cursorSpan = element.querySelector('.cursor');

            // Add blinking animation to the cursor
            gsap.to(cursorSpan, {
                opacity: 0,
                repeat: -1,
                yoyo: true,
                duration: 0.5
            });

            const type = () => {
                if (isTyping) {
                    // Typing forward
                    if (currentIndex < originalText.length) {
                        textSpan.textContent += originalText[currentIndex];
                        currentIndex++;
                        setTimeout(type, typingSpeed * 1000);
                    } else {
                        isTyping = false;
                        setTimeout(type, delayBetweenLoops * 1000); // Delay before backspacing
                    }
                } else {
                    // Backspacing
                    if (currentIndex > 0) {
                        textSpan.textContent = textSpan.textContent.slice(0, -1);
                        currentIndex--;
                        setTimeout(type, typingSpeed * 1000);
                    } else {
                        isTyping = true;
                        setTimeout(type, delayBetweenLoops * 1000); // Delay before typing again
                    }
                }
            };

            type(); // Start the typing loop
        });
    }
}