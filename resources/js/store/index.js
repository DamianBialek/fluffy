import Vue from 'vue';
import Vuex from 'vuex';
import {getLoggedInUser, getLoggedInUserToken} from '../helpers/auth';

const user = getLoggedInUser();
const token = getLoggedInUserToken();

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        currentUser: user,
        currentUserToken: token,
        isLoggedIn: !!user,
        loading: false,
        auth_error: null,
        reg_error:null,
        registeredUser: null,
    },
    getters: {
        isLoading(state){
            return state.loading;
        },
        isLoggedIn(state){
            return state.isLoggedIn;
        },
        currentUser(state){
            return state.currentUser;
        },
        currentUserToken(state) {
            return state.currentUserToken;
        },
        authError(state){
            return state.auth_error;
        },
        regError(state){
            return state.reg_error;
        },
        registeredUser(state){
            return state.registeredUser;
        },
    },
    mutations: {
        login(state){
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload){
            state.auth_error = null;
            state.isLoggedIn = true;
            state.loading = false;
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },
        loginFailed(state, payload){
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state){
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
        },
        registerSuccess(state, payload){
            state.reg_error = null;
            state.registeredUser = payload.user;
        },
        registerFailed(state, payload){
            state.reg_error = payload.error;
        },
        setAuthorizationToken(state, payload) {
            state.currentUser = Object.assign({}, state.currentUser, {token: payload});
            localStorage.setItem("user", JSON.stringify(state.currentUser));
        }
    },
    actions: {
        login(context){
            context.commit("login");
        },
        logout({ commit }) {
            commit("logout")
        },
        setAuthorizationToken({commit}, payload) {
            commit("setAuthorizationToken", payload);
        }
    }
})
