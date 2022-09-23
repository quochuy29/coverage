window.Vue = require('vue').default;
import axios from 'axios';

axios.defaults.baseURL = '/api';
axios.interceptors.request.use((request) => {
    const token = sessionStorage.getItem('token');
    if (token) {
        request.headers.common['Authorization'] = `Bearer ${token}`;
    }

    return request;
})
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

Vue.prototype.$axios = axios;

export default axios;