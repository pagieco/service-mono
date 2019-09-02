<script>

import {
  Select,
  TextField,
  InputLabel,
  ConnectedField,
} from '@pagie/focus-ui';
import StyleProp from '../../mixins/StyleProp';

export default {
  mixins: [StyleProp],

  components: {
    Select,
    TextField,
    InputLabel,
    ConnectedField,
  },

  props: {
    id: {
      type: String,
      required: true,
    },

    label: {
      type: String,
    },

    styleProp: {
      type: String,
      required: true,
    },

    defaultValue: {
      type: String,
      default: 'auto',
    },
  },

  computed: {
    value() {
      return this.getStyleProp(this.styleProp) || this.defaultValue;
    },

    units() {
      return [
        { label: 'px', value: 'px' },
        { label: 'em', value: 'em' },
        { label: '%', value: '%' },
        { label: 'vh', value: 'vh' },
        { label: 'vw', value: 'vw' },
      ];
    },
  },
};

</script>

<template>
  <div>
    <InputLabel :label="label" :label-for="`f-${id}`" />

    <ConnectedField>
      <template v-slot:connected-left>
        <TextField v-style-prop="styleProp"
                   :id="`f-${id}`"
                   :value="value" />
      </template>

      <template v-slot:connected-right>
        <Select :options="units" v-if="value !== 'auto'" />
      </template>
    </ConnectedField>
  </div>
</template>
