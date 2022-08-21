window.Vue = require('vue').default;
import axios from 'axios';

axios.defaults.baseURL = '/api'
axios.interceptors.request.use((request) => {
    const token = localStorage.getItem('token');
    if (token) {
        request.headers.common['Authorization'] = `Bearer ${token}`;
    }

    return request;
})

Vue.prototype.$axios = axios;

export default axios;