import { clearAccessToken } from '../auth';
import auth from './middleware/auth';
import Dashboard from './views/Dashboard.vue';
import SignIn from './views/SignIn.vue';

export default [
  {
    path: '/',
    name: 'dashboard',
    component: Dashboard,
    meta: {
      middleware: [auth],
    },
  },
  {
    path: '/sign-in',
    name: 'sign-in',
    component: SignIn,
  },
  {
    path: '/sign-out',
    name: 'sign-out',
    beforeEnter: (to, from, next) => {
      clearAccessToken();
      next({ name: 'sign-in' });
    },
  },
  {
    path: '/404',
    name: '404',
    // Allows props to be passed to the 404 page through route
    // params, such as `resource` to define what wasn't found.
    props: true,
  },
  // Redirect any unmatched routes to the 404 page. This may
  // require some server configuration to work in production:
  // https://router.vuejs.org/en/essentials/history-mode.html#example-server-configurations
  {
    path: '*',
    redirect: '404',
  },
];
