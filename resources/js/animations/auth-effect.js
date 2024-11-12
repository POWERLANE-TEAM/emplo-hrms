    // JavaScript to add animation on scroll (IntersectionObserver)
    const elements = document.querySelectorAll('.green-wave-container, .left-circle, .right-circle');

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5 // Trigger animation when at least 50% of the element is visible
    });

    elements.forEach(element => {
        observer.observe(element);
    });