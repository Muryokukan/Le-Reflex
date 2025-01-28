/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      fontFamily: {
        'playwrite-in': ['Playwrite IN', 'cursive'],
      },
    },
  },
  plugins: [],
}
