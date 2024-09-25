const mix = require('laravel-mix');
const webpack = require('webpack');
const path = require('path');

mix.js('resources/js/app.js', 'public/js')
    .vue({ version: 2 })
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.webpackConfig({
  resolve: {
    alias: {
      'jquery$': path.resolve(path.join(__dirname, 'node_modules', 'jquery')),
    }
  },
  plugins: [
    new webpack.ProvidePlugin({
      jQuery: 'jquery',
      $: 'jquery',
      'window.jQuery': 'jquery',
    }),
  ],
});

