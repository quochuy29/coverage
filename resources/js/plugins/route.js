import Vue from 'vue';

const routes = window.App.routes;
Vue.prototype.$routea = function(...args) {
  const name = args.shift();
  if (routes[name] === undefined) {
    console.error('Route not found ', name);
  } else {
    return window.App.baseUrl + '/' + routes[name]
        .split('/')
        .map((s) => s[0] == '{' ? args.shift() : s)
        .join('/');
  }
};
