import AppIndex from '../app/product/pages/index.vue';

export default {
    mode: 'history',
    base: process.env.BASE_URL,
    fallback: true,
    routes: [{
      path: '/app',
      component: AppIndex
    }]
}