import Vue from 'vue';
import VueRouter from 'vue-router';
import { isLoggedIn } from "../helpers/auth";
import Login from "../views/Login.vue";
import Layout from '../layout/Layout';
import modules from "./modulesRoutesLoader";

Vue.use(VueRouter);

const modulesRoutes = Object.keys(modules).map(key => modules[key])

const router = new VueRouter({
    base: window.Config.base,
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/',
            component: Layout,
            redirect: '/dashboard',
            name: 'start',
            meta: {
                title: 'Start'
            },
            children: [
                ...modulesRoutes
            ]
        }
    ]
})

router.beforeEach((to, from, next) => {
    if(!isLoggedIn() && to.name !== 'login') {
        next({ name: 'login' });
    } else {
        next();
    }
})

export const layoutRoutes = modules;
export default router
