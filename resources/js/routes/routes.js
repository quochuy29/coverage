import AppIndex from '../modules/product/pages/index.vue';
import Login from '../modules/Auth/pages/Login.vue';
export default {
  mode: 'history',
  base: process.env.BASE_URL,
  fallback: true,
  routes: [
    {
      path: '/app',
      component: AppIndex,
      name: 'homepage',
      meta: { requiresAuth: false }
    },
    {
      path: '/login',
      component: Login,
      name: 'login',
      meta: { requiresAuth: false }
    }
  ]
}