<script>

import { Page } from '@pagie/focus-ui';
import Layout from '../../Layout.vue';

export default {
  components: { Layout, Page },
};

</script>

<template>
  <Layout>
    <Page title="Dashboard">
      <router-link :to="{ name: 'sign-out' }">
        Sign-out
      </router-link>
    </Page>
  </Layout>
</template>
