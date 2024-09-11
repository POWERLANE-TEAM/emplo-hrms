document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const totalSteps = 3;

    const formSteps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.step');
    const nextBtns = document.querySelectorAll('.next-btn');
    const prevBtns = document.querySelectorAll('.prev-btn');

    function updateProgress() {
        progressSteps.forEach((step, index) => {
            step.classList.remove('active');
            if (index + 1 === currentStep) {
                step.classList.add('active');
            }
        });
    }

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep < totalSteps) {
                formSteps[currentStep - 1].classList.remove('active');
                formSteps[currentStep].classList.add('active');
                currentStep++;
                updateProgress();
            }
        });
    });

    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) {
                formSteps[currentStep - 1].classList.remove('active');
                formSteps[currentStep - 2].classList.add('active');
                currentStep--;
                updateProgress();
            }
        });
    });

    // Initialize progress bar
    updateProgress();
});