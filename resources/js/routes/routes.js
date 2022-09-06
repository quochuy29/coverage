import Main from '../modules/Layouts/Main.vue';
import Login from '../modules/Auth/pages/Login.vue';
import Page404 from '../modules/Layouts/404.vue';
export default {
  mode: 'history',
  base: process.env.BASE_URL,
  fallback: true,
  routes: [
    {
      path: '/app',
      component: Main,
      name: 'homepage',
      meta: { requiresAuth: true }
    },
    {
      path: '/login',
      component: Login,
      name: 'login',
      meta: { requiresAuth: false }
    },
    {
      path: '/:NotFound(.*)*',
      component: Page404,
    }
  ]
}