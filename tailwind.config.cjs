import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // ضفتلك الألوان اللي استخدمناها بالديزاين عشان يتعرف عليها أوفلاين
        mainDark: '#1E293B',
        goldCustom: '#A18C2C',
        bgGold: '#F6DCBD',
      }
    },
  },
  plugins: [],
}
