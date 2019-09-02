import { reflectNodeSelection } from '../../dom';

export const state = {
  selectionSet: [],
};

export const getters = {
  // Get the selectionset array.
  selectionSet(state) {
    return state.selectionSet;
  },

  // Determine if something is selected.
  hasSelection(state) {
    return state.selectionSet.length > 0;
  },
};

export const mutations = {
  // Clear the selectionset.
  SELECTIONSET_CLEAR(state) {
    state.selectionSet.length = 0;
  },

  // Delete an item from the selectionset.
  SELECTIONSET_DELETE(state, nodeId) {
    const index = state.selectionSet.indexOf(nodeId);

    if (index > -1) {
      state.selectionSet.splice(index, 1);
    }
  },

  // Replace the selectionset with the given item.
  SELECTIONSET_SET(state, nodeId) {
    state.selectionSet.length = 0;
    state.selectionSet.push(nodeId);
  },

  // Add an item to the selectionset.
  SELECTIONSET_ADD(state, nodeId) {
    if (!state.selectionSet.includes(nodeId)) {
      state.selectionSet.push(nodeId);
    }
  },
};

export const actions = {
  setNodeSelection({ getters, commit }, { originalEvent, nodeId }) {
    // If the meta-key is pressed and the node is already in the
    // selection set then the user wants to de-select the node
    // so we need to delete it from the selection if it exists.
    if (originalEvent.metaKey) {
      if (getters.selectionSet.includes(nodeId)) {
        commit('SELECTIONSET_DELETE', nodeId);
      } else {
        commit('SELECTIONSET_ADD', nodeId);
      }
    } else {
      // Otherwise just one node needs to be selected so
      // we need to clear out the whole selection first.
      commit('SELECTIONSET_SET', nodeId);
    }

    reflectNodeSelection(getters.selectionSet);
  },

  deselectAllNodes({ commit, getters }) {
    getters.selectionSet.forEach((nodeId) => {
      commit('SELECTIONSET_DELETE', nodeId);
    });
  },
};
