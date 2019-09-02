<script>

import {
  NavigationSection,
  NavigationItem,
} from '@pagie/focus-ui';
import { mapGetters } from 'vuex';

export default {
  components: {
    NavigationItem,
    NavigationSection,
  },

  computed: {
    ...mapGetters(['selectionSet']),
  },
};

</script>

<template>
  <div>
    <NavigationSection title="Design">
      <NavigationItem label="Layout" :to="{ name: 'layout-panel' }" />
      <NavigationItem label="Typography" :to="{ name: 'typography-panel' }" />
      <NavigationItem label="Background" :to="{ name: 'background-panel' }" />
      <NavigationItem label="Border" :to="{ name: 'border-panel' }" />
      <NavigationItem label="Effects" :to="{ name: 'effects-panel' }" />
    </NavigationSection>

    <NavigationSection title="Management">
      <NavigationItem label="Structure" :to="{ name: 'structure-panel' }" />
      <NavigationItem label="Page settings" :to="{ name: 'settings-panel' }" />
    </NavigationSection>
  </div>
</template>
