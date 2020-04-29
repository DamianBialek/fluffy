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
axios.interceptors.response.use(null, (error) => {
    if (error.response.status == 401) {
        store.dispatch('logout');
        router.push('/login');
    }

    return Promise.reject(error);
});

if(isLoggedIn()) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${getLoggedInUserToken()}`
}
