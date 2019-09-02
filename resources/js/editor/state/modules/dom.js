import Node from '../../dom';

export const state = {
  nodes: [],
};

export const getters = {
  nodes(state) {
    return state.nodes;
  },
};

export const mutations = {
  DOM_NODES_REPLACE(state, newNodes) {
    state.nodes = newNodes;
  },
};

export const actions = {
  collectNodes({ commit }, collectedNodes) {
    commit('DOM_NODES_REPLACE', [...collectedNodes].map(n => new Node(n)));
  },
};
