import Main from '../modules/Layouts/Main.vue';
import Test from '../modules/App/pages/Test.vue';
import UserIndex from '../modules/User/pages/index.vue';
import DetailUser from '../modules/User/pages/detail.vue';
import ImportUser from '../modules/User/pages/import.vue';
import Login from '../modules/Auth/pages/Login.vue';
import Page404 from '../modules/Layouts/404.vue';
export default {
  mode: 'history',
  base: process.env.BASE_URL,
  fallback: true,
  routes: [
    {
      path: '',
      component: Main,
      name: 'homepage',
      meta: { requiresAuth: true },
      children: [
        {
          path: 'app',
          component: Test,
          name: 'test',
          meta: { requiresAuth: false }
        },
        {
          path: 'user',
          component: UserIndex,
          name: 'userIndex',
          meta: { requiresAuth: false }
        },
        {
          path: 'user/detail/:id',
          component: DetailUser,
          name: 'detail-user',
          props: route => ({ id: Number(route.params.id) }),
          meta: { requiresAuth: false }
        },
        {
          path: 'user/import',
          component: ImportUser,
          name: 'import-user',
          meta: { requiresAuth: false }
        }
      ]
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