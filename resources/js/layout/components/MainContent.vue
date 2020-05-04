<template>
    <main>
        <PageHeader v-if="activePageHeader" />
        <transition name="fade">
            <router-view></router-view>
        </transition>
    </main>
</template>

<script>
    import PageHeader from "./PageHeader";
    export default {
        name: "MainContent",
        components: {PageHeader},
        data() {
            return {
                activePageHeader: this.$route.name !== 'dashboard'
            }
        },
        watch: {
            $route() {
                this.activePageHeader = this.$route.name !== 'dashboard';
            }
        }
    }
</script>

<style>

</style>
