import Vue from 'vue';
import VueRouter from 'vue-router';
import ExampleComponent from "../views/ExampleComponent.vue";

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'index',
            component: ExampleComponent
        }
    ]
})
