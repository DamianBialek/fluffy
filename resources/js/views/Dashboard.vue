<template>
    <div>
        User: {{this.user.email}}
    </div>
</template>

<script lang="ts">
    import Vue from "vue"
    import Component from "vue-class-component"
    import axios from "axios";

    @Component
    export default class Dashboard extends Vue {
        user: object = {}

        mounted() {
            axios.post("/api/auth/me").then(res => {
                this.user = res.data;
            })
        }

        get token() {
            return this.$store.getters.currentUserToken;
        }
    }
</script>

<style scoped>

</style>
