import Swiper from 'swiper';
import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

const items = document.querySelectorAll('.carousel-item');
const next = document.getElementById('next');
const prev = document.getElementById('prev');
const indicators = document.querySelectorAll('.carousel-indicator');
let current = 0;

function updateCarousel(index) {
    items.forEach((item, i) => {
        item.classList.toggle('hidden', i !== index);
        indicators[i].classList.toggle('bg-accent', i === index);
        indicators[i].classList.toggle('bg-neutral', i !== index);
    });
}

// Navigation avec les boutons
next.addEventListener('click', () => {
    current = (current + 1) % items.length;
    updateCarousel(current);
});

prev.addEventListener('click', () => {
    current = (current - 1 + items.length) % items.length;
    updateCarousel(current);
});

// Navigation avec les indicateurs
indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
        current = index;
        updateCarousel(current);
    });
});

// Auto-slide toutes les 5 secondes
setInterval(() => {
    current = (current + 1) % items.length;
    updateCarousel(current);
}, 5000);
