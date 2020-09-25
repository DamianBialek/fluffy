<template>
    <div class="page">
        <h1 class="text-center">Informacje</h1>
        <section>
            <p class="text-center">Tutaj pojawią się informacje o nadchodzących zleceniach</p>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "Dashboard",
        data() {
            return {
                user: {}
            }
        },
        computed: {
            token() {
                return this.$store.getters.currentUserToken;
            }
        },
        mounted() {
            axios.post("/api/auth/me").then(res => {
                this.user = res.data;
            })
        }
    }
</script>

<style scoped>

</style>
