<script>

import {
  Card,
  Button,
  Form,
  FormLayout,
  TextField,
} from '@pagie/focus-ui';

export default {
  components: {
    Card,
    Button,
    Form,
    FormLayout,
    TextField,
  },

  data() {
    return {
      email: null,
      password: null,
    };
  },

  methods: {
    tryToLogin() {
      const { email, password } = this;

      this.$store.dispatch('auth/authenticate', { email, password }).then(() => {
        const { href } = this.$router.resolve({ name: 'dashboard' });

        window.location.href = href;
      }).catch(() => {

      });
    },
  },
};

</script>

<template>
  <Card sectioned>
    <Form @submit.prevent="tryToLogin" auto-complete>
      <FormLayout>
        <TextField
            required
            label="Email"
            type="email"
            name="email"
            v-model="email" />
      </FormLayout>

      <FormLayout>
        <TextField
            required
            label="Password"
            type="password"
            name="password"
            v-model="password" />
      </FormLayout>

      <Button primary submit>Login</Button>
    </Form>
  </Card>
</template>
