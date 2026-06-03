/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './downloads/zidooka-tw/**/*.php',
    './downloads/picostrap5-child-base/**/*.php',
    './zidooka_template/**/*.php',
    './downloads/zidooka-tw/assets/**/*.js',
  ],
  theme: {
    extend: {},
  },
  corePlugins: {
    preflight: false,
  },
  plugins: [],
};
