import axios from 'axios';
import { isLoggedIn, getLoggedInUserToken } from "./helpers/auth";
import router from './router';
import store from './store';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token: HTMLMetaElement | null = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// @ts-ignore
axios.interceptors.response.use(response => {
    let headers = response.headers

    // your 401 check here
    // token refresh - update client session
    if (headers.authorization !== undefined) {
        store.dispatch("setAuthorizationToken", headers.authorization);
    }
    return response;
}, (error) => {
    if (error.response.status == 401) {
        store.dispatch('logout');
        if(router.currentRoute.name != 'login') {
            router.push('/login');
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
