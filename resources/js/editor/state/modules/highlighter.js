import { pick } from 'lodash';
import { getNodeRect } from '../../dom';
import { objectClean } from '../../utils';

export const state = {
  isResizing: false,
  resizingProperties: {},
};

export const getters = {
  isResizing(state) {
    return state.isResizing;
  },

  resizingProperties(state) {
    return objectClean(state.resizingProperties);
  },
};

export const mutations = {
  HIGHLIGHTER_IS_RESIZING(state, isResizing) {
    state.isResizing = isResizing;
  },

  HIGHLIGHTER_RESIZE(state, resizingProperties) {
    // startPosition: { x, y },
    // currentHandle: { width | height },
    // selectionDimensions: { nodeId: { width, height } }
    state.resizingProperties = resizingProperties;
  },
};

export const actions = {
  startResizing({ commit, rootGetters }, { startPosition, currentHandle }) {
    const selectionDimensions = {};

    rootGetters.selectionSet.forEach((nodeId) => {
      selectionDimensions[nodeId] = pick(getNodeRect(nodeId), ['width', 'height']);
    });

    commit('HIGHLIGHTER_IS_RESIZING', true);
    commit('HIGHLIGHTER_RESIZE', { startPosition, currentHandle, selectionDimensions });
  },

  stopResizing({ commit }) {
    commit('HIGHLIGHTER_IS_RESIZING', false);
  },
};
