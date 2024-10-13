/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig', // Chemin vers les fichiers Twig
    './assets/**/*.js', // Chemin vers les fichiers JS (s'ils existent)
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
