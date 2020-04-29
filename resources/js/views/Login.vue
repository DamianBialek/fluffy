<template>
    <div class="login">
        <div class="login-form">
            <h3 class="text-center mb-5">Panel logowania</h3>
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" class="form-control" aria-label="Użytkownik" placeholder="Użytkownik" />
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" aria-label="Hasło" placeholder="Hasło" />
            </div>
            <button class="btn btn-block btn-success">Zaloguj</button>
        </div>
    </div>
</template>

<script lang="ts">
    import Vue from "vue"
    import Component from "vue-class-component"

    @Component
    export default class Login extends Vue {
        //
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
