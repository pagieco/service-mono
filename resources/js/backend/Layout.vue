<script>

import {
  Frame,
  TopBar,
  NavigationItem,
  NavigationSection,
} from '@pagie/focus-ui';

export default {
  components: {
    Frame,
    TopBar,
    NavigationItem,
    NavigationSection,
  },
};

</script>

<template>
  <Frame>
    <template v-slot:top-bar>
      <TopBar  />
    </template>

    <template v-slot:navigation>
      <NavigationSection>
        <NavigationItem label="Dashboard" url="/dashboard" />
      </NavigationSection>

      <NavigationSection title="Content">
        <NavigationItem label="Assets" url="/assets" />
        <NavigationItem label="Pages" url="/pages" />
        <NavigationItem label="Collections" url="/collections" />
      </NavigationSection>

      <NavigationSection title="Engagement">
        <NavigationItem label="Forms" url="/forms" />
        <NavigationItem label="Automations" url="/automations" />
      </NavigationSection>

      <NavigationSection title="Management">
        <NavigationItem label="Domains" url="/domains" />
        <NavigationItem label="Workflows" url="/workflows" />
        <NavigationItem label="Teams" url="/teams" />
        <NavigationItem label="Settings" url="/settings" />
      </NavigationSection>
    </template>

    <slot />
  </Frame>
</template>
