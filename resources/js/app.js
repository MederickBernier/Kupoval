import Swiper from 'swiper';
import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

const items = document.querySelectorAll('.carousel-item');
const next = document.getElementById('next');
const prev = document.getElementById('prev');
let current = 0;

function updateCarousel(index) {
    items.forEach((item, i) => {
        item.classList.toggle('hidden', i !== index);
    });
}

next.addEventListener('click', () => {
    current = (current + 1) % items.length;
    updateCarousel(current);
});

prev.addEventListener('click', () => {
    current = (current - 1 + items.length) % items.length;
    updateCarousel(current);
});

// Auto-slide every 5 seconds
setInterval(() => {
    current = (current + 1) % items.length;
    updateCarousel(current);
}, 5000);
