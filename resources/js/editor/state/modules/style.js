import Vue from 'vue';
import { head } from 'lodash';
import { fetchHeadData, objectClean } from '../../libraries/data';
import { reflectStylesheet } from '../../style';
import { nodeSelector } from '../../dom';

export const state = {
  currentMediaQuery: 'screen',
  rules: fetchHeadData('editor/dom/styles', {}),
};

export const getters = {
  currentMediaQuery(state) {
    return state.currentMediaQuery;
  },

  rules(state) {
    return state.rules;
  },

  /**
   * Get a specific style rule based on the element's
   * selector, media-query and given css property.
   *
   * @param   {Object} state
   * @param   {Object} getters
   * @returns {function(*, *): null}
   */
  styleRule(state, getters) {
    return (selection, prop) => {
      const query = getters.currentMediaQuery;

      // If the mediaQuery isn't used before in the ruleset,
      // create an empty object for that ruleset.
      if (!state.rules[query]) {
        state.rules[query] = {};
      }

      // Map trough each element in selectionList and try
      // to retrieve the property in the css rules list.
      const styleList = selection.map((nodeId) => {
        const selector = nodeSelector(nodeId);

        // If the selector isn't used before in the mediaquery,
        // create an empty object for that selector.
        if (!state.rules[query][selector]) {
          state.rules[query][selector] = {};
        }

        const rule = state.rules[query][selector];

        return rule[prop] || null;
      });

      // Count the uniques of the retrieved style properties.
      // If there are more then one unique elements in the list it means that we're
      // not able to retrieve the style value. Otherwise, just return the value.
      if (new Set(styleList).size <= 1) {
        return head(styleList);
      }

      return null;
    };
  },
};

export const mutations = {
  SET_STYLE_PROP(state, { nodeId, property, value }) {
    const mq = state.currentMediaQuery;
    const selector = nodeSelector(nodeId);

    if (!state.rules[mq]) {
      state.rules[mq] = {};
    }

    if (Array.isArray(state.rules[mq][selector])) {
      state.rules[mq][selector] = {};
    }

    if (!state.rules[mq][selector]) {
      state.rules[mq][selector] = {};
    }

    Vue.set(state.rules[mq][selector], property, value);

    reflectStylesheet(objectClean(state.rules));
  },

  SET_MEDIA_QUERY(state, mediaQuery) {
    state.currentMediaQuery = mediaQuery;
  },
};

export const actions = {
  setStyleProp({ commit }, style) {
    const { selectionSet } = style;

    if (selectionSet.length === 0) {
      return;
    }

    selectionSet.forEach((nodeId) => {
      commit('SET_STYLE_PROP', { nodeId, ...style });
    });
  },

  setNodeStyleProp({ commit }, style) {
    commit('SET_STYLE_PROP', style);
  },

  resetStyleValue({ dispatch }, { selectionSet, prop, toValue = 'auto' }) {
    return dispatch('setStyleProp', {
      selectionSet,
      property: prop,
      value: toValue,
    });
  },

  /**
   * Set the current media-query.
   *
   * @param   {String} mediaQuery
   * @returns {void}
   */
  setMediaQuery({ commit }, mediaQuery) {
    commit('SET_MEDIA_QUERY', mediaQuery);
  },
};
