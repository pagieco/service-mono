import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './state/store';

// Dont warn about using the dev version of Vue in development.
Vue.config.productionTip = process.env.NODE_ENV === 'production';

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app');
