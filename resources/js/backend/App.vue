<script>

import appConfig from './app.config';

export default {
  meta: {
    titleTemplate: () => this.getPageTitle,
  },

  methods: {
    /**
     * Get the page's title.
     *
     * @param   {string | function} title
     * @returns {string}
     */
    getPageTitle(title) {
      const template = typeof title === 'function' ? title(this.$store) : title;

      return template ? `${template} | ${appConfig.title}` : appConfig.title;
    },
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
