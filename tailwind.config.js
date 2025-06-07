const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'media',

  presets: [
    require("./vendor/wireui/wireui/tailwind.config.js")
  ],

  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    "./vendor/wireui/wireui/src/*.php",
    "./vendor/wireui/wireui/ts/**/*.ts",
    "./vendor/wireui/wireui/src/WireUi/**/*.php",
    "./vendor/wireui/wireui/src/Components/**/*.php",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['"Instrument Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      colors: {
        primary: colors.green,
        secondary: colors.slate,
      }
    },
  },

  plugins: [],
}
