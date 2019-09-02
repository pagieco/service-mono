<script>

import { hideOverlay } from '../editor-overlay';

export default {
  methods: {
    hide() {
      hideOverlay();
    },
  },
};

</script>

<template>
  <div id="editor-overlay">
    <div class="bg-white p-2 border shadow-lg">

      <div>
        <button @click="hide">&times;</button>
      </div>

      test

    </div>
  </div>
</template>

<style>
  #editor-overlay {
    position: fixed;
    top: 0;
    left: 0;
    display: none;
    width: 300px;
  }
</style>
