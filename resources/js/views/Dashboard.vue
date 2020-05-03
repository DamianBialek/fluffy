<template>
    <div>
        User: {{this.user.email}}
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
