import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

new Glide('.glide', {
  type: 'carousel',
  perView: 1,
  focusAt: 'center',
  gap: 20,
  autoplay: 5000,
  hoverpause: true,
}).mount();
