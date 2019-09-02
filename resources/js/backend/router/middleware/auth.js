import { isLoggedIn } from '../../auth';

export default function auth({ next, router }) {
  if (!isLoggedIn()) {
    return router.push({ name: 'sign-in' });
  }

  return next();
}
