<script>

import { Frame, TopBar } from '@pagie/focus-ui';
import { wrapPageIntoIframe } from '../iframe';
import Highlighter from './Highlighter';
import OverlayEditor from './OverlayEditor';

export default {
  components: {
    Frame,
    TopBar,
    Highlighter,
    OverlayEditor,
  },

  data() {
    return {
      loading: false,
    };
  },

  mounted() {
    this.loading = true;

    this.moveIframeIntoCanvasContainer();
    this.setupInitialStoreData();
  },

  methods: {
    /**
     * Move the iframe into the canvas container element.
     *
     * @returns {void}
     */
    moveIframeIntoCanvasContainer() {
      wrapPageIntoIframe('.canvas-container');
    },

    setupInitialStoreData() {
      Promise.all([
        this.$store.dispatch('fetchFontList'),
      ]).then(() => {
        this.loading = false;
      });
    },
  },
};

</script>

<template>
  <Frame>
    <template v-slot:top-bar>
      <TopBar />
    </template>

    <template v-slot:navigation>
      <router-view></router-view>
    </template>

    <div class="editor-wrapper">
      <div id="loading-overlay" v-if="loading">
        loading...
      </div>

      <div class="content-wrapper">
        <Highlighter />
        <OverlayEditor />

        <div class="canvas-container"></div>
      </div>
    </div>
  </Frame>
</template>

<style>
  .editor-wrapper,
  .content-wrapper {
    display: flex;
    width: 100%;
    height: 100%;
  }

  .canvas-container {
    width: 100%;
  }

  #canvas-frame {
    width: 100%;
    height: 100%;
    border: 0;
  }
</style>
