const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.{php,js,ts,vue}',
  ],
  theme: {
    extend: {
      fontFamily: {
        display: ['Gabriela', ...defaultTheme.fontFamily.serif],
        sans: ['"Open Sans"', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
