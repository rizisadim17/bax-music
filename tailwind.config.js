/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig', // Add all the directories containing your Twig files
    './assets/**/*.js',          // Add JS files in assets
    './assets/**/*.css',         // Add CSS files in assets
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
