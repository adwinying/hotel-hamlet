const mix = require('laravel-mix');
const path = require('path');

require('laravel-mix-eslint')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
  output: {
    chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
  },
  resolve: {
    alias: {
      '@': path.resolve('resources/js'),
    },
  },
})

mix.ts('resources/js/app.ts', 'public/js')
  .postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
  ])
  .vue()
  .eslint({
    extensions: ['js', 'ts', 'vue'],
    files: ['resources/js/**'],
  });
