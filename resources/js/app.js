import './bootstrap';
import Vue from "vue"
import router from "./router";
import store from "./store";
import axios from "axios";
import dialogPlugin from "./plugins/dialog";

Vue.use(dialogPlugin);

Vue.prototype.$api = axios;

Vue.mixin({
    methods: {
        setLoading(state) {
            this.$store.dispatch("setLoading", state);
        }
    }
});

new Vue({
    el: '#app',
    router,
    store
})
