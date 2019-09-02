<script>

import { transform } from 'lodash';
import { mapGetters } from 'vuex';
import { reflectFontList } from '../../../font';
import StyleProp from '../../../mixins/StyleProp';
import { getStylePropertyFromAllNodes } from '../../../style';

function transformFontList(fonts) {
  return transform(fonts, (result, value) => {
    result[value.family] = { ...value, label: value.family };

    return result;
  }, {});
}

export default {
  mixins: [StyleProp],

  computed: {
    ...mapGetters(['fontList']),

    fontOptions() {
      return transformFontList(this.fontList);
    },
  },

  methods: {
    onFontChange() {
      reflectFontList(
        getStylePropertyFromAllNodes('font-family'),
      );
    },
  },
};

</script>

<template>
  <div>
    typography
  </div>
</template>
