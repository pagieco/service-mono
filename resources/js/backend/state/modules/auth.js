import http from '../../services/http';
import { setAccessToken, isLoggedIn } from '../../auth';

export const state = {
  currentUser: null,
};

export const getters = {
  currentUser(state) {
    return state.currentUser;
  },
};

export const mutations = {
  SET_CURRENT_USER(state, currentUser) {
    state.currentUser = currentUser;
  },
};

export const actions = {
  init({ dispatch }) {
    if (isLoggedIn()) {
      dispatch('fetchCurrentUser');
    }
  },

  fetchCurrentUser({ commit }) {
    return http.get('/auth/current-user')
      .then(({ data }) => data)
      .then(({ data, status }) => {
        if (status === 200) {
          commit('SET_CURRENT_USER', data);
        }
      });
  },

  authenticate(_, payload) {
    return new Promise((resolve, reject) => {
      http.post('auth/authenticate', payload)
        .then(({ data, status }) => {
          if (status === 200) {
            setAccessToken(data.access_token);

            resolve(data);
          } else {
            reject(status);
          }
        }).catch(reject);
    });
  },
};
