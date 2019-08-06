/* eslint-disable global-require */
/* eslint-disable no-param-reassign */

// Register each file as a corresponding Vuex module. Module nesting
// will mirror [sub-]directory hierarchy and modules are namespaced
// as the camelCase equivalent of their file name.

import { camelCase } from 'lodash';

const moduleCache = {};
const storeData = { modules: {} };

function getNamespace(subtree, path) {
  if (path.length === 1) {
    return subtree;
  }

  const namespace = path.shift();

  subtree.modules[namespace] = {
    modules: {},
    namespaced: true,
    ...subtree.modules[namespace],
  };

  return getNamespace(subtree.modules[namespace], path);
}

(function updateModules() {
  // Allow us to dynamically require all Vuex module files.
  const requireModule = require.context(
    '.', true, /^((?!index|\.unit\.).)*\.js$/,
  );

  requireModule.keys().forEach((fileName) => {
    const moduleDefinition = requireModule(fileName);

    // Skip the module during hot reload if it refers to the
    // same module definition as the one we have cache.
    if (moduleCache[fileName] === moduleDefinition) {
      return;
    }

    // Update the module cache, for efficient hot reloading.
    moduleCache[fileName] = moduleDefinition;

    const modulePath = fileName
      .replace(/^\.\//, '')
      .replace(/\.\w+$/, '')
      .split(/\//)
      .map(camelCase);

    // Get the modules object for the current path.
    const { modules } = getNamespace(storeData, modulePath);

    // Add the module to our modules object.
    modules[modulePath.pop()] = {
      namespaced: true,
      ...moduleDefinition,
    };
  });

  if (module.hot) {
    module.hot.accept(requireModule.id, () => {
      updateModules();

      require('../store').default.hotUpdate({
        modules: storeData.modules,
      });
    });
  }
}());

export default storeData.modules;
