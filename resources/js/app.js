require('./bootstrap');

import VueRouter from 'vue-router';
import routes from './routes/routes';
import {BootstrapVue} from 'bootstrap-vue';
import '@/plugins';
import App from './app.vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import load from '@/WindowLoad/load';
import firebase from "firebase/app";

window.Vue = require('vue').default;
const router = new VueRouter(routes);
Vue.use(BootstrapVue);
Vue.use(VueRouter);
window.addEventListener('load', load, false);
const firebaseConfig = {
  apiKey: "AIzaSyCgXxMIZRD4o0IlTsNTahsKjb40BYUnkWA",
  authDomain: "coverage-3d0f9.firebaseapp.com",
  projectId: "coverage-3d0f9",
  storageBucket: "coverage-3d0f9.appspot.com",
  messagingSenderId: "460731516702",
  appId: "1:460731516702:web:fb03f62c9911ca967fab0f",
  measurementId: "G-E81JW13QP8"
};

firebase.initializeApp(firebaseConfig);

const capitalizeFirstLetter = ([ first, ...rest ], locale = navigator.language) =>
  first === undefined ? '' : first.toLocaleUpperCase(locale) + rest.join('');

function joinComponentsUppercase(string) {
  var UpperCaseArr = string.split(/[A-Z]+/g);
  var lowercaseArr = string.split(/[a-z]+/g);
  var name = '';
  UpperCaseArr.splice(UpperCaseArr.indexOf('') , 1);
  lowercaseArr.splice(lowercaseArr.indexOf('') , 1);
  UpperCaseArr.forEach((value, index)=>{
      name += `${lowercaseArr[index]}${value}-`
  });
  return name.substring('-', name.length - 1);
}
/**
 * Modules component register
 * @param {context} context
 */
function importModuleComponents(context) {
  for (const key of context.keys()) {
    const keys = key.split('/');
    const component = [];
    component.push(capitalizeFirstLetter(keys[1]));
    component.push(joinComponentsUppercase(capitalizeFirstLetter(keys.pop().split('.')[0])));
    Vue.component(component.join('-'), context(key).default);
  }
}
importModuleComponents(require.context('../../resources/js/modules', true, /\.vue$/i));

/**
 * Modules component register
 * @param {context} context
 */
 function importModuleCommons(context) {
  for (const key of context.keys()) {
    const keys = key.split('/');
    const component = [];
    component.push('Common');
    component.push(capitalizeFirstLetter(keys.pop().split('.')[0]));
    Vue.component(component.join('-'), context(key).default);
  }
}
importModuleCommons(require.context('../../resources/js/components', true, /\.vue$/i));

function loggedIn() {
  return sessionStorage.getItem('token')
}

router.beforeEach((to, from, next) => {
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
