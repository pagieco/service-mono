export default function middlewareFactory(context, middleware, index) {
  const subsequentMiddleware = middleware[index];

  // If no subsequent middleware exists, the default `next()` callback is returned.
  if (!subsequentMiddleware) {
    return context.next;
  }

  return (...parameters) => {
    // Run the default vue-router `next()` callback first.
    context.next(...parameters);

    // The run the subsequent middleware with a new `nextMiddleware` callback.
    const nextMiddleware = middlewareFactory(context, middleware, index + 1);

    subsequentMiddleware({
      ...context,
      next: nextMiddleware,
    });
  };
}
