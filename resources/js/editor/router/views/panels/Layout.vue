<script>

import UnitInput from '../../../components/inputs/UnitInput.vue';

export default {
  components: {
    UnitInput,
  },
};

</script>

<template>
  <div style="padding: 1em;">
    <router-link :to="{ name: 'home-panel' }"><< back</router-link>

    <div>
      <UnitInput label="Width" id="width" style-prop="width" />
      <UnitInput label="Height" id="height" style-prop="height" />
    </div>

  </div>
</template>

<style>
  .grid-2 {
    align-items: center;
    display: grid;
    gap: 4px 8px;
    grid-template-columns: 44px 1fr;
    margin-bottom: 2em;
  }

  .grid-4 {
    align-items: center;
    display: grid;
    gap: 4px 8px;
    grid-template-columns: 44px 1fr 44px 1fr;
    margin-bottom: 2em;
  }
</style>
