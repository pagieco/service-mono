import Vue from 'vue';
import VueMeta from 'vue-meta';
import VueRouter from 'vue-router';
import routes from './routes';
import store from '../state/store';

Vue.use(VueRouter);
Vue.use(VueMeta, {
  keyName: 'meta',
});

const router = new VueRouter({
  routes,
  base: 'app',
  mode: 'history',
});

router.beforeEach((routeTo, routeFrom, next) => {
  // Check if auth is required on this route (including nested routes.
  const authRequired = routeTo.matched.some(route => route.meta.authRequired);

  function redirectToLogin() {
    next({ name: 'forbidden', query: { redirectFrom: routeFrom.fullPath } });
  }

  // If auth isn't required for the route, just continue...
  if (!authRequired) {
    return next();
  }

  // If auth is required and the user is logged in...
  if (store.getters['auth/loggedIn']) {
    return store.dispatch('auth/validatePolicy', routeTo.meta.authRequired)
      .then(() => next())
      .catch(() => redirectToLogin());
  }

  // If auth is reuqired and the user is currently not logged in, redirect to login.
  return redirectToLogin();
});

export default router;
