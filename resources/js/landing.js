
import './script.js';
import './animations/texts-effect.js';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import togglePassword from './toggle-password.js';
import { initPasswordEvaluator, evalPassword } from './forms/eval-password.js';
import InputValidator, { setInvalidMessage, setFormDirty } from './forms/input-validator.js';
import initEmailValidation, { validateEmail } from './forms/email-validation.js';
import PasswordValidator, { DEFAULT_PASSWORD_VALIDATION } from './forms/password-validation.js';
import ConsentValidator from './forms/consent-validation.js';
import initPasswordConfirmValidation, { validateConfirmPassword } from './forms/password-confirm-validation.js';
import ThemeManager, { initPageTheme } from './theme-listener.js';
import debounce from 'debounce-script';
import NameValidator from 'name-validator-script';
import { LAST_NAME_VALIDATION, MIDDLE_NAME_VALIDATION, FIRST_NAME_VALIDATION } from 'name-validate-rule';
// import './livewire.js'



initPageTheme(window.ThemeManager);


// Scene setup
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });

// Set renderer size to match hero section
const heroSection = document.querySelector('.hero').getBoundingClientRect();
renderer.setSize(window.innerWidth, heroSection.height);
document.querySelector('.content').prepend(renderer.domElement);

// Colors
const colors = {
    green: 0x61b000,
    blue: 0x006390,
    yellow: 0xeeee13
};

// Modified particle system to spread across the entire width
function createParticleSystem(color, size, count, spread) {
    const particles = new THREE.BufferGeometry();
    const positions = new Float32Array(count * 3);
    const velocities = new Float32Array(count * 3);

    for (let i = 0; i < count * 3; i += 3) {
        positions[i] = (Math.random() - 0.5) * spread;
        positions[i + 1] = (Math.random() - 0.5) * spread;
        positions[i + 2] = (Math.random() - 0.5) * spread;

        velocities[i] = (Math.random() - 0.5) * 0.02;
        velocities[i + 1] = -Math.random() * 0.02 - 0.01;
        velocities[i + 2] = (Math.random() - 0.5) * 0.02;
    }

    particles.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    particles.velocities = velocities;

    const material = new THREE.PointsMaterial({
        color: color,
        size: size,
        transparent: true,
        opacity: 0.7,
        blending: THREE.AdditiveBlending
    });

    return new THREE.Points(particles, material);
}

// Modified ring creation to concentrate on the left side
function createRing(radius, tubeRadius, color) {
    const geometry = new THREE.TorusGeometry(radius, tubeRadius, 16, 100);
    const material = new THREE.MeshPhongMaterial({
        color: color,
        transparent: true,
        opacity: 0.7
    });
    const ring = new THREE.Mesh(geometry, material);
    // Offset rings to the left side
    ring.position.x = -5;
    return ring;
}

// Add particle systems
const particleSystem1 = createParticleSystem(colors.green, 0.05, 1000, 20);
const particleSystem2 = createParticleSystem(colors.yellow, 0.03, 1500, 20);
scene.add(particleSystem1);
scene.add(particleSystem2);

// Add floating rings
const rings = [];
const numRings = 5;
for (let i = 0; i < numRings; i++) {
    const ring = createRing(2 + i * 0.5, 0.05, i % 2 === 0 ? colors.blue : colors.green);
    ring.rotation.x = Math.random() * Math.PI;
    ring.rotation.y = Math.random() * Math.PI;
    rings.push(ring);
    scene.add(ring);
}

// Lighting
const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
scene.add(ambientLight);

const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
directionalLight.position.set(5, 5, 5);
scene.add(directionalLight);

camera.position.z = 10;

// Modified mouse interaction to only affect the left side
let mouseX = 0;
let mouseY = 0;
document.addEventListener('mousemove', (event) => {
    const halfWidth = window.innerWidth / 2;
    if (event.clientX < halfWidth) {
        mouseX = (event.clientX / halfWidth) * 2 - 1;
        mouseY = -(event.clientY / window.innerHeight) * 2 + 1;
    }
});

// Animation
function animate() {
    requestAnimationFrame(animate);

    // Animate particles
    [particleSystem1, particleSystem2].forEach(particles => {
        const positions = particles.geometry.attributes.position.array;
        const velocities = particles.geometry.velocities;

        for (let i = 0; i < positions.length; i += 3) {
            positions[i] += velocities[i];
            positions[i + 1] += velocities[i + 1];
            positions[i + 2] += velocities[i + 2];

            if (positions[i + 1] < -10) positions[i + 1] = 10;
            if (Math.abs(positions[i]) > 10) positions[i] *= -0.9;
            if (Math.abs(positions[i + 2]) > 10) positions[i + 2] *= -0.9;
        }
        particles.geometry.attributes.position.needsUpdate = true;
    });

    // Animate rings
    rings.forEach((ring, i) => {
        ring.rotation.x += 0.002 * (i + 1);
        ring.rotation.y += 0.003 * (i + 1);
        ring.scale.x = ring.scale.y = 1 + Math.sin(Date.now() * 0.001 + i) * 0.1;
    });

    camera.position.x += (mouseX * 3 - camera.position.x) * 0.05;
    camera.position.y += (mouseY * 3 - camera.position.y) * 0.05;
    camera.lookAt(scene.position);

    renderer.render(scene, camera);
}

// Handle resize
window.addEventListener('resize', () => {
    const heroSection = document.querySelector('.hero').getBoundingClientRect();
    camera.aspect = window.innerWidth / heroSection.height;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, heroSection.height);
});

animate();
