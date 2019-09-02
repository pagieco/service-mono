<script>

import { mapGetters } from 'vuex';
import { bindResizeEventHandlers, getPositionForNode, repositionHighlighters } from '../highlighter';
import event, { HIGHLIGHTER_RECALCPOS, IFRAME_LOADED } from '../services/event';
import { getIframeDocument } from '../iframe';

export default {
  data() {
    return {
      sidesEnabled: true,
      topsEnabled: true,
    };
  },

  computed: {
    ...mapGetters(['selectionSet']),
  },

  mounted() {
    this.handleEvents();
  },

  methods: {
    handleEvents() {
      event.$on(IFRAME_LOADED, () => {
        bindResizeEventHandlers();

        getIframeDocument().body.addEventListener('click', () => {
          this.$store.dispatch('deselectAllNodes');
        });
      });

      event.$on(HIGHLIGHTER_RECALCPOS, () => {
        this.$nextTick(() => {
          repositionHighlighters(this.$el);
        });
      });
    },

    getPosition(id) {
      return getPositionForNode(id);
    },

    handleResize(e, currentHandle) {
      this.$store.dispatch('startResizing', {
        currentHandle,
        startPosition: { x: e.clientX, y: e.clientY },
      });
    },
  },
};

</script>

<template>
  <div id="highlighter-wrapper">
    <div v-for="id in selectionSet"
         class="highlighter-element"
         :data-node-ref="id"
         :style="getPosition(id)"
         :key="id">

      <div @mousedown="e => handleResize(e, 'width')" class="resize-width"></div>
      <div @mousedown="e => handleResize(e, 'height')" class="resize-height"></div>

    </div>
  </div>
</template>

<style lang="scss">
  $resize-handle-offset: -4px;

  #highlighter-wrapper {
    position: fixed;
    top: var(--toolbar-height);
    left: var(--sidebar-width);
    bottom: 0;
    right: 0;
    pointer-events: none;
  }

  .highlighter-element {
    position: absolute;
    pointer-events: none;
    border: 1px solid #007bff;
    box-shadow: 1px 1px 1px transparentize(#007bff, 0.75);

    .resize-width,
    .resize-height {
      position: absolute;
      width: 8px;
      height: 8px;
      background-color: #ddefff;
      border: 1px solid #007bff;
      border-radius: 2px;
      pointer-events: initial;
    }

    .resize-width {
      cursor: e-resize;
      top: 50%;
      transform: translateY(-50%);
      right: $resize-handle-offset;
    }

    .resize-height {
      cursor: s-resize;
      bottom: $resize-handle-offset;
      transform: translateX(-50%);
      left: 50%
    }
  }
</style>
