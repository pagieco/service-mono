import Vue from 'vue';
import VueMeta from 'vue-meta';
import VueRouter from 'vue-router';
import routes from './routes';
import middlewareFactory from './middleware/middleware-factory';

Vue.use(VueRouter);
Vue.use(VueMeta, {
  keyName: 'meta',
});

const router = new VueRouter({
  routes,
  base: 'app',
  mode: 'history',
});

router.beforeEach((to, from, next) => {
  if (to.meta.middleware) {
    const middleware = Array.isArray(to.meta.middleware)
      ? to.meta.middleware
      : [to.meta.middleware];

    const context = { from, next, router, to };
    const nextMiddleware = middlewareFactory(context, middleware, 1);

    return middleware[0]({
      ...context,
      next: nextMiddleware,
    });
  }

  return next();
});

export default router;
