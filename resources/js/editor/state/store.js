import Vue from 'vue';
import Vuex from 'vuex';
import * as color from './modules/color';
import * as dom from './modules/dom';
import * as highlighter from './modules/highlighter';
import * as selection from './modules/selection';
import * as style from './modules/style';

Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,
  modules: [
    color,
    dom,
    highlighter,
    selection,
    style,
  ],
});
