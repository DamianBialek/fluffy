<template>
    <div class="login">
        <div class="login-form">
            <h3 class="text-center">Panel logowania</h3>
            <div class="alert alert-danger mt-5 text-center" role="alert" v-if="authError">
                {{authError}}
            </div>
            <form @submit.prevent="login">
                <div class="form-group mt-5">
                    <i class="fas fa-user"></i>
                    <input v-model="formData.email" type="email" class="form-control" aria-label="Email" placeholder="Email" />
                </div>
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input v-model="formData.password" type="password" class="form-control" aria-label="Hasło" placeholder="Hasło" />
                </div>
                <button class="btn btn-block btn-success">Zaloguj</button>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
    import Vue from "vue"
    import Component from "vue-class-component"
    import { login } from "../helpers/auth";

    @Component
    export default class Login extends Vue {
        formData: object = {
            email: 'test@test.pl',
            password: 'test'
        }

        get authError(): string {
            return this.$store.getters.authError;
        }

        login(): void {
            this.$store.dispatch('login');
            login(this.formData)
                .then(res => {
                    this.$store.commit("loginSuccess", res);
                    this.$router.push({path: '/dashboard'});
                })
                .catch(error => {
                    this.$store.commit("loginFailed", {error});
                })
        }
    }
</script>

<style scoped lang="scss">
    @import "@scss/_variables";

    .login {
        justify-content: center;
        align-items: center;
        display: flex;
        min-height: 100vh;
        background-color: $dark;

        .login-form {
            width: 100%;
            max-width: 500px;
            padding: 15px;

            h3 {
                color: #ffffff;
            }

            .form-group {
                position: relative;

                i {
                    position: absolute;
                    top: 11px;
                    left: 10px;
                    color: $gray;
                }
            }

            input {
                background-color: $black;
                border-color: $mediumDark;
                color: #fff;
                padding-left: 30px;

                ::placeholder {
                    color: $gray;
                }
            }

            button {
                background-color: $turquoise;
                margin-top: 40px;

                &:hover {
                    background-color: darken($turquoise, 5%);
                }
            }
        }
    }
</style>
