import { mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters(['selectionSet', 'styleRule']),
  },

  methods: {
    hasStyleValue(prop) {
      return !!this.getStyleProp(prop);
    },

    getStyleProp(prop) {
      return this.styleRule(this.selectionSet, (prop));
    },

    resetStyleValue(prop, toValue = 'auto') {
      return this.$store.dispatch('resetStyleValue', {
        prop,
        toValue,
        selectionSet: this.selectionSet,
      });
    },
  },
};
