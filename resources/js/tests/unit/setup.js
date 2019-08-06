import Vue from 'vue';
import { mount, shallowMount, createLocalVue } from '@vue/test-utils';

Vue.config.productionTip = false;

global.mount = mount;
global.shallowMount = shallowMount;
global.createComponentMocks = ({ mocks, stubs }) => {
  const localVue = createLocalVue();
  const returnOptions = { localVue };

  returnOptions.stubs = stubs || {};
  returnOptions.mocks = mocks || {};

  return returnOptions;
};
