import { isPlainObject } from 'lodash';

const customMatchers = {};

customMatchers.toBeAComponent = function (options) {
  function isAComponent() {
    return isPlainObject(options) && typeof options.render === 'function';
  }

  const expected = this.utils.printReceived(options);

  if (isAComponent()) {
    return {
      message: () => `expected ${expected} not to be a Vue component`,
      pass: true,
    };
  }

  return {
    message: () => `expected ${expected} to be a valid Vue component, exported from a .vue file`,
    pass: false,
  };
};

global.expect.extend(customMatchers);
