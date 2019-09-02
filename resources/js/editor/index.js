import Vue from 'vue';
import router from './router';
import store from './state/store';
import Editor from './components/Editor.vue';
import { setupEditor } from './editor';
import { collectNodes, nodeSelector } from './dom';

// Dont warn about using the dev version of Vue in development.
Vue.config.productionTip = process.env.NODE_ENV === 'production';

require('./directives');

// eslint-disable-next-line no-new
new Vue({
  el: '#editor-wrapper',
  router,
  store,
  created() {
    // When the editor is created, it's time to collect
    // all DOM nodes and store them for later use...
    const collectedNodes = collectNodes(nodeSelector(), document);

    this.$store.dispatch('collectNodes', collectedNodes);

    // ...also we create an 'editor wrapper' element where an
    // iframe will be created and the page will be put into.
    setupEditor();
  },
  components: { Editor },
  template: '<Editor />',
});
