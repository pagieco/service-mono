export const ACCESS_TOKEN_KEY = 'access-token';

/**
 * Get the user's access-token.
 *
 * @returns {string | null}
 */
export function getAccessToken() {
  return localStorage.getItem(ACCESS_TOKEN_KEY);
}

/**
 * Set the access-token.
 *
 * @param   {string} token
 * @returns {void}
 */
export function setAccessToken(token) {
  localStorage.setItem(ACCESS_TOKEN_KEY, token);
}

/**
 * Determine that the user is logged in.
 *
 * @returns {boolean}
 */
export function isLoggedIn() {
  return !!getAccessToken();
}

/**
 * Clear the access-token from the local-storage. This
 * essentially logs the user out of the application.
 *
 * @returns {void}
 */
export function clearAccessToken() {
  localStorage.removeItem(ACCESS_TOKEN_KEY);
}
