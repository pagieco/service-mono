import http from '../../services/http';

export const state = {
  fontList: [],
};

export const getters = {
  fontList(state) {
    return state.fontList;
  },
};

export const mutations = {
  SET_FONT_LIST(state, fontList) {
    state.fontList = fontList;
  },
};

export const actions = {
  fetchFontList({ commit }) {
    return http.get('/font-list').then(({ data }) => {
      commit('SET_FONT_LIST', data);
    });
  },
};
