const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      spacing: {
        128: '32rem',
      },
      colors: {
        navbar: {
          DEFAULT: '#049191', // Teal
          hover: '#046869', // Deep Teal
        },
        page: '#dce7e4', // Soft Aqua
        cta: '#e69500', // Softer Orange
        border: '#2e8b57', //Deep Emerald
        accent: '#50c878', //Emerald Green
        link: '#ff6f61', //Soft Coral
        neutral: '#f5f5f5', //Light Gray
        heading: '#003366', //Navy Blue
        body: '#333333', //Charcoal Gray
      },
      boxShadow: {
        emerald: '0 4px 6px rgba(46,139,87,0.1)', //Emerald Shadow
      },
      fontFamily: {
        title: ['Bona Nova SC', 'serif'], // Updated from Lora
        body: ['Signika', 'sans-serif'], // Updated from Open Sans
        accent: ['Old Standard TT', 'serif'], // Updated from Dancing Script
      },
      gradientColorStops: {
        tealToAqua: ['#049191', '#dce7e4'], //Gradient from Teal to Aqua
      },
    },
  },
  plugins: [],
};
