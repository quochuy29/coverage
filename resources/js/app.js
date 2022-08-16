require('./bootstrap');

window.Vue = require('vue').default;

import {BootstrapVue} from 'bootstrap-vue';
Vue.use(BootstrapVue);

/**
 * Modules component register
 * @param {context} context
 */
function importModuleComponents(context) {
  for (const key of context.keys()) {
    const keys = key.split('/');
    const component = [];
    component.push(keys[1]);
    component.push(keys.pop().split('.')[0]);
    Vue.component(component.join(''), context(key).default);
  }
}
importModuleComponents(require.context('../../app', true, /\.vue$/i));

const app = new Vue({
  el: '#app'
});

export default app;
