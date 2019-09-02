import http from '../../services/http';

export const state = {
  colors: [],
};

export const getters = {
  colors(state) {
    return state.colors;
  },
};

export const mutations = {
  SET_COLORS(state, colors) {
    state.colors = colors;
  },
};

export const actions = {
  fetchColors({ commit }) {
    return http.get('colors').then(({ status, data }) => {
      if (status === 200 || status === 204) {
        commit('SET_COLORS', data);
      }
    });
  },
};
