const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
  content: [
    './storage/framework/views/*.php',
    './resources/**/*.{blade.php,js,ts,vue}',
  ],
  theme: {
    extend: {
      colors: {
        cyan: colors.cyan,
      },
    },
    fontFamily: {
      display: ['Gabriela', ...defaultTheme.fontFamily.serif],
      sans: ['"Open Sans"', ...defaultTheme.fontFamily.sans],
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
