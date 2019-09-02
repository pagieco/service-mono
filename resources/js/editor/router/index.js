import Vue from 'vue';
import VueRouter from 'vue-router';

import homePanel from './views/panels/Home.vue';
import backgroundPanel from './views/panels/Background.vue';
import borderPanel from './views/panels/Border.vue';
import effectsPanel from './views/panels/Effects.vue';
import layoutPanel from './views/panels/Layout.vue';
import typographyPanel from './views/panels/Typography.vue';
import structurePanel from './views/panels/Structure.vue';
import settingsPanel from './views/panels/Settings.vue';

Vue.use(VueRouter);

const router = new VueRouter({
  routes: [
    { path: '/', name: 'home-panel', component: homePanel },
    { path: '/background', name: 'background-panel', component: backgroundPanel },
    { path: '/border', name: 'border-panel', component: borderPanel },
    { path: '/effects', name: 'effects-panel', component: effectsPanel },
    { path: '/layout', name: 'layout-panel', component: layoutPanel },
    { path: '/typography', name: 'typography-panel', component: typographyPanel },
    { path: '/structure', name: 'structure-panel', component: structurePanel },
    { path: '/settings', name: 'settings-panel', component: settingsPanel },
  ],
});

export default router;
