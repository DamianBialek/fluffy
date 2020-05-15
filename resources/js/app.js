import './bootstrap';
import Vue from "vue"
import router from "./router";
import store from "./store";
import axios from "axios";
import dialogPlugin from "./plugins/dialog";

Vue.use(dialogPlugin);

Vue.prototype.$api = axios;

new Vue({
    el: '#app',
    router,
    store
})
