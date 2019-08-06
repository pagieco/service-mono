const mix = require('laravel-mix');

mix.options({ extractVueStyles: 'css/vendor.css' });

mix.js('resources/js/backend/index.js', 'public/js/backend.js')
   .extract(['vue', 'vuex', 'vue-router', 'lodash', '@pagie/focus-ui'])
   .version();
