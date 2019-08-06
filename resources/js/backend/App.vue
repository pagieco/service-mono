<script>

import appConfig from './app.config';

export function getPageTitle(title, $store = null) {
  const template = typeof title === 'function' ? title($store) : title;

  return template ? `${template} | ${appConfig.title}` : appConfig.title;
}

export default {
  meta: {
    titleTemplate: title => getPageTitle(title, this.$store),
  },
};

</script>

<template>
  <!--
    Even when routes use the same component, treat them
    as distinct and create the component again.
  -->
  <RouterView :key="$route.fullPath" />
</template>
