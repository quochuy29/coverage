require('./bootstrap');

import VueRouter from 'vue-router';
import routes from './routes/routes';
import {BootstrapVue} from 'bootstrap-vue';
import '@/plugins';
import App from './app.vue';
window.Vue = require('vue').default;
const router = new VueRouter(routes);
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import load from '@/WindowLoad/load';
Vue.use(BootstrapVue);
Vue.use(VueRouter);
window.addEventListener('load', load, false);

const capitalizeFirstLetter = ([ first, ...rest ], locale = navigator.language) =>
  first === undefined ? '' : first.toLocaleUpperCase(locale) + rest.join('')
/**
 * Modules component register
 * @param {context} context
 */
function importModuleComponents(context) {
  for (const key of context.keys()) {
    const keys = key.split('/');
    const component = [];
    component.push(capitalizeFirstLetter(keys[1]));
    component.push(capitalizeFirstLetter(keys.pop().split('.')[0]));
    Vue.component(component.join('-'), context(key).default);
  }
}
importModuleComponents(require.context('../../resources/js/modules', true, /\.vue$/i));

function loggedIn() {
  return localStorage.getItem('token')
}

router.beforeEach((to, from, next) => {
  if (to.path == "/") {
    next({
      path: '/login'
    })
  }

  if (to.matched.some(record => record.meta.requiresAuth)) {
      if (loggedIn() == null) {
          next({
              path: '/login',
              query: { redirect: to.fullPath }
          })
      } else {
          next()
      }
  } else if (to.matched.some(record => record.meta.guest)) {
      if (loggedIn()) {
          next({
            path: '/login',
              query: { redirect: to.fullPath }
          })
      } else {
          next()
      }
  } else {
      next()
  }
})

const app = new Vue({
  el: '#app',
  router: router,
  components: {App}
});
