import { shallowMount, createLocalVue } from '@vue/test-utils';
import VueRouter from 'vue-router';
import appConfig from './app.config';
import App from './App.vue';

const localVue = createLocalVue();
const router = new VueRouter();

localVue.use(VueRouter);

describe('<App />', () => {
  it('should be a valid vue component', () => {
    expect(App).toBeAComponent();
  });

  it('should have the getPageTitle function', () => {
    const wrapper = shallowMount(App, { localVue, router });

    expect(wrapper.vm.getPageTitle).toBeInstanceOf(Function);
  });

  it('should generate the initial page title when no arguments are passed into the getPageTitle function', () => {
    const wrapper = shallowMount(App, { localVue, router });

    expect(wrapper.vm.getPageTitle()).toEqual(appConfig.title);
  });

  it('should start with the given argument a string is passed into the getPageTitle function', () => {
    const wrapper = shallowMount(App, { localVue, router });

    expect(wrapper.vm.getPageTitle('Dashboard')).toMatch(/^Dashboard\s\|\s*/);
  });

  it('should execute the function when a function is passed into the getPageTitle function', () => {
    const wrapper = shallowMount(App, { localVue, router });

    expect(wrapper.vm.getPageTitle(() => 'Page overview')).toMatch(/^Page overview\s\|\s*/);
  });
});
