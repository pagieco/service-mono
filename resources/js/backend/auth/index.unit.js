import {
  ACCESS_TOKEN_KEY,
  getAccessToken,
  setAccessToken,
  isLoggedIn,
  clearAccessToken,
} from './index';

beforeEach(() => {
  localStorage.clear();
});

describe('auth/index', () => {
  it('should return null when no access-token is available', () => {
    expect(getAccessToken()).toBeNull();
  });

  it('should return the access-token when set.', () => {
    const testToken = 'test-token';

    localStorage.setItem(ACCESS_TOKEN_KEY, testToken);

    expect(getAccessToken()).toEqual(testToken);
  });

  it('should can set the access token', () => {
    const testToken = 'awesome-test-token';

    setAccessToken(testToken);

    expect(localStorage.getItem(ACCESS_TOKEN_KEY)).toEqual(testToken);
  });

  it('should not return a logged in state when no token is available', () => {
    expect(isLoggedIn()).toBeFalsy();
  });

  it('should return a logged in state when a token is available', () => {
    setAccessToken('test-token');

    expect(isLoggedIn()).toBeTruthy();
  });

  it('should only clear the access-token and not other localStorage items', () => {
    localStorage.setItem(ACCESS_TOKEN_KEY, 'a-value');

    clearAccessToken();

    expect(getAccessToken()).toBeNull();
  });
});
