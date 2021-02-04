<template>
    <header class="fixed-top">
        <nav class="navbar justify-content-between" role="navigation">
            <div class="btn bars-btn" @click="$emit('barsBtnClick')">
                <i class="fa fa-bars"></i>
            </div>
            <div class="col-auto login-info">
                Zalogowano jako: <b>{{user.name}}</b><span class="ml-2 logout" @click="logout"><i class="fas fa-sign-out-alt mr-2"></i>Wyloguj</span>
                <router-link tag="button" :to="{name: 'userEdit'}" class="ml-2 btn-outline-secondary btn"><i class="fas fa-cogs"></i></router-link>
            </div>
        </nav>
    </header>
</template>

<script>
    export default {
        name: "Navbar",
        data() {
            return {
                miniNavbar: false,
            }
        },
        computed: {
            user() {
                return this.$store.getters.currentUser
            },
        },
        methods: {
            logout() {
                this.$store.dispatch("logout");
                this.$router.push({name: 'login'})
            }
        }
    }
</script>

<style>

</style>
