require('./bootstrap');

import VueRouter from 'vue-router';
import routes from './routes/routes';
import {BootstrapVue} from 'bootstrap-vue';
import '@/plugins';
import App from './app.vue';
window.Vue = require('vue').default;
const router = new VueRouter(routes);
Vue.use(BootstrapVue);
Vue.use(VueRouter);


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
importModuleComponents(require.context('../../resources/js', true, /\.vue$/i));

const app = new Vue({
  el: '#app',
  router: router,
  components: {App}
});
