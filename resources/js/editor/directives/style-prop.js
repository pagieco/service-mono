import Vue from 'vue';
import { kebabCase } from 'lodash';
import event, { HIGHLIGHTER_RECALCPOS } from '../services/event';

Vue.directive('style-prop', {
  bind(el, binding, vnode) {
    const store = vnode.context.$store;

    el.addEventListener('input', (e) => {
      const { selectionSet } = store.getters;

      event.$emit(HIGHLIGHTER_RECALCPOS, { selectionSet });

      return store.dispatch('setStyleProp', {
        selectionSet,
        property: kebabCase(binding.value),
        value: e.target.value,
      });
    });
  },
});
