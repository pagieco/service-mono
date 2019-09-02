const mix = require('laravel-mix');

mix
  .js('resources/js/backend/index.js', 'public/js/backend.js')
  .js('resources/js/editor/index.js', 'public/js/editor.js')
  .extract(['vue', 'vuex', 'vue-router', 'lodash'])
  .sass('resources/sass/backend/index.scss', 'public/css/backend.css')
  .version();
