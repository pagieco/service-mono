import appConfig from './app.config';
import App, { getPageTitle } from './App.vue';

describe('<App />', () => {
  it('should be a valid vue component', () => {
    expect(App).toBeAComponent();
  });

  it('should generate the initial page title when no arguments are passed into the getPageTitle function', () => {
    expect(getPageTitle()).toEqual(appConfig.title);
  });

  it('should start with the given argument a string is passed into the getPageTitle function', () => {
    expect(getPageTitle('Dashboard')).toMatch(/^Dashboard\s\|\s*/);
  });

  it('should execute the function when a function is passed into the getPageTitle function', () => {
    expect(getPageTitle(() => 'Page overview')).toMatch(/^Page overview\s\|\s*/);
  });
});
