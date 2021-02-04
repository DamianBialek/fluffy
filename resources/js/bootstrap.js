import axios from 'axios';
import { isLoggedIn, getLoggedInUserToken } from "./helpers/auth";
import router from './router';
import store from './store';
import 'bootstrap';
import jquery from 'jquery';
import swal from 'sweetalert';

window.$ = window.jQuery = jquery;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = window.Config.baseUrl;

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// @ts-ignore
axios.interceptors.response.use(response => {
    let headers = response.headers

    if (headers.authorization !== undefined) {
        store.dispatch("setAuthorizationToken", headers.authorization);
    }
    return response;
}, (error) => {
    const originalRequest = error.config;

    if(!error.response || !error.response.status || (error.response.status !== 401)) {
        if(!error.response.data || typeof error.response.data.success == 'undefined' || !error.response.data.message.length) {
            swal("Wystąpił błąd podczas połączenia z serwerem !", "", "error");
            store.dispatch('logout');
            if(router.currentRoute.name !== 'login') {
                router.push('/login');
            }
        } else {
            swal(error.response.data.message, "", "error");
        }
        return Promise.reject(error);
    }

    if (error.response.status === 401 && originalRequest.url === '/api/auth/refresh') {
        store.dispatch('logout');
        if(router.currentRoute.name !== 'login') {
            router.push('/login');
        }
        return Promise.reject(error);
    }

    if (error.response.status === 401 && originalRequest.url !== '/api/auth/login') {
        if(!originalRequest._retry) {
            originalRequest._retry = true;
            return axios.post('/api/auth/refresh')
                .then(res => {
                    if (res.status === 200) {
                        store.commit("loginSuccess", res.data);
                        axios.defaults.headers.common['Authorization'] = `Bearer ${getLoggedInUserToken()}`;
                        return axios(originalRequest);
                    }
                })
        } else {
            store.dispatch('logout');
            if(router.currentRoute.name !== 'login') {
                router.push('/login');
            }
        }
    }

    return Promise.reject(error);
});

axios.interceptors.request.use(config => {
    if(isLoggedIn()) {
        config.headers["Authorization"] = `Bearer ${getLoggedInUserToken()}`
    }

    return config;
})
